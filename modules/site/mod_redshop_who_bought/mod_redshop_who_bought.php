<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_who_bought
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$thumbWidth           = trim($params->get('thumbwidth', 100));
$thumbHeight          = trim($params->get('thumbheight', 100));
$sliderWidth          = trim($params->get('sliderwidth', 500));
$showProductImage     = trim($params->get('show_product_image', 1));
$showAddToCart 		  = trim($params->get('show_addtocart_button', 1));
$showProductName      = trim($params->get('show_product_name', 1));
$productTitleLinkable = trim($params->get('product_title_linkable', 1));
$showProductPrice     = trim($params->get('show_product_price', 1));

$productHelper 	= productHelper::getInstance();
$redHelper 		= redhelper::getInstance();
$redTemplate 	= Redtemplate::getInstance();
$extraField 	= extraField::getInstance();
$moduleId 		= "mod_" . $module->id;

$itemId = JRequest::getInt('Itemid');
$user = JFactory::getUser();
$document = JFactory::getDocument();

JLoader::import('helper', __DIR__);

$rows = ModRedshopWhoBoughtProductHelper::getList($params);

require JModuleHelper::getLayoutPath('mod_redshop_who_bought');
