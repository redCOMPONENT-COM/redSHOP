<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('shopmenu', JPATH_ROOT . '/modules/mod_redshop_categories/helpers');

$mbtmenu = new ShopMenu($db, $params, $shopperGroupId);
$mbtmenu->genMenu();
