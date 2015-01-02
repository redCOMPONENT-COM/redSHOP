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
require_once dirname(__FILE__) . '/helper.php';

$list      = redmodMenuHelper::getList($params);
$app       = JFactory::getApplication();
$menu      = $app->getMenu();
$active    = $menu->getActive();
$active_id = isset($active) ? $active->id : $menu->getDefault()->id;
$path      = isset($active) ? $active->tree : array();
$showAll   = $params->get('showAllChildren');
$class_sfx = htmlspecialchars($params->get('class_sfx'));

if (count($list))
{
	require JModuleHelper::getLayoutPath('mod_redshop_shoppergroup_category', $params->get('layout', 'default'));
}
