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
		<h2>Routing</h2>
		<div id="section-routing" >
	    <?php foreach($template['routes'] as $matchedRouteName => $matchedRouteInfo ): ?>
			<h3 id="adtMatchedRouteShow_<?php echo md5($matchedRouteName); ?>" ><strong><?php echo $matchedRouteName ?></strong></h3>
			<div id="adtMatchedRouteInfo_<?php echo md5($matchedRouteName) ?>" >
				<strong>Module</strong>: <?php echo $matchedRouteInfo['opt']['module']; ?><br />
				<strong>Action</strong>: <?php echo $matchedRouteInfo['opt']['action']; ?><br />
				<strong>Stop</strong>: <?php echo $matchedRouteInfo['opt']['stop']==true ? 'True' : 'False'; ?><br />
				<strong>Parameters</strong>: 
				<ul>
				<?php foreach( $matchedRouteInfo['par'] as $oneParameter ): ?>
					<li><strong><?php echo $oneParameter ?></strong><br />
					Default <br />
					<?php if ( isset($matchedRouteInfo['opt']['defaults'][$oneParameter]) ): ?>
					<!-- 
					<ul>
	                    $matchedRoutesTemplate .= 'Pre: '.$matchedRouteInfo['opt']['defaults'][$oneParameter]['pre'];
	                    $matchedRoutesTemplate .= '<br />';
	                    $matchedRoutesTemplate .= 'Value: '.$matchedRouteInfo['opt']['defaults'][$oneParameter]['val'];
	                    $matchedRoutesTemplate .= '<br />';
	                   $matchedRoutesTemplate .= 'Post: '.$matchedRouteInfo['opt']['defaults'][$oneParameter]['post'];
	                  $matchedRoutesTemplate .= '</ul>';
	                } else {
	                  $matchedRoutesTemplate .= ' No default ';
	                }
	                 -->
	                 <!-- 
	                $matchedRoutesTemplate .= 'Required: ';
	
	                if ( !isset($matchedRouteInfo['opt']['optional_parameters'][$oneParameter]) ) {
	                  $matchedRoutesTemplate .= 'False';
	                } else {
	                  $matchedRoutesTemplate .= 'True';
	                }
	                
	               $matchedRoutesTemplate .= '</li>';
	           }
	           $matchedRoutesTemplate .= '
	           -->
					<?php endif; ?>
				<?php endforeach; ?>
	           </ul>
	           <!-- 
		<?php endforeach; ?>
	           
	           # Output type for router
	           $matchedRoutesTemplate .= '<strong>Output type</strong>: ';
	           if ( empty( $matchedRouteInfo['opt']['output_type'] ) ) {
	             $tmpOutputTypeName = $this->getContext()->getController()->getOutputType()->getName();
	           } else {
	             $tmpOutputTypeName = $matchedRouteInfo['opt']['output_type'];
	           }
	           $matchedRoutesTemplate .= '<a id="adtMatchedRouteOutputTypeShow_'.md5($matchedRouteName).'" href="#">'.$tmpOutputTypeName.'</a>';
	           
	           # Output type info
	           $routeOutputType = $this->getContext()->getController()->getOutputType($tmpOutputTypeName);
	           
	           $matchedRoutesTemplate .= '<div id="adtMatchedRouteOutputTypeInfo_'.md5($matchedRouteName).'" style="display: none;">';
	             $matchedRoutesTemplate .= 'Has renderers: ';
	             $matchedRoutesTemplate .= $routeOutputType->hasRenderers()==true?'True':'False';
	             $matchedRoutesTemplate .= '<br />';
	             $matchedRoutesTemplate .= 'Default layout name: '.$routeOutputType->getDefaultLayoutName();
	           $matchedRoutesTemplate .= '</div>';
	           
	           if ( strcmp($this->getContext()->getController()->getOutputType()->getName(), $matchedRouteInfo['opt']['output_type']) == 0 ) {
	             $matchedRoutesTemplate .= ' ( default ) ';
	           }
	
	           $matchedRoutesTemplate .= '<br />';
	           
	           /*
	           $matchedRoutesTemplate .= '<strong>Parameters</strong>: ';
	           
	           if ( count( $matchedRouteInfo['opt']['parameters'] ) != 0 ) {
	             $matchedRoutesTemplate .= '<ul>';
	             
	             foreach( $matchedRouteInfo['opt']['parameters'] as $parameter ) {
	              $matchedRoutesTemplate .= '<li>'.$parameter.'</li>';
	             }
	             $matchedRoutesTemplate .= '</ul>';
	           } else {
	            $matchedRoutesTemplate .= 'No parameters';
	           }*/
	           
	           # Ignores
	           $matchedRoutesTemplate .= 'Ignores: ';
	           if ( count( $matchedRouteInfo['opt']['ignores'] ) != 0 ) {
	             $matchedRoutesTemplate .= '<ul>';
	             foreach( $matchedRouteInfo['opt']['ignores'] as $ignore ) {
	               $matchedRoutesTemplate .= '<li>'.$ignore.'</li>';
	             }
	             $matchedRoutesTemplate .= '</ul>';
	           } else {
	             $matchedRoutesTemplate .= 'No ignores';
	           }
	           
	           $matchedRoutesTemplate .= '<br />';
	           
	           # Childs of route
	           $matchedRoutesTemplate .= 'Childs';
	           if ( count($matchedRouteInfo['opt']['childs']) != null ) {
	             $matchedRoutesTemplate .= '<ul>';
	             foreach( $matchedRouteInfo['opt']['childs'] as $children ) {
	               $matchedRoutesTemplate .= '<li>'.$children.'</li>';
	             }
	             $matchedRoutesTemplate .= '</ul>';
	           }
	           
	           $matchedRoutesTemplate .= '<br />';
	           
	           # Callback class
	           $matchedRoutesTemplate .= 'Callback: '.$matchedRouteInfo['opt']['callback'];
	           
	           $matchedRoutesTemplate .= '<br />';
	           
	           # Imply
	           $matchedRoutesTemplate .= 'Imply: ';
	           $matchedRoutesTemplate .= $matchedRouteInfo['opt']['imply']==true?'True':'False';
	           
	           $matchedRoutesTemplate .= '<br />';
	           
	           # Cut
	           $matchedRoutesTemplate .= 'Cut: ';
	           $matchedRoutesTemplate .= $matchedRouteInfo['opt']['cut']==true?'True':'False';
	           
	           $matchedRoutesTemplate .= '<br />';
	           
	           # RPX
	           $matchedRoutesTemplate .= 'rpx: '.$matchedRouteInfo['rxp'];
	         
	       $matchedRoutesTemplate .= '</div>';
	       $matchedRoutesTemplate .= '<br />';
	            -->
		</div>
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