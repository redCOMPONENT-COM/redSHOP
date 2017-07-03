<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergrouplogo
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::import('helper', __DIR__);

$portalLogo = ModRedshopShopperGroupLogoHelper::getPortalLogo($params);

require JModuleHelper::getLayoutPath('mod_redshop_shoppergrouplogo');
