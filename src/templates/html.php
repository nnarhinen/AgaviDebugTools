<?php
//define some helper methods

function _s($string) {
	return htmlspecialchars($string);
}
?>

<div id="adt-container">
	<h1>Debug Log</h1>

	<div id="sections">
		<h2>Matched Routes</h2>
		<div id="section-routing">
			<ul>
			<?php foreach($template['routes'] as $route): ?>
				<li><a id="adtMatchedRouteShow_<?php echo md5($route['opt']['name']); ?>" href="#"><?php echo $route['opt']['name']; ?></a></li>
			<?php endforeach; ?>
			</ul>

			<?php foreach($template['routes'] as $routeName => $routeInfo): ?>
			<div id="adtMatchedRouteInfo_<?php echo md5($routeName); ?>" style="display: none; border: 1px black solid; margin-top: 5px; padding: 3px;">
				<h3><?php echo $routeName; ?></h3>
				<dl>
					<dt>Module:</dt>
						<dd><?php echo !empty($routeInfo['opt']['module']) ? $routeInfo['opt']['module'] : '&#160;'; ?></dd>
					<dt>Action:</dt>
						<dd><?php echo !empty($routeInfo['opt']['action']) ? $routeInfo['opt']['action'] : '&#160;'; ?></dd>
					<dt>Stop:</dt>
						<dd><?php echo $routeInfo['opt']['stop']==true ? 'True' : 'False'; ?></dd>
					<dt>Parameters:</dt>
					<dd>
						<?php if(count($routeInfo['par'])): ?>
						<ul>
						<?php foreach( $routeInfo['par'] as $oneParameter ): ?>
							<li><strong style="text-decoration: underline;"><?php echo $oneParameter ?></strong><br />
							Default:
							<?php if ( isset($routeInfo['opt']['defaults'][$oneParameter]) ): ?>
							<ul>
								<li>Pre: <?php echo $routeInfo['opt']['defaults'][$oneParameter]['pre']; ?></li>
								<li>Value: <?php echo $routeInfo['opt']['defaults'][$oneParameter]['val']; ?></li>
								<li>Post: <?php echo $routeInfo['opt']['defaults'][$oneParameter]['post']; ?></li>
							</ul>
							<?php else: ?>
							No default
							<?php endif; ?>
							<br />
							Required: <?php echo isset($routeInfo['opt']['optional_parameters'][$oneParameter]) ? 'False' : 'True'; ?>
							</li>
						<?php endforeach; ?>
						</ul>
						<?php else: ?>
						No Parameters
						<?php endif; ?>
					</dd>

				<?php
					$otTimeName = time()+rand();
					$otName = empty($routeInfo['opt']['output_type']) ? $this->getContext()->getController()->getOutputType()->getName() : $routeInfo['opt']['output_type'];
					$outputType = $this->getContext()->getController()->getOutputType($otName);
				?>
				<dt>Output type: <a id="adtMatchedRouteOutputTypeShow_<?php echo $otTimeName; ?>" href="#"><?php echo $otName;	?></a></dt>
				<dd>
				<div id="adtMatchedRouteOutputTypeInfo_<?php echo $otTimeName; ?>" style="display: none;">
					Has renderers:	<?php echo $outputType->hasRenderers()==true ? 'True' : 'False'; ?><br />
					Default layout name: <?php echo $outputType->getDefaultLayoutName(); ?>
				</div>
				<?php
				if ( strcmp($this->getContext()->getController()->getOutputType()->getName(), $routeInfo['opt']['output_type']) == 0 ) {
					echo ' ( default ) ';
				}
				?>
				</dd>

				<dt>Ignores:</dt>
				<dd>
				<?php if ( count( $routeInfo['opt']['ignores'] ) != 0 ): ?>
				<ul>
				<?php foreach( $routeInfo['opt']['ignores'] as $ignore ): ?>
					<li><?php echo $ignore; ?></li>
				<?php endforeach; ?>
				</ul>
				<?php else: ?>
					No ignores
				<?php endif; ?>
				</dd>

				<dt>Children:</dt>
				<dd>
				<?php if (count($routeInfo['opt']['childs']) != null ): ?>
					<ul>
					<?php foreach( $routeInfo['opt']['childs'] as $children ): ?>
						<li><?php echo $children; ?></li>
					<?php endforeach; ?>
					</ul>
				<?php else: ?>
				No children
				<?php endif; ?>
				</dd>

				<dt>Callback:</dt>
				<dd>
				<?php echo !empty( $routeInfo['opt']['callback'] )?$routeInfo['opt']['callback']:' No callback '; ?>
				</dd>

				<dt>Imply:</dt>
				<dd>
				<?php echo $routeInfo['opt']['imply']==true?'True':'False'; ?>
				</dd>

				<dt>Cut:</dt>
				<dd>
				<?php echo $routeInfo['opt']['cut']==true?'True':'False'; ?>
				</dd>

				<dt>rpx:</dt>
				<dd>
				<?php echo _s($routeInfo['rxp']); ?>
				</dd>
			</dl>
			</div><!-- route -->
		<?php endforeach; //routes ?>
		</div><!-- routing -->

		<h2>Global Request Data</h2>
		<div id="adtBlock_Request" >
			<?php foreach( $template['request_data'] as $parameter => $value ): ?>
				<?php echo $parameter; ?>: <?php echo $value; ?>
				<br />
			<?php endforeach; ?>
		</div>

		<h2>Actions</h2>
		<div id="adtBlock_View" >
		<?php foreach($template['actions'] as $action): ?>
			<h3><?php echo $action['module']; ?>.<?php echo $action['name']; ?></h3>
			View name: <?php echo $template['view']['view_name']; ?>
			<br />
			Output type: <a href="#"><?php echo $template['view']['output_type']; ?></a>
			<?php if ( strcmp($template['view']['output_type'], $template['view']['default_output_type']) == 0 ): ?>
			( default )
			<?php endif; ?>
			<br />
			Has renderers: <?php echo $template['view']['has_renders']?'True':'False'; ?>
			<br />
			Default layout name: <?php echo $template['view']['default_layout_name']; ?>
		<?php endforeach; ?>
		</div>

		<h2>FPF</h2>
		<div>
			FormPopulationFilter TODO
		</div>

		<?php if ( AgaviConfig::get('core.use_database') ): ?>
		<h2>Databases</h2>
		<div>
			Class name: <?php echo $template['database']['class_name']; ?>
		</div>
		<?php endif; ?>

		<?php if ( AgaviConfig::get('core.use_translation') ): ?>
		<h2>Translation</h2>
		<div>
			Current locale: <?php echo $template['tm']->getCurrentLocaleIdentifier(); ?>
			<br />
			Default locale: <?php echo $template['tm']->getDefaultLocaleIdentifier(); ?>

			<br /><br />

			Available locales
			<br />
			<ul>
				<?php foreach ( $template['tm']->getAvailableLocales() as $locale ): ?>
				<li>
					<?php echo $locale['parameters']['description']; ?>
					<br />
					Identifier: <?php echo $locale['identifier']; ?>
					<br />
					Language: <?php echo $locale['identifierData']['language'] ?>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>

		<h2>Environment</h2>
		<div>
			Environment: <?php echo AgaviConfig::get('core.environment'); ?>

			<br /><br />

			<strong style="text-decoration: underline;">Defaults</strong>
			<dl>
				<dt>Module and action</dt>
					<dd>
						Module: <?php echo AgaviConfig::get('actions.default_module'); ?>
						<br />
						Action: <?php echo AgaviConfig::get('actions.default_action'); ?>
					</dd>

				<dt>404</dt>
					<dd>
						Module: <?php echo AgaviConfig::get('actions.error_404_module'); ?>
						<br />
						Action: <?php echo AgaviConfig::get('actions.error_404_action'); ?>
					</dd>

				<dt>Unavailable</dt>
					<dd>
						Module: <?php echo AgaviConfig::get('actions.module_disabled_module'); ?>
						<br />
						Action: <?php echo AgaviConfig::get('actions.module_disabled_action'); ?>
					</dd>
				<dt>Disabled</dt>
					<dd>
						Module: <?php echo AgaviConfig::get('actions.unavailable_module'); ?>
						<br />
						Action: <?php echo AgaviConfig::get('actions.unavailable_action'); ?>
					</dd>
				<dt>Secure</dt>
					<dd>
						Module: <?php echo AgaviConfig::get('actions.secure_module'); ?>
						<br />
						Action: <?php echo AgaviConfig::get('actions.secure_action'); ?>
					</dd>
				<dt>Login</dt>
					<dd>
						Module: <?php echo AgaviConfig::get('actions.login_module'); ?>
						<br />
						Action: <?php echo AgaviConfig::get('actions.login_action'); ?>
					</dd>
			</dl>

			<br /><br />

			<strong style="text-decoration: underline;">Locations</strong>
			<dl>
				<dt>App:</dt>
				 <dd><?php echo AgaviConfig::get('core.app_dir'); ?></dd>

				<dt>Cache:</dt>
				 <dd><?php echo AgaviConfig::get('core.cache_dir'); ?></dd>

				<dt>Config:</dt>
				 <dd><?php echo AgaviConfig::get('core.config_dir'); ?></dd>

				<dt>Lib:</dt>
					<dd><?php echo AgaviConfig::get('core.lib_dir'); ?></dd>

				<dt>Modules:</dt>
					<dd><?php echo AgaviConfig::get('core.module_dir'); ?></dd>

				<dt>Templates:</dt>
					<dd><?php echo AgaviConfig::get('core.template_dir'); ?></dd>
			</dl>

			<br /><br />

			<strong style="text-decoration: underline;">Agavi</strong>
			<dl>
				<dt>Version:</dt>
					<dd><?php echo AgaviConfig::get('agavi.version'); ?></dd>

				<dt>Location:</dt>
					<dd><?php echo AgaviConfig::get('core.agavi_dir'); ?></dd>
			</dl>
		</div>

		<h2>Log</h2>
		<div>
			<?php foreach($template['log'] as $logLine): ?>
				<?php echo $logLine['timestamp']->format('c') ?>: <?php echo _s($logLine['message']); ?><br/>
			<?php endforeach; ?>
		</div>
	</div><!-- sections / tabs -->
</div>
