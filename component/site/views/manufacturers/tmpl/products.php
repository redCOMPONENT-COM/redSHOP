<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$producthelper    = productHelper::getInstance();
$extra_field      = extra_field::getInstance();
$redTemplate      = Redtemplate::getInstance();
$redhelper        = redhelper::getInstance();
$extraField       = extraField::getInstance();
$Redconfiguration = Redconfiguration::getInstance();
$app              = JFactory::getApplication();

JHTML::_('behavior.modal');

$url            = JUri::base();
$user           = JFactory::getUser();
$model          = $this->getModel('manufacturers');
$itemId         = $app->input->getInt('Itemid');
$print          = $app->input->getInt('print');
$orderBySelect  = $app->input->getString('order_by', Redshop::getConfig()->getString('DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD'));
$filterBySelect = $app->input->getString('filter_by', 0);

$document     = JFactory::getDocument();
$manufacturer = $this->detail[0];
$limit        = $model->getProductLimit();
$router       = $app->getRouter();

// Page Title
$pagetitle = JText::_('COM_REDSHOP_MANUFACTURER_PRODUCTS');
?>
<?php if ($this->params->get('show_page_heading', 1)): ?>
    <h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php
		if ($this->params->get('page_title') != $pagetitle)
		{
			echo $this->escape($this->params->get('page_title'));
		}
		else
		{
			echo $pagetitle;
		} ?>
    </h1>
<?php endif; ?>

<?php
// Page title end

$manufacturertemplate = RedshopHelperTemplate::getTemplate("manufacturer_products", $manufacturer->id);

if (count($manufacturertemplate) > 0 && $manufacturertemplate[0]->template_desc)
{
	$template_desc = $manufacturertemplate[0]->template_desc;
	$template_id   = $manufacturertemplate[0]->id;
}
else
{
	$template_desc = "<div class=\"category_print\">{print}</div>\r\n<div style=\"clear: both;\"></div>\r\n<div class=\"manufacturer_name\">{manufacturer_name}</div>\r\n<div class=\"manufacturer_image\">{manufacturer_image}</div>\r\n<div class=\"manufacturer_description\">{manufacturer_description}</div>\r\n\r\n<div style=\"clear: both;\"></div>\r\n\r\n<div id=\"category_header\">\r\n	<div class=\"category_order_by\">\r\n		{order_by} \r\n	</div>\r\n</div>\r\n\r\n<div class=\"category_box_wrapper\">{product_loop_start}\r\n<div>{category_heading_start}<div>*{category_name}<div>{category_heading_end}</div>\r\n<div class=\"category_box_outside\">\r\n<div class=\"category_box_inside\">\r\n<div class=\"category_product_image\">{product_thumb_image_1}</div>\r\n<div class=\"category_product_title\">\r\n<h3>{product_name}</h3>\r\n</div>\r\n<div class=\"category_product_price\">{product_price}</div>\r\n<div class=\"category_product_readmore\">{read_more}</div>\r\n</div>\r\n</div>\r\n{product_loop_end}</div>\r\n<div class=\"pagination\">{pagination} </div>";
	$template_id   = 0;
}

if ($print)
{
	$onclick = "onclick='window.print();'";
}
else
{
	$print_url = $url . "index.php?option=com_redshop&view=manufacturers&layout=products&mid=" . $manufacturer->id . "&print=1&tmpl=component&Itemid=" . $itemId;
	$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
}

$print_tag = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png' alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
$print_tag .= "</a>";

$template_start  = $template_desc;
$template_middle = "";
$template_end    = "";

if (strstr($template_desc, '{product_loop_start}') && strstr($template_desc, '{product_loop_end}'))
{
	$template_sdata  = explode('{product_loop_start}', $template_desc);
	$template_start  = $template_sdata[0];
	$template_edata  = explode('{product_loop_end}', $template_sdata[1]);
	$template_end    = $template_edata[1];
	$template_middle = $template_edata[0];
}

$cart_mdata       = '';
$prod_thumb_image = "";

$manufacturer_products = $model->getManufacturerProducts($template_desc);

$cname = '';

