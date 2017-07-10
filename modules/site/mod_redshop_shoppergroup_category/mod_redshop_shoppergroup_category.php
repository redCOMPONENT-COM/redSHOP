<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergroup_category
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
JLoader::import('helper', __DIR__);

$list      	 = RedModMenuHelper::getList($params);
$app       	 = JFactory::getApplication();
$menu      	 = $app->getMenu();
$active    	 = $menu->getActive();
$activeId  	 = isset($active) ? $active->id : $menu->getDefault()->id;
$path      	 = isset($active) ? $active->tree : array();
$showAll   	 = $params->get('showAllChildren');
$classSuffix = htmlspecialchars($params->get('class_sfx'));

if (count($list))
{
	require JModuleHelper::getLayoutPath('mod_redshop_shoppergroup_category', $params->get('layout', 'default'));
}
