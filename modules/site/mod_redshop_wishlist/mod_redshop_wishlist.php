<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_wishlist
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('helper', __DIR__);

$moduleClassSuffix = htmlspecialchars($params->get('moduleclass_sfx'));

if (Redshop::getConfig()->get('MY_WISHLIST'))
{
	$wishList = ModRedshopWishlistHelper::getList();

	require JModuleHelper::getLayoutPath('mod_redshop_wishlist', $params->get('layout', 'default'));
}