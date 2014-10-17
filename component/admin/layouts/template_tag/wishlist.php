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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_WISHLIST_HINT'); ?></b><br /><br />
{if product_userfield} {product_userfield end if} {all_cart} {attribute_template:attributes} {product_loop_start} {product_loop_end} {mail_link} {product_price} {remove_product_link} <br />
{product_thumb_image} {product_name} {back_link} {product_thumb_image_2} {product_thumb_image_3} <br />
{price_excluding_vat} {product_price_table} {product_old_price} {product_price_saving} {accessory_template:templatename} {product_s_desc} {read_more} {read_more_link}