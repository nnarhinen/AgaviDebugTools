<?php

class SampleDataSource extends AdtDebugFilterDataSource
{

	public function getData()
	{
		return array(
			'headers' => array('Foo', 'Bar'),
			'rows' => array(
				array('Joe', 'Bear'),
				array('Jimmy', 'Page'),
				array('Page', 'Not Found'),
			)
		);
	}
	
	public function getDataType()
	{
		return AdtDebugFilterDataSource::TYPE_TABULAR;
	}
}

?>