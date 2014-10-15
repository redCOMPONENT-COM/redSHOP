<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_GIFTCARD_HINT'); ?></b><br /><br />
{customer_quantity_lbl} {customer_quantity} {customer_amount_lbl} {customer_amount} {giftcard_name} {giftcard_desc} {giftcard_image} <br />
{giftcard_price_lbl} {giftcard_price} {giftcard_value_lbl} {giftcard_value} <br />
{giftcard_validity} {giftcard_validity_from} {giftcard_validity_to} {giftcard_reciver_name_lbl} {giftcard_reciver_name} {giftcard_reciver_email_lbl} {giftcard_reciver_email} {form_addtocart:cart_templatename} <br />
{if giftcard_userfield} {giftcard_userfield end if}