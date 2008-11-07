
<div id="adt-container">
	<h1>ADT Debug Toolbar</h1>

	<div id="sections">
		<h2>Matched Routes</h2>
		<div id="section-routing">
			<?php include('html-routing.php'); ?>			
		</div><!-- routing -->

		<h2>Global Request Data</h2>
		<div id="section-globalrd" >
			<?php include('html-globalrd.php'); ?>
		</div>

		<h2>Actions</h2>
		<div id="section-actions" >
			<?php include('html-actions.php'); ?>
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
			
			<div id="environments">
			<?php foreach( $template['environments'] as $name => $environment): ?>
				<h3><?php echo $name; ?></h3>
				<div>
					<?php if ( isset($environment['system_actions']) ): ?>
					<h4>Defaults</h4>
					<dl>
					<?php foreach( $environment['system_actions'] as $name => $value ): ?>
						<dt><?php echo $name; ?></dt>
						<dd>
							Module: <?php echo $value['module']; ?>
							<br />
							Action: <?php echo $value['action']; ?>
						</dd>
					<?php endforeach; ?>
					</dl>
					<?php endif; ?>
					
					<?php if ( isset($environment['settings']) ): ?>
					<h4>Settings</h4>
					<ul>
						<?php foreach( $environment['settings'] as $name => $value ): ?>
						<li><?php echo $name; ?>: <?php echo $value; ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
					
					<?php if ( isset($environment['exception_templates']) ): ?>
					<h4>Exception templates</h4>
					<ul>
						<?php foreach( $environment['exception_templates'] as $exception ): ?>
						<li>Context: <?php echo !empty($exception['context'])?$exception['context']:'default'; ?> <?php echo $exception['template']; ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				
				</div>
			<?php endforeach; ?>
			</div>


			<h3>Locations</h3>
			<div>
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
			</div>

			<h3>Agavi</h3>
			<div>
				<dl>
					<dt>Version:</dt>
					<dd><?php echo AgaviConfig::get('agavi.version'); ?></dd>

					<dt>Location:</dt>
					<dd><?php echo AgaviConfig::get('core.agavi_dir'); ?></dd>
				</dl>
			</div>
		</div>

		<h2>Log</h2>
		<div>
			<?php foreach($template['log'] as $logLine): ?>
				<?php echo $logLine['timestamp']->format('c') ?>: <?php echo htmlspecialchars($logLine['message']); ?><br/>
			<?php endforeach; ?>
		</div>
	</div><!-- sections / tabs -->
</div>
