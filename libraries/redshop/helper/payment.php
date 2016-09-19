<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Class Redshop Helper for Payment Methods
 *
 * @since  1.5
 */
class RedshopHelperPayment
{
	/**
	 * Payment Method type constant
	 */
	const TYPE = 'redshop_payment';

	/**
	 * Check for specific payment group type plugin - suffixed using given `type`
	 * Specially Checking for suffixed using `rs_payment_banktransfer` plugin
	 *
	 * @param   string  $name        Payment Plugin Element Name
	 * @param   string  $typeSuffix  Suffix to match
	 *
	 * @return  boolean  True when position found else false
	 */
	public static function isPaymentType($name, $typeSuffix = 'rs_payment_banktransfer')
	{
		$position = strpos($name, $typeSuffix);

		// Return false when given suffix is not found in string
		if ($position === false)
		{
			return false;
		}

		// True when position found
		return true;
	}

	/**
	 * Get payment method info
	 *
	 * @param   string  $name  Payment Method name - Null to get all plugin info
	 *
	 * @return  mixed   Object is return one payment method, array for all.
	 */
	public static function info($name = '')
	{
		if (!JPluginHelper::isEnabled(self::TYPE, $name))
		{
			throw new Exception(JText::sprintf('COM_REDSHOP_PAYMENT_IS_NOT_ENABLED', $name));
		}

		$plugins = JPluginHelper::getPlugin(self::TYPE, $name);

		if ($name == '' && is_array($plugins))
		{
			array_walk($plugins, function(&$plugin) {
				$plugin->params = new Registry($plugin->params);
			});
		}
		else
		{
			$plugins->params = new Registry($plugins->params);
		}

		return $plugins;
	}

	/**
	 * Load payment languages
	 *
	 * @since version
	 */
	public static function loadPaymentLanguages()
	{
		$redhelper = redhelper::getInstance();

		// Load payment plugin language file
		$payment_lang_list = $redhelper->getPlugins("redshop_payment");
		$language = JFactory::getLanguage();
		$base_dir = JPATH_ADMINISTRATOR;
		$language_tag = $language->getTag();

		for ($l = 0, $ln = count($payment_lang_list); $l < $ln; $l++)
		{
			$extension = 'plg_redshop_payment_' . $payment_lang_list[$l]->element;
			$language->load($extension, $base_dir, $language_tag, true);
		}
	}
}
