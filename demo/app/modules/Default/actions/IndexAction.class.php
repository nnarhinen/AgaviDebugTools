<?php

class Default_IndexAction extends AdtDemoDefaultBaseAction
{
	public function execute(AgaviRequestDataHolder $rd)
	{
//		$this->context->getRequest()->setAttribute('sections',
//			array('actions', 'globalrd'),
//			'adt.debugfilter.options'
//		);
		return 'Success';
	}
	
	public function handleError(AgaviRequestDataHolder $rd)
	{
		return 'Success';
	}
}

?>