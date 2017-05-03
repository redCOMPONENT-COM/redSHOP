<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_category_scroller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$config 		= Redconfiguration::getInstance();
$producthelper 	= productHelper::getInstance();
$redhelper     	= redhelper::getInstance();
$view      		= JRequest::getCmd('view', 'category');
$module_id 		= "mod_" . $module->id;
$document 		= JFactory::getDocument();
$input	 		= JFactory::getApplication()->input;
$itemId 		= $input->get('Itemid', 0, 'int');

$uri 			= JURI::getInstance();
$url 			= $uri->root();

$showProductName  = trim($params->get('show_product_name', 1));
$showAddToCart    = trim($params->get('show_addtocart', 1));
$showPrice        = trim($params->get('show_price', 1));
$showImage        = trim($params->get('show_image', 1));
$showReadMore     = trim($params->get('show_readmore', 1));
$showVat          = trim($params->get('show_vat', 1));

$scrollerwidth            = trim($params->get('scrollerwidth', 500));
$scrollerheight           = trim($params->get('scrollerheight', 200));
$thumbwidth               = trim($params->get('thumbwidth', 100));
$thumbheight              = trim($params->get('thumbheight', 100));
$productTitleEndSuffix 	  = trim($params->get('product_title_end_suffix', '...'));
$productTitleMaxChars     = trim($params->get('product_title_max_chars', 10));
$showDiscountPriceLayout  = trim($params->get('show_discountpricelayout', 1));
$pretext                  = trim($params->get('pretext', ''));

JLoader::import('helper', __DIR__);

$rows = ModRedshopCategoryScrollerHelper::getList($params);

require JModuleHelper::getLayoutPath('mod_redshop_category_scroller');
