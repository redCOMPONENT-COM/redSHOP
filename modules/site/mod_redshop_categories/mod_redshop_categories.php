<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$uri = JURI::getInstance();
$urlpath = $uri->root();
$user = JFactory::getUser();
$db = JFactory::getDbo();
$liveModulePath     = $urlpath . 'modules/mod_redshop_categories';
$absoluteModulePath = JPATH_SITE . '/modules/mod_redshop_categories';

// Get category id
$categoryId = JRequest::getInt('cid');
unset($GLOBALS['category_info']['category_tree']);

// Get Item id
$Itemid = JRequest::getInt('Itemid', '1');

JLoader::import('helper', __DIR__);

/* Get module parameters */
$show_noofproducts = $params->get('show_noofproducts', 'yes');
$menutype = $params->get('menutype', "links");
$class_sfx = $params->get('class_sfx', '');
$pretext = $params->get('pretext', '');
$posttext = $params->get('posttext', '');
$menu_orientation = $params->get('menu_orientation', 'hbr');
$root_label = $params->get('root_label', 'Shop');
$categorySortType = $params->get('categorysorttype', 'catname');
$use_shoppergroup = $params->get('use_shoppergroup', 'no');

$shopperGroupId = ModProMenuHelper::getShopperGroupId($use_shoppergroup, $user);

require_once __DIR__ . '/controllers/controller.php';
