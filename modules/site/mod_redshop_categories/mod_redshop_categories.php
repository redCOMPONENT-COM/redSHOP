<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JLoader::import('redshop.library');

$uri = JURI::getInstance();
$urlpath = $uri->root();
$user = JFactory::getUser();
$db = JFactory::getDbo();
$input = JFactory::getApplication()->input;

$liveModulePath     = $urlpath . 'modules/mod_redshop_categories';
$absoluteModulePath = JPATH_SITE . '/modules/mod_redshop_categories';

// Get category id
$categoryId = $input->get('cid', '0', 'int');
$categoryId = $params->get('redshop_category', 0);
unset($GLOBALS['category_info']['category_tree']);

// Get Item id
$Itemid = JRequest::getInt('Itemid', '1');

JLoader::import('helper', __DIR__);

/* Get module parameters */
$showCountProducts = $params->get('show_noofproducts', 'yes');
$menutype = $params->get('menutype', "links");
$classSfx = $params->get('class_sfx', '');
$pretext = $params->get('pretext', '');
$posttext = $params->get('posttext', '');
$menuOrientation = $params->get('menu_orientation', 'hbr');
$rootLabel = $params->get('root_label', 'Shop');
$categorySortType = $params->get('categorysorttype', 'catname');
$use_shoppergroup = $params->get('use_shoppergroup', 'no');

$shopperGroupId = ModProMenuHelper::getShopperGroupId($use_shoppergroup, $user);

require_once __DIR__ . '/controllers/controller.php';
