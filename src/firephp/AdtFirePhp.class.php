<?php

/**
 *
 * AdtFirePhp extends FirePHP and overrides user-agent header
 * fetching.
 *
 * @author     Michael Stolovitzsky
 */
class AdtFirePhp extends FirePHP
{

	protected $context;

	public function setContext(AgaviContext $context)
	{
		$this->context = $context;
	}

	public function getUserAgent()
	{
		$rd = $this->context->getRequest()->getRequestData();
		return $rd->getHeader('USER_AGENT');
	}

	/**
	 * Gets singleton instance of FirePHP
	 *
	 * @param boolean $AutoCreate
	 * @return FirePHP
	 */
	public static function getInstance($AutoCreate=false)
	{
		// Workaround, FirePHP 1.0 instantiates the parent class so when ADT
		// gets around to instantiate "itself", self::$instance already points to
		// a FirePHP_Insight object. If so, we nuke the shit out of it.
		if (($AutoCreate === true && !self::$instance) || get_class(self::$instance) == 'FirePHP_Insight') {
			self::init();
		}
		return self::$instance;
	}

	/**
	 * Creates FirePHP object and stores it for singleton access
	 *
	 * @return FirePHP
	 */
	public static function init()
	{
		return self::setInstance(new self());
	}

}

?>