<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_discount
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Helper for mod_redshop_discount
 *
 * @since  1.7.0
 */
abstract class ModRedshopDiscountHelper
{
	/**
	 * Retrieve a list of article
	 *
	 * @param   \Joomla\Registry\Registry  &$params  Module parameters
	 *
	 * @return  mixed
	 */
	public static function getList(&$params)
	{
		$time = time();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select(
				$db->qn(
					[
						'd.discount_id', 'd.name', 'd.amount',
						'd.condition', 'd.discount_amount', 'd.discount_type',
						'd.start_date', 'd.end_date', 'd.published'
					]
				)
			)
			->from($db->qn('#__redshop_discount', 'd'))
			->leftJoin($db->qn('#__redshop_discount_shoppers', 'ds') . ' ON ' . $db->qn('ds.discount_id') . ' = ' . $db->qn('d.discount_id'))
			->where($db->qn('d.published') . ' = 1')
			->where($db->qn('d.start_date') . ' <= ' . $db->q($time))
			->where($db->qn('d.end_date') . ' >= ' . $db->q($time))
			->where($db->qn('ds.shopper_group_id') . ' = ' . (int) RedshopHelperUser::getShopperGroup(JFactory::getUser()->id))
			->order($db->qn('d.amount') . ' ASC');
		$data = $db->setQuery($query)->LoadObjectList();

		return $data;
	}
}
