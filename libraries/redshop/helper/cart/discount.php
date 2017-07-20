<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.7
 */
defined('_JEXEC') or die;
/**
 * Class Redshop Helper for Cart - Discount
 *
 * @since  2.0.7
 */
class RedshopHelperCartDiscount
{
	/**
	 * @param   string   $pdcextraids
	 * @param   integer  $productId
	 *
	 * @return  array<object>
	 *
	 * @since   2.0.7
	 */
	public static function getDiscountCalcDataExtra($pdcextraids = "", $productId = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')->from($db->quoteName('#__product_discount_calc_extra'));

		if (!empty($pdcextraids))
		{
			// Secure $pdcextraids
			if ($extraIds = explode(',', $pdcextraids))
			{
				$extraIds = Joomla\Utilities\ArrayHelper::toInteger($extraIds);
			}

			$query->where($db->quoteName('pdcextra_id') . ' IN (' . implode(',', $extraIds) . ')');
		}

		if ($productId)
		{
			$query->where($db->quoteName('product_id') . ' = ' . (int) $productId);
		}

		$query->order($db->quoteName('option_name'));

		return $db->setQuery($query)->loadObjectList();
	}
}
