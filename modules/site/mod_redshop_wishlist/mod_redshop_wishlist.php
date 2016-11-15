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

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

if (Redshop::getConfig()->get('MY_WISHLIST'))
{
	$result = ModRedWishList::getList($params);

	extract($result);

	$uri = JURI::getInstance();
	$url = $uri->root();

	$user      = JFactory::getUser();
	$redhelper = redhelper::getInstance();
	$Itemid    = $redhelper->getItemid();

	require JModuleHelper::getLayoutPath('mod_redshop_wishlist', $params->get('layout', 'default'));
}
