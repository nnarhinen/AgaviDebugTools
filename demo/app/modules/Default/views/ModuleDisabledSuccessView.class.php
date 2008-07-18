<?php

class Default_ModuleDisabledSuccessView extends AdtDemoDefaultBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->getResponse()->setHttpStatusCode('503');
	}
}

?>
