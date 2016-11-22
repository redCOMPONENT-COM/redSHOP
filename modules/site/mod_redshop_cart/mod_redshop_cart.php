<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_cart
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::import('helper', __DIR__);

$showWithVat      = trim($params->get('show_with_vat', 0));
$buttonText        = trim($params->get('button_text', ''));
$showShippingLine = ($params->get('show_shipping_line', 0));
$showWithDiscount = ($params->get('show_with_discount', 0));

$showEmptyBtn = 0;

if ($params->get("checkout_empty") != 0)
{
	$showEmptyBtn = 1;
}

$outputView = $params->get('cart_output', 'simple');
$cart = ModRedshopCartHelper::processCart();
$count = isset($cart['idx'])? $cart['idx']: 0;

$itemId = ModRedshopCartHelper::getItemId();

require JModuleHelper::getLayoutPath('mod_redshop_cart');
