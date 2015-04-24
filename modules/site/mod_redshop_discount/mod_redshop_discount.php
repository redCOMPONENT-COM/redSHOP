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

$time = time();
$db = JFactory::getDbo();
$query = $db->getQuery(true)
	->select('d.*')
	->from($db->qn('#__redshop_discount', 'd'))
	->leftJoin($db->qn('#__redshop_discount_shoppers', 'ds') . ' ON ds.discount_id = d.discount_id')
	->where('d.published = 1')
	->where('d.start_date <= ' . $db->q($time))
	->where('d.end_date >= ' . $db->q($time))
	->where('ds.shopper_group_id = ' . (int) RedshopHelperUser::getShopperGroup(JFactory::getUser()->id))
	->order('d.amount ASC');
$data = $db->setQuery($query)->LoadObjectList();

if ($data)
{
	require JModuleHelper::getLayoutPath('mod_redshop_discount', $params->get('layout', 'default'));
}
