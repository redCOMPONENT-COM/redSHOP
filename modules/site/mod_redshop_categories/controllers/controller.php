<?php

/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$classMainLevel = "mainlevel_redshop" . $class_sfx;

echo $pretext;

if ($menutype == 'links')
{
	echo $redproduct_menu->get_category_tree($params, $categoryId, $classMainLevel, $listCssClass = "mm123", $highlightedStyle = "font-style:italic;", $shopperGroupId);
}
else
{
	require_once $menutype . '.php';
}

echo $posttext;
