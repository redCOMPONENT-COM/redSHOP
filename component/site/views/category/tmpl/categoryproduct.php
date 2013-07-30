<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

$objhelper       = new redhelper;
$config          = new Redconfiguration;
$producthelper   = new producthelper;
$extraField      = new extraField;
$redTemplate     = new Redtemplate;
$stockroomhelper = new rsstockroomhelper;

$url    = JURI::base();
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$catid  = JRequest::getVar('cid', 0, '', 'int');
$print  = JRequest::getVar('print');

$model                = $this->getModel('category');
$loadCategorytemplate = $redTemplate->getTemplate('categoryproduct');

if (count($loadCategorytemplate) > 0 && $loadCategorytemplate[0]->template_desc != "")
{
	$template_desc = $loadCategorytemplate[0]->template_desc;
}
else
{
	$template_desc = "<div><div>{print}</div><div>{filter_by_lbl}{filter_by}</div><div>{order_by_lbl}{order_by}</div><p>{category_loop_start}</p><div style='border: 1px solid;'><div id='categories'><div style='width: 200px;'><div class='category_image'>{category_thumb_image}</div><div class='category_description'><h4 class='category_title'>{category_name}</h4>{category_description}</div></div></div><div class='category_box_wrapper clearfix'>{product_loop_start}<div class='category_product_box_outside'><div class='category_box_inside'><table border='0'><tbody><tr><td><div class='category_product_image'>{product_thumb_image}</div></td></tr><tr><td><div class='category_product_title'><h3>{product_name}</h3></div></td></tr><tr><td><div class='category_product_price'>{product_price}</div></td></tr>
<tr><td><div class='category_product_readmore'>{read_more}</div></td></tr><tr><td><div class='category_product_addtocart'>{attribute_template:attributes}</div></td></tr><tr><td><div class='category_product_addtocart'>{form_addtocart:add_to_cart1}</div></td></tr></tbody></table></div></div>
{product_loop_end}</div></div><p>{category_loop_end}</p><div class='category_pagination'>{pagination}</div></div>";
}

if (count($this->detail) <= 0)
{
	$template_desc = '<div>' . JText::_('COM_REDSHOP_ALL_CATEGORY_VIEW_NO_RESULT_TEXT') . '</div>';
}

$app    = JFactory::getApplication();
$router = $app->getRouter();
$uri    = new JURI('index.php?option=' . $option . '&category&layout=categoryproduct&Itemid=' . $Itemid . '&category_template=' . $this->category_template_id);

if ($print)
{
	$onclick       = "onclick='window.print();'";
	$template_desc = str_replace("{product_price_slider}", "", $template_desc);
	$template_desc = str_replace("{pagination}", "", $template_desc);
}
else
{
	$print_url = $url . "index.php?option=com_redshop&view=category&layout=categoryproduct&print=1&tmpl=component&Itemid=" . $Itemid;

	$onclick = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
}

$print_tag = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png' alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
$print_tag .= "</a>";

$template_desc = str_replace("{print}", $print_tag, $template_desc);

$template_desc = str_replace("{category_frontpage_introtext}", CATEGORY_FRONTPAGE_INTROTEXT, $template_desc);

