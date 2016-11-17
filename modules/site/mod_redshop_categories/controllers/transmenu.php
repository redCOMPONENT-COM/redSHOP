<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require JModuleHelper::getLayoutPath('mod_redshop_categories', $params->get('layout', 'transmenu/Shop_Menu'));
$mbtmenu = new Shop_Menu($db, $params, $shopperGroupId);
$mbtmenu->genMenu();
