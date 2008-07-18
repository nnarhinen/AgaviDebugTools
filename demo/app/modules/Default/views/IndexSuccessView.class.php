<?php

class Default_IndexSuccessView extends AdtDemoDefaultBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('title', 'Index');
	}
}

?>