if ($template_middle != "")
{
	$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(RedshopHelperExtrafields::SECTION_PRODUCT, 1, 1);

	for ($i = 0, $in = count($manufacturer_products); $i < $in; $i++)
	{
		$cart_mdata .= $template_middle;

		if (strstr($cart_mdata, "{category_heading_start}") && strstr($cart_mdata, "{category_heading_end}"))
		{
			$cart_mdata1 = explode("{category_heading_start}", $cart_mdata);
			$cart_mdata2 = explode("{category_heading_end}", $cart_mdata1[1]);

			if ($cname != $manufacturer_products[$i]->name)
			{
				$cart_mdata = str_replace("{category_name}", $manufacturer_products[$i]->name, $cart_mdata);
				$cart_mdata = str_replace("{category_heading_start}", "", $cart_mdata);
				$cart_mdata = str_replace("{category_heading_end}", "", $cart_mdata);
			}
			else
			{
				$cart_mdata = $cart_mdata1[0] . $cart_mdata2[1];
			}

			$cname = $manufacturer_products[$i]->name;

			$cart_mdata = str_replace("{category_heading_start}", "", $cart_mdata);
			$cart_mdata = str_replace("{category_heading_end}", "", $cart_mdata);
		}

		$link         = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $manufacturer_products[$i]->product_id);
		$product_name = "<a href='" . $link . "'>" . $manufacturer_products[$i]->product_name . "</a>";
		$cart_mdata   = str_replace("{product_name}", $product_name, $cart_mdata);

		$cart_mdata   = $producthelper->getProductOnSaleComment($manufacturer_products[$i], $cart_mdata);
		$cart_mdata   = $producthelper->getProductNotForSaleComment($manufacturer_products[$i], $cart_mdata);
		$cart_mdata   = $producthelper->getSpecialProductComment($manufacturer_products[$i], $cart_mdata);
		$product_id   = $manufacturer_products[$i]->product_id;
		$childproduct = RedshopHelperProduct::getChildProduct($product_id);

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

			if ($manufacturer_products[$i]->attribute_set_id > 0)
			{
				$attributes_set = $producthelper->getProductAttribute(0, $manufacturer_products[$i]->attribute_set_id, 0, 1);
			}

			$attributes = $producthelper->getProductAttribute($product_id);
			$attributes = array_merge($attributes, $attributes_set);
		}

		/////////////////////////////////// Product attribute  Start /////////////////////////////////
		$totalatt = count($attributes);

		// Check product for not for sale
		$cart_mdata = $producthelper->getExtraSectionTag($extraFieldName, $product_id, "1", $cart_mdata, 1);

		$attribute_template = \Redshop\Template\Helper::getAttribute($cart_mdata);
		$cart_mdata         = Redshop\Product\Stock::replaceInStock($product_id, $cart_mdata, $attributes, $attribute_template);

		$cart_mdata = $producthelper->replaceAttributeData($product_id, 0, 0, $attributes, $cart_mdata, $attribute_template, $isChilds, 0, $totalatt);

		// Get cart tempalte
		$cart_mdata = Redshop\Cart\Render::replace($product_id, 0, 0, 0, $cart_mdata, $isChilds);

		$cart_mdata = str_replace("{product_id_lbl}", JText::_('COM_REDSHOP_PRODUCT_ID_LBL'), $cart_mdata);
		$cart_mdata = str_replace("{product_id}", $manufacturer_products[$i]->product_id, $cart_mdata);
		$cart_mdata = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $cart_mdata);
		$cart_mdata = str_replace("{product_number}", $manufacturer_products[$i]->product_number, $cart_mdata);
		$cart_mdata = str_replace("{product_s_desc}", $manufacturer_products[$i]->product_s_desc, $cart_mdata);

		$cart_mdata = str_replace("{category_name}", $manufacturer_products[$i]->name, $cart_mdata);

		if (strstr($cart_mdata, '{product_desc}'))
		{
			$p_desc     = $Redconfiguration->maxchar($manufacturer_products[$i]->product_desc, Redshop::getConfig()->get('CATEGORY_PRODUCT_DESC_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_PRODUCT_DESC_END_SUFFIX'));
			$cart_mdata = str_replace("{product_desc}", $p_desc, $cart_mdata);
		}

		$cart_mdata = $producthelper->replaceWishlistButton($product_id, $cart_mdata);

		if (strstr($cart_mdata, '{product_thumb_image_2}'))
		{
			$tag     = '{product_thumb_image_2}';
			$h_thumb = Redshop::getConfig()->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT_2');
			$w_thumb = Redshop::getConfig()->get('MANUFACTURER_PRODUCT_THUMB_WIDTH_2');
		}
        elseif (strstr($cart_mdata, '{product_thumb_image_3}'))
		{
			$tag     = '{product_thumb_image_3}';
			$h_thumb = Redshop::getConfig()->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT_3');
			$w_thumb = Redshop::getConfig()->get('MANUFACTURER_PRODUCT_THUMB_WIDTH_3');
		}
        elseif (strstr($cart_mdata, '{product_thumb_image_1}'))
		{
			$tag     = '{product_thumb_image_1}';
			$h_thumb = Redshop::getConfig()->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT');
			$w_thumb = Redshop::getConfig()->get('MANUFACTURER_PRODUCT_THUMB_WIDTH');
		}
		else
		{
			$tag     = '{product_thumb_image}';
			$h_thumb = Redshop::getConfig()->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT');
			$w_thumb = Redshop::getConfig()->get('MANUFACTURER_PRODUCT_THUMB_WIDTH');
		}

		$prod_thumb_image = Redshop\Product\Image\Image::getImage($manufacturer_products[$i]->product_id, $link, $w_thumb, $h_thumb);
		$cart_mdata       = str_replace($tag, $prod_thumb_image, $cart_mdata);
		$redmore          = "<a href='" . $link . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
		$cart_mdata       = str_replace("{read_more}", $redmore, $cart_mdata);
		$cart_mdata       = str_replace("{read_more_link}", $link, $cart_mdata);

		if (strstr($cart_mdata, '{manufacturer_product_link}'))
		{
			$cart_mdata = str_replace("{manufacturer_product_link}", $link, $cart_mdata);
		}

		$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);
		$cart_mdata     = $producthelper->getExtraSectionTag($extraFieldName, $manufacturer_products[$i]->product_id, "1", $cart_mdata);

		$productAvailabilityDate = strstr($cart_mdata, "{product_availability_date}");
		$stockNotifyFlag         = strstr($cart_mdata, "{stock_notify_flag}");
		$stockStatus             = strstr($cart_mdata, "{stock_status");

		$attributeproductStockStatus = array();

		if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus)
		{
			$attributeproductStockStatus = $producthelper->getproductStockStatus($manufacturer_products[$i]->product_id, $totalatt);
		}

		$cart_mdata = \Redshop\Helper\Stockroom::replaceProductStockData(
			$manufacturer_products[$i]->product_id,
			0,
			0,
			$cart_mdata,
			$attributeproductStockStatus
		);

