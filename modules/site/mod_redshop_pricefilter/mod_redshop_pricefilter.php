<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_pricefilter
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
$app  = JFactory::getApplication();
$user = JFactory::getUser();

$minslider                = trim($params->get('minslider', 0));
$maxslider                = trim($params->get('maxslider', 5000));
$category                 = $params->get('category', 0);

if (is_array($category))
{
	$category = implode(',', $category);
}
else
{
	$category = trim($category);
}

$count                    = trim($params->get('count', 5));
$image                    = trim($params->get('image', 0));
$thumbwidth               = trim($params->get('thumbwidth', 100));
$thumbheight              = trim($params->get('thumbheight', 100));
$show_price               = trim($params->get('show_price', 0));
$show_readmore            = trim($params->get('show_readmore', 1));
$show_addtocart           = trim($params->get('show_addtocart', 1));
$show_discountpricelayout = trim($params->get('show_discountpricelayout', 1));
$show_desc                = trim($params->get('show_desc', 1));

global $context;

$context     = 'product_id';
$texpricemin = $app->getUserStateFromRequest($context . 'texpricemin', 'texpricemin', $minslider);
$texpricemax = $app->getUserStateFromRequest($context . 'texpricemax', 'texpricemax', $maxslider);

require JModuleHelper::getLayoutPath('mod_redshop_pricefilter');
