<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Redshop\Helper\ExtraFields;
use Redshop\Template\General;

defined('_JEXEC') or die;

JHtml::_('behavior.modal');

// Get product helper
$productHelper   = productHelper::getInstance();
$config          = Redconfiguration::getInstance();
$stockroomHelper = rsstockroomhelper::getInstance();

$compare           = new RedshopProductCompare;
$compareCategoryId = $compare->getCategoryId();

if (Redshop::getConfig()->get('PRODUCT_COMPARISON_TYPE') == 'category')
{
	$compareTemplate = $this->redTemplate->getTemplate(
		'compare_product',
		Redshop\Product\Compare::getCategoryCompareTemplate($compareCategoryId)
	);
}
else
{
	$compareTemplate = $this->redTemplate->getTemplate("compare_product", Redshop::getConfig()->get('COMPARE_TEMPLATE_ID'));
}

$template = "<div><span>{compare_product_heading}</span></div><div><a href=\"{returntocategory_link}\">{returntocategory_name}</a></div><table border=\"1\"><tbody><tr><th> </th><td align=\"center\">{expand_collapse}</td></tr><tr><th>Product Name</th><td>{product_name}</td></tr><tr><th>Image</th><td>{product_image}</td></tr><tr><th>Manufacturer</th><td>{manufacturer_name}</td></tr><tr><th>Discount Start <br /></th><td>{discount_start_date}</td></tr><tr><th>Discount End<br /></th><td>{discount_end_date}</td></tr><tr><th>Price</th><td>{product_price}</td></tr><tr><th>Short Desc<br /></th><td>{product_s_desc}</td></tr><tr><th>Desc</th><td>{product_desc}</td></tr><tr><th>Rating</th><td>{product_rating_summary}</td></tr><tr><th>Delivery Time</th><td>{product_delivery_time}</td></tr><tr><th>Product Number<br /></th><td>{product_number}</td></tr><tr><th>Stock<br /></th><td>{products_in_stock}</td></tr><tr><th>Stock<br /></th><td>{product_stock_amount_image}</td></tr><tr><th>Weight<br /></th><td>{product_weight}</td></tr><tr><th>Length<br /></th><td>{product_length}</td></tr><tr><th>Height<br /></th><td>{product_height}</td></tr><tr><th>Width<br /></th><td>{product_width}</td></tr><tr><th>Availability Date<br /></th><td>{product_availability_date}</td></tr><tr><th>Volume<br /></th><td>{product_volume}</td></tr><tr><th>Category<br /></th><td>{product_category}</td></tr><tr><th> </th><td>{remove}</td></tr><tr><th> </th><td>{add_to_cart}</td></tr></tbody></table>";

if (!empty($compareTemplate) && $compareTemplate[0]->template_desc != "")
{
	$template = $compareTemplate[0]->template_desc;
}

$pagetitle = JText::_('COM_REDSHOP_COMPARE_PRODUCTS');
$template  = str_replace('{compare_product_heading}', $pagetitle, $template);

$list  = $compare->getItems();
$total = $compare->getItemsTotal();

