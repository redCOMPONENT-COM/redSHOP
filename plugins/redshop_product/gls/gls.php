<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Update Shipping information for GLS Shipping Plugin
 *
 * @since  1.3.3.1
 */
class PlgRedshop_ProductGls extends JPlugin
{
	/**
	 * Method will trigger on After redSHOP Order Place to update GLS shipping info
	 *
	 * @param   array   $cart         Cart information Array
	 * @param   object  $orderResult  Order information Object
	 *
	 * @return  void
	 */
	public function afterOrderPlace($cart, $orderResult)
	{
		if (empty($orderResult->shop_id))
		{
			return;
		}

		$orderShippingInfo = RedshopShippingRate::decrypt($orderResult->ship_method_id);

		if ('plgredshop_shippingdefault_shipping_gls' != strtolower($orderShippingInfo[0]))
		{
			return;
		}

		$locationInfo = explode("|", trim($orderResult->shop_id));

		if (count($locationInfo) <= 0)
		{
			return;
		}

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$companyName = 'ServicePointID:' . $locationInfo[0] . ':PostDanmark';
		$city = explode('###', $locationInfo[7]);
		$city = trim($city[0]);

		// Create the base update statement.
		$query->update($db->qn('#__redshop_order_users_info'))
			->set($db->qn('company_name') . ' = ' . $db->q($companyName))
			->set($db->qn('firstname') . ' = ' . $db->q($locationInfo[1]))
			->set($db->qn('lastname') . ' = ' . $db->q(''))
			->set($db->qn('address') . ' = ' . $db->q($locationInfo[2]))
			->set($db->qn('zipcode') . ' = ' . $db->q($locationInfo[3]))
			->set($db->qn('city') . ' = ' . $db->q($city))
			->where($db->qn('order_id') . ' = ' . (int) $orderResult->order_id)
			->where($db->qn('address_type') . ' = ' . $db->q('ST'));

		// Set the query and execute the update.
		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}
	}
}
