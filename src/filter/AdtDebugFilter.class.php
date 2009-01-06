<?php

/**
 * AgaviDebugFilter gathers information for debug purposes
 *
 * @author     Daniel Ancuta <daniel.ancuta@whisnet.pl>
 * @author     Veikko Mäkinen <veikko@veikko.fi>
 * @copyright  Authors
 * @version    $Id$
 */
abstract class AdtDebugFilter extends AgaviFilter implements AgaviIActionFilter
{
	protected $log = array();

	protected $options = array();

	protected $datasources = array();

	public function initialize(AgaviContext $context, array $parameters = array())
	{
		parent::initialize($context, $parameters);

		$this->options = array_merge(
			//default options
			array(
				'sections' => array(
					'routing',
					'globalrd',
					'actions',
					'fpf',
					'translation',
					'environment',
					'log'
				),
			),
			$this->getParameters()
		);

		$this->log['datasources'] = array();
		foreach($this->getParameter('datasources', array()) as $datasource) {
			$ds = new $datasource['class'];

			$ds->initialize($context, (isset($datasource['parameters']) && is_array($datasource['parameters']) ? $datasource['parameters'] : array()));
			$this->log['datasources'][] = $ds;
		}
	}

	protected function updateOptions()
	{
		$req = $this->context->getRequest();
		$runtimeOptions = $req->hasAttributeNamespace('adt.debugfilter.options') ?
			$req->getAttributeNamespace('adt.debugfilter.options') : array();
		$this->options = array_merge(
			$this->options,
			$runtimeOptions
		);
	}

	public function executeOnce(AgaviFilterChain $filterChain, AgaviExecutionContainer $container)
	{
		//trigger datasource event listeners
		foreach($this->log['datasources'] as $ds) {
			$ds->beforeExecuteOnce($container);
		}

		//procede to execute
		$this->execute($filterChain, $container);

		//trigger datasource event listeners
		foreach($this->log['datasources'] as $ds) {
			$ds->afterExecuteOnce($container);
		}

		//log global (i.e. not per action) stuff
		$this->log['routes'] = $this->getMatchedRoutes();
		$this->log['request_data'] = array(
			'request_parameters' => $this->getContext()->getRequest()->getRequestData()->getParameters(),
			'cookies' => $this->getContext()->getRequest()->getRequestData()->getCookies(),
			'headers' => $this->getContext()->getRequest()->getRequestData()->getHeaders()
		);
		$this->log['log'] = $this->getLogLines();
		$this->log['database'] = $this->getDatabase();
		$this->log['tm'] = $this->getContext()->getTranslationManager();
		$this->log['environments'] = $this->getAvailableEnvironments();

		$this->render($container);
	}

	public function execute(AgaviFilterChain $filterChain, AgaviExecutionContainer $container)
	{
		//trigger datasource event listeners
		foreach($this->log['datasources'] as $ds) {
			$ds->beforeExecute($container);
		}

		//procede with execution
		$filterChain->execute($container);

		//trigger datasource event listeners
		foreach($this->log['datasources'] as $ds) {
			$ds->afterExecute($container);
		}

		$this->updateOptions();

		//now the action has been executed and we'll log what can be logged
		if(in_array('actions', $this->options['sections'])) {
			$this->log($container);
		}
	}

	abstract protected function render(AgaviExecutionContainer $container);

	protected function log(AgaviExecutionContainer $container)
	{
		//keep this simple for now
		$this->log['actions'][] = array (
			'name' => $container->getActionName(),
			'module' => $container->getModuleName(),
			'request_data' => array(
				'request_parameters' => $container->getRequestData()->getParameters(),
				'cookies' => $container->getRequestData()->getCookies(),
				'headers' => $container->getRequestData()->getHeaders(),
			),
			'validation' => $this->getValidationInfo($container),
			'view' => $this->getViewInfo($container),
		);
	}

