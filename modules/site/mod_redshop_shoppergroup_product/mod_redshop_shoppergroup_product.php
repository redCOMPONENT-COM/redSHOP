<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergroup_product
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
$image                   = trim($params->get('image', 0));
$showPrice               = trim($params->get('show_price', 0));
$thumbWidth              = trim($params->get('thumbwidth', 100));
$thumbHeight             = trim($params->get('thumbheight', 100));
$showShortDescription    = trim($params->get('show_short_description', 1));
$showReadmore            = trim($params->get('show_readmore', 1));
$showAddToCart           = trim($params->get('show_addtocart', 1));
$showDiscountPriceLayout = trim($params->get('show_discountpricelayout', 1));
$showDesc                = trim($params->get('show_desc', 1));
$showVat                 = trim($params->get('show_vat', 1));

$productHelper = productHelper::getInstance();
$redHelper     = redhelper::getInstance();
$redTemplate   = Redtemplate::getInstance();
$extraField    = extraField::getInstance();

$uri = JURI::getInstance();
$url = $uri->root();

$itemId    = JRequest::getInt('Itemid');
$user      = JFactory::getUser();
$view      = JRequest::getCmd('view');
$getOption = JRequest::getCmd('option');
$moduleId  = "mod_" . $module->id;

$document = JFactory::getDocument();

// Include the syndicate functions only once
JLoader::import('helper', __DIR__);

$rows = ModRedshopShopperGroupProduct::getList($params);

if (count($rows))
{
	require JModuleHelper::getLayoutPath('mod_redshop_shoppergroup_product', $params->get('layout', 'default'));
}
