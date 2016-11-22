<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

// Initialize variables.

$showPrice               = trim($params->get('show_price', 0));
$thumbWidth              = trim($params->get('thumbwidth', 100));
$thumbHeight             = trim($params->get('thumbheight', 100));
$showShortDescription    = trim($params->get('show_short_description', 1));
$showReadmore            = trim($params->get('show_readmore', 1));
$showAddToCart           = trim($params->get('show_addtocart', 1));
$showDiscountPriceLayout = trim($params->get('show_discountpricelayout', 1));
$showDescription         = trim($params->get('show_desc', 1));
$showVat                 = trim($params->get('show_vat', 1));
$showStockroomStatus     = trim($params->get('show_stockroom_status', 1));
$image                   = trim($params->get('image', 0));

$uri 					 = JURI::getInstance();
$url 					 = $uri->root();
$itemId					 = JRequest::getInt('Itemid');
$user  					 = JFactory::getUser();
$document 				 = JFactory::getDocument();
$productHelper  		 = productHelper::getInstance();
$redHelper      		 = redhelper::getInstance();
$redTemplate    		 = Redtemplate::getInstance();
$extraField     		 = extraField::getInstance();
$stockRoomHelper 		 = rsstockroomhelper::getInstance();

JLoader::import('helper', __DIR__);

$moduleId = "mod_" . $module->id;

$rows = ModRedshopProductsHelper::getList($params);

require JModuleHelper::getLayoutPath('mod_redshop_products');
