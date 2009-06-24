<?php

/**
 * @author     Luke Dywicki <luke@code-house.org>
 */
class AdtDoctrineDataSource extends AdtDebugFilterDataSource
{
	/**
	 * @var        Doctrine_Connection_Profiler Doctrine connection event listener.
	 */
	private $profiler;

	/**
	 * @var        array Data prepared for rendering.
	 */
	private $rows;

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
		parent::initialize($context, $parameters);
		$conn = $this->context->getDatabaseConnection($this->getParameter('database_name'));

		if ($conn instanceof Doctrine_Connection) {
			$this->profiler = new Doctrine_Connection_Profiler();
			$conn->addListener($this->profiler);
		}
	}

	protected final function getQueries() {
		if ($this->rows == null) {
			$this->rows = array();
			foreach ($this->profiler as $event) {
				$info = null;
				switch ($event->getCode()) {
					case Doctrine_Event::STMT_EXECUTE: $info = 'Query execution'; break;
					case Doctrine_Event::TX_BEGIN: $info = 'Transaction begin'; break;
					case Doctrine_Event::TX_COMMIT: $info = 'Transatction commit'; break;
					case Doctrine_Event::TX_ROLLBACK: $info = 'Transatction rollback'; break;
				}
				if ($info != null) {
					$this->rows[] = array($info, $event->getElapsedSecs(), $event->getQuery(),
					    $event->getParams()
					);
				}
			}
		}
		return $this->rows;
	}

	/**
	 * Return the name of this data source
	 *
	 * @return     string
	 */
	public function getName()
	{
		$queries = 0;
		if ($this->profiler != null) {
			$queries = sizeof($this->getQueries());
		}
		return sprintf('Doctine Query Log (%d %s)', $queries, $queries > 1 ? 'queries' : 'query');
	}

	public function getDataType()
	{
		return AdtDebugFilterDataSource::TYPE_TABULAR;
	}

	public function getData()
	{
		$rows = array();
		foreach ($this->getQueries() as $key => $value) {
			$rows[] = array($value[0], $value[1], $value[2]);
			foreach ($value[3] as $param => $value) {
				$rows[] = array('Parameter', 'n/a', 'Parameter \''. $param .'\' is bound to: '. $value);
			}
		}

		return array(
			'headers' => array('Type', 'Microtime', 'Message'),
			'rows' => $rows,
		);
	}


}

?>