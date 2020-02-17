<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_cart
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$moduleName = 'mod_redshop_cart';

$showWithVat      = trim($params->get('show_with_vat', 0));
$buttonText       = trim($params->get('button_text', ''));
$showShippingLine = ($params->get('show_shipping_line', 0));
$showWithDiscount = ($params->get('show_with_discount', 0));

$document = \JFactory::getDocument();
$document->addStyleSheet("modules/mod_redshop_cart/css/cart.css");

$showEmptyBtn = 0;

if ($params->get("checkout_empty") != 0)
{
	$showEmptyBtn = 1;
}

\RedshopHelperUtility::databaseToCart();

$outputView = $params->get('cart_output', 'simple');
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

echo RedshopLayoutHelper::render(
    $layout,
    $twigParams,
    '',
    array(
        'component'     => 'com_redshop',
        'layoutType'    => 'Twig',
        'layoutOf'      => 'module',
        'prefix'        => $moduleName
    ));
