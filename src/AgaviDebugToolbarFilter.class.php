<?php
/**
 * Filter for displaying couple of informations :)
 *
 * @author    Daniel Ancuta <daniel.ancuta@whisnet.pl>
 * @author    Veikko Mäkinen <veikko@veikko.fi>
 * @copyright Authors
 * @version   0.1
 */
class AgaviDebugToolbarFilter extends AgaviDebugFilter implements AgaviIActionFilter
{

	/**
	 * @var AgaviExecutionContainer
	 */
	private $container = null;

	public function executeOnce(AgaviFilterChain $filterChain, AgaviExecutionContainer $container)
	{
		$this->container = $container;

		//AgaviDebugFilter does the actual logging
		parent::executeOnce($filterChain, $container);


		$filterChain->execute($container);

		// Check if we can inject AgaviDebugToolbar to response
		// and if output type is one of our defined output types
		if ( !$container->getResponse()->isContentMutable() ||
		(is_array($this->getParameter('output_types')) &&
		!in_array($container->getResponse()->getOutputType()->getName(), $this->getParameter('output_types')) ) ) {
			return;
		}

		// Render the toolbar		
		$template = $this->log;

		// TODO: handle relative and absolute paths
		ob_start();
		include(dirname(__FILE__) .'/'. $this->getParameter('template') );
		$output = ob_get_contents();
		ob_end_clean();

		// Inject AgaviDebugToolbar to response
		$output  = str_replace('</body>', $output."\n</body>", $container->getResponse()->getContent());

		// FIXME
		// ...now this is just stupid. I hate myself for this:
		$cssOutput = '';
		foreach($this->getParameter('css', array()) as $css) {
			$cssOutput .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />',
			$this->getParameter('modpub') . '/' . $css);
		}
		$output = str_replace('</head>', $cssOutput."\n</head>", $output);

		$container->getResponse()->setContent($output);
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