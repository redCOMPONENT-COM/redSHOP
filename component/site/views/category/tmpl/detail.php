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
$url = JURI::base();

$catid = $this->catid;
$objhelper = new redhelper;
$Redconfiguration = new Redconfiguration;
$producthelper = new producthelper;
$extraField = new extraField;
$stockroomhelper = new rsstockroomhelper;
$redTemplate = new Redtemplate;
$texts = new text_library;
$url = JURI::base();
$model = $this->getModel('category');
$option = JRequest::getVar('option');
$Itemid = JRequest::getInt('Itemid');
$start = JRequest::getInt('limitstart', 0, '', 'int');
$print = JRequest::getInt('print');
$slide = JRequest::getInt('ajaxslide');
$filter_by = JRequest::getInt('manufacturer_id', $this->params->get('manufacturer_id'), '', 'int');
$category_template = JRequest::getInt('category_template', 0, '', 'int');
$dispatcher = JDispatcher::getInstance();

$minmax = $model->getMaxMinProductPrice();
$texpricemin = $minmax[0];
$texpricemax = $minmax[1];

$loadCategorytemplate = $this->loadCategorytemplate;
$fieldArray = $extraField->getSectionFieldList(17, 0, 0);

if (count($loadCategorytemplate) > 0 && $loadCategorytemplate[0]->template_desc != "")
{
	$template_desc = $loadCategorytemplate[0]->template_desc;
}
else
{
	$template_desc = "<div class=\"category_print\">{print}</div>\r\n<div style=\"clear: both;\"></div>\r\n<div class=\"category_main_description\">{category_main_description}</div>\r\n<p>{if subcats} {category_loop_start}</p>\r\n<div id=\"categories\">\r\n<div style=\"float: left; width: 200px;\">\r\n<div class=\"category_image\">{category_thumb_image}</div>\r\n<div class=\"category_description\">\r\n<h2 class=\"category_title\">{category_name}</h2>\r\n{category_description}</div>\r\n</div>\r\n</div>\r\n<p>{category_loop_end} {subcats end if}</p>\r\n<div style=\"clear: both;\"></div>\r\n<div id=\"category_header\">\r\n<div class=\"category_order_by\">{order_by}</div>\r\n</div>\r\n<div class=\"category_box_wrapper\">{product_loop_start}\r\n<div class=\"category_box_outside\">\r\n<div class=\"category_box_inside\">\r\n<div class=\"category_product_image\">{product_thumb_image}</div>\r\n<div class=\"category_product_title\">\r\n<h3>{product_name}</h3>\r\n</div>\r\n<div class=\"category_product_price\">{product_price}</div>\r\n<div class=\"category_product_readmore\">{read_more}</div>\r\n<div>{product_rating_summary}</div>\r\n<div class=\"category_product_addtocart\">{form_addtocart:add_to_cart1}</div>\r\n</div>\r\n</div>\r\n{product_loop_end}\r\n<div class=\"category_product_bottom\" style=\"clear: both;\"></div>\r\n</div>\r\n<div class=\"category_pagination\">{pagination}</div>";
}

$endlimit = count($this->product);

if (!strstr($template_desc, "{show_all_products_in_category}") && strstr($template_desc, "{pagination}"))
{
	$endlimit = $model->getProductPerPage();

	if (strstr($template_desc, "{product_display_limit}"))
	{
		$endlimit = JRequest::getInt('limit', $endlimit, '', 'int');
	}
}
else
{
	$endlimit = $model->getProductPerPage();
}

$app = JFactory::getApplication();
$router = $app->getRouter();

$uri = new JURI('index.php?option=' . $option . '&view=category&layout=detail&cid=' . $catid . '&Itemid=' . $Itemid . '&limit=' . $endlimit . '&texpricemin=' . $texpricemin . '&texpricemax=' . $texpricemax . '&order_by=' . $this->order_by_select . '&manufacturer_id=' . $this->manufacturer_id . '&category_template=' . $this->category_template_id);

$document = JFactory::getDocument();
$model = $this->getModel('category');

// Replace redproductfilder filter tag
if (strstr($template_desc, "{redproductfinderfilter:"))
{
	if (file_exists(JPATH_SITE . '/components/com_redproductfinder/helpers/redproductfinder_helper.php'))
	{
		include_once JPATH_SITE . "/components/com_redproductfinder/helpers/redproductfinder_helper.php";
		$redproductfinder_helper = new redproductfinder_helper;
		$hdnFields               = array('texpricemin' => '0', 'texpricemax' => '0', 'manufacturer_id' => $filter_by, 'category_template' => $category_template);
		$hide_filter_flag        = false;

		if ($this->catid)
		{
			$prodctofcat = $producthelper->getProductCategory($this->catid);

			if (empty($prodctofcat))
				$hide_filter_flag = true;
		}

		$template_desc = $redproductfinder_helper->replaceProductfinder_tag($template_desc, $hdnFields, $hide_filter_flag);
	}
}

