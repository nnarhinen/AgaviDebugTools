<?php

interface AdtIDebugFilterDataSource
{
	const TYPE_TABULAR = 1;

	const TYPE_KEYVALUE = 2;

	const TYPE_LINEAR = 3;
	
	public function beforeExecuteOnce(AgaviExecutionContainer $container);

	public function afterExecuteOnce(AgaviExecutionContainer $container);

	public function beforeExecute(AgaviExecutionContainer $container);

	public function afterExecute(AgaviExecutionContainer $container);

	public function getData();
	
	public function getDataType();
}

?>