<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_cart
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$show_with_vat      = trim($params->get('show_with_vat', 0));
$button_text        = trim($params->get('button_text', ''));
$show_shipping_line = ($params->get('show_shipping_line', 0));
$show_with_discount = ($params->get('show_with_discount', 0));

$document = JFactory::getDocument()->addStyleSheet("modules/mod_redshop_cart/css/cart.css");
$show_empty_btn = 0;

if ($params->get("checkout_empty") != 0)
{
	$show_empty_btn = 1;
}

// Load Model Cart to calculate Cart Total
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_redshop/models');
$model = JModelLegacy::getInstance("Cart", "RedshopModel");

// Helper object
$helper = redhelper::getInstance();
RedshopHelperUtility::databaseToCart();

$output_view = $params->get('cart_output', 'simple');
$session     = JFactory::getSession();
$cart        = $session->get('cart');

if (count($cart) <= 0 || $cart == "")
{
	$cart = array();
}

$idx = 0;

if (is_array($cart) && !array_key_exists("quotation_id", $cart))
{
	if (isset($cart['idx']))
	{
		$idx = $cart['idx'];
	}
}

$count = 0;

for ($i = 0; $i < $idx; $i++)
{
	$count += $cart[$i]['quantity'];
}

$session->set('cart', $cart);

require JModuleHelper::getLayoutPath('mod_redshop_cart');
