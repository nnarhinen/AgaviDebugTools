<?php

class Default_IndexAction extends AdtDemoDefaultBaseAction
{
	public function execute(AgaviRequestDataHolder $rd)
	{
		return 'Success';
	}
	
	public function handleError(AgaviRequestDataHolder $rd)
	{
		return 'Success';
	}
}

?>