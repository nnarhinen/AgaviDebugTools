<?php

abstract class AdtDebugFilterDataSource extends AgaviParameterHolder
{
	const TYPE_TABULAR = 1;

	const TYPE_KEYVALUE = 2;

	const TYPE_LINEAR = 3;

	/**
	 * @var        AgaviContext An AgaviContext instance.
	 */
	protected $context = null;

	/**
	 * Retrieve the current application context.
	 *
	 * @return     AgaviContext The current Context instance.
	 */
	public final function getContext()
	{
		return $this->context;
	}

	/**
	 * Initialize this data source.
	 *
	 * @param      AgaviContext The current application context.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>AgaviInitializationException</b> If an error occurs while
	 *                                                 initializing.
	 *
	 */
	public function initialize(AgaviContext $context, array $parameters = array())
	{
		$this->context = $context;

		$this->setParameters($parameters);
	}

	/**
	 * Return the name of this data source
	 *
	 * @return string
	 */
	public function getName()
	{
		return get_class($this);
	}

	abstract public function getData();

	abstract public function getDataType();


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