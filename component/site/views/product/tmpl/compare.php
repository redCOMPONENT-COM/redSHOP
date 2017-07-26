<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.modal');

// Get product helper

$print  = $this->input->getBool('print', false);

$producthelper   = productHelper::getInstance();
$config          = Redconfiguration::getInstance();
$stockroomhelper = rsstockroomhelper::getInstance();
$compare         = new RedshopProductCompare;

$compareCategoryId = $compare->getCategoryId();

if (Redshop::getConfig()->get('PRODUCT_COMPARISON_TYPE') == 'category')
{
	$compareTemplate = $this->redTemplate->getTemplate(
		'compare_product',
		$producthelper->getCategoryCompareTemplate($compareCategoryId)
	);
}
else
{
	$compareTemplate = $this->redTemplate->getTemplate("compare_product", Redshop::getConfig()->get('COMPARE_TEMPLATE_ID'));
}

$template = "<div><span>{compare_product_heading}</span></div><div><a href=\"{returntocategory_link}\">{returntocategory_name}</a></div><table border=\"1\"><tbody><tr><th> </th><td align=\"center\">{expand_collapse}</td></tr><tr><th>Product Name</th><td>{product_name}</td></tr><tr><th>Image</th><td>{product_image}</td></tr><tr><th>Manufacturer</th><td>{manufacturer_name}</td></tr><tr><th>Discount Start <br /></th><td>{discount_start_date}</td></tr><tr><th>Discount End<br /></th><td>{discount_end_date}</td></tr><tr><th>Price</th><td>{product_price}</td></tr><tr><th>Short Desc<br /></th><td>{product_s_desc}</td></tr><tr><th>Desc</th><td>{product_desc}</td></tr><tr><th>Rating</th><td>{product_rating_summary}</td></tr><tr><th>Delivery Time</th><td>{product_delivery_time}</td></tr><tr><th>Product Number<br /></th><td>{product_number}</td></tr><tr><th>Stock<br /></th><td>{products_in_stock}</td></tr><tr><th>Stock<br /></th><td>{product_stock_amount_image}</td></tr><tr><th>Weight<br /></th><td>{product_weight}</td></tr><tr><th>Length<br /></th><td>{product_length}</td></tr><tr><th>Height<br /></th><td>{product_height}</td></tr><tr><th>Width<br /></th><td>{product_width}</td></tr><tr><th>Availability Date<br /></th><td>{product_availability_date}</td></tr><tr><th>Volume<br /></th><td>{product_volume}</td></tr><tr><th>Category<br /></th><td>{product_category}</td></tr><tr><th> </th><td>{remove}</td></tr><tr><th> </th><td>{add_to_cart}</td></tr></tbody></table>";

if (count($compareTemplate) > 0 && $compareTemplate[0]->template_desc != "")
{
	$template = $compareTemplate[0]->template_desc;
}

$pagetitle = JText::_('COM_REDSHOP_COMPARE_PRODUCTS');
$template = str_replace('{compare_product_heading}', $pagetitle, $template);

$list = $compare->getItems();
$total = $compare->getItemsTotal();

