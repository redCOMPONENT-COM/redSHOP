<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redcategoryscroller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::import('helper', __DIR__);

// Standard parameters
$show_category_name = $params->get('show_category_name', "yes");
$show_addtocart     = $params->get('show_addtocart', "yes");
$show_price         = $params->get('show_price', "yes");

$thumbWidth  = $params->get('thumbwidth', 100);
$thumbHeight = $params->get('thumbheight', 100);

$ScrollSection        = $params->get('ScrollSection', 0);
$scrollBehavior       = $params->get('ScrollBehavior', 'scroll'); // scroll, alternate, slide
$scrollDirection      = $params->get('ScrollDirection', 'up');
$scrollHeight         = $params->get('ScrollHeight', 125);
$scrollWidth          = $params->get('ScrollWidth', 150);
$scrollAmount         = $params->get('ScrollAmount', 2);
$scrollDelay          = $params->get('ScrollDelay', 80);
$scrollAlign          = $params->get('ScrollAlign', 'center');
$ScrollTitles         = $params->get('ScrollTitles', 'yes');
$ScrollSpaceChar      = $params->get('ScrollSpaceChar', '&nbsp;');
$ScrollSpaceCharTimes = $params->get('ScrollSpaceCharTimes', 5);
$scrollLineChar       = $params->get('ScrollLineChar', '<br />');
$scrollLineCharTimes  = $params->get('ScrollLineCharTimes', 2);

// Customization parameters
$scrollCSSOverride        = $params->get('ScrollCSSOverride', 'no');
$scrollTextAlign          = $params->get('ScrollTextAlign', 'left');
$scrollTextWeight         = $params->get('ScrollTextWeight', 'normal');
$scrollTextSize           = $params->get('ScrollTextSize', 10);
$scrollTextColor          = $params->get('ScrollTextColor', '#000000');
$scrollBackgroundColor    = $params->get('ScrollBGColor', 'transparent');
$scrollMargin             = $params->get('ScrollMargin', 2);
$boxWidth                 = $params->get('boxwidth', 100);

$data = ModRedCategoryScrollerHelper::getList($params);

if ($data)
{
	require JModuleHelper::getLayoutPath('mod_redcategoryscroller', $params->get('layout', 'default'));
}
