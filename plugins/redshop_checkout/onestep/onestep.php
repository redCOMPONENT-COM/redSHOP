<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

/**
 * Plugins redSHOP One step checkout
 *
 * @since  1.0
 */
class PlgRedshop_CheckoutOnestep extends JPlugin
{
	/**
	 * Constructor - note in Joomla 2.5 PHP4.x is no longer supported so we can use this.
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * render redSHOP One step checkout
	 *
	 * @return void
	 */
	public function onRenderOnstepCheckout()
	{
		echo RedshopLayoutHelper::render(
			'onestep',
			array(),
			JPATH_PLUGINS . '/redshop_checkout/onestep/layouts'
		);
	}
}
