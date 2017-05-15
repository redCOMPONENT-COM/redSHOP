<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
	 * @param   string $name       Payment Plugin Element Name
	 * @param   string $typeSuffix Suffix to match
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
	 * @param   string $name Payment Method name - Null to get all plugin info
	 *
	 * @return  mixed   Object is return one payment method, array for all.
	 *
	 * @throws  Exception
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
			array_walk($plugins, function (&$plugin)
			{
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
	 * @param   boolean  $all  True for all (discover, enabled, disabled). False for just enabled only.
	 *
	 * @return   void
	 *
	 * @since   2.0.2
	 */
	public static function loadLanguages($all = false)
	{
		// Load payment plugin language file
		if ($all)
		{
			$paymentsLangList = RedshopHelperUtility::getPlugins("redshop_payment");
		}
		else
		{
			$paymentsLangList = RedshopHelperUtility::getPlugins("redshop_payment", 1);
		}

		$language = JFactory::getLanguage();

		for ($index = 0, $ln = count($paymentsLangList); $index < $ln; $index++)
		{
			$extension = 'plg_redshop_payment_' . $paymentsLangList[$index]->element;
			$language->load($extension, JPATH_ADMINISTRATOR, $language->getTag(), true);
			$language->load(
				$extension,
				JPATH_PLUGINS . '/' . $paymentsLangList[$index]->folder . '/' . $paymentsLangList[$index]->element,
				$language->getTag(),
				true
			);
		}
	}

	/**
	 * Method for check if order has this payment is update yet?
	 *
	 * @param   integer $orderId       Order ID
	 * @param   mixed   $transactionId Order payment transaction id
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public static function orderPaymentNotYetUpdated($orderId, $transactionId)
	{
		if (empty($orderId) || empty($transactionId))
		{
			return false;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__redshop_order_payment'))
			->where($db->qn('order_id') . ' = ' . (int) $orderId)
			->where($db->qn('order_payment_trans_id') . ' = ' . $db->quote($transactionId));

		$result = $db->setQuery($query)->loadResult();

		if (!$result)
		{
			return true;
		}

		return false;
	}
}