// Replace redproductfilder filter tag end here
if (!$slide)
{
	echo '<div class="category">';

	if ($this->params->get('show_page_heading', 0))
	{
		?>
		<div class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
			<?php
			if ($this->maincat->pageheading != "")
			{
				echo $this->escape($this->maincat->pageheading);
			}
			else
			{
				echo $this->escape($this->pageheadingtag);
			}
			?>
		</div>
	<?php
	}

	echo "</div>";

	if ($print)
	{
		$onclick       = "onclick='window.print();'";
		$template_desc = str_replace("{product_price_slider}", "", $template_desc);
		$template_desc = str_replace("{pagination}", "", $template_desc);
	}
	else
	{
		$print_url = $url . "index.php?option=com_redshop&view=category&layout=detail&cid=" . $catid . "&print=1&tmpl=component&Itemid=" . $Itemid;
		$print_url .= "&limit=" . $endlimit . "&texpricemin=" . $texpricemin . "&texpricemax=" . $texpricemax . "&order_by=" . $this->order_by_select;
		$print_url .= "&manufacturer_id=" . $this->manufacturer_id . "&category_template=" . $this->category_template_id;

		$onclick = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
	}

	$print_tag = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
	$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png' alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
	$print_tag .= "</a>";

	$template_desc = str_replace("{print}", $print_tag, $template_desc);
	$template_desc = str_replace("{total_product}", count($this->product), $template_desc);
	$template_desc = str_replace("{total_product_lbl}", JText::_('COM_REDSHOP_TOTAL_PRODUCT'), $template_desc);

	if (strstr($template_desc, '{returntocategory_link}') || strstr($template_desc, '{returntocategory_name}') || strstr($template_desc, '{returntocategory}'))
	{
		$parentid = $producthelper->getParentCategory($catid);

		if ($parentid != 0)
		{
			$categorylist     = $producthelper->getSection("category", $parentid);
			$returncatlink    = JRoute::_("index.php?option=" . $option . "&view=category&cid=" . $parentid . '&manufacturer_id=' . $this->manufacturer_id . "&Itemid=" . $Itemid);
			$returntocategory = '<a href="' . $returncatlink . '">' . DAFULT_RETURN_TO_CATEGORY_PREFIX . '&nbsp;' . $categorylist->category_name . '</a>';
		}
		else
		{
			$categorylist->category_name = DAFULT_RETURN_TO_CATEGORY_PREFIX;
			$returncatlink               = JRoute::_("index.php?option=" . $option . "&view=category&manufacturer_id=" . $this->manufacturer_id . "&Itemid=" . $Itemid);
			$returntocategory            = '<a href="' . $returncatlink . '">' . DAFULT_RETURN_TO_CATEGORY_PREFIX . '</a>';
		}

		$template_desc = str_replace("{returntocategory_link}", $returncatlink, $template_desc);
		$template_desc = str_replace('{returntocategory_name}', $categorylist->category_name, $template_desc);
		$template_desc = str_replace("{returntocategory}", $returntocategory, $template_desc);
	}

	if (strstr($template_desc, '{category_main_description}'))
	{
		$main_cat_desc = $Redconfiguration->maxchar($this->maincat->category_description, CATEGORY_SHORT_DESC_MAX_CHARS, CATEGORY_SHORT_DESC_END_SUFFIX);
		$template_desc = str_replace("{category_main_description}", $main_cat_desc, $template_desc);
	}

	if (strstr($template_desc, '{category_main_short_desc}'))
	{
		$main_cat_s_desc = $Redconfiguration->maxchar($this->maincat->category_short_description, CATEGORY_SHORT_DESC_MAX_CHARS, CATEGORY_SHORT_DESC_END_SUFFIX);
		$template_desc   = str_replace("{category_main_short_desc}", $main_cat_s_desc, $template_desc);
	}

	if (strstr($template_desc, '{shopname}'))
	{
		$template_desc = str_replace("{shopname}", SHOP_NAME, $template_desc);
	}

	$main_cat_name = $Redconfiguration->maxchar($this->maincat->category_name, CATEGORY_TITLE_MAX_CHARS, CATEGORY_TITLE_END_SUFFIX);
	$template_desc = str_replace("{category_main_name}", $main_cat_name, $template_desc);

	if (strstr($template_desc, '{category_main_thumb_image_2}'))
	{
		$ctag     = '{category_main_thumb_image_2}';
		$ch_thumb = THUMB_HEIGHT_2;
		$cw_thumb = THUMB_WIDTH_2;
	}
	elseif (strstr($template_desc, '{category_main_thumb_image_3}'))
	{
		$ctag     = '{category_main_thumb_image_3}';
		$ch_thumb = THUMB_HEIGHT_3;
		$cw_thumb = THUMB_WIDTH_3;
	}
	elseif (strstr($template_desc, '{category_main_thumb_image_1}'))
	{
		$ctag     = '{category_main_thumb_image_1}';
		$ch_thumb = THUMB_HEIGHT;
		$cw_thumb = THUMB_WIDTH;
	}
	else
	{
		$ctag     = '{category_main_thumb_image}';
		$ch_thumb = THUMB_HEIGHT;
		$cw_thumb = THUMB_WIDTH;
	}

	$cItemid = $objhelper->getCategoryItemid($catid);

	if ($cItemid != "")
	{
		$tmpItemid = $cItemid;
	}
	else
	{
		$tmpItemid = $Itemid;
	}

	$link = JRoute::_('index.php?option=' . $option . '&view=category&cid=' . $catid . '&manufacturer_id=' . $this->manufacturer_id . '&layout=detail&Itemid=' . $tmpItemid);

	$cat_main_thumb = "";

	if ($this->maincat->category_full_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $this->maincat->category_full_image))
	{
		$water_cat_img  = $objhelper->watermark('category', $this->maincat->category_full_image, $cw_thumb, $ch_thumb, WATERMARK_CATEGORY_THUMB_IMAGE, '0');
		$cat_main_thumb = "<a href='" . $link . "' title='" . $main_cat_name . "'><img src='" . $water_cat_img . "' alt='" . $main_cat_name . "' title='" . $main_cat_name . "'></a>";
	}

	$template_desc = str_replace($ctag, $cat_main_thumb, $template_desc);

	$extraFieldName = $extraField->getSectionFieldNameArray(2, 1, 1);
	$template_desc  = $producthelper->getExtraSectionTag($extraFieldName, $catid, "2", $template_desc, 0);

	if (strstr($template_desc, "{compare_product_div}"))
	{
		$compare_product_div = "";

		if (PRODUCT_COMPARISON_TYPE != "")
		{
			$comparediv          = $producthelper->makeCompareProductDiv();
			$compare_product_div = "<form name='frmCompare' method='post' action='" . JRoute::_('index.php?option=com_redshop&view=product&layout=compare&Itemid=' . $Itemid) . "' >";
			$compare_product_div .= "<a href='javascript:compare();' >" . JText::_('COM_REDSHOP_COMPARE') . "</a>";
			$compare_product_div .= "<div id='divCompareProduct'>" . $comparediv . "</div>";
			$compare_product_div .= "</form>";
		}

		$template_desc = str_replace("{compare_product_div}", $compare_product_div, $template_desc);
	}

	if (strstr($template_desc, "{category_loop_start}") && strstr($template_desc, "{category_loop_end}"))
	{
		$template_d1     = explode("{category_loop_start}", $template_desc);
		$template_d2     = explode("{category_loop_end}", $template_d1 [1]);
		$subcat_template = $template_d2 [0];

		if (strstr($subcat_template, '{category_thumb_image_2}'))
		{
			$tag     = '{category_thumb_image_2}';
			$h_thumb = THUMB_HEIGHT_2;
			$w_thumb = THUMB_WIDTH_2;
		}
		elseif (strstr($subcat_template, '{category_thumb_image_3}'))
		{
			$tag     = '{category_thumb_image_3}';
			$h_thumb = THUMB_HEIGHT_3;
			$w_thumb = THUMB_WIDTH_3;
		}
		elseif (strstr($subcat_template, '{category_thumb_image_1}'))
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

		$cat_detail = "";

		for ($i = 0; $i < count($this->detail); $i++)
		{
			$row = $this->detail[$i];

			$data_add = $subcat_template;

			$cItemid = $objhelper->getCategoryItemid($row->category_id);

			if ($cItemid != "")
			{
				$tmpItemid = $cItemid;
			}
			else
			{
				$tmpItemid = $Itemid;
			}

			$link = JRoute::_('index.php?option=' . $option . '&view=category&cid=' . $row->category_id . '&manufacturer_id=' . $this->manufacturer_id . '&layout=detail&Itemid=' . $tmpItemid);

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
				$cat_thumb = "<a class='modal' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . $row->category_full_image . "' rel=\"{handler: 'image', size: {}}\" " . $title . ">";
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
				$cat_desc = $Redconfiguration->maxchar($row->category_description, CATEGORY_SHORT_DESC_MAX_CHARS, CATEGORY_SHORT_DESC_END_SUFFIX);
				$data_add = str_replace("{category_description}", $cat_desc, $data_add);
			}

			if (strstr($data_add, '{category_short_desc}'))
			{
				$cat_s_desc = $Redconfiguration->maxchar($row->category_short_description, CATEGORY_SHORT_DESC_MAX_CHARS, CATEGORY_SHORT_DESC_END_SUFFIX);
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

			// Shopper group category ACL
			$checkcid = $objhelper->getShopperGroupCategory($row->category_id);
			$sgportal = $objhelper->getShopperGroupPortal();
			$portal   = 0;

			if (count($sgportal) > 0)
			{
				$portal = $sgportal->shopper_group_portal;
			}

			if (PORTAL_SHOP == 1)
			{
				if ($checkcid != "")
				{
					$cat_detail .= $data_add;
				}
			}
			else
			{
				if ($portal == 1 && $checkcid != "")
				{
					$cat_detail .= $data_add;
				}
				elseif ($portal == 0)
				{
					$cat_detail .= $data_add;
				}
			}
		}

		$template_desc = str_replace("{category_loop_start}", "", $template_desc);
		$template_desc = str_replace("{category_loop_end}", "", $template_desc);
		$template_desc = str_replace($subcat_template, $cat_detail, $template_desc);
	}

	if (strstr($template_desc, "{if subcats}") && strstr($template_desc, "{subcats end if}"))
	{
		$template_d1 = explode("{if subcats}", $template_desc);
		$template_d2 = explode("{subcats end if}", $template_d1 [1]);

		if (count($this->detail) > 0)
		{
			$template_desc = str_replace("{if subcats}", "", $template_desc);
			$template_desc = str_replace("{subcats end if}", "", $template_desc);
		}
		else
		{
			$template_desc = $template_d1 [0] . $template_d2 [1];
		}
	}

	if (strstr($template_desc, "{product_price_slider}"))
	{
		$price_slider  = '<div id="pricefilter">
			    <div class="left" id="leftSlider">
			        <div id="range">' . JText::_('COM_REDSHOP_PRICE') . ': <span id="redcatamount"> </span></div>
			        <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="redcatslider">
			        	<div style="left: 52.381%; width: 0%;" class="ui-slider-range ui-widget-header"></div>
			        	<a style="left: 52.381%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
			        	<a style="left: 52.381%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
			        </div>
				</div>
				<div class="left" id="blankfilter"></div>
				<div class="left" id="productsWrap">
			        <div style="display: none;" id="ajaxcatMessage">' . JText::_('COM_REDSHOP_LOADING') . '</div>
			    </div>
			</div>';
		$template_desc = str_replace("{product_price_slider}", $price_slider, $template_desc);
		$product_tmpl  = JText::_('COM_REDSHOP_NO_PRODUCT_FOUND');
	}
}

