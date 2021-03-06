<?php

/**
 * The base view from which all project views inherit.
 */
class AdtDemoBaseView extends AgaviView
{
	/**
	 * Handles output types that are not handled elsewhere in the view. The
	 * default behavior is to simply throw an exception.
	 *
	 * @param      AgaviRequestDataHolder The request data associated with
	 *                                    this execution.
	 *
	 * @throws     AgaviViewException if the output type is not handled.
	 */
	public final function execute(AgaviRequestDataHolder $rd)
	{
		throw new AgaviViewException(sprintf(
			'The view "%1$s" does not implement an "execute%3$s()" method to serve '.
			'the output type "%2$s", and the base view "%4$s" does not implement an '.
			'"execute%3$s()" method to handle this situation.',
			get_class($this),
			$this->container->getOutputType()->getName(),
			ucfirst(strtolower($this->container->getOutputType()->getName())),
			get_class()
		));
	}

	/**
	 * Prepares the HTML output type.
	 *
	 * @param      AgaviRequestDataHolder The request data associated with
	 *                                    this execution.
	 * @param      string The layout to load.
	 */
	public function setupHtml(AgaviRequestDataHolder $rd, $layoutName = null)
	{
		$this->context->getLoggerManager()->log('Look Ma! Me debugging '.__CLASS__, 'debug');
		if ($layoutName === null && $this->container->getParameter('is_slot')) {
			$layoutName = 'slot';
		}
		$this->loadLayout($layoutName);

		if (!$this->container->getParameter('is_slot')) {
			$menuContainer = $this->createSlotContainer('Default', 'Menu');
			$this->getLayer('decorator')->setSlot('menu', $menuContainer);
		}
	}
}

?>