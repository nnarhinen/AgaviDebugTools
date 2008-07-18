<?php
/**
 * Filter for displaying couple of informations :)
 *
 * @author    Daniel Ancuta <daniel.ancuta@whisnet.pl>
 * @copyright Authors
 * @version   0.1
 */
class AgaviDebugToolbarFilter extends AgaviFilter implements AgaviIGlobalFilter, AgaviIActionFilter
{
<<<<<<< .mine
=======
  public function execute(AgaviFilterChain $filterChain, AgaviExecutionContainer $container) {
    $this->container = $container;
    
    $filterChain->execute($container);
    
    # We're checking if we can add AgaviDebugToolbar to response
    # If output type is one of our defined output types
    if ( !$container->getResponse()->isContentMutable() || 
       (is_array($this->getParameter('output_types'))   && 
       !in_array($container->getResponse()->getOutputType()->getName(), $this->getParameter('output_types')) ) ) {
     return;
    }
    
    //stuff all data into this array
    $template = array();
    
    $template['routes'] = $this->getMatchedRoutes();
    
    # Load routing block
//    $adtTemplate = str_replace('{adtBlock_Routing}', $this->adtGetMatchedRoutesHtml(), $adtTemplate);
//    
//    # Load request parameters block
//    $adtTemplate = str_replace('{adtBlock_Request}', $this->adtGetRequestHtml(), $adtTemplate);
//    
//    # Load view block
//    $adtTemplate = str_replace('{adtBlock_View}', $this->adtGetViewHtml(), $adtTemplate);
>>>>>>> .r14

	/**
	 * @var AgaviExecutionContainer
	 */
	private $container = null;

	public function execute(AgaviFilterChain $filterChain, AgaviExecutionContainer $container)
	{
		$this->container = $container;

		$filterChain->execute($container);

		# We're checking if we can add AgaviDebugToolbar to response
		# If output type is one of our defined output types
		if ( !$container->getResponse()->isContentMutable() ||
		(is_array($this->getParameter('output_types')) &&
		!in_array($container->getResponse()->getOutputType()->getName(), $this->getParameter('output_types')) ) ) {
			return;
		}

		//stuff all data into this array
		$template = array();

		$template['routes'] = $this->getMatchedRoutes();
		$template['request_data'] = $this->getContext()->getRequest()->getRequestData()->getParameters();
		$template['view'] = $this->adtGetViewHtml();
		$template['log'] = $this->getLogLines();

		// load the template
		// TODO: handle relative and absolute paths
		ob_start();
		include(dirname(__FILE__) .'/'. $this->getParameter('template') );
		$output = ob_get_contents();
		ob_end_clean();

		// Inject AgaviDebugToolbar to response
		// TODO: How to handle other output types?
		//  - should we abstract the whole rendering part and have specialized renderers?
		
		$output  = str_replace('</body>', $output."\n</body>", $container->getResponse()->getContent());
		
		// ...now this is just stupid. I hate myself for this:
		$cssOutput = '';
		foreach($this->getParameter('css', array()) as $css) {
			$cssOutput .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />',
			$this->getParameter('modpub') . '/' . $css);
		}
		$output = str_replace('</head>', $cssOutput."\n</head>", $output);

		$container->getResponse()->setContent($output);

		#echo '<pre>';
		#var_dump( $this->getContext()->getRouting()->getRoute('dupa') );
		#var_dump( $this->getContext()->getRouting()->getRoute('dupa.itsChild') );
	}

	/**
	 * Get array with matched routes
	 *
	 * @return array
	 * @since 0.1
	 */
	private function getMatchedRoutes() {
		# Array with information about matched routes, name of route is an index of array
		$matchedRoutesInformation = array();
		# Matched routes
		$matchedRoutes = $this->getContext()->getRequest()->getAttribute('matched_routes', 'org.agavi.routing');

		foreach( $matchedRoutes as $matchedRoute ) {
			$matchedRoutesInformation[$matchedRoute] = $this->getContext()->getRouting()->getRoute($matchedRoute);
		}

		return $matchedRoutesInformation;
	}

	public function getLogLines()
	{
		return $this->context->getRequest()->getAttribute('log', 'debugtoolbar', array());
	}


	/**
	 * Generate HTML code for View
	 *
	 * @return string
	 * @since 0.1
	 */
	protected function adtGetViewHtml() {
		$viewTemplate = '';

		$outputType = $this->getContext()->getController()->getOutputType( $this->container->getOutputType()->getName() );


		$viewTemplate .= 'View name: '. $this->container->getViewName();
		$viewTemplate .= '<br />';
		$viewTemplate .= 'Output type: <a id="adtViewOutputTypeGet_'.md5($this->container->getOutputType()->getName()).'" href="#">'.$this->container->getOutputType()->getName().'</a>';
		if ( strcmp($this->getContext()->getController()->getOutputType()->getName(), $this->container->getOutputType()->getName()) == 0 ) {
			$viewTemplate .= ' ( default ) ';
		}
		$viewTemplate .= '<div id="adtViewOutputTypeInfo_'.md5($this->container->getOutputType()->getName()).'" >';
		$viewTemplate .= 'Has renderers: ';
		$viewTemplate .= $outputType->hasRenderers()==true?'True':'False';
		$viewTemplate .= '<br />';
		$viewTemplate .= 'Default layout name: '.$outputType->getDefaultLayoutName();
		$viewTemplate .= '</div>';
		return $viewTemplate;
	}

}
?>