if ($total > 0)
{
	if ($total == 1)
	{
		JLog::add(JText::_('COM_REDSHOP_ADD_ONE_MORE_PRODUCT_TO_COMPARE'), JLog::NOTICE);
	}

	$returnLink = JRoute::_("index.php?option=com_redshop&view=category&cid=" . $compareCategoryId . "&Itemid=" . $this->itemId);

	if ($this->input->getBool('print', false))
	{
		$printTag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' ><img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' /></a>";
	}
	else
	{
		$printUrl = JURI::base() . "index.php?option=com_redshop&view=product&layout=compare&print=1&tmpl=component";
		$printTag = "<a href='#' onclick='window.open(\"$printUrl\",\"mywindow\",\"scrollbars=1\",\"location=1\")' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' ><img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' /></a>";
	}

	$template = str_replace("{print}", $printTag, $template);
	$template = str_replace('{compare_product_heading}', $pagetitle, $template);
	$template = str_replace('{returntocategory_name}', JText::_("COM_REDSHOP_GO_BACK"), $template);
	$template = str_replace('{returntocategory_link}', $returnLink, $template);

	$removeAll = '<a class="remove" href="' . JUri::root() . 'index.php?option=com_redshop&view=product&task=removecompare&tmpl=component&Itemid=' . $this->itemId . '">'
		. JText::_('COM_REDSHOP_REMOVE_ALL_PRODUCT_FROM_COMPARE_LIST')
		. '</a>';
	$template  = str_replace('{remove_all}', $removeAll, $template);

	// Make extrafield object..
	$field = extraField::getInstance();

	$productTag = array();

	if (!empty($compareTemplate))
	{
		$productTag = Redshop\Helper\Utility::getProductTags(1, $template);
	}

	$index = 0;

	foreach ($list as $data)
	{
		$product = RedshopHelperProduct::getProductById($data['item']->productId);
		$tdStart = '';
		$tdEnd   = '';

		if ($index != ($total - 1))
		{
			$tdStart = "<td>";
			$tdEnd   = "</td>";
		}

		$expDiv = "<div name='exp_" . $product->product_id . "'>";
		$divEnd = "</div>";

		$ItemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);

		if (!empty($ItemData))
		{
			$pItemid = $ItemData->id;
		}
		else
		{
			$catIdMain = $product->cat_in_sefurl;
			$pItemid   = RedshopHelperRouter::getItemId($product->product_id, $catIdMain);
		}

		$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&Itemid=' . $pItemid);

		$thumbUrl = RedshopHelperMedia::getImagePath(
			$product->product_full_image,
			'',
			'thumb',
			'product',
			Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_WIDTH'),
			Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_HEIGHT'),
			Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
		);
		$img      = "<div style='width:" . Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_WIDTH') . "px;height:" . Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_HEIGHT') . "px;float: left;' >"
			. "<a href='" . $link . "' title='" . $product->product_name . "'><img src='" . $thumbUrl . "'></a>"
			. "</div>";

		$expand = "<a href='javascript:void(0)' onClick='expand_collapse(this," . $product->product_id . ")' style='font-size:18px;text-decoration:none;' >-</a>";

		if ($index != ($total - 1))
		{
			$template = str_replace('{expand_collapse}', $expand . $tdEnd . '<td align="center">' . "{expand_collapse}", $template);
		}
		else
		{
			$template = str_replace('{expand_collapse}', $expand . $tdEnd . $tdStart . "{expand_collapse}", $template);
		}

		$template = str_replace('{product_name}', $expDiv . $product->product_name . $divEnd . $tdEnd . $tdStart . "{product_name}", $template);
		$template = str_replace('{product_image}', $expDiv . $img . $divEnd . $tdEnd . $tdStart . "{product_image}", $template);

		if (strstr($template, "{manufacturer_name}"))
		{
			$manufacturer     = RedshopEntityManufacturer::getInstance($product->manufacturer_id);
			$manufacturerName = $manufacturer->get('name');
			$template         = str_replace('{manufacturer_name}', $expDiv . $manufacturerName . $divEnd . $tdEnd . $tdStart . "{manufacturer_name}", $template);
		}

		if (strstr($template, "{discount_start_date}"))
		{
			$discStartDate = '';

			if ($product->discount_stratdate)
			{
				$discStartDate = $config->convertDateFormat($product->discount_stratdate);
			}

			$template = str_replace('{discount_start_date}', $expDiv . $discStartDate . $divEnd . $tdEnd . $tdStart . "{discount_start_date}", $template);
		}

		if (strstr($template, "{discount_end_date}"))
		{
			$discEndDate = "";

			if ($product->discount_enddate)
			{
				$discEndDate = $config->convertDateFormat($product->discount_enddate);
			}

			$template = str_replace('{discount_end_date}', $expDiv . $discEndDate . $divEnd . $tdEnd . $tdStart . "{discount_end_date}", $template);
		}

		$template = str_replace('{product_s_desc}', $expDiv . $product->product_s_desc . $divEnd . $tdEnd . $tdStart . "{product_s_desc}", $template);
		$template = str_replace('{product_desc}', $expDiv . $product->product_desc . $divEnd . $tdEnd . $tdStart . "{product_desc}", $template);

		$productNumberOutput = '<span id="product_number_variable' . $product->product_id . '">' . $product->product_number . '</span>';
		$template            = str_replace('{product_number}', $expDiv . $product->product_number . $divEnd . $tdEnd . $tdStart . "{product_number}", $template);

		$productWeightUnit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT') . '</span>';
		$template          = str_replace('{product_weight}', $expDiv . $productHelper->redunitDecimal($product->weight) . "&nbsp;" . $productWeightUnit . $divEnd . $tdEnd . $tdStart . "{product_weight}", $template);

		$productUnit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . '</span>';
		$template    = str_replace('{product_length}', $expDiv . $productHelper->redunitDecimal($product->product_length) . "&nbsp;" . $productUnit . $divEnd . $tdEnd . $tdStart . "{product_length}", $template);
		$template    = str_replace('{product_height}', $expDiv . $productHelper->redunitDecimal($product->product_height) . "&nbsp;" . $productUnit . $divEnd . $tdEnd . $tdStart . "{product_height}", $template);
		$template    = str_replace('{product_width}', $expDiv . $productHelper->redunitDecimal($product->product_width) . "&nbsp;" . $productUnit . $divEnd . $tdEnd . $tdStart . "{product_width}", $template);

		$productVolumeUnit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . "3" . '</span>';
		$template          = str_replace('{product_volume}', $expDiv . $productHelper->redunitDecimal($product->product_volume) . "&nbsp;" . $productVolumeUnit . $divEnd . $tdEnd . $tdStart . "{product_volume}", $template);

		if (strstr($template, "{product_price}"))
		{
			$price = 0;

			if (Redshop::getConfig()->get('SHOW_PRICE')
				&& !Redshop::getConfig()->get('USE_AS_CATALOG')
				&& (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
					|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
						&& Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
			)
			{
				$productPrices = $productHelper->getProductNetPrice($product->product_id);
				$price         = $productHelper->getProductFormattedPrice($productPrices['product_price']);
			}

			$template = str_replace('{product_price}', $expDiv . $price . $divEnd . $tdEnd . $tdStart . "{product_price}", $template);
		}

		if (strstr($template, "{product_rating_summary}"))
		{
			$finalAvgReviewData = Redshop\Product\Rating::getRating($data['item']->productId);
			$template           = str_replace('{product_rating_summary}', $expDiv . $finalAvgReviewData . $divEnd . $tdEnd . $tdStart . "{product_rating_summary}", $template);
		}

		if (strstr($template, "{products_in_stock}") || strstr($template, "{product_stock_amount_image}"))
		{
			$productStock = RedshopHelperStockroom::getStockAmountWithReserve($data['item']->productId);
			$template     = str_replace('{products_in_stock}', $expDiv . $productStock . $divEnd . $tdEnd . $tdStart . "{products_in_stock}", $template);

			$stockamountList  = RedshopHelperStockroom::getStockAmountImage($data['item']->productId, "product", $productStock);
			$stockamountImage = "";

			if (!empty($stockamountList))
			{
				$stockamountImage = '<a class="imgtooltip"><span>';
				$stockamountImage .= '<div class="spnheader">' . JText::_('COM_REDSHOP_STOCK_AMOUNT') . '</div>';
				$stockamountImage .= '<div class="spnalttext" id="stockImageTooltip' . $data['item']->productId . '">' . $stockamountList[0]->stock_amount_image_tooltip . '</div></span>';
				$stockamountImage .= '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'stockroom/' . $stockamountList[0]->stock_amount_image . '" width="150px" height="90px" alt="' . $stockamountList[0]->stock_amount_image_tooltip . '" id="stockImage' . $data['item']->productId . '" /></a>';
			}

			$template = str_replace('{product_stock_amount_image}', $expDiv . $stockamountImage . $divEnd . $tdEnd . $tdStart . "{product_stock_amount_image}", $template);
		}

		if (strstr($template, "{product_delivery_time}"))
		{
			$productDeliveryTime = $productHelper->getProductMinDeliveryTime($data['item']->productId);
			$template            = str_replace('{product_delivery_time}', $expDiv . $productDeliveryTime . $divEnd . $tdEnd . $tdStart . "{product_delivery_time}", $template);
		}

		if (strstr($template, "{product_availability_date}"))
		{
			$availableDate = "";

			if ($product->product_availability_date != "")
			{
				$availableDate = $config->convertDateFormat($product->product_availability_date);
			}

			$template = str_replace('{product_availability_date}', $expDiv . $availableDate . $divEnd . $tdEnd . $tdStart . "{product_availability_date}", $template);
		}

		if (strstr($template, "{product_category}"))
		{
			$categoriesId = explode(',', $data['item']->categoriesId);

			if (count($categoriesId) <= 1)
			{
				$category     = RedshopEntityCategory::getInstance($data['item']->categoryId);
				$categoryName = $category->get('name');

			}
			else
			{
				$categoriesName = array();

				foreach ($categoriesId as $categoryId)
				{
					$category = RedshopEntityCategory::getInstance((int) $categoryId);

					if (!in_array($category->get('name'), $categoriesName))
					{
						$categoriesName[] = $category->get('name');
					}
				}

				$categoryName = implode(' ,', $categoriesName);

			}

			$template = str_replace('{product_category}', $expDiv . $categoryName . $divEnd . $tdEnd . $tdStart . "{product_category}", $template);
		}

		$linkRemove = JUri::root() . 'index.php?option=com_redshop&view=product&task=removecompare&layout=compare&pid=' . $product->product_id . '&cid=' . $categoriesId[0] . '&Itemid=' . $this->itemId . '&tmpl=component';

		$remove   = "<a href='" . $linkRemove . "'>" . JText::_('COM_REDSHOP_REMOVE_PRODUCT_FROM_COMPARE_LIST') . "</a>";
		$template = str_replace('{remove}', $expDiv . $remove . $divEnd . $tdEnd . $tdStart . "{remove}", $template);

		if (strstr($template, "{add_to_cart}"))
		{
			$addToCart = Redshop\Cart\Render::replace($data['item']->productId, 0, 0, 0, '{form_addtocart:add_to_cart1}');
			$template  = str_replace('{add_to_cart}', $expDiv . $addToCart . $divEnd . $tdEnd . $tdStart . "{add_to_cart}", $template);
		}

		// Extra field display
		foreach ($productTag as $aProductTag)
		{
			$str = "'" . $aProductTag . "'";

			if ($index != ($total - 1))
			{
				$template = str_replace(
					'{' . $aProductTag . '}',
					$expDiv . '{' . $aProductTag . '}' . $divEnd . $tdEnd . $tdStart . '{addedext_tag}',
					$template
				);
			}

			$template = ExtraFields::displayExtraFields("1", $product->product_id, $str, $template);
			$template = str_replace('{addedext_tag}', '{' . $aProductTag . '}', $template);
		}

		$index++;
	}

	$template = General::replaceBlank(array(
		'{expand_collapse}',
		'{product_name}',
		'{product_image}',
		'{manufacturer_name}',
		'{discount_start_date}',
		'{discount_end_date}',
		'{product_price}',
		'{product_s_desc}',
		'{product_desc}',
		'{product_rating_summary}',
		'{product_delivery_time}',
		'{product_number}',
		'{products_in_stock}',
		'{product_stock_amount_image}',
		'{product_weight}',
		'{product_length}',
		'{product_height}',
		'{product_width}',
		'{product_availability_date}',
		'{product_volume}',
		'{product_category}',
		'{remove}',
		'{add_to_cart}'
	), $template);

	$template = $this->redTemplate->parseredSHOPplugin($template);
}
else
{
	$template = JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE');
}

echo eval("?>" . $template . "<?php ");
