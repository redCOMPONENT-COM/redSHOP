<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_who_bought
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$category = $params->get('category', '');

if (is_array($category))
{
	$category = implode(',', $category);
}
else
{
	$category = trim($category);
}

$number_of_items        = trim($params->get('number_of_items', 5));
$thumbwidth             = trim($params->get('thumbwidth', 100));
$thumbheight            = trim($params->get('thumbheight', 100));
$sliderwidth            = trim($params->get('sliderwidth', 500));
$show_product_image     = trim($params->get('show_product_image', 1));
$show_addtocart_button  = trim($params->get('show_addtocart_button', 1));
$show_product_name      = trim($params->get('show_product_name', 1));
$product_title_linkable = trim($params->get('product_title_linkable', 1));
$show_product_price     = trim($params->get('show_product_price', 1));

$db = JFactory::getDbo();
$query = $db->getQuery(true)
	->select('p.product_id')
	->from($db->qn('#__redshop_product', 'p'))
	->where($db->qn('p.published') . ' = 1')
	->group('p.product_id');

if ($category != "")
{
	$query->leftJoin('#__redshop_product_category_xref AS pc ON pc.product_id = p.product_id')
		->where('pc.category_id IN (' . $category . ')');
}

$rows = array();

if ($productIds = $db->setQuery($query, 0, $number_of_items)->loadColumn())
{
	$query->clear()
		->where('p.product_id IN (' . implode(',', $productIds) . ')')
		->order('FIELD(p.product_id, ' . implode(',', $productIds) . ')');

	$user = JFactory::getUser();
	$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
		->select('CONCAT_WS(' . $db->q('.') . ', p.product_id, ' . (int) $user->id . ') AS concat_id');

	if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
	{
		RedshopHelperProduct::setProduct($rows);
		$rows = array_values($rows);
	}
}

require JModuleHelper::getLayoutPath('mod_redshop_who_bought');
