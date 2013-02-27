<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die ('restricted access');

$adminproducthelper = new adminproducthelper();
$session            = JFactory::getSession();

$shipp_users_info_id  = $session->get('shipp_users_info_id');
$shipping_box_post_id = 0;

$d['user_id']         = $this->detail->user_id;
$d['users_info_id']   = $shipp_users_info_id;
$d['shipping_box_id'] = $shipping_box_post_id;
$d['ordertotal']      = $this->detail->order_total;
$d['order_subtotal']  = $this->detail->order_subtotal;

$responce = $adminproducthelper->replaceShippingMethod($d, $shipp_users_info_id, $this->detail->ship_method_id, $shipping_box_post_id);

echo $responce;

?>
