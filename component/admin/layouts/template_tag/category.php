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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CATEGORY_HINT'); ?></b><br /><br />
{if subcats} {category_main_description} {category_main_short_desc} {category_main_name} {category_main_thumb_image} {subcats end if} <br />
{category_loop_start} {category_loop_end} {category_name} {category_description} {category_short_desc} {category_thumb_image} <br />
{product_price_lbl} {product_price_slider} {product_loop_start} {product_loop_end} {product_name} {product_price} {product_rating_summary} {product_s_desc} <br />
{pagination} {perpagelimit} {product_display_limit} {show_all_products_in_category} {order_by} {if product_on_sale} {product_on_sale end if} {price_excluding_vat} <br />
{more_documents} {product_id_lbl} {product_id} {product_number_lbl} {product_number} {product_discount_price} {product_old_price} {product_price_saving} {with_vat} {without_vat} {filter_by} <br />
{template_selector_category_lbl} {template_selector_category} {manufacturer_link} {manufacturer_name} {stock_status:class for available stock : class for out of stock: class for pre order} {shopname} {read_more} {category_readmore} {category_main_thumb_image_2} {category_main_thumb_image_3} {category_thumb_image_2} {category_thumb_image_3} <br />
{product_stock_amount_image} {product_price_table} {product_thumb_image_3} {product_thumb_image_2} {discount_start_date} {discount_end_date} {compare_products_button} {compare_product_div} {front_img_link} {back_img_link} {product_preview_img} {read_more_link} {product_name_nolink} {category_product_link} <br />
{order_by_lbl} {filter_by_lbl} {returntocategory_link} {returntocategory_name} {returntocategory} {category_total_product_lbl} {category_total_product} {product_delivery_time} {delivery_time_lbl} {product_length} {product_width} {product_height} {front_preview_img_link} {back_preview_img_link} {if product_userfield} {product_userfield end if}