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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ATTRIBUTE_WITH_CART_HINT'); ?></b><br /><br />
{if accessory_main} {accessory_main end if} {accessory_mainproduct_price} {accessory_main_image} {accessory_main_title} {accessory_main_short_desc} {accessory_main_readmore} {accessory_main_image_3} {accessory_main_image_2} <br />
{accessory_product_start} {accessory_product_end} {accessory_title} {accessory_image} {accessory_price} {accessory_price_saving} {accessory_main_price} <br />
{accessory_short_desc} {accessory_quantity} {product_number} {accessory_readmore} {accessory_image_3} {accessory_image_2} <br />
{manufacturer_name} {manufacturer_link} {without_vat} {accessory_readmore_link} {accessory_add_chkbox_lbl} {accessory_quantity_lbl} <br />
{selected_accessory_price} {accessory_add_chkbox} {attribute_template:attributes} {stock_status}