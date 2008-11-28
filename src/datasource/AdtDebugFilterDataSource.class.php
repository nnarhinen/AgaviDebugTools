<?php

abstract class AdtDebugFilterDataSource
{
	const TYPE_TABULAR = 1;

	const TYPE_KEYVALUE = 2;

	const TYPE_LINEAR = 3;
	

	abstract public function getData();
	
	abstract public function getDataType();

	public function getName()
	{
		return get_class($this);
	}

	public function beforeExecuteOnce(AgaviExecutionContainer $container)
	{
		
	}

	public function afterExecuteOnce(AgaviExecutionContainer $container)
	{
		
	}

	public function beforeExecute(AgaviExecutionContainer $container)
	{
		
	}

	public function afterExecute(AgaviExecutionContainer $container)
	{
		
	}
}

?>