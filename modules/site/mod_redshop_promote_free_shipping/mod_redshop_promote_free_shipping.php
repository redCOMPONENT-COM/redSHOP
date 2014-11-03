<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_promote_free_shipping
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

$option = JRequest::getCmd('option');
JLoader::import('redshop.library');

if ($option != 'com_redshop')
{
	require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
	JLoader::load('RedshopHelperAdminConfiguration');
	$Redconfiguration = new Redconfiguration;
	$Redconfiguration->defineDynamicVars();
}

JLoader::load('RedshopHelperAdminShipping');
$shippinghelper = new shipping;
$shipping_rate_id = $params->get("shipping_rate_id");
$text = $shippinghelper->getfreeshippingRate($shipping_rate_id);

require JModuleHelper::getLayoutPath('mod_redshop_promote_free_shipping');