if (strstr($template_desc, "{category_loop_start}") && strstr($template_desc, "{category_loop_end}"))
{
	$cattemplate_desc = explode('{category_loop_start}', $template_desc);
	$catheader        = $cattemplate_desc [0];

	$cattemplate_desc    = explode('{category_loop_end}', $cattemplate_desc [1]);
	$middletemplate_desc = $cattemplate_desc[0];

	if (strstr($middletemplate_desc, '{category_thumb_image_2}'))
	{
		$tag     = '{category_thumb_image_2}';
		$h_thumb = THUMB_HEIGHT_2;
		$w_thumb = THUMB_WIDTH_2;
	}
	elseif (strstr($middletemplate_desc, '{category_thumb_image_3}'))
	{
		$tag     = '{category_thumb_image_3}';
		$h_thumb = THUMB_HEIGHT_3;
		$w_thumb = THUMB_WIDTH_3;
	}
	elseif (strstr($middletemplate_desc, '{category_thumb_image_1}'))
	{
		$tag     = '{category_thumb_image_1}';
		$h_thumb = THUMB_HEIGHT;
		$w_thumb = THUMB_WIDTH;
	}
	else
	{
		$tag     = '{category_thumb_image}';
		$h_thumb = THUMB_HEIGHT;
		$w_thumb = THUMB_WIDTH;
	}

	$extraFieldName = $extraField->getSectionFieldNameArray(2, 1, 1);
	$data_add       = "";

	for ($i = 0; $i < count($this->detail); $i++)
	{
		$row = $this->detail[$i];

		$data_add .= $middletemplate_desc;

		$cItemid = $objhelper->getCategoryItemid($row->category_id);

		if ($cItemid != "")
		{
			$tmpItemid = $cItemid;
		}
		else
		{
			$tmpItemid = $Itemid;
		}

		$link        = JRoute::_('index.php?option=' . $option . '&view=category&cid=' . $row->category_id . '&layout=detail&Itemid=' . $tmpItemid);
		$middlepath  = REDSHOP_FRONT_IMAGES_RELPATH . 'category/';
		$title       = " title='" . $row->category_name . "' ";
		$alt         = " alt='" . $row->category_name . "' ";
		$product_img = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";
		$linkimage   = $product_img;

		if ($row->category_full_image && file_exists($middlepath . $row->category_full_image))
		{
			$product_img = $objhelper->watermark('category', $row->category_full_image, $w_thumb, $h_thumb, WATERMARK_CATEGORY_THUMB_IMAGE, '0');
			$linkimage   = $objhelper->watermark('category', $row->category_full_image, '', '', WATERMARK_CATEGORY_IMAGE, '0');
		}
		elseif (CATEGORY_DEFAULT_IMAGE && file_exists($middlepath . CATEGORY_DEFAULT_IMAGE))
		{
			$product_img = $objhelper->watermark('category', CATEGORY_DEFAULT_IMAGE, $w_thumb, $h_thumb, WATERMARK_CATEGORY_THUMB_IMAGE, '0');
			$linkimage   = $objhelper->watermark('category', CATEGORY_DEFAULT_IMAGE, '', '', WATERMARK_CATEGORY_IMAGE, '0');
		}

		if (CAT_IS_LIGHTBOX)
		{
			$cat_thumb = "<a rel=\"myallimg\" href='" . $linkimage . "'  " . $title . ">";
		}
		else
		{
			$cat_thumb = "<a href='" . $link . "' " . $title . ">";
		}

		$cat_thumb .= "<img src='" . $product_img . "' " . $alt . $title . ">";
		$cat_thumb .= "</a>";
		$data_add = str_replace($tag, $cat_thumb, $data_add);

		if (strstr($data_add, '{category_name}'))
		{
			$cat_name = '<a href="' . $link . '" ' . $title . '>' . $row->category_name . '</a>';
			$data_add = str_replace("{category_name}", $cat_name, $data_add);
		}

		if (strstr($data_add, '{category_readmore}'))
		{
			$cat_name = '<a href="' . $link . '" ' . $title . '>' . JText::_('COM_REDSHOP_READ_MORE') . '</a>';
			$data_add = str_replace("{category_readmore}", $cat_name, $data_add);
		}

		if (strstr($data_add, '{category_description}'))
		{
			$cat_desc = $config->maxchar($row->category_description, CATEGORY_SHORT_DESC_MAX_CHARS, CATEGORY_SHORT_DESC_END_SUFFIX);
			$data_add = str_replace("{category_description}", $cat_desc, $data_add);
		}

		if (strstr($data_add, '{category_short_desc}'))
		{
			$cat_s_desc = $config->maxchar($row->category_short_description, CATEGORY_SHORT_DESC_MAX_CHARS, CATEGORY_SHORT_DESC_END_SUFFIX);
			$data_add   = str_replace("{category_short_desc}", $cat_s_desc, $data_add);
		}

		if (strstr($data_add, '{category_total_product}'))
		{
			$totalprd = $producthelper->getProductCategory($row->category_id);
			$data_add = str_replace("{category_total_product}", count($totalprd), $data_add);
			$data_add = str_replace("{category_total_product_lbl}", JText::_('COM_REDSHOP_TOTAL_PRODUCT'), $data_add);
		}

		/*
		 * category template extra field
		 * "2" argument is set for category
		 */
		$data_add = $producthelper->getExtraSectionTag($extraFieldName, $row->category_id, "2", $data_add);

		if (strstr($data_add, "{product_loop_start}") && strstr($data_add, "{product_loop_end}"))
		{
			$template_d1      = explode("{product_loop_start}", $data_add);
			$template_d2      = explode("{product_loop_end}", $template_d1 [1]);
			$template_product = $template_d2 [0];

			$attribute_template = $producthelper->getAttributeTemplate($template_product);
			$extraFieldName     = $extraField->getSectionFieldNameArray(1, 1, 1);
			$product_data       = '';
			$prddata_add        = "";

			$this->product = $model->getCategorylistProduct($row->category_id);

			for ($j = 0; $j < count($this->product); $j++)
			{
				$product = $this->product[$j];

				if (!is_object($product))
				{
					break;
				}

				$count_no_user_field = 0;

				// Counting accessory
				$accessorylist = $producthelper->getProductAccessory(0, $product->product_id);
				$totacc        = count($accessorylist);

				$prddata_add .= $template_product;

				// Product User Field Start
				$hidden_userfield   = "";
				$returnArr          = $producthelper->getProductUserfieldFromTemplate($prddata_add);
				$template_userfield = $returnArr[0];
				$userfieldArr       = $returnArr[1];

				if ($template_userfield != "")
				{
					$ufield = "";

					for ($ui = 0; $ui < count($userfieldArr); $ui++)
					{
						$product_userfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $product->product_id);
						$ufield .= $product_userfileds[1];

						if ($product_userfileds[1] != "")
						{
							$count_no_user_field++;
						}

						$prddata_add = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $product_userfileds[0], $prddata_add);
						$prddata_add = str_replace('{' . $userfieldArr[$ui] . '}', $product_userfileds[1], $prddata_add);
					}

					$product_userfileds_form = "<form method='post' action='' id='user_fields_form_" . $product->product_id . "' name='user_fields_form_" . $product->product_id . "'>";

					if ($ufield != "")
					{
						$prddata_add = str_replace("{if product_userfield}", $product_userfileds_form, $prddata_add);
						$prddata_add = str_replace("{product_userfield end if}", "</form>", $prddata_add);
					}
					else
					{
						$prddata_add = str_replace("{if product_userfield}", "", $prddata_add);
						$prddata_add = str_replace("{product_userfield end if}", "", $prddata_add);
					}
				}
				elseif (AJAX_CART_BOX)
				{
					$ajax_detail_template_desc = "";
					$ajax_detail_template      = $producthelper->getAjaxDetailboxTemplate($product);

					if (count($ajax_detail_template) > 0)
					{
						$ajax_detail_template_desc = $ajax_detail_template->template_desc;
					}

					$returnArr          = $producthelper->getProductUserfieldFromTemplate($ajax_detail_template_desc);
					$template_userfield = $returnArr[0];
					$userfieldArr       = $returnArr[1];

					if ($template_userfield != "")
					{
						$ufield = "";

						for ($ui = 0; $ui < count($userfieldArr); $ui++)
						{
							$product_userfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $product->product_id);
							$ufield .= $product_userfileds[1];

							if ($product_userfileds[1] != "")
							{
								$count_no_user_field++;
							}

							$template_userfield = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $product_userfileds[0], $template_userfield);
							$template_userfield = str_replace('{' . $userfieldArr[$ui] . '}', $product_userfileds[1], $template_userfield);
						}

						if ($ufield != "")
						{
							$hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $product->product_id . "' name='user_fields_form_" . $product->product_id . "'>" . $template_userfield . "</form></div>";
						}
					}
				}

				$prddata_add = $prddata_add . $hidden_userfield;
				/************** end user fields ***************************/

				$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);

				if (count($ItemData) > 0)
				{
					$pItemid = $ItemData->id;
				}
				else
				{
					$pItemid = $objhelper->getItemid($product->product_id);
				}

				$prddata_add = str_replace("{product_id_lbl}", JText::_('COM_REDSHOP_PRODUCT_ID_LBL'), $prddata_add);
				$prddata_add = str_replace("{product_id}", $product->product_id, $prddata_add);

				$prddata_add           = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $prddata_add);
				$product_number_output = '<span id="product_number_variable' . $product->product_id . '">' . $product->product_number . '</span>';
				$prddata_add           = str_replace("{product_number}", $product_number_output, $prddata_add);

				$product_volume_unit = '<span class="product_unit_variable">' . DEFAULT_VOLUME_UNIT . "3" . '</span>';
				$prddata_add         = str_replace("{product_size}", $producthelper->redunitDecimal($product->product_volume) . "&nbsp;" . $product_volume_unit, $prddata_add);

				$product_unit = '<span class="product_unit_variable">' . DEFAULT_VOLUME_UNIT . '</span>';
				$prddata_add  = str_replace("{product_length}", $producthelper->redunitDecimal($product->product_length) . "&nbsp;" . $product_unit, $prddata_add);
				$prddata_add  = str_replace("{product_width}", $producthelper->redunitDecimal($product->product_width) . "&nbsp;" . $product_unit, $prddata_add);
				$prddata_add  = str_replace("{product_height}", $producthelper->redunitDecimal($product->product_height) . "&nbsp;" . $product_unit, $prddata_add);

				$prddata_add = $producthelper->replaceVatinfo($prddata_add);
				$catid       = $row->category_id;
				$link        = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $product->product_id . '&cid=' . $catid . '&Itemid=' . $pItemid);

				if (strstr($prddata_add, '{product_name}'))
				{
					$pname       = $config->maxchar($product->product_name, CATEGORY_PRODUCT_TITLE_MAX_CHARS, CATEGORY_PRODUCT_TITLE_END_SUFFIX);
					$pname       = "<a href='" . $link . "' title='" . $product->product_name . "'>" . $pname . "</a>";
					$prddata_add = str_replace("{product_name}", $pname, $prddata_add);
				}

				if (strstr($prddata_add, '{read_more}'))
				{
					$rmore       = "<a href='" . $link . "' title='" . $product->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
					$prddata_add = str_replace("{read_more}", $rmore, $prddata_add);
				}

				if (strstr($prddata_add, '{read_more_link}'))
				{
					$prddata_add = str_replace("{read_more_link}", $link, $prddata_add);
				}

				if (strstr($prddata_add, '{product_s_desc}'))
				{
					$p_s_desc    = $config->maxchar($product->product_s_desc, CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS, CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX);
					$prddata_add = str_replace("{product_s_desc}", $p_s_desc, $prddata_add);
				}

				if (strstr($prddata_add, '{product_desc}'))
				{
					$p_desc      = $config->maxchar($product->product_desc, CATEGORY_PRODUCT_DESC_MAX_CHARS, CATEGORY_PRODUCT_DESC_END_SUFFIX);
					$prddata_add = str_replace("{product_desc}", $p_desc, $prddata_add);
				}

				if (strstr($prddata_add, '{product_rating_summary}'))
				{
					// Product Review/Rating Fetching reviews
					$final_avgreview_data = $producthelper->getProductRating($product->product_id);
					$prddata_add          = str_replace("{product_rating_summary}", $final_avgreview_data, $prddata_add);
				}

				if (strstr($prddata_add, '{manufacturer_link}'))
				{
					$manufacturer_link_href = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $product->manufacturer_id . '&Itemid=' . ktemid);
					$manufacturer_link      = '<a href="' . $manufacturer_link_href . '" title="' . $product->manufacturer_name . '">' . $product->manufacturer_name . '</a>';
					$prddata_add            = str_replace("{manufacturer_link}", $manufacturer_link, $prddata_add);

					if (strstr($prddata_add, "{manufacturer_link}"))
					{
						$prddata_add = str_replace("{manufacturer_name}", "", $prddata_add);
					}
				}

				if (strstr($prddata_add, '{manufacturer_product_link}'))
				{
					$manufacturerPLink = "<a href='" . JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $product->manufacturer_id . '&Itemid=' . $Itemid) . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . " " . $product->manufacturer_name . "</a>";
					$prddata_add       = str_replace("{manufacturer_product_link}", $manufacturerPLink, $prddata_add);
				}

				if (strstr($prddata_add, '{manufacturer_name}'))
				{
					$prddata_add = str_replace("{manufacturer_name}", $product->manufacturer_name, $prddata_add);
				}

				if (strstr($prddata_add, "{product_thumb_image_3}"))
				{
					$pimg_tag = '{product_thumb_image_3}';
					$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_3;
					$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_3;
				}
				elseif (strstr($prddata_add, "{product_thumb_image_2}"))
				{
					$pimg_tag = '{product_thumb_image_2}';
					$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_2;
					$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_2;
				}
				elseif (strstr($prddata_add, "{product_thumb_image_1}"))
				{
					$pimg_tag = '{product_thumb_image_1}';
					$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT;
					$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH;
				}
				else
				{
					$pimg_tag = '{product_thumb_image}';
					$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT;
					$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH;
				}

				$hidden_thumb_image = "<input type='hidden' name='prd_main_imgwidth' id='prd_main_imgwidth' value='" . $pw_thumb . "'><input type='hidden' name='prd_main_imgheight' id='prd_main_imgheight' value='" . $ph_thumb . "'>";
				$thum_image         = $producthelper->getProductImage($product->product_id, $link, $pw_thumb, $ph_thumb, 2, 1);

				// Product image flying addwishlist time start
				$thum_image = "<span class='productImageWrap' id='productImageWrapID_" . $product->product_id . "'>" . $producthelper->getProductImage($product->product_id, $link, $pw_thumb, $ph_thumb, 2, 1) . "</span>";

				// Product image flying addwishlist time end
				$prddata_add = str_replace($pimg_tag, $thum_image . $hidden_thumb_image, $prddata_add);

				$prddata_add = $producthelper->getJcommentEditor($product, $prddata_add);

				/*
				 * product loop template extra field
				 * lat arg set to "1" for indetify parsing data for product tag loop in category
				 * last arg will parse {producttag:NAMEOFPRODUCTTAG} nameing tags.
				 * "1" is for section as product
				 */
				if (count($loadCategorytemplate) > 0)
				{
					$prddata_add = $producthelper->getExtraSectionTag($extraFieldName, $product->product_id, "1", $prddata_add, 1);
				}

				/************************************
				 *  Conditional tag
				 *  if product on discount : Yes
				 *  {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
				 *  NO : // OUTPUT : Display blank
				 ************************************/
				$prddata_add = $producthelper->getProductOnSaleComment($product, $prddata_add);

				// Replace wishlistbutton
				$prddata_add = $producthelper->replaceWishlistButton($product->product_id, $prddata_add);

				// Replace compare product button
				$prddata_add = $producthelper->replaceCompareProductsButton($product->product_id, $catid, $prddata_add);

				if (strstr($prddata_add, "{stockroom_detail}"))
				{
					$prddata_add = $stockroomhelper->replaceStockroomAmountDetail($prddata_add, $product->product_id);
				}

				// Checking for child products
				$childproduct = $producthelper->getChildProduct($product->product_id);

				if (count($childproduct) > 0)
				{
					$isChilds   = true;
					$attributes = array();
				}
				else
				{
					$isChilds = false;

					// Get attributes
					$attributes_set = array();

					if ($product->attribute_set_id > 0)
					{
						$attributes_set = $producthelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
					}

					$attributes = $producthelper->getProductAttribute($product->product_id);
					$attributes = array_merge($attributes, $attributes_set);
				}

				/////////////////////////////////// Product attribute  Start /////////////////////////////////
				$totalatt = count($attributes);

				// Check product for not for sale
				$prddata_add = $producthelper->getProductNotForSaleComment($product, $prddata_add, $attributes);

				$prddata_add = $producthelper->replaceProductInStock($product->product_id, $prddata_add, $attributes, $attribute_template);

				$prddata_add = $producthelper->replaceAttributeData($product->product_id, 0, 0, $attributes, $prddata_add, $attribute_template, $isChilds);

				// Get cart tempalte
				$prddata_add = $producthelper->replaceCartTemplate($product->product_id, $catid, 0, 0, $prddata_add, $isChilds, $userfieldArr, $totalatt, $totacc, $count_no_user_field);
			}

			$data_add = str_replace("{product_loop_start}", "", $data_add);
			$data_add = str_replace("{product_loop_end}", "", $data_add);
			$data_add = str_replace($template_product, $prddata_add, $data_add);
		}
	}

	$template_desc = str_replace("{category_loop_start}", "", $template_desc);
	$template_desc = str_replace("{category_loop_end}", "", $template_desc);
	$template_desc = str_replace($middletemplate_desc, $data_add, $template_desc);
}

