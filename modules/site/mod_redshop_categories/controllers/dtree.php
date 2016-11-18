<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once $absoluteModulePath . '/helpers/dtree.php';

$catdatas = ModDtreeMenuHelper::traverseTreeDown($myMenuContent, $categoryId, '0', $params, $shopperGroupId);

$rootLabel = $params->get('root_label', 'Shop');
$uri = JURI::getInstance();
$urlpath = $uri->root();
$urllive = $urlpath;
$liveModulePath     = $urlpath . 'modules/mod_redshop_categories';
$classSfx = $params->get('class_sfx', '');
$classMainLevel = "mainlevel_redshop" . $classSfx;
$objhelper       = new redhelper;
$Itemid          = JRequest::getInt('Itemid', '1');

/* dTree API, default value
* change to fit your needs **/
$useSelection   = 'true';
$useLines       = 'true';
$useIcons       = 'true';
$useStatusText  = 'false';
$useCookies     = 'false';
$closeSameLevel = 'false';

// If all folders should be open, we will ignore the closeSameLevel
$openAll = 'false';

if ($openAll == "true")
{
	$closeSameLevel = "false";
}

// What should be used as the base of the tree?
// ( could be *first* menu item, *site* name, *module*, *menu* name or *text* )
$base = "first";

// In case *text* should be the base node, what text should be displayed?
$basetext = "";

/**
 * How many menu items in this menu?
 * Create a unique tree identifier, in case multiple dtrees are used
 * Max one per module
 * */
$tree = "d" . uniqid("tree_");

require JModuleHelper::getLayoutPath('mod_redshop_categories', $params->get('layout', 'dtree'));
