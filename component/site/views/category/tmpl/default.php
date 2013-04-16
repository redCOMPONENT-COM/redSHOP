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

$objhelper     = new redhelper;
$config        = new Redconfiguration;
$producthelper = new producthelper;
$extraField    = new extraField;
$redTemplate   = new Redtemplate;

$url    = JURI::base();
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$catid  = JRequest::getVar('cid', 0, '', 'int');
$print  = JRequest::getVar('print');

$model                = $this->getModel('category');
$loadCategorytemplate = $this->loadCategorytemplate;

if (count($loadCategorytemplate) > 0 && $loadCategorytemplate[0]->template_desc != "")
{
	$template_desc = $loadCategorytemplate[0]->template_desc;
}
else
{
	$template_desc = "<div class=\"category_front_introtext\">{print}<p>{category_frontpage_introtext}</p></div>\r\n{category_frontpage_loop_start}<div class=\"category_front\">\r\n<div class=\"category_front_image\">{category_thumb_image}</div>\r\n<div class=\"category_front_title\"><h3>{category_name}</h3></div>\r\n</div>{category_frontpage_loop_end}";
}

$endlimit = count($this->detail);

if (!strstr($template_desc, "{show_all_products_in_category}") && strstr($template_desc, "{pagination}"))
{
	$endlimit = $model->getProductPerPage();
}

$app = JFactory::getApplication();
$router    = $app->getRouter();
$uri       = new JURI('index.php?option=' . $option . '&category&layout=default&Itemid=' . $Itemid . '&limit=' . $endlimit . '&category_template=' . $this->category_template_id);

if ($this->params->get('show_page_heading', 0))
{
	if (!$catid)
		echo '<div class="category_title' . $this->escape($this->params->get('pageclass_sfx')) . '">';
	else
		echo '<div class="category' . $this->escape($this->params->get('pageclass_sfx')) . '">';

	if (!$catid)
	{
		echo '<h1>';

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

if ($print)
{
	$onclick = "onclick='window.print();'";
}
else
{
	$print_url = $url . "index.php?option=com_redshop&view=category&print=1&tmpl=component&Itemid=" . $Itemid;
	$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
}

$print_tag = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png' alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
$print_tag .= "</a>";

$template_desc = str_replace("{print}", $print_tag, $template_desc);
$template_desc = str_replace("{category_frontpage_introtext}", CATEGORY_FRONTPAGE_INTROTEXT, $template_desc);

if (strstr($template_desc, "{category_frontpage_loop_start}") && strstr($template_desc, "{category_frontpage_loop_end}"))
{
	$cattemplate_desc = explode('{category_frontpage_loop_start}', $template_desc);
	$catheader        = $cattemplate_desc [0];

	$cattemplate_desc    = explode('{category_frontpage_loop_end}', $cattemplate_desc [1]);
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
	$cat_detail     = "";

	for ($i = 0; $i < count($this->detail); $i++)
	{
		$row = $this->detail[$i];

		$data_add = $middletemplate_desc;

		$cItemid = $objhelper->getCategoryItemid($row->category_id);

		if ($cItemid != "")
		{
			$tmpItemid = $cItemid;
		}
		else
		{
			$tmpItemid = $Itemid;
		}

		$link = JRoute::_('index.php?option=' . $option . '&view=category&cid=' . $row->category_id . '&layout=detail&Itemid=' . $tmpItemid);

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
			$cat_thumb = "<a class='modal' href='" . $linkimage . "' rel=\"{handler: 'image', size: {}}\" " . $title . ">";
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
			$cat_desc = $config->maxchar($row->category_description, CATEGORY_DESC_MAX_CHARS, CATEGORY_DESC_END_SUFFIX);
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

		// Shopper group category ACL
		$checkcid = $objhelper->getShopperGroupCategory($row->category_id);

		$read_more = "<a href='" . $link . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
		$data_add  = str_replace("{read_more}", $read_more, $data_add);
		$sgportal  = $objhelper->getShopperGroupPortal();
		$portal    = 0;

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

	$template_desc = str_replace("{category_frontpage_loop_start}", "", $template_desc);
	$template_desc = str_replace("{category_frontpage_loop_end}", "", $template_desc);
	$template_desc = str_replace($middletemplate_desc, $cat_detail, $template_desc);
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

if (strstr($template_desc, "{show_all_products_in_category}"))
{
	$template_desc = str_replace("{show_all_products_in_category}", "", $template_desc);
	$template_desc = str_replace("{pagination}", "", $template_desc);
}

if (strstr($template_desc, "{pagination}"))
{
	$pagination    = $model->getCategoryPagination();
	$template_desc = str_replace("{pagination}", $pagination->getPagesLinks(), $template_desc);
}

if (strstr($template_desc, "perpagelimit:"))
{
	$perpage       = explode('{perpagelimit:', $template_desc);
	$perpage       = explode('}', $perpage[1]);
	$template_desc = str_replace("{perpagelimit:" . intval($perpage[0]) . "}", "", $template_desc);
}

if (strstr($template_desc, "{product_display_limit}"))
{
	$template_desc = str_replace("{product_display_limit}", '', $template_desc);
}

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
echo eval("?>" . $template_desc . "<?php ");