if ($total > 0)
{
	if ($total == 1)
	{
		JLog::add(JText::_('COM_REDSHOP_ADD_ONE_MORE_PRODUCT_TO_COMPARE'), JLog::NOTICE);
	}

	$returnlink = JRoute::_("index.php?option=com_redshop&view=category&cid=" . $compareCategoryId . "&Itemid=" . $this->itemId);

	if ($print)
	{
		$print_tag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' ><img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' /></a>";
	}
	else
	{
		$print_url = JURI::base() . "index.php?option=com_redshop&view=product&layout=compare&print=1&tmpl=component";
		$print_tag = "<a href='#' onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' ><img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' /></a>";
	}

	$template = str_replace("{print}", $print_tag, $template);

	$template = str_replace('{compare_product_heading}', $pagetitle, $template);
	$template = str_replace('{returntocategory_name}', JText::_("COM_REDSHOP_GO_BACK"), $template);
	$template = str_replace('{returntocategory_link}', $returnlink, $template);

	$removeAll = '<a class="remove" href="' . JUri::root() . 'index.php?option=com_redshop&view=product&task=removecompare&tmpl=component&Itemid=' . $this->itemId . '">'
				. JText::_('COM_REDSHOP_REMOVE_ALL_PRODUCT_FROM_COMPARE_LIST')
			. '</a>';
	$template = str_replace('{remove_all}', $removeAll, $template);

	// Make extrafield object..
	$field    = extraField::getInstance();

	$product_tag = array();

	if (count($compareTemplate) > 0)
	{
		$product_tag = $producthelper->product_tag($compareTemplate[0]->template_id, "1", $template);
	}

	$i = 0;

	foreach ($list as $data)
	{
		$product = RedshopHelperProduct::getProductById($data['item']->productId);

		if ($i == ($total - 1))
		{
			$td_start = "";
			$td_end   = "";
		}
		else
		{
			$td_start = "<td>";
			$td_end   = "</td>";
		}

		$exp_div = "<div name='exp_" . $product->product_id . "'>";
		$div_end = "</div>";

		$ItemData  = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);

		if (count($ItemData) > 0)
		{
			$pItemid = $ItemData->id;
		}
		else
		{
			$catidmain = $product->cat_in_sefurl;
			$pItemid = RedshopHelperUtility::getItemId($product->product_id, $catidmain);
		}

		$link        = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&Itemid=' . $pItemid);

		$thumbUrl = RedShopHelperImages::getImagePath(
							$product->product_full_image,
							'',
							'thumb',
							'product',
							Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_WIDTH'),
							Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_HEIGHT'),
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
		$img    = "<div style='width:" . Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_WIDTH') . "px;height:" . Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_HEIGHT') . "px;float: left;' ><a href='" . $link . "' title='" . $product->product_name . "'><img src='" . $thumbUrl . "'></a></div>";

		$expand = "<a href='javascript:void(0)' onClick='expand_collapse(this," . $product->product_id . ")' style='font-size:18px;text-decoration:none;' >-</a>";

		if ($i != ($total - 1))
		{
			$template = str_replace('{expand_collapse}', $expand . $td_end . '<td align="center">' . "{expand_collapse}", $template);
		}
		else
		{
			$template = str_replace('{expand_collapse}', $expand . $td_end . $td_start . "{expand_collapse}", $template);
		}

		$template = str_replace('{product_name}', $exp_div . $product->product_name . $div_end . $td_end . $td_start . "{product_name}", $template);
		$template = str_replace('{product_image}', $exp_div . $img . $div_end . $td_end . $td_start . "{product_image}", $template);

		if (strstr($template, "{manufacturer_name}"))
		{
			if ($manufacturer = $producthelper->getSection('manufacturer', $product->manufacturer_id))
			{
				$manufacturerName = $manufacturer->manufacturer_name;
			}
			else
			{
				$manufacturerName = '';
			}

			$template     = str_replace('{manufacturer_name}', $exp_div . $manufacturerName . $div_end . $td_end . $td_start . "{manufacturer_name}", $template);
		}

		if (strstr($template, "{discount_start_date}"))
		{
			$disc_start_date = "";

			if ($product->discount_stratdate)
			{
				$disc_start_date = $config->convertDateFormat($product->discount_stratdate);
			}

			$template = str_replace('{discount_start_date}', $exp_div . $disc_start_date . $div_end . $td_end . $td_start . "{discount_start_date}", $template);
		}

		if (strstr($template, "{discount_end_date}"))
		{
			$disc_end_date = "";

			if ($product->discount_enddate)
			{
				$disc_end_date = $config->convertDateFormat($product->discount_enddate);
			}

			$template = str_replace('{discount_end_date}', $exp_div . $disc_end_date . $div_end . $td_end . $td_start . "{discount_end_date}", $template);
		}

		$template = str_replace('{product_s_desc}', $exp_div . $product->product_s_desc . $div_end . $td_end . $td_start . "{product_s_desc}", $template);
		$template = str_replace('{product_desc}', $exp_div . $product->product_desc . $div_end . $td_end . $td_start . "{product_desc}", $template);

		$product_number_output = '<span id="product_number_variable' . $product->product_id . '">' . $product->product_number . '</span>';
		$template              = str_replace('{product_number}', $exp_div . $product->product_number . $div_end . $td_end . $td_start . "{product_number}", $template);

		$product_weight_unit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT') . '</span>';
		$template            = str_replace('{product_weight}', $exp_div . $producthelper->redunitDecimal($product->weight) . "&nbsp;" . $product_weight_unit . $div_end . $td_end . $td_start . "{product_weight}", $template);

		$product_unit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . '</span>';
		$template     = str_replace('{product_length}', $exp_div . $producthelper->redunitDecimal($product->product_length) . "&nbsp;" . $product_unit . $div_end . $td_end . $td_start . "{product_length}", $template);
		$template     = str_replace('{product_height}', $exp_div . $producthelper->redunitDecimal($product->product_height) . "&nbsp;" . $product_unit . $div_end . $td_end . $td_start . "{product_height}", $template);
		$template     = str_replace('{product_width}', $exp_div . $producthelper->redunitDecimal($product->product_width) . "&nbsp;" . $product_unit . $div_end . $td_end . $td_start . "{product_width}", $template);

		$product_volume_unit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . "3" . '</span>';
		$template            = str_replace('{product_volume}', $exp_div . $producthelper->redunitDecimal($product->product_volume) . "&nbsp;" . $product_volume_unit . $div_end . $td_end . $td_start . "{product_volume}", $template);

		if (strstr($template, "{product_price}"))
		{
			$price = 0;

			if (Redshop::getConfig()->get('SHOW_PRICE') && !Redshop::getConfig()->get('USE_AS_CATALOG') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))))
			{
				$productPrices = $producthelper->getProductNetPrice($product->product_id);
				$price = $producthelper->getProductFormattedPrice($productPrices['product_price']);
			}

			$template = str_replace('{product_price}', $exp_div . $price . $div_end . $td_end . $td_start . "{product_price}", $template);
		}

		if (strstr($template, "{product_rating_summary}"))
		{
			$final_avgreview_data = $producthelper->getProductRating($data['item']->productId);
			$template             = str_replace('{product_rating_summary}', $exp_div . $final_avgreview_data . $div_end . $td_end . $td_start . "{product_rating_summary}", $template);
		}

		if (strstr($template, "{products_in_stock}") || strstr($template, "{product_stock_amount_image}"))
		{
			$product_stock = $stockroomhelper->getStockAmountwithReserve($data['item']->productId);
			$template      = str_replace('{products_in_stock}', $exp_div . $product_stock . $div_end . $td_end . $td_start . "{products_in_stock}", $template);

			$stockamountList  = $stockroomhelper->getStockAmountImage($data['item']->productId, "product", $product_stock);
			$stockamountImage = "";

			if (count($stockamountList) > 0)
			{
				$stockamountImage = '<a class="imgtooltip"><span>';
				$stockamountImage .= '<div class="spnheader">' . JText::_('COM_REDSHOP_STOCK_AMOUNT') . '</div>';
				$stockamountImage .= '<div class="spnalttext" id="stockImageTooltip' . $data['item']->productId . '">' . $stockamountList[0]->stock_amount_image_tooltip . '</div></span>';
				$stockamountImage .= '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'stockroom/' . $stockamountList[0]->stock_amount_image . '" width="150px" height="90px" alt="' . $stockamountList[0]->stock_amount_image_tooltip . '" id="stockImage' . $data['item']->productId . '" /></a>';
			}

			$template = str_replace('{product_stock_amount_image}', $exp_div . $stockamountImage . $div_end . $td_end . $td_start . "{product_stock_amount_image}", $template);
		}

		if (strstr($template, "{product_delivery_time}"))
		{
			$product_delivery_time = $producthelper->getProductMinDeliveryTime($data['item']->productId);
			$template              = str_replace('{product_delivery_time}', $exp_div . $product_delivery_time . $div_end . $td_end . $td_start . "{product_delivery_time}", $template);
		}

		if (strstr($template, "{product_availability_date}"))
		{
			$available_date = "";

			if ($product->product_availability_date != "")
			{
				$available_date = $config->convertDateFormat($product->product_availability_date);
			}

			$template = str_replace('{product_availability_date}', $exp_div . $available_date . $div_end . $td_end . $td_start . "{product_availability_date}", $template);
		}

		if (strstr($template, "{product_category}"))
		{
			$category = $producthelper->getSection('category', $data['item']->categoryId);
			$template = str_replace('{product_category}', $exp_div . $category->name . $div_end . $td_end . $td_start . "{product_category}", $template);
		}

		$link_remove = JUri::root() . 'index.php?option=com_redshop&view=product&task=removecompare&layout=compare&pid=' . $product->product_id . '&cid=' . $category->id . '&Itemid=' . $this->itemId . '&tmpl=component';

		$remove = "<a href='" . $link_remove . "'>" . JText::_('COM_REDSHOP_REMOVE_PRODUCT_FROM_COMPARE_LIST') . "</a>";
		$template = str_replace('{remove}', $exp_div . $remove . $div_end . $td_end . $td_start . "{remove}", $template);

		if (strstr($template, "{add_to_cart}"))
		{
			$addtocart = $producthelper->replaceCartTemplate($data['item']->productId, 0, 0, 0, '{form_addtocart:add_to_cart1}');
			$template  = str_replace('{add_to_cart}', $exp_div . $addtocart . $div_end . $td_end . $td_start . "{add_to_cart}", $template);
		}

		// Extra field display
		for ($tag = 0; $tag < count($product_tag); $tag++)
		{
			$str = "'" . $product_tag[$tag] . "'";

			if ($i != ($total - 1))
			{
				$template = str_replace(
					'{' . $product_tag[$tag] . '}',
					$exp_div . '{' . $product_tag[$tag] . '}' . $div_end . $td_end . $td_start . '{addedext_tag}',
					$template
				);
			}

			$template = $field->extra_field_display("1", $product->product_id, $str, $template);
			$template = str_replace('{addedext_tag}', '{' . $product_tag[$tag] . '}', $template);
		}

		$i++;
	}

	$template = str_replace('{expand_collapse}', "", $template);
	$template = str_replace('{product_name}', "", $template);
	$template = str_replace('{product_image}', "", $template);
	$template = str_replace('{manufacturer_name}', "", $template);
	$template = str_replace('{discount_start_date}', "", $template);
	$template = str_replace('{discount_end_date}', "", $template);
	$template = str_replace('{product_price}', "", $template);
	$template = str_replace('{product_s_desc}', "", $template);
	$template = str_replace('{product_desc}', "", $template);
	$template = str_replace('{product_rating_summary}', "", $template);
	$template = str_replace('{product_delivery_time}', "", $template);
	$template = str_replace('{product_number}', "", $template);
	$template = str_replace('{products_in_stock}', "", $template);
	$template = str_replace('{product_stock_amount_image}', "", $template);
	$template = str_replace('{product_weight}', "", $template);
	$template = str_replace('{product_length}', "", $template);
	$template = str_replace('{product_height}', "", $template);
	$template = str_replace('{product_width}', "", $template);
	$template = str_replace('{product_availability_date}', "", $template);
	$template = str_replace('{product_volume}', "", $template);
	$template = str_replace('{product_category}', "", $template);
	$template = str_replace('{remove}', "", $template);
	$template = str_replace('{add_to_cart}', "", $template);

	$template = $this->redTemplate->parseredSHOPplugin($template);
}
else
{
	$template = JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE');
}

echo eval("?>" . $template . "<?php ");