	/**
	 * Get array with matched routes
	 *
	 * @return     array
	 * @since      0.1
	 */
	private function getMatchedRoutes()
	{
		$result = array();
		$matchedRoutes = $this->getContext()->getRequest()->getAttribute('matched_routes', 'org.agavi.routing');

		foreach( $matchedRoutes as $matchedRoute ) {
			$result[$matchedRoute] = $this->getContext()->getRouting()->getRoute($matchedRoute);
		}

		return $result;
	}

	/**
	 * Get information about database
	 *
	 * @since 0.1
	 */
	private function getDatabase()
	{
		$result = array();
		if ( !AgaviConfig::get('core.use_database') ) {
			return $result;
		}

		//
		// THIS IS DEFINITELY NOT GOOD ENOUGH
		// we'll probably just have to parse databases.xml or something
		//
		$result['class_name'] = get_class($this->context->getDatabaseManager()->getDatabase());

		return $result;
	}

	/**
	 * Get information about view for action
	 *
	 * @return array
	 * @since 0.1
	 */
	private function getViewInfo(AgaviExecutionContainer $container)
	{
		$result = array();

		$outputType = $this->getContext()->getController()->getOutputType($container->getOutputType()->getName());

		$result['view_name'] = $container->getViewName();
		$result['output_type'] = $container->getOutputType()->getName();
		$result['default_output_type'] = $this->getContext()->getController()->getOutputType()->getName();
		$result['has_renders'] = $outputType->hasRenderers();
		$result['default_layout_name'] = $outputType->getDefaultLayoutName();

		return $result;
	}

	public function getLogLines()
	{
		return $this->context->getRequest()->getAttribute('log', 'adt.debugtoolbar', array());
	}

	private function getValidationInfo(AgaviExecutionContainer $container)
	{
		$vm = $container->getValidationManager();
		$result = array();

		$result['has_errors'] = $vm->hasErrors();
		$result['severities'] = array(
			200 => 'SILENT',
			300 => 'NOTICE',
			400 => 'ERROR',
			500 => 'CRITICAL',
		);
		$result['incidents'] = $vm->getIncidents();

		return $result;
	}

	/**
	 * Get list of available environments
	 *
	 * @author Daniel Ancuta
	 * @return
	 * @since 0.1
	 */
	private function getAvailableEnvironments()
	{
		$result = array();

		$doc = new AgaviXmlConfigDomDocument();

		$doc->load(AgaviConfig::get('core.config_dir').'/settings.xml');

		//TODO: XPath is broken, fix it

		$xpath = new DOMXPath($doc);
		$xpath->registerNamespace('agavi', 'http://agavi.org/agavi/1.0/config');
		$query = "//agavi:configurations/agavi:configuration/..";

		$nodes = $xpath->query($query);

		foreach( $nodes as $node ) {
			$env = $node->hasAttribute('environment') ? $node->getAttribute('environment') : '(default)';
			$result[$env] = array();

			// System actions
			foreach( $node->getElementsByTagName('system_actions') as $oneSystemAction ) {
				foreach( $oneSystemAction->getElementsByTagName('system_action') as $systemAction ) {
					$result[$env]['system_actions'][$systemAction->getAttribute('name')] =
					array('module' => $systemAction->getElementsByTagName('module')->item(0)->nodeValue,
								'action' => $systemAction->getElementsByTagName('action')->item(0)->nodeValue);

				}
			}

			// Settings
			foreach( $node->getElementsByTagName('settings') as $oneSetting ) {
				foreach( $oneSetting->getElementsByTagName('setting') as $setting ) {
					$result[$env]['settings'][$setting->getAttribute('name')] = $setting->nodeValue;
				}
			}

			// Exception templates
			foreach( $node->getElementsByTagName('exception_templates') as $oneExceptionTemplate ) {
				foreach( $oneExceptionTemplate->getElementsByTagName('exception_template') as $execeptionTemplate ) {
					$result[$env]['exception_templates'][] = array('context' => $execeptionTemplate->getAttribute('context'),
						'template' => $execeptionTemplate->nodeValue);
				}
			}
		}

		return $result;
	}

}
?>