if (strstr($template_desc, "{filter_by}"))
{
	$template_desc = str_replace("{filter_by_lbl}", "", $template_desc);
	$template_desc = str_replace("{filter_by}", "", $template_desc);
}

if (strstr($template_desc, "{template_selector_category}"))
{
	$template_selecter_form = "<form name='template_selecter_form' action='' method='post' >";
	$template_selecter_form .= $this->lists['category_template'];
	$template_selecter_form .= "<input type='hidden' name='manufacturer_id' id='manufacturer_id' value='" . $this->manufacturer_id . "' />";
	$template_selecter_form .= "<input type='hidden' name='order_by' id='order_by' value='" . $this->order_by_select . "' />";
	$template_selecter_form .= "</form>";

	$template_desc = str_replace("{template_selector_category_lbl}", JText::_('COM_REDSHOP_TEMPLATE_SELECTOR_CATEGORY_LBL'), $template_desc);
	$template_desc = str_replace("{template_selector_category}", $template_selecter_form, $template_desc);
}

if (strstr($template_desc, "{order_by}"))
{
	$template_desc = str_replace("{order_by_lbl}", "", $template_desc);
	$template_desc = str_replace("{order_by}", "", $template_desc);
}

if (strstr($template_desc, "{pagination}"))
{
	$pagination    = $model->getCategoryProductPagination();
	$template_desc = str_replace("{pagination}", $pagination->getPagesLinks(), $template_desc);
}

$template_desc = str_replace("{with_vat}", "", $template_desc);
$template_desc = str_replace("{without_vat}", "", $template_desc);
$template_desc = str_replace("{attribute_price_with_vat}", "", $template_desc);
$template_desc = str_replace("{attribute_price_without_vat}", "", $template_desc);

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);

if ($this->params->get('show_page_heading', 0))
{
	if (!$catid)
		echo '<div class="category_product__front">';
	else
		echo '<div class="category">';

	if (!$catid)
	{
		echo '<h1 class="componentheading' . $this->escape($this->params->get('pageclass_sfx')) . '">';

		if ($this->params->get('page_title') != $this->pageheadingtag)
		{
			echo $this->escape($this->params->get('page_title'));
		}
		else
		{
			echo $this->pageheadingtag;
		}
		echo '</h1>';
	}
	echo '</div>';
}
echo eval("?>" . $template_desc . "<?php ");
