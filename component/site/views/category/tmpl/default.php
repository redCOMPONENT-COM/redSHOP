<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

$objhelper     = redhelper::getInstance();
$config        = Redconfiguration::getInstance();
$producthelper = productHelper::getInstance();
$extraField    = extraField::getInstance();
$redTemplate   = Redtemplate::getInstance();

$url    = JURI::base();

$model                = $this->getModel('category');
$loadCategorytemplate = $this->loadCategorytemplate;

if (count($loadCategorytemplate) > 0 && $loadCategorytemplate[0]->template_desc != "")
{
	$template_desc = $loadCategorytemplate[0]->template_desc;
}
else
{
	$template_desc  = "<div class=\"category_front_introtext\">{print}<p>{category_frontpage_introtext}</p></div>";
	$template_desc .= "\r\n{category_frontpage_loop_start}<div class=\"category_front\">\r\n";
	$template_desc .= "<div class=\"category_front_image\">{category_thumb_image}</div>\r\n";
	$template_desc .= "<div class=\"category_front_title\"><h3>{category_name}</h3></div>\r\n</div>{category_frontpage_loop_end}";
}

if ($this->params->get('show_page_heading', 0))
{
	if (!$this->catid)
	{
		echo '<div class="category_title' . $this->escape($this->params->get('pageclass_sfx')) . '">';
	}
	else
	{
		echo '<div class="category' . $this->escape($this->params->get('pageclass_sfx')) . '">';
	}

	if (!$this->catid)
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

if ($this->print)
{
	$onclick = "onclick='window.print();'";
}
else
{
	$print_url = $url . "index.php?option=com_redshop&view=category&print=1&tmpl=component&Itemid=" . $this->itemid;
	$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
}

$print_tag  = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png' alt='" .
				JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
$print_tag .= "</a>";

$template_desc = str_replace("{print}", $print_tag, $template_desc);
$template_desc = str_replace("{category_frontpage_introtext}", Redshop::getConfig()->get('CATEGORY_FRONTPAGE_INTROTEXT'), $template_desc);

if (strstr($template_desc, "{category_frontpage_loop_start}") && strstr($template_desc, "{category_frontpage_loop_end}"))
{
	$cattemplate_desc = explode('{category_frontpage_loop_start}', $template_desc);
	$catheader        = $cattemplate_desc [0];

	$cattemplate_desc    = explode('{category_frontpage_loop_end}', $cattemplate_desc [1]);
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

	$extraFieldName = $extraField->getSectionFieldNameArray(2, 1, 1);
	$cat_detail     = "";
	$countCategories = count($this->detail);

	if (!$countCategories)
	{
		$cat_detail .= '<h3 class="noCategoriesToShow">' . JText::_('COM_REDSHOP_THERE_ARE_NO_CATEGORIES_TO_SHOW') . '</h3>';
	}

	for ($i = 0; $i < $countCategories; $i++)
	{
		$row = $this->detail[$i];

		// Filter categories based on Shopper group category ACL
		$checkcid = RedshopHelperAccess::checkPortalCategoryPermission($row->id);
		$sgportal = RedshopHelperShopper_Group::getShopperGroupPortal();
		$portal   = 0;

		if (count($sgportal) > 0)
		{
			$portal = $sgportal->shopper_group_portal;
		}

		if (!$checkcid && (Redshop::getConfig()->get('PORTAL_SHOP') == 1 || $portal == 1))
		{
			continue;
		}

		$data_add = $middletemplate_desc;

		$cItemid = RedshopHelperUtility::getCategoryItemid($row->id);

		if ($cItemid != "")
		{
			$tmpItemid = $cItemid;
		}
		else
		{
			$tmpItemid = $this->itemid;
		}

		$link = JRoute::_('index.php?option=com_redshop&view=category&cid=' . $row->id . '&layout=detail&Itemid=' . $tmpItemid);

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
			$cat_desc = $config->maxchar($row->description, Redshop::getConfig()->get('CATEGORY_DESC_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_DESC_END_SUFFIX'));
			$data_add = str_replace("{category_description}", $cat_desc, $data_add);
		}

		if (strstr($data_add, '{category_short_desc}'))
		{
			$cat_s_desc = $config->maxchar($row->short_description, Redshop::getConfig()->get('CATEGORY_SHORT_DESC_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_SHORT_DESC_END_SUFFIX'));
			$data_add   = str_replace("{category_short_desc}", $cat_s_desc, $data_add);
		}

		if (strstr($data_add, '{category_total_product}'))
		{
			$totalprd = $producthelper->getProductCategory($row->id);
			$data_add = str_replace("{category_total_product}", count($totalprd), $data_add);
			$data_add = str_replace("{category_total_product_lbl}", JText::_('COM_REDSHOP_TOTAL_PRODUCT'), $data_add);
		}

		/*
		 * category template extra field
		 * "2" argument is set for category
		 */
		$data_add = $producthelper->getExtraSectionTag($extraFieldName, $row->id, "2", $data_add);

		$read_more = "<a href='" . $link . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
		$data_add  = str_replace("{read_more}", $read_more, $data_add);

		$cat_detail .= $data_add;
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
