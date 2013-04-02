<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('restricted access');

$adminproducthelper = new adminproducthelper();
$session = JFactory::getSession();

$ordertotal = $session->get('ordertotal');
$ordersubtotal = $session->get('ordersubtotal');
$user_id = $session->get('order_user_id');
$shipp_users_info_id = $session->get('shipp_users_info_id');
$shipping_box_post_id = 0;

$d['user_id'] = $user_id;
$d['users_info_id'] = $shipp_users_info_id;
$d['shipping_box_id'] = $shipping_box_post_id;
$d['ordertotal'] = $ordertotal;
$d['order_subtotal'] = $ordersubtotal;

$responce = $adminproducthelper->replaceShippingMethod($d, $shipp_users_info_id, 0, $shipping_box_post_id);

echo $responce;

?>
