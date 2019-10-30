<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.modal');

$redHelper       = redhelper::getInstance();
$config          = Redconfiguration::getInstance();
$productHelper   = productHelper::getInstance();
$extraField      = extraField::getInstance();
$redTemplate     = Redtemplate::getInstance();
$stockRoomHelper = rsstockroomhelper::getInstance();

$url = JURI::base();

$model            = $this->getModel('category');
$categoryTemplate = RedshopHelperTemplate::getTemplate('categoryproduct');

if (!empty($categoryTemplate) && $categoryTemplate[0]->template_desc != "")
{
	$templateHtml = $categoryTemplate[0]->template_desc;
}
else
{
	$templateHtml = "<div><div>{print}</div>";
	$templateHtml .= "<div>{filter_by_lbl}{filter_by}</div>";
	$templateHtml .= "<div>{order_by_lbl}{order_by}</div>";
	$templateHtml .= "<p>{category_loop_start}</p>";
	$templateHtml .= "<div style='border: 1px solid;'><div id='categories'><div style='width: 200px;'>";
	$templateHtml .= "<div class='category_image'>{category_thumb_image}</div><div class='category_description'>";
	$templateHtml .= "<h4 class='category_title'>{category_name}</h4>{category_description}</div></div></div>";
	$templateHtml .= "<div class='category_box_wrapper clearfix'>{product_loop_start}<div class='category_product_box_outside'>";
	$templateHtml .= "<div class='category_box_inside'><table border='0'><tbody><tr><td>";
	$templateHtml .= "<div class='category_product_image'>{product_thumb_image}</div></td></tr>";
	$templateHtml .= "<tr><td><div class='category_product_title'><h3>{product_name}</h3></div></td></tr>";
	$templateHtml .= "<tr><td><div class='category_product_price'>{product_price}</div></td></tr>";
	$templateHtml .= "<tr><td><div class='category_product_readmore'>{read_more}</div></td></tr>";
	$templateHtml .= "<tr><td><div class='category_product_addtocart'>{attribute_template:attributes}</div></td></tr>";
	$templateHtml .= "<tr><td><div class='category_product_addtocart'>{form_addtocart:add_to_cart1}</div></td></tr></tbody></table></div></div>";
	$templateHtml .= "{product_loop_end}</div></div><p>{category_loop_end}</p><div class='pagination'>{pagination}</div></div>";
}

if (empty($this->detail))
{
	$templateHtml = '<div>' . JText::_('COM_REDSHOP_ALL_CATEGORY_VIEW_NO_RESULT_TEXT') . '</div>';
}

$app    = JFactory::getApplication();
$router = $app->getRouter();

if ($this->print)
{
	$templateHtml = str_replace("{product_price_slider}", "", $templateHtml);
	$templateHtml = str_replace("{pagination}", "", $templateHtml);
}

$print_url = $url . "index.php?option=com_redshop&view=category&layout=categoryproduct&print=1&tmpl=component&Itemid=" . $this->itemid;
$onclick   = "onclick='window.print();'";

$printHtml = JLayoutHelper::render('general.print', /** @scrutinizer ignore-type */ array('onclick' => $onclick));

$templateHtml = str_replace("{print}", ($this->print) ? '' : $printHtml, $templateHtml);
$templateHtml = str_replace("{category_frontpage_introtext}", Redshop::getConfig()->get('CATEGORY_FRONTPAGE_INTROTEXT'), $templateHtml);

