<?php

/**
 * The base action from which all project actions inherit.
 */
class AdtDemoBaseAction extends AgaviAction
{
	
	public function initialize(AgaviExecutionContainer $container)
	{
		parent::initialize($container);
		$this->context->getLoggerManager()->log('Look Ma! Me debugging '.__CLASS__, 'debug');
	}

}

?>