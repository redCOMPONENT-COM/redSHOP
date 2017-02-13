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
$image                    = trim($params->get('image', 0));
$show_price               = trim($params->get('show_price', 0));
$thumbwidth               = trim($params->get('thumbwidth', 100));
$thumbheight              = trim($params->get('thumbheight', 100));
$show_short_description   = trim($params->get('show_short_description', 1));
$show_readmore            = trim($params->get('show_readmore', 1));
$show_addtocart           = trim($params->get('show_addtocart', 1));
$show_discountpricelayout = trim($params->get('show_discountpricelayout', 1));
$show_desc                = trim($params->get('show_desc', 1));
$show_vat                 = trim($params->get('show_vat', 1));

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$rows = ModRedshopShopperGroupProduct::getList($params);

if ($rows)
{
	require JModuleHelper::getLayoutPath('mod_redshop_shoppergroup_product', $params->get('layout', 'default'));
}