if (strstr($templateHtml, "{category_loop_start}") && strstr($templateHtml, "{category_loop_end}"))
{
	$cattemplate_desc = explode('{category_loop_start}', $templateHtml);
	$catheader        = $cattemplate_desc [0];

	$cattemplate_desc    = explode('{category_loop_end}', $cattemplate_desc [1]);
	$middletemplate_desc = $cattemplate_desc[0];

	if (strstr($middletemplate_desc, '{category_thumb_image_2}'))
	{
		$tag     = '{category_thumb_image_2}';
		$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT_2');
		$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH_2');
	}
	elseif (strstr($middletemplate_desc, '{category_thumb_image_3}'))
	{
		$tag     = '{category_thumb_image_3}';
		$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT_3');
		$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH_3');
	}
	elseif (strstr($middletemplate_desc, '{category_thumb_image_1}'))
	{
		$tag     = '{category_thumb_image_1}';
		$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT');
		$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH');
	}
	else
	{
		$tag     = '{category_thumb_image}';
		$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT');
		$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH');
	}

	$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(2, 1, 1);
	$data_add       = "";

	for ($i = 0; $i < count($this->detail); $i++)
	{
		$row = $this->detail[$i];

		$data_add .= $middletemplate_desc;

		$cItemid = RedshopHelperRouter::getCategoryItemid($row->id);

		if ($cItemid != "")
		{
			$tmpItemid = $cItemid;
		}
		else
		{
			$tmpItemid = $this->itemid;
		}

		$link        = JRoute::_('index.php?option=com_redshop&view=category&cid=' . $row->id . '&layout=detail&Itemid=' . $tmpItemid);
		$middlepath  = REDSHOP_FRONT_IMAGES_RELPATH . 'category/';
		$title       = " title='" . $row->name . "' ";
		$alt         = " alt='" . $row->name . "' ";
		$product_img = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";
		$linkimage   = $product_img;

		if ($row->category_full_image && file_exists($middlepath . $row->category_full_image))
		{
			$product_img = RedshopHelperMedia::watermark('category', $row->category_full_image, $w_thumb, $h_thumb, Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE'), '0');
			$linkimage   = RedshopHelperMedia::watermark('category', $row->category_full_image, '', '', Redshop::getConfig()->get('WATERMARK_CATEGORY_IMAGE'), '0');
		}
		elseif (Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE') && file_exists($middlepath . Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE')))
		{
			$product_img = RedshopHelperMedia::watermark('category', Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'), $w_thumb, $h_thumb, Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE'), '0');
			$linkimage   = RedshopHelperMedia::watermark('category', Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'), '', '', Redshop::getConfig()->get('WATERMARK_CATEGORY_IMAGE'), '0');
		}

		if (Redshop::getConfig()->get('CAT_IS_LIGHTBOX'))
		{
			$cat_thumb = "<a rel=\"myallimg\" href='" . $linkimage . "'  " . $title . ">";
		}
		else
		{
			$cat_thumb = "<a href='" . $link . "' " . $title . ">";
		}

		$cat_thumb .= "<img src='" . $product_img . "' " . $alt . $title . ">";
		$cat_thumb .= "</a>";
		$data_add  = str_replace($tag, $cat_thumb, $data_add);

		if (strstr($data_add, '{category_name}'))
		{
			$cat_name = '<a href="' . $link . '" ' . $title . '>' . $row->name . '</a>';
			$data_add = str_replace("{category_name}", $cat_name, $data_add);
		}

		if (strstr($data_add, '{category_readmore}'))
		{
			$cat_name = '<a href="' . $link . '" ' . $title . '>' . JText::_('COM_REDSHOP_READ_MORE') . '</a>';
			$data_add = str_replace("{category_readmore}", $cat_name, $data_add);
		}

		if (strstr($data_add, '{category_description}'))
		{
			$cat_desc = $config->maxchar($row->description, Redshop::getConfig()->get('CATEGORY_SHORT_DESC_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_SHORT_DESC_END_SUFFIX'));
			$data_add = str_replace("{category_description}", $cat_desc, $data_add);
		}

		if (strstr($data_add, '{category_short_desc}'))
		{
			$cat_s_desc = $config->maxchar($row->short_description, Redshop::getConfig()->get('CATEGORY_SHORT_DESC_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_SHORT_DESC_END_SUFFIX'));
			$data_add   = str_replace("{category_short_desc}", $cat_s_desc, $data_add);
		}

		if (strstr($data_add, '{category_total_product}'))
		{
			$totalprd = $productHelper->getProductCategory($row->id);
			$data_add = str_replace("{category_total_product}", count($totalprd), $data_add);
			$data_add = str_replace("{category_total_product_lbl}", JText::_('COM_REDSHOP_TOTAL_PRODUCT'), $data_add);
		}

		/*
		 * category template extra field
		 * "2" argument is set for category
		 */
		$data_add = $productHelper->getExtraSectionTag($extraFieldName, $row->id, "2", $data_add);

		if (strstr($data_add, "{product_loop_start}") && strstr($data_add, "{product_loop_end}"))
		{
			$template_d1      = explode("{product_loop_start}", $data_add);
			$template_d2      = explode("{product_loop_end}", $template_d1 [1]);
			$template_product = $template_d2 [0];

			$attribute_template = \Redshop\Template\Helper::getAttribute($template_product);
			$extraFieldName     = Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);
			$product_data       = '';
			$prddata_add        = "";

			$this->product = $model->getCategorylistProduct($row->id);

			for ($j = 0; $j < count($this->product); $j++)
			{
				$product   = $this->product[$j];
				$productId = (int) $product->product_id;

				if (!is_object($product))
				{
					break;
				}

				$count_no_user_field = 0;

				// Counting accessory
				$accessorylist = RedshopHelperAccessory::getProductAccessories(0, $productId);
				$totacc        = count($accessorylist);

				$prddata_add .= $template_product;

				// Product User Field Start
				$hidden_userfield   = "";
				$returnArr          = $productHelper->getProductUserfieldFromTemplate($prddata_add);
				$template_userfield = $returnArr[0];
				$userfieldArr       = $returnArr[1];

				if ($template_userfield != "")
				{
					$ufield = "";

					for ($ui = 0; $ui < count($userfieldArr); $ui++)
					{
						$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($userfieldArr[$ui], 12, '', '', 0, $product->product_id);
						$ufield            .= $productUserFields[1];

						if ($productUserFields[1] != "")
						{
							$count_no_user_field++;
						}

						$prddata_add = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $prddata_add);
						$prddata_add = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $prddata_add);
					}

					$productUserFieldsForm = "<form method='post' action='' id='user_fields_form_" .
						$product->product_id . "' name='user_fields_form_" .
						$product->product_id . "'>";

					if ($ufield != "")
					{
						$prddata_add = str_replace("{if product_userfield}", $productUserFieldsForm, $prddata_add);
						$prddata_add = str_replace("{product_userfield end if}", "</form>", $prddata_add);
					}
					else
					{
						$prddata_add = str_replace("{if product_userfield}", "", $prddata_add);
						$prddata_add = str_replace("{product_userfield end if}", "", $prddata_add);
					}
				}
				elseif (Redshop::getConfig()->get('AJAX_CART_BOX'))
				{
					$ajax_detail_template_desc = "";
					$ajax_detail_template      = \Redshop\Template\Helper::getAjaxDetailBox($product);

					if (null !== $ajax_detail_template)
					{
						$ajax_detail_template_desc = $ajax_detail_template->template_desc;
					}

					$returnArr          = $productHelper->getProductUserfieldFromTemplate($ajax_detail_template_desc);
					$template_userfield = $returnArr[0];
					$userfieldArr       = $returnArr[1];

					if ($template_userfield != "")
					{
						$ufield = "";

						for ($ui = 0; $ui < count($userfieldArr); $ui++)
						{
							$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($userfieldArr[$ui], 12, '', '', 0, $productId);
							$ufield            .= $productUserFields[1];

							if ($productUserFields[1] != "")
							{
								$count_no_user_field++;
							}

							$template_userfield = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $template_userfield);
							$template_userfield = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $template_userfield);
						}

						if ($ufield != "")
						{
							$hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" .
								$productId . "' name='user_fields_form_" . $productId . "'>" .
								$template_userfield . "</form></div>";
						}
					}
				}

				$prddata_add = $prddata_add . $hidden_userfield;
				/************** end user fields ***************************/

				$ItemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $productId);

				if (!empty($ItemData))
				{
					$pItemid = $ItemData->id;
				}
				else
				{
					$pItemid = RedshopHelperRouter::getItemId($productId);
				}

				$prddata_add = str_replace("{product_id_lbl}", JText::_('COM_REDSHOP_PRODUCT_ID_LBL'), $prddata_add);
				$prddata_add = str_replace("{product_id}", $productId, $prddata_add);

				$prddata_add           = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $prddata_add);
				$product_number_output = '<span id="product_number_variable' . $productId . '">' . $product->product_number . '</span>';
				$prddata_add           = str_replace("{product_number}", $product_number_output, $prddata_add);

				$product_volume_unit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . "3" . '</span>';
				$strToInsert         = $productHelper->redunitDecimal($product->product_volume) . "&nbsp;" . $product_volume_unit;
				$prddata_add         = str_replace("{product_size}", $strToInsert, $prddata_add);

				$product_unit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . '</span>';
				$strToInsert  = $productHelper->redunitDecimal($product->product_length) . "&nbsp;" . $product_unit;
				$prddata_add  = str_replace("{product_length}", $strToInsert, $prddata_add);

				$prddata_add = str_replace("{product_width}", $productHelper->redunitDecimal($product->product_width) . "&nbsp;" . $product_unit, $prddata_add);

				$strToInsert = $productHelper->redunitDecimal($product->product_height) . "&nbsp;" . $product_unit;
				$prddata_add = str_replace("{product_height}", $strToInsert, $prddata_add);

				$prddata_add = $productHelper->replaceVatinfo($prddata_add);
				$this->catid = isset($row->category_id) ? $row->category_id : '';
				$link        = JRoute::_(
					'index.php?option=com_redshop&view=product&pid=' .
					$productId . '&cid=' . $this->catid . '&Itemid=' . $pItemid
				);

				if (strstr($prddata_add, '{product_name}'))
				{
					$pname       = $config->maxchar($product->product_name, Redshop::getConfig()->get('CATEGORY_PRODUCT_TITLE_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_PRODUCT_TITLE_END_SUFFIX'));
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
					$p_s_desc    = $config->maxchar($product->product_s_desc, Redshop::getConfig()->get('CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX'));
					$prddata_add = str_replace("{product_s_desc}", $p_s_desc, $prddata_add);
				}

				if (strstr($prddata_add, '{product_desc}'))
				{
					$p_desc      = $config->maxchar($product->product_desc, Redshop::getConfig()->get('CATEGORY_PRODUCT_DESC_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_PRODUCT_DESC_END_SUFFIX'));
					$prddata_add = str_replace("{product_desc}", $p_desc, $prddata_add);
				}

				if (strstr($prddata_add, '{product_rating_summary}'))
				{
					// Product Review/Rating Fetching reviews
					$final_avgreview_data = Redshop\Product\Rating::getRating($productId);
					$prddata_add          = str_replace("{product_rating_summary}", $final_avgreview_data, $prddata_add);
				}

				if (strstr($prddata_add, '{manufacturer_link}'))
				{
					$manufacturer_link_href = JRoute::_(
						'index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' .
						$product->manufacturer_id . '&Itemid=' . $this->temid
					);
					$manufacturer_link      = '<a  class="btn btn-primary" href="' . $manufacturer_link_href . '" title="' . $product->manufacturer_name . '">' .
						$product->manufacturer_name .
						'</a>';
					$prddata_add            = str_replace("{manufacturer_link}", $manufacturer_link, $prddata_add);

					if (strstr($prddata_add, "{manufacturer_link}"))
					{
						$prddata_add = str_replace("{manufacturer_name}", "", $prddata_add);
					}
				}

				if (strstr($prddata_add, '{manufacturer_product_link}'))
				{
					$manuUrl           = JRoute::_(
						'index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $product->manufacturer_id .
						'&Itemid=' . $this->itemid
					);
					$manufacturerPLink = "<a  class='btn btn-primary' href='" . $manuUrl . "'>" .
						JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . " " . $product->manufacturer_name .
						"</a>";
					$prddata_add       = str_replace("{manufacturer_product_link}", $manufacturerPLink, $prddata_add);
				}

				if (strstr($prddata_add, '{manufacturer_name}'))
				{
					$prddata_add = str_replace("{manufacturer_name}", $product->manufacturer_name, $prddata_add);
				}

				if (strstr($prddata_add, "{product_thumb_image_3}"))
				{
					$pimg_tag = '{product_thumb_image_3}';
					$ph_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_3');
					$pw_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_3');
				}
				elseif (strstr($prddata_add, "{product_thumb_image_2}"))
				{
					$pimg_tag = '{product_thumb_image_2}';
					$ph_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_2');
					$pw_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_2');
				}
				elseif (strstr($prddata_add, "{product_thumb_image_1}"))
				{
					$pimg_tag = '{product_thumb_image_1}';
					$ph_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
					$pw_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
				}
				else
				{
					$pimg_tag = '{product_thumb_image}';
					$ph_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
					$pw_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
				}

				$hidden_thumb_image = "<input type='hidden' name='prd_main_imgwidth' id='prd_main_imgwidth' value='" . $pw_thumb . "'>";
				$hidden_thumb_image .= "<input type='hidden' name='prd_main_imgheight' id='prd_main_imgheight' value='" . $ph_thumb . "'>";
				$thum_image         = Redshop\Product\Image\Image::getImage($productId, $link, $pw_thumb, $ph_thumb, 2, 1);

				// Product image flying addwishlist time start.
				$thum_image = "<span class='productImageWrap' id='productImageWrapID_" . $productId . "'>" .
					Redshop\Product\Image\Image::getImage($productId, $link, $pw_thumb, $ph_thumb, 2, 1) . "</span>";

				// Product image flying addwishlist time end.
				$prddata_add = str_replace($pimg_tag, $thum_image . $hidden_thumb_image, $prddata_add);

				$prddata_add = $productHelper->getJcommentEditor($product, $prddata_add);

				/*
				 * Product loop template extra field
				 * lat arg set to "1" for indetify parsing data for product tag loop in category
				 * last arg will parse {producttag:NAMEOFPRODUCTTAG} nameing tags.
				 * "1" is for section as product.
				 */
				if (count($categoryTemplate) > 0)
				{
					$prddata_add = $productHelper->getExtraSectionTag($extraFieldName, $productId, "1", $prddata_add, 1);
				}

				/************************************
				 *  Conditional tag
				 *  if product on discount : Yes
				 *  {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
				 *  NO : // OUTPUT : Display blank
				 ************************************/
				$prddata_add = $productHelper->getProductOnSaleComment($product, $prddata_add);

				// Replace wishlistbutton.
				$prddata_add = RedshopHelperWishlist::replaceWishlistTag($productId, $prddata_add);

				// Replace compare product button.
				$prddata_add = Redshop\Product\Compare::replaceCompareProductsButton($productId, (int) $this->catid, $prddata_add);

				$prddata_add = RedshopHelperStockroom::replaceStockroomAmountDetail($prddata_add, $productId);

				// Checking for child products.
				$childproduct = RedshopHelperProduct::getChildProduct($productId);

				if (count($childproduct) > 0)
				{
					$isChilds   = true;
					$attributes = array();
				}
				else
				{
					$isChilds = false;

					// Get attributes.
					$attributes_set = array();

					if ($product->attribute_set_id > 0)
					{
						$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
					}

					$attributes = RedshopHelperProduct_Attribute::getProductAttribute($productId);
					$attributes = array_merge($attributes, $attributes_set);
				}

				// Product attribute - Start.
				$totalatt = count($attributes);

				// Check product for not for sale.
				$prddata_add = $productHelper->getProductNotForSaleComment($product, $prddata_add, $attributes);

				$prddata_add = Redshop\Product\Stock::replaceInStock($productId, $prddata_add, $attributes, $attribute_template);

				$prddata_add = $productHelper->replaceAttributeData($productId, 0, 0, $attributes, $prddata_add, $attribute_template, $isChilds);

				// Get cart tempalte.
				$prddata_add = Redshop\Cart\Render::replace(
					$productId,
					(int) $this->catid,
					0,
					0,
					$prddata_add,
					$isChilds,
					$userfieldArr,
					$totalatt,
					$totacc,
					$count_no_user_field
				);
			}

			$data_add = str_replace("{product_loop_start}", "", $data_add);
			$data_add = str_replace("{product_loop_end}", "", $data_add);
			$data_add = str_replace($template_product, $prddata_add, $data_add);
		}
	}

	$templateHtml = str_replace("{category_loop_start}", "", $templateHtml);
	$templateHtml = str_replace("{category_loop_end}", "", $templateHtml);
	$templateHtml = str_replace($middletemplate_desc, $data_add, $templateHtml);
}

