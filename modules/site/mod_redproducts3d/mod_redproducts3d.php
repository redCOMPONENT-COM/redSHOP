<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redproducts3d
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$category = $params->get('category', array());

$count                  = trim($params->get('count', 2));
$stageWidth             = trim($params->get('stageWidth', 600));
$stageHeight            = trim($params->get('stageHeight', 400));
$thumbwidth             = trim($params->get('thumbwidth', 100));
$thumbheight            = trim($params->get('thumbheight', 100));
$radius                 = trim($params->get('radius', 230));
$focalBlur              = trim($params->get('focalBlur', 5));
$elevation              = trim($params->get('elevation', -50));
$enableImageReflection  = trim($params->get('enableImageReflection', 'yes'));
$enableimageStroke      = trim($params->get('enableimageStroke', 'yes'));
$enableMouseOverToolTip = trim($params->get('enableMouseOverToolTip', 'yes'));
$enableMouseOverEffects = trim($params->get('enableMouseOverEffects', 'yes'));

$db = JFactory::getDbo();
JLoader::import('redshop.library');

$leftjoin = "";
$and      = "";

if (is_array($category) && count($category) > 0)
{
	JArrayHelper::toInteger($category);
	$leftjoin .= "LEFT JOIN #__redshop_product_category_xref cx ON cx.product_id = p.product_id ";
	$and .= "AND cx.category_id IN (" . implode(',', $category) . ") ";
}

$sql = "SELECT * FROM #__redshop_product p "
	. $leftjoin
	. "WHERE p.published=1 "
	. $and
	. "LIMIT 0," . (int) $count;
$db->setQuery($sql);
$rows = $db->loadObjectList();

require JModuleHelper::getLayoutPath('mod_redproducts3d');
