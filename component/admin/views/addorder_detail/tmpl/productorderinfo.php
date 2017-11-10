<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');

$producthelper = productHelper::getInstance();
$session       = JFactory::getSession();

$post = JFactory::getApplication()->input->get->getArray();

$ordertotal          = $post['ordertotal'];
$ordersubtotal       = $post['ordersubtotal'];
$productarr          = $post['productarr'];
$qntarr              = $post['qntarr'];
$user_id             = $post['order_user_id'];
$shipp_users_info_id = $post['shipp_users_info_id'];
$userinfo            = $producthelper->getUserInformation($user_id, "BT");

if ($shipp_users_info_id == 0 && count($userinfo) > 0)
{
	$shipp_users_info_id = $userinfo->users_info_id;
}
$productItem = explode(",", $productarr);
$qntItem     = explode(",", $qntarr);

$cart = array();

for ($pi = 0; $pi < count($productItem); $pi++)
{
	$cart[$pi]['product_id'] = $productItem[$pi];
	$cart[$pi]['quantity']   = $qntItem[$pi];
}

$cart['idx']   = count($cart);
$this->billing = $userinfo;
$session->set('order_user_id', $user_id);
$session->set('shipp_users_info_id', $shipp_users_info_id);
$session->set('ordertotal', $ordertotal);
$session->set('ordersubtotal', $ordersubtotal);
\Redshop\Cart\Session\Helper::setCart($cart);

echo "<div id='paymentblock'>" . $this->loadTemplate('payment') . "</div>";
echo "<div id='shippingblock'>" . $this->loadTemplate('shipping') . "</div>";
