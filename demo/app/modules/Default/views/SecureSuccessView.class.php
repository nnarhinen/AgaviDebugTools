<?php

class Default_SecureSuccessView extends AdtDemoDefaultBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->getResponse()->setHttpStatusCode('401');
	}
}

?>