if (strstr($template_desc, "{product_loop_start}") && strstr($template_desc, "{product_loop_end}"))
{
	$template_d1      = explode("{product_loop_start}", $template_desc);
	$template_d2      = explode("{product_loop_end}", $template_d1 [1]);
	$template_product = $template_d2 [0];

	$attribute_template = $producthelper->getAttributeTemplate($template_product);

	$extraFieldName = $extraField->getSectionFieldNameArray(1, 1, 1);
	$product_data   = '';

	// For all products
	if ($endlimit == 0)
	{
		$final_endlimit = count($this->product);
	}
	else
	{
		$final_endlimit = $endlimit;
	}

	for ($i = $start; $i < ($start + $final_endlimit); $i++)
	{
		$product = $this->product[$i];

		if (!is_object($product))
		{
			break;
		}

		$count_no_user_field = 0;

		// Counting accessory
		$accessorylist = $producthelper->getProductAccessory(0, $product->product_id);
		$totacc        = count($accessorylist);

		$data_add = $template_product;

		// ProductFinderDatepicker Extra Field Start

		$data_add = $producthelper->getProductFinderDatepickerValue($template_product, $product->product_id, $fieldArray);

		// ProductFinderDatepicker Extra Field End

		/*
		 * Process the prepare Product plugins
		 */
		JPluginHelper::importPlugin('redshop_product');
		$params  = array();
		$results = $dispatcher->trigger('onPrepareProduct', array(& $data_add, & $params, $product));

		if (strstr($data_add, "{product_delivery_time}"))
		{
			$product_delivery_time = $producthelper->getProductMinDeliveryTime($product->product_id);

			if ($product_delivery_time != "")
			{
				$data_add = str_replace("{delivery_time_lbl}", JText::_('COM_REDSHOP_DELIVERY_TIME'), $data_add);
				$data_add = str_replace("{product_delivery_time}", $product_delivery_time, $data_add);
			}
			else
			{
				$data_add = str_replace("{delivery_time_lbl}", "", $data_add);
				$data_add = str_replace("{product_delivery_time}", "", $data_add);
			}
		}

		// More documents
		if (strstr($data_add, "{more_documents}"))
		{
			$media_documents = $producthelper->getAdditionMediaImage($product->product_id, "product", "document");
			$more_doc        = '';

			for ($m = 0; $m < count($media_documents); $m++)
			{
				$alttext = $producthelper->getAltText("product", $media_documents[$m]->section_id, "", $media_documents[$m]->media_id, "document");

				if (!$alttext)
				{
					$alttext = $media_documents[$m]->media_name;
				}

				if (is_file(REDSHOP_FRONT_DOCUMENT_RELPATH . 'product/' . $media_documents[$m]->media_name))
				{
					$downlink = JUri::root() . 'index.php?tmpl=component&option=' . $option . '&view=product&pid=' . $this->data->product_id . '&task=downloadDocument&fname=' . $media_documents[$m]->media_name . '&Itemid=' . $Itemid;
					$more_doc .= "<div><a href='" . $downlink . "' title='" . $alttext . "'>";
					$more_doc .= $alttext;
					$more_doc .= "</a></div>";
				}
			}

			$data_add = str_replace("{more_documents}", "<span id='additional_docs" . $product->product_id . "'>" . $more_doc . "</span>", $data_add);
		}

		// More documents end

		// Product User Field Start
		$hidden_userfield   = "";
		$returnArr          = $producthelper->getProductUserfieldFromTemplate($data_add);
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

				$data_add = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $product_userfileds[0], $data_add);
				$data_add = str_replace('{' . $userfieldArr[$ui] . '}', $product_userfileds[1], $data_add);
			}

			$product_userfileds_form = "<form method='post' action='' id='user_fields_form_" . $product->product_id . "' name='user_fields_form_" . $product->product_id . "'>";

			if ($ufield != "")
			{
				$data_add = str_replace("{if product_userfield}", $product_userfileds_form, $data_add);
				$data_add = str_replace("{product_userfield end if}", "</form>", $data_add);
			}
			else
			{
				$data_add = str_replace("{if product_userfield}", "", $data_add);
				$data_add = str_replace("{product_userfield end if}", "", $data_add);
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

		$data_add = $data_add . $hidden_userfield;
		/************** end user fields ***************************/

		$ItemData  = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);
		$catidmain = Jrequest::getVar("cid");

		if (count($ItemData) > 0)
		{
			$pItemid = $ItemData->id;
		}
		else
		{
			$pItemid = $objhelper->getItemid($product->product_id, $catidmain);
		}

		$data_add              = str_replace("{product_id_lbl}", JText::_('COM_REDSHOP_PRODUCT_ID_LBL'), $data_add);
		$data_add              = str_replace("{product_id}", $product->product_id, $data_add);
		$data_add              = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $data_add);
		$product_number_output = '<span id="product_number_variable' . $product->product_id . '">' . $product->product_number . '</span>';
		$data_add              = str_replace("{product_number}", $product_number_output, $data_add);

		$product_volume_unit = '<span class="product_unit_variable">' . DEFAULT_VOLUME_UNIT . "3" . '</span>';
		$data_add            = str_replace("{product_size}", $producthelper->redunitDecimal($product->product_volume) . "&nbsp;" . $product_volume_unit, $data_add);

		$product_unit = '<span class="product_unit_variable">' . DEFAULT_VOLUME_UNIT . '</span>';
		$data_add     = str_replace("{product_length}", $producthelper->redunitDecimal($product->product_length) . "&nbsp;" . $product_unit, $data_add);
		$data_add     = str_replace("{product_width}", $producthelper->redunitDecimal($product->product_width) . "&nbsp;" . $product_unit, $data_add);
		$data_add     = str_replace("{product_height}", $producthelper->redunitDecimal($product->product_height) . "&nbsp;" . $product_unit, $data_add);

		$data_add   = $producthelper->replaceVatinfo($data_add);
		$link       = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $product->product_id . '&cid=' . $catid . '&Itemid=' . $pItemid);
		$pname      = $Redconfiguration->maxchar($product->product_name, CATEGORY_PRODUCT_TITLE_MAX_CHARS, CATEGORY_PRODUCT_TITLE_END_SUFFIX);
		$product_nm = $pname;

		if (strstr($data_add, '{product_name_nolink}'))
		{
			$data_add = str_replace("{product_name_nolink}", $product_nm, $data_add);
		}

		if (strstr($data_add, '{product_name}'))
		{
			$pname    = "<a href='" . $link . "' title='" . $product->product_name . "'>" . $pname . "</a>";
			$data_add = str_replace("{product_name}", $pname, $data_add);
		}

		if (strstr($data_add, '{category_product_link}'))
		{
			$data_add = str_replace("{category_product_link}", $link, $data_add);
		}

		if (strstr($data_add, '{read_more}'))
		{
			$rmore    = "<a href='" . $link . "' title='" . $product->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
			$data_add = str_replace("{read_more}", $rmore, $data_add);
		}

		if (strstr($data_add, '{read_more_link}'))
		{
			$data_add = str_replace("{read_more_link}", $link, $data_add);
		}

		/**
		 * related Product List in Lightbox
		 * Tag Format = {related_product_lightbox:<related_product_name>[:width][:height]}
		 */
		if (strstr($data_add, '{related_product_lightbox:'))
		{
			$related_product = $producthelper->getRelatedProduct($product->product_id);
			$rtlnone         = explode("{related_product_lightbox:", $data_add);
			$rtlntwo         = explode("}", $rtlnone[1]);
			$rtlnthree       = explode(":", $rtlntwo[0]);
			$rtln            = $rtlnthree[0];
			$rtlnfwidth      = (isset($rtlnthree[1])) ? $rtlnthree[1] : "900";
			$rtlnwidthtag    = (isset($rtlnthree[1])) ? ":" . $rtlnthree[1] : "";

			$rtlnfheight   = (isset($rtlnthree[2])) ? $rtlnthree[2] : "600";
			$rtlnheighttag = (isset($rtlnthree[2])) ? ":" . $rtlnthree[2] : "";

			$rtlntag = "{related_product_lightbox:$rtln$rtlnwidthtag$rtlnheighttag}";

			if (count($related_product) > 0)
			{
				$linktortln = JUri::root() . "index.php?option=com_redshop&view=product&pid=" . $product->product_id . "&tmpl=component&template=" . $rtln . "&for=rtln";
				$rtlna      = '<a class="redcolorproductimg" href="' . $linktortln . '"  >' . JText::_('COM_REDSHOP_RELATED_PRODUCT_LIST_IN_LIGHTBOX') . '</a>';
			}
			else
			{
				$rtlna = "";
			}

			$data_add = str_replace($rtlntag, $rtlna, $data_add);
		}

		if (strstr($data_add, '{product_s_desc}'))
		{
			$p_s_desc = $Redconfiguration->maxchar($product->product_s_desc, CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS, CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX);
			$data_add = str_replace("{product_s_desc}", $p_s_desc, $data_add);
		}

		if (strstr($data_add, '{product_desc}'))
		{
			$p_desc   = $Redconfiguration->maxchar($product->product_desc, CATEGORY_PRODUCT_DESC_MAX_CHARS, CATEGORY_PRODUCT_DESC_END_SUFFIX);
			$data_add = str_replace("{product_desc}", $p_desc, $data_add);
		}

		if (strstr($data_add, '{product_rating_summary}'))
		{
			// Product Review/Rating Fetching reviews
			$final_avgreview_data = $producthelper->getProductRating($product->product_id);
			$data_add             = str_replace("{product_rating_summary}", $final_avgreview_data, $data_add);
		}

		if (strstr($data_add, '{manufacturer_link}'))
		{
			$manufacturer_link_href = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $product->manufacturer_id . '&Itemid=' . $Itemid);
			$manufacturer_link      = '<a href="' . $manufacturer_link_href . '" title="' . $product->manufacturer_name . '">' . $product->manufacturer_name . '</a>';
			$data_add               = str_replace("{manufacturer_link}", $manufacturer_link, $data_add);

			if (strstr($data_add, "{manufacturer_link}"))
			{
				$data_add = str_replace("{manufacturer_name}", "", $data_add);
			}
		}

		if (strstr($data_add, '{manufacturer_product_link}'))
		{
			$manufacturerPLink = "<a href='" . JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $product->manufacturer_id . '&Itemid=' . $Itemid) . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . " " . $product->manufacturer_name . "</a>";
			$data_add          = str_replace("{manufacturer_product_link}", $manufacturerPLink, $data_add);
		}

		if (strstr($data_add, '{manufacturer_name}'))
		{
			$data_add = str_replace("{manufacturer_name}", $product->manufacturer_name, $data_add);
		}

		if (strstr($data_add, "{product_thumb_image_3}"))
		{
			$pimg_tag = '{product_thumb_image_3}';
			$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_3;
			$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_3;
		}
		elseif (strstr($data_add, "{product_thumb_image_2}"))
		{
			$pimg_tag = '{product_thumb_image_2}';
			$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_2;
			$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_2;
		}
		elseif (strstr($data_add, "{product_thumb_image_1}"))
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
		$data_add = str_replace($pimg_tag, $thum_image . $hidden_thumb_image, $data_add);

		// Front-back image tag...
		if (strstr($data_add, "{front_img_link}") || strstr($data_add, "{back_img_link}"))
		{
			if ($this->_data->product_thumb_image)
			{
				$mainsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_thumb_image;
			}
			else
			{
				$mainsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_full_image . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
			}

			if ($this->_data->product_back_thumb_image)
			{
				$backsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_back_thumb_image;
			}
			else
			{
				$backsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_back_full_image . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
			}

			$ahrefpath     = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image;
			$ahrefbackpath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_back_full_image;

			$product_front_image_link = "<a href='#' onClick='javascript:changeproductImage(" . $product->product_id . ",\"" . $mainsrcPath . "\",\"" . $ahrefpath . "\");'>" . JText::_('COM_REDSHOP_FRONT_IMAGE') . "</a>";
			$product_back_image_link  = "<a href='#' onClick='javascript:changeproductImage(" . $product->product_id . ",\"" . $backsrcPath . "\",\"" . $ahrefbackpath . "\");'>" . JText::_('COM_REDSHOP_BACK_IMAGE') . "</a>";

			$data_add = str_replace("{front_img_link}", $product_front_image_link, $data_add);
			$data_add = str_replace("{back_img_link}", $product_back_image_link, $data_add);
		}
		else
		{
			$data_add = str_replace("{front_img_link}", "", $data_add);
			$data_add = str_replace("{back_img_link}", "", $data_add);
		}

		// Front-back image tag end

		// Product preview image.
		if (strstr($data_add, '{product_preview_img}'))
		{
			if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $product->product_preview_image))
			{
				$previewsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_preview_image . "&newxsize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH . "&newysize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
				$previewImg     = "<img src='" . $previewsrcPath . "' class='rs_previewImg' />";
				$data_add       = str_replace("{product_preview_img}", $previewImg, $data_add);
			}
			else
			{
				$data_add = str_replace("{product_preview_img}", "", $data_add);
			}
		}

		// 	product preview image end.

		// Front-back preview image tag...
		if (strstr($data_add, "{front_preview_img_link}") || strstr($data_add, "{back_preview_img_link}"))
		{
			if ($product->product_preview_image)
			{
				$mainpreviewsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_preview_image . "&newxsize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH . "&newysize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
			}

			if ($product->product_preview_back_image)
			{
				$backpreviewsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_preview_back_image . "&newxsize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH . "&newysize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
			}

			$product_front_image_link = "<a href='#' onClick='javascript:changeproductPreviewImage(" . $product->product_id . ",\"" . $mainpreviewsrcPath . "\");'>" . JText::_('COM_REDSHOP_FRONT_IMAGE') . "</a>";
			$product_back_image_link  = "<a href='#' onClick='javascript:changeproductPreviewImage(" . $product->product_id . ",\"" . $backpreviewsrcPath . "\");'>" . JText::_('COM_REDSHOP_BACK_IMAGE') . "</a>";

			$data_add = str_replace("{front_preview_img_link}", $product_front_image_link, $data_add);
			$data_add = str_replace("{back_preview_img_link}", $product_back_image_link, $data_add);
		}
		else
		{
			$data_add = str_replace("{front_preview_img_link}", "", $data_add);
			$data_add = str_replace("{back_preview_img_link}", "", $data_add);
		}

		// Front-back preview image tag end

		$data_add = $producthelper->getJcommentEditor($product, $data_add);

		/*
		 * product loop template extra field
		 * lat arg set to "1" for indetify parsing data for product tag loop in category
		 * last arg will parse {producttag:NAMEOFPRODUCTTAG} nameing tags.
		 * "1" is for section as product
		 */
		if (count($loadCategorytemplate) > 0)
		{
			$data_add = $producthelper->getExtraSectionTag($extraFieldName, $product->product_id, "1", $data_add, 1);
		}

		/************************************
		 *  Conditional tag
		 *  if product on discount : Yes
		 *  {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
		 *  NO : // OUTPUT : Display blank
		 ************************************/
		$data_add = $producthelper->getProductOnSaleComment($product, $data_add);

		// Replace wishlistbutton
		$data_add = $producthelper->replaceWishlistButton($product->product_id, $data_add);

		// Replace compare product button
		$data_add = $producthelper->replaceCompareProductsButton($product->product_id, $catid, $data_add);

		if (strstr($data_add, "{stockroom_detail}"))
		{
			$data_add = $stockroomhelper->replaceStockroomAmountDetail($data_add, $product->product_id);
		}

		// Checking for child products
		$childproduct = $producthelper->getChildProduct($product->product_id);

		if (count($childproduct) > 0)
		{
			if (PURCHASE_PARENT_WITH_CHILD == 1)
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
			else
			{
				$isChilds   = true;
				$attributes = array();
			}
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

		// Product attribute  Start
		$totalatt = count($attributes);

		// Check product for not for sale

		$data_add = $producthelper->getProductNotForSaleComment($product, $data_add, $attributes);

		$data_add = $producthelper->replaceProductInStock($product->product_id, $data_add, $attributes, $attribute_template);

		$data_add = $producthelper->replaceAttributeData($product->product_id, 0, 0, $attributes, $data_add, $attribute_template, $isChilds);

		// Get cart tempalte
		$data_add = $producthelper->replaceCartTemplate($product->product_id, $catid, 0, 0, $data_add, $isChilds, $userfieldArr, $totalatt, $totacc, $count_no_user_field);

		$product_data .= $data_add;
	}

	if (!$slide)
	{
		$product_tmpl = "<div id='redcatproducts'>" . $product_data . "</div>";
	}
	else
	{
		$product_tmpl = $product_data;
	}

	$product_tmpl .= "<input type='hidden' name='slider_texpricemin' id='slider_texpricemin' value='" . $texpricemin . "' />";
	$product_tmpl .= "<input type='hidden' name='slider_texpricemax' id='slider_texpricemax' value='" . $texpricemax . "' />";

	$slidertag = "";

	if (strstr($template_desc, "{show_all_products_in_category}"))
	{
		$template_desc = str_replace("{show_all_products_in_category}", "", $template_desc);
		$template_desc = str_replace("{pagination}", "", $template_desc);
	}

	$product_display_limit = '';

	if (strstr($template_desc, "{pagination}"))
	{
		$pagination = new redPagination($model->_total, $start, $endlimit);
		$slidertag  = $pagination->getPagesLinks();

		if (strstr($template_desc, "{product_display_limit}"))
		{
			$slidertag     = "<form action='' method='post'> " . $pagination->getListFooter() . "</form>";
			$template_desc = str_replace("{product_display_limit}", $slidertag, $template_desc);
			$template_desc = str_replace("{pagination}", '', $template_desc);
		}

		$template_desc = str_replace("{pagination}", $slidertag, $template_desc);
	}

	$template_desc = str_replace("{product_display_limit}", "", $template_desc);

	if (strstr($template_desc, "perpagelimit:"))
	{
		$perpage       = explode('{perpagelimit:', $template_desc);
		$perpage       = explode('}', $perpage[1]);
		$template_desc = str_replace("{perpagelimit:" . intval($perpage[0]) . "}", "", $template_desc);
	}

	$product_tmpl = "<div id='productlist'>" . $product_tmpl . "</div>" . "<div id='redcatpagination' style='display:none'>" . $slidertag . "</div>";

	$template_desc = str_replace("{product_loop_start}", "", $template_desc);
	$template_desc = str_replace("{product_loop_end}", "", $template_desc);
	$template_desc = str_replace($template_product, $product_tmpl, $template_desc);
}

