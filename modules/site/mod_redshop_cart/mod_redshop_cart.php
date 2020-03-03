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
$document->addScript("modules/mod_redshop_cart/js/cart.js");

$showEmptyBtn = 0;

if ($params->get("checkout_empty") != 0)
{
	$showEmptyBtn = 1;
}

\RedshopHelperUtility::databaseToCart();

$outputView = $params->get('cart_output', 'simple');
$session     = \JFactory::getSession();
$cart        = $session->get('cart');

$layout =$params->get('layout', 'default');
$cart = \Redshop\Cart\Helper::getCart();
$totalQuantity = \Redshop\Cart\Helper::getTotalQuantity();
$twigParams = [];
$twigParams['token'] = \JSession::getFormToken();
$twigParams['app'] = \JFactory::getApplication();
$itemId = (int)RedshopHelperRouter::getCartItemId();
$getNewItemId = true;

if ($itemId != 0) {
    $menu = $app->getMenu();
    $item = $menu->getItem($itemId);

    $getNewItemId = false;

    if (isset($item->id) === false) {
        $getNewItemId = true;
    }
}

if ($getNewItemId) {
    $itemId = (int) \RedshopHelperRouter::getCategoryItemid();
}

$displayButton = \JText::_('MOD_REDSHOP_CART_CHECKOUT');

if ($buttonText != "") {
    $displayButton = $buttonText;
}

JFactory::getDocument()->addStyleDeclaration(
    '.mod_cart_checkout{background-color:' . Redshop::getConfig()->get('ADDTOCART_BACKGROUND') . ';}'
);

$twigParams['cartHtml'] = RedshopLayoutHelper::render(
    'cart.cart',
    array(
        'cartOutput' => $outputView,
        'totalQuantity' => $totalQuantity,
        'cart' => $cart,
        'showWithVat' => $showWithVat,
        'showShippingLine' => $showShippingLine,
        'showWithDiscount' => $showWithDiscount
    ),
    '',
    array(
        'component' => 'com_redshop'
    )
);

$twigParams['itemId'] = $itemId;
$twigParams['count'] = $totalQuantity;
$twigParams['showEmptyBtn'] = $showEmptyBtn;
$twigParams['displayButton'] = $displayButton;

print RedshopLayoutHelper::render(
    $layout,
    $twigParams,
    '',
    array(
        'component'     => 'com_redshop',
        'layoutType'    => 'Twig',
        'layoutOf'      => 'module',
        'prefix'        => $moduleName
    ));
