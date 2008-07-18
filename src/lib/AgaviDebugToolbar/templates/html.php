<?php
//define some helper methods

function _s($string) {
	return htmlspecialchars($string);
}

?>

<div id="adt-container">

<!-- this cannot be here -->
<style type="text/css">
.tab-wrapper {
	padding: 1em 1.5em;
	margin: 0;
	border: 1px solid #eee;
	clear: both;
}

.tab-menu {
	margin: 0;
	margin-top: 1em;
	padding: 0;
	list-style: none;
	overflow: auto;
}

.tab-menu li {
	margin: 0;
	margin-right: 1em;
	padding: 0;
	float: left;
	line-height: 2em;
	border-top: 1px solid #eee;
	border-left: 1px solid #eee;
	border-right: 1px solid #eee;
	background: url('tab-back.png') repeat-x bottom left;
}
.tab-menu li.tab-selected {
	background: url('tab-sel-back.png') repeat-x top left;
}
.tab-menu li a {
	padding: 0 .5em;
	font-weight: bold;
}

.tab-menu li:hover, .tab-menu li:focus {
	background: url('tab-sel-back.png') repeat-x top left;
}

.tab-menu li.tab-selected a {
	color: #000;
}
</style>

<?php if ($this->hasParameter('js')) foreach($this->getParameter('js') as $js) :?>
	<script type="text/javascript" src="<?php echo $this->getParameter('modpub').'/'.$js; ?>"></script>
<?php endforeach; ?>
	<h1>Agavi Debug Log</h1>

	<div id="sections">
		<h2>Matched Routes</h2>
		<div id="section-routing">
	    <?php foreach($template['routes'] as $routeName => $routeInfo): ?>
			<h3><?php echo $routeName ?></h3>
			<div id="route-<?php echo md5($routeName); ?>">
				<dl>
					<dt>Module</dt>
						<dd><?php echo $routeInfo['opt']['module']; ?></dd>
					<dt>Action</dt>
						<dd><?php echo $routeInfo['opt']['action']; ?></dd>
					<dt>Stop</dt>
						<dd><?php echo $routeInfo['opt']['stop']==true ? 'True' : 'False'; ?></dd>
					<dt>Parameters</dt>
					<dd> 
						<?php if(count($routeInfo['par'])): ?>
						<ul>
						<?php foreach( $routeInfo['par'] as $oneParameter ): ?>
							<li><strong><?php echo $oneParameter ?></strong><br />
							Default 
							<?php if ( isset($routeInfo['opt']['defaults'][$oneParameter]) ): ?>
							<ul>
								<li>Pre: <?php echo $routeInfo['opt']['defaults'][$oneParameter]['pre']; ?></li>
								<li>Value: <?php echo $routeInfo['opt']['defaults'][$oneParameter]['val']; ?></li>
								<li>Post: <?php echo $routeInfo['opt']['defaults'][$oneParameter]['post']; ?></li>
							</ul>
			                <?php else: ?>
							No default
			                <?php endif; ?>
							Required: <?php echo !isset($routeInfo['opt']['optional_parameters'][$oneParameter]) ? 'False' : 'True'; ?>
							</li>
						<?php endforeach; ?>
						</ul>
						<?php else: ?>
						No Parameters
						<?php endif; ?>
					</dd>
				</dl>
				<strong>Output type</strong>:
				<?php
					$otName = empty($routeInfo['opt']['output_type']) ? $this->getContext()->getController()->getOutputType()->getName() : $routeInfo['opt']['output_type']; 
					$outputType = $this->getContext()->getController()->getOutputType($otName); 
				?>
				<?php echo $otName;  ?>

				<div>
					Has renderers:  <?php echo $outputType->hasRenderers()==true ? 'True' : 'False'; ?><br />
					Default layout name: <?php echo $outputType->getDefaultLayoutName(); ?>
				</div>
				<?php 
				if ( strcmp($this->getContext()->getController()->getOutputType()->getName(), $routeInfo['opt']['output_type']) == 0 ) {
					echo ' ( default ) ';
				}
				?>
				<br />

				<strong>Parameters</strong>:
				<?php if ( count( $routeInfo['opt']['parameters'] ) != 0 ): ?>
				<ul>
				<?php foreach( $routeInfo['opt']['parameters'] as $parameter ): ?>
					<li><?php echo $parameter; ?></li>
				<?php endforeach; ?>
				</ul>
				<?php else: ?>
					No parameters
				<?php endif; ?>

				<br/>Ignores:
				<?php if ( count( $routeInfo['opt']['ignores'] ) != 0 ): ?>
				<ul>
				<?php foreach( $routeInfo['opt']['ignores'] as $ignore ): ?>
					<li><?php echo $ignore; ?></li>
				<?php endforeach; ?>
				</ul>
				<?php else: ?> 
					No ignores
				<?php endif; ?>
				<br />

				Children
				<?php if (count($routeInfo['opt']['childs']) != null ): ?>
					<ul>
					<?php foreach( $routeInfo['opt']['childs'] as $children ): ?>
						<li>'.$children.'</li>
					<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<br />

				Callback: <?php echo $routeInfo['opt']['callback']; ?>
				<br />
				Imply: <?php echo $routeInfo['opt']['imply']==true?'True':'False'; ?>
				<br />
				Cut: <?php echo $routeInfo['opt']['cut']==true?'True':'False'; ?>
				<br />
				rpx: <?php echo _s($routeInfo['rxp']); ?>	         
		
			</div><!-- route -->
		<?php endforeach; //routes ?> 
		</div><!-- routing -->

		<h2>Request</h2>
		<div id="adtBlock_Request" >
		{adtBlock_Request}
		</div>
		  
		<h2>View</h2>
		<div id="adtBlock_View" >
		{adtBlock_View}
		</div>
	</div><!-- sections / tabs -->
</div>