if (!$slide)
{
	if (strstr($template_desc, "{filter_by}"))
	{
		$filterby_form = "<form name='filterby_form' action='' method='post' >";
		$filterby_form .= $this->lists['manufacturer'];
		$filterby_form .= "<input type='hidden' name='texpricemin' id='manuf_texpricemin' value='" . $texpricemin . "' />";
		$filterby_form .= "<input type='hidden' name='texpricemax' id='manuf_texpricemax' value='" . $texpricemax . "' />";
		$filterby_form .= "<input type='hidden' name='order_by' id='order_by' value='" . $this->order_by_select . "' />";
		$filterby_form .= "<input type='hidden' name='category_template' id='category_template' value='" . $this->category_template_id . "' />";
		$filterby_form .= "</form>";

		if ($this->lists['manufacturer'] != "")
			$template_desc = str_replace("{filter_by_lbl}", JText::_('COM_REDSHOP_SELECT_FILTER_BY'), $template_desc);
		else
			$template_desc = str_replace("{filter_by_lbl}", "", $template_desc);
		$template_desc = str_replace("{filter_by}", $filterby_form, $template_desc);
	}

	if (strstr($template_desc, "{template_selector_category}"))
	{
		if ($this->lists['category_template'] != "")
		{
			$template_selecter_form = "<form name='template_selecter_form' action='' method='post' >";
			$template_selecter_form .= $this->lists['category_template'];
			$template_selecter_form .= "<input type='hidden' name='texpricemin' id='temp_texpricemin' value='" . $texpricemin . "' />";
			$template_selecter_form .= "<input type='hidden' name='texpricemax' id='temp_texpricemax' value='" . $texpricemax . "' />";
			$template_selecter_form .= "<input type='hidden' name='order_by' id='order_by' value='" . $this->order_by_select . "' />";
			$template_selecter_form .= "<input type='hidden' name='manufacturer_id' id='manufacturer_id' value='" . $this->manufacturer_id . "' />";
			$template_selecter_form .= "</form>";

			$template_desc = str_replace("{template_selector_category_lbl}", JText::_('COM_REDSHOP_TEMPLATE_SELECTOR_CATEGORY_LBL'), $template_desc);
			$template_desc = str_replace("{template_selector_category}", $template_selecter_form, $template_desc);
		}

		$template_desc = str_replace("{template_selector_category_lbl}", "", $template_desc);
		$template_desc = str_replace("{template_selector_category}", "", $template_desc);
	}

	if (strstr($template_desc, "{order_by}"))
	{
		$orderby_form = "<form name='orderby_form' action='' method='post'>";
		$orderby_form .= $this->lists['order_by'];
		$orderby_form .= "<input type='hidden' name='texpricemin' id='texpricemin' value='" . $texpricemin . "' />";
		$orderby_form .= "<input type='hidden' name='texpricemax' id='texpricemax' value='" . $texpricemax . "' />";
		$orderby_form .= "<input type='hidden' name='manufacturer_id' id='manufacturer_id' value='" . $this->manufacturer_id . "' />";
		$orderby_form .= "<input type='hidden' name='category_template' id='category_template' value='" . $this->category_template_id . "' />";
		$orderby_form .= "</form>";

		$template_desc = str_replace("{order_by_lbl}", JText::_('COM_REDSHOP_SELECT_ORDER_BY'), $template_desc);
		$template_desc = str_replace("{order_by}", $orderby_form, $template_desc);
	}
}

$template_desc = str_replace("{with_vat}", "", $template_desc);
$template_desc = str_replace("{without_vat}", "", $template_desc);
$template_desc = str_replace("{attribute_price_with_vat}", "", $template_desc);
$template_desc = str_replace("{attribute_price_without_vat}", "", $template_desc);
$template_desc = str_replace("{redproductfinderfilter_formstart}", "", $template_desc);
$template_desc = str_replace("{product_price_slider1}", "", $template_desc);
$template_desc = str_replace("{redproductfinderfilter_formend}", "", $template_desc);
$template_desc = str_replace("{redproductfinderfilter:rp_myfilter}", "", $template_desc);

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);

$template_desc = $texts->replace_texts($template_desc);
echo eval("?>" . $template_desc . "<?php ");

if ($slide)
{
	exit;
}