//		$cart_tr .=$cart_mdata ;

//		$cname = $manufacturer_products[$i]->category_name;
	}
}

$template_desc = $template_start . $cart_mdata . $template_end;

if (strstr($template_desc, "{manufacturer_image}"))
{
	$thumbImage = '';
	$media      = RedshopEntityManufacturer::getInstance($manufacturer->id)->getMedia();

	if ($media->isValid() && !empty($media->get('media_name'))
		&& JFile::exists(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $manufacturer->id . '/' . $media->get('media_name')))
	{
		$thumbHeight = Redshop::getConfig()->get('MANUFACTURER_THUMB_HEIGHT');
		$thumbWidth  = Redshop::getConfig()->get('MANUFACTURER_THUMB_WIDTH');

		if (Redshop::getConfig()->get('WATERMARK_MANUFACTURER_IMAGE') || Redshop::getConfig()->get('WATERMARK_MANUFACTURER_THUMB_IMAGE'))
		{
			$imagePath = RedshopHelperMedia::watermark(
				'manufacturer',
				$media->get('media_name'),
				$thumbWidth,
				$thumbHeight,
				Redshop::getConfig()->get('WATERMARK_MANUFACTURER_IMAGE')
			);
		}
		else
		{
			$imagePath = RedshopHelperMedia::getImagePath(
				$media->get('media_name'),
				'',
				'thumb',
				'manufacturer',
				$thumbWidth,
				$thumbHeight,
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING'),
				'manufacturer',
				$manufacturer->id
			);
		}

		$altText = $media->get('media_alternate_text', $manufacturer->name);

		$thumbImage = "<a title='" . $altText . "' class=\"modal\" href='" . REDSHOP_MEDIA_IMAGE_ABSPATH . 'manufacturer/' . $manufacturer->id . '/' . $media->get('media_name') . "'   rel=\"{handler: 'image', size: {}}\">
				<img alt='" . $altText . "' title='" . $altText . "' src='" . $imagePath . "'></a>";
	}

	$template_desc = str_replace("{manufacturer_image}", $thumbImage, $template_desc);
}

$manlink = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $manufacturer->id . '&Itemid=' . $itemId);

$manproducts = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $manufacturer->id . '&Itemid=' . $itemId);

$template_desc = str_replace("{manufacturer_name}", $manufacturer->name, $template_desc);

// Extra field display
$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(10, 1, 1);
$template_desc  = $producthelper->getExtraSectionTag($extraFieldName, $manufacturer->id, "10", $template_desc);
$template_desc  = str_replace("{manufacturer_description}", $manufacturer->description, $template_desc);

$manufacturer_extra_fields = RedshopHelperExtrafields::listAllFieldDisplay(10, $manufacturer->id);
$template_desc             = str_replace("{manufacturer_extra_fields}", $manufacturer_extra_fields, $template_desc);

$template_desc = str_replace("{manufacturer_link}", $manlink, $template_desc);

$template_desc = str_replace("{print}", $print_tag, $template_desc);

if (strstr($template_desc, '{filter_by}'))
{
	$filterby_form = "<form name='filter_form' action='' method='post'>" . JText::_('COM_REDSHOP_SELECT_FILTER_BY') . $this->lists['filter_select'];
	$filterby_form .= "<input type='hidden' name='order_by' value='" . $app->input->getString('order_by', '') . "' /></form>";
	$template_desc = str_replace("{filter_by}", $filterby_form, $template_desc);
}

if (strstr($template_desc, '{order_by}'))
{
	$orderby_form  = "<form name='orderby_form' action='' method='post'>" . JText::_('COM_REDSHOP_SELECT_ORDER_BY') . $this->lists['order_select'];
	$orderby_form  .= "<input type='hidden' name='filter_by' value='" . $app->input->getString('filter_by', 0) . "' /></form>";
	$template_desc = str_replace("{order_by}", $orderby_form, $template_desc);
}

if (strstr($template_desc, '{pagination}'))
{
	$productpagination = $model->getProductPagination();
	$template_desc     = str_replace("{pagination}", $productpagination->getPagesLinks(), $template_desc);
}

$template_desc = RedshopHelperTemplate::parseRedshopPlugin($template_desc);
echo eval("?>" . $template_desc . "<?php ");
