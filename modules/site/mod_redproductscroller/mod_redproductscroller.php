<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redproductscroller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$document = JFactory::getDocument();
JHTML::script('com_redshop/redbox.js', false, true);
JHtml::script('com_redshop/attribute.js', false, true);
JHtml::script('com_redshop/common.js', false, true);

$module_id = "mod_" . $module->id;
$NumberOfProducts = 5;
$featuredProducts = false;
$ScrollBehavior = 'scroll';
$ScrollDirection = 'up';
$ScrollHeight = '125';
$ScrollWidth = '150';
$ScrollAmount = '2';
$ScrollDelay = '80';
$ScrollAlign = 'center';
$ScrollSortMethod = 'random';
$ScrollTitles = 'yes';
$ScrollSpaceChar = '&nbsp;';
$ScrollSpaceCharTimes = 5;
$ScrollLineChar = '<br />';
$ScrollLineCharTimes = 2;
$ScrollSection = 0;

// CSS override -----------------------

$ScrollCSSOverride = 'no';
$ScrollTextAlign = 'left';
$ScrollTextWeight = 'normal';
$ScrollTextSize = '10';
$ScrollTextColor = '#000000';
$ScrollBGColor = 'transparent';
$ScrollMargin = '2';
$show_discountpricelayout = 0;
$boxwidth = '100';

$showProductName   = $params->get('show_product_name', "yes");
$showAddToCart    = $params->get('show_addtocart', "yes");
$showPrice        = $params->get('show_price', "yes");
$categoryId       = JRequest::getInt('cid', 0);

$thumbwidth        = $params->get('thumbwidth', 100);
$thumbheight       = $params->get('thumbheight', 100);

$NumberOfProducts = $params->get('NumberOfProducts', $NumberOfProducts);
$featuredProducts = $params->get('featuredProducts', $featuredProducts);

$ScrollSection        = $params->get('ScrollSection', $ScrollSection);
$ScrollBehavior       = $params->get('ScrollBehavior', $ScrollBehavior);
$ScrollDirection      = $params->get('ScrollDirection', $ScrollDirection);
$ScrollHeight         = $params->get('ScrollHeight', $ScrollHeight);
$ScrollWidth          = $params->get('ScrollWidth', $ScrollWidth);
$ScrollAmount         = $params->get('ScrollAmount', $ScrollAmount);
$ScrollDelay          = $params->get('ScrollDelay', $ScrollDelay);
$ScrollAlign          = $params->get('ScrollAlign', $ScrollAlign);
$ScrollSortMethod     = $params->get('ScrollSortMethod', $ScrollSortMethod);
$ScrollTitles         = $params->get('ScrollTitles', $ScrollTitles);
$ScrollSpaceChar      = $params->get('ScrollSpaceChar', $ScrollSpaceChar);
$ScrollSpaceCharTimes = $params->get('ScrollSpaceCharTimes', $ScrollSpaceCharTimes);
$ScrollLineChar       = $params->get('ScrollLineChar', $ScrollLineChar);
$ScrollLineCharTimes  = $params->get('ScrollLineCharTimes', $ScrollLineCharTimes);

// Customization mammeters
$ScrollCSSOverride        = $params->get('ScrollCSSOverride', $ScrollCSSOverride);
$ScrollTextAlign          = $params->get('ScrollTextAlign', $ScrollTextAlign);
$ScrollTextWeight         = $params->get('ScrollTextWeight', $ScrollTextWeight);
$ScrollTextSize           = $params->get('ScrollTextSize', $ScrollTextSize);
$ScrollTextColor          = $params->get('ScrollTextColor', $ScrollTextColor);
$ScrollBGColor            = $params->get('ScrollBGColor', $ScrollBGColor);
$ScrollMargin             = $params->get('ScrollMargin', $ScrollMargin);
$show_discountpricelayout = $params->get('show_discountpricelayout', $show_discountpricelayout);
$boxwidth                 = $params->get('boxwidth', $boxwidth);

JLoader::import('helper', __DIR__);
$producthelper = productHelper::getInstance();
$redhelper     = redhelper::getInstance();

/**
 * Load Products
 **/
$rows = ModRedProductScrollerrHelper::getredProductSKU($NumberOfProducts, $ScrollSortMethod, $categoryId, $featuredProducts);

/**
 * Display Product Scroller
 **/

require JModuleHelper::getLayoutPath('mod_redproductscroller', $params->get('layout', 'default'));
