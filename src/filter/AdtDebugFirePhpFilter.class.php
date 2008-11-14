<?php

/**
 * AdtDebugFirePhpFilter renders AdtDebugFilter's log using
 * FirePHP 
 *
 * @author     Veikko Mäkinen <veikko@veikko.fi>
 * @copyright  Authors
 * @version    $Id$
 */
class AdtDebugFirePhpFilter extends AdtDebugFilter implements AgaviIActionFilter
{

	public function render(AgaviExecutionContainer $container)
	{
		$firephp = AdtFirePhp::getInstance(true);
		$firephp->setContext($this->context);
		//$firephp->detectClientExtension()
		
		$template = $this->log;
		
		$firephp->group('Matched Routes');
		foreach($template['routes'] as $routeName => $routeInfo) {
			$firephp->log($routeName);
		}
		$firephp->groupEnd();

		$firephp->group('Global Request Data');
			$firephp->group('Request Parameters');
			foreach($template['request_data']['request_parameters'] as $parameter => $value ) {
				$firephp->log($parameter.':'.var_export($value, true));
			}
			$firephp->groupEnd();
			
			$firephp->group('Cookies');
			foreach($template['request_data']['cookies'] as $parameter => $value ) {
				$firephp->log($parameter.':'.var_export($value, true));
			}
			$firephp->groupEnd();
		$firephp->groupEnd();

//		Not really needed with FireBug
//		$firephp->group('Headers');
//		foreach($template['request_data']['headers'] as $parameter => $value ) {
//			$firephp->log($parameter.':'.var_export($value, true));
//		}
//		$firephp->groupEnd();

		$firephp->group('Actions');
		
		foreach($template['actions'] as $action) {
			$firephp->group($action['module'] .'.'.$action['name']);
			$firephp->log('Has validation errors: ' . var_export($action['validation']['has_errors'], true));
			if ($action['validation']['has_errors']) { 
				$firephp->group('Validation Incidents');
				foreach($action['validation']['incidents'] as $incident) {
					$firephp->log('Validator: '.$incident->getValidator()->getName());
					$firephp->log('Severity:: '.$action['validation']['severities'][$incident->getSeverity()]);
					$firephp->log('Fields: '.implode(', ', $incident->getFields()));
				}
				$firephp->groupEnd(); // validation incidents
			}
			
			$firephp->group('Request Data (from execution container)');
			$firephp->group('Request Parameters');
			if ($action['request_data']['request_parameters']) {
				foreach( $action['request_data']['request_parameters'] as $parameter => $value ) {
					$firephp->log($parameter.': '.var_export($value, true));
				}
			}
			else {
				$firephp->log('-');
			}
			$firephp->groupEnd(); //params

			$firephp->group('Cookies');
			if ($action['request_data']['cookies']) {
				foreach( $action['request_data']['request_parameters'] as $parameter => $value ) {
					$firephp->log($parameter.': '.var_export($value, true));
				}
			}
			else {
				$firephp->log('-');
			}
			$firephp->groupEnd(); //cookies

			$firephp->group('Headers');
			if ($action['request_data']['cookies']) {
				foreach( $action['request_data']['request_parameters'] as $parameter => $value ) {
					$firephp->log($parameter.': '.var_export($value, true));
				}
			}
			else {
				$firephp->log('-');
			}
			$firephp->groupEnd(); //headers

			$firephp->groupEnd(); //req data
			
			$firephp->groupEnd(); //action
		} // actions
		
		$firephp->group('Log');
		foreach($template['log'] as $logLine) {
			$firephp->log($logLine['microtime'] . ': ' .$logLine['message']);
		}
		$firephp->groupEnd(); // log
		
	}
}
?>