if (strstr($templateHtml, "{filter_by}"))
{
	$templateHtml = str_replace("{filter_by_lbl}", "", $templateHtml);
	$templateHtml = str_replace("{filter_by}", "", $templateHtml);
}

if (strstr($templateHtml, "{template_selector_category}"))
{
	$template_selecter_form = "<form name='template_selecter_form' action='' method='post' >";
	$template_selecter_form .= $this->lists['category_template'];
	$template_selecter_form .= "<input type='hidden' name='manufacturer_id' id='manufacturer_id' value='" . $this->manufacturer_id . "' />";
	$template_selecter_form .= "<input type='hidden' name='order_by' id='order_by' value='" . $this->order_by_select . "' />";
	$template_selecter_form .= "</form>";

	$templateHtml = str_replace("{template_selector_category_lbl}", JText::_('COM_REDSHOP_TEMPLATE_SELECTOR_CATEGORY_LBL'), $templateHtml);
	$templateHtml = str_replace("{template_selector_category}", $template_selecter_form, $templateHtml);
}

if (strstr($templateHtml, "{order_by}"))
{
	$templateHtml = str_replace("{order_by_lbl}", "", $templateHtml);
	$templateHtml = str_replace("{order_by}", "", $templateHtml);
}

if (strstr($templateHtml, "{pagination}"))
{
	$pagination   = $model->getCategoryProductPagination();
	$templateHtml = str_replace("{pagination}", $pagination->getPagesLinks(), $templateHtml);
}

$templateHtml = str_replace("{with_vat}", "", $templateHtml);
$templateHtml = str_replace("{without_vat}", "", $templateHtml);
$templateHtml = str_replace("{attribute_price_with_vat}", "", $templateHtml);
$templateHtml = str_replace("{attribute_price_without_vat}", "", $templateHtml);

$templateHtml = RedshopHelperTemplate::parseRedshopPlugin($templateHtml);

if ($this->params->get('show_page_heading', 0))
{
	if (!$this->catid)
	{
		echo '<div class="category_product__front">';
	}
	else
	{
		echo '<div class="category">';
	}

	if (!$this->catid)
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

echo eval("?>" . $templateHtml . "<?php ");
