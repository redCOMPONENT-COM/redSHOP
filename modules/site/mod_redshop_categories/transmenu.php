<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Loads main class file
 */
global $urlpath, $js_src;
$live_module_dir     = $urlpath . 'modules/mod_redshop_categories';
$absolute_module_dir = JPATH_SITE . '/modules/mod_redshop_categories';

$params->set('module_name', 'ShopMenu');
$params->set('module', 'transmenu');
$params->set('absPath', $absolute_module_dir . '/' . $params->get('module'));
$params->set('LSPath', $live_module_dir . '/' . $params->get('module'));


include_once $params->get('absPath') . '/Shop_Menu.php';


$db      = JFactory::getDbo();
$mbtmenu = new Shop_Menu($db, $params, $shopper_group_id);

$mbtmenu->genMenu();
