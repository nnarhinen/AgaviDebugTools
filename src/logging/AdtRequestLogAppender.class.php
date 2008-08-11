<?php

class AdtRequestLogAppender extends AgaviLoggerAppender
{

	public function write($message)
	{
		if(($layout = $this->getLayout()) === null) {
			throw new AgaviLoggingException('No Layout set');
		}
		
		$this->context->getRequest()->appendAttribute('log',
			array('timestamp' => new DateTime(), 'message' => $this->getLayout()->format($message)),
			'debugtoolbar');
	}
	
	public function shutdown() 
	{
		//
	}
}

?>