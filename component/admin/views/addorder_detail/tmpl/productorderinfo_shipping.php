<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$adminproducthelper = RedshopAdminProduct::getInstance();
$session = JFactory::getSession();

$ordertotal = $session->get('ordertotal');
$ordersubtotal = $session->get('ordersubtotal');
$user_id = $session->get('order_user_id');
$shipp_users_info_id = $session->get('shipp_users_info_id');

$d['user_id'] = $user_id;
$d['users_info_id'] = $shipp_users_info_id;
$d['ordertotal'] = $ordertotal;
$d['order_subtotal'] = $ordersubtotal;

$responce = $adminproducthelper->replaceShippingMethod($d, $shipp_users_info_id, 0);

echo $responce;

?>
