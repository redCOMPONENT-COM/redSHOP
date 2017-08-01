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

$shipp_users_info_id = $session->get('shipp_users_info_id');
$d['user_id'] = $this->detail->user_id;
$d['users_info_id'] = $shipp_users_info_id;
$d['ordertotal'] = $this->detail->order_total;
$d['order_subtotal'] = $this->detail->order_subtotal;

$responce = $adminproducthelper->replaceShippingMethod($d, $shipp_users_info_id, $this->detail->ship_method_id);

echo $responce;
