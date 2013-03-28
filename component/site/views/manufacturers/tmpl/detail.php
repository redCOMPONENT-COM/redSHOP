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
$extraField = new extraField;
$extra_field = new extra_field;
$producthelper = new producthelper;
$redTemplate = new Redtemplate;
$redhelper = new redhelper;
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$model = $this->getModel('manufacturers');

// Page Title Start
$pagetitle = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL');

if ($this->pageheadingtag != '')
{
	$pagetitle = $this->pageheadingtag;
}

if ($this->params->get('show_page_heading', 1))
{
	?>
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
<?php
}

// Page title End

$manufacturerdetail_template = $redTemplate->getTemplate("manufacturer_detail");

if (count($manufacturerdetail_template) > 0 && $manufacturerdetail_template[0]->template_desc != "")
{
	$template_desc = $manufacturerdetail_template[0]->template_desc;
	$template_id   = $manufacturerdetail_template[0]->template_id;
}
else
{
	$template_desc = "<div style=\"clear: both;\"></div>\r\n<div class=\"manufacturer_name\">{manufacturer_name}</div>\r\n<div class=\"manufacturer_image\">{manufacturer_image}</div>\r\n<div class=\"manufacturer_description\">{manufacturer_description}</div>\r\n<div class=\"manufacturer_product_link\"><a href=\"{manufacturer_allproductslink}\">{manufacturer_allproductslink_lbl}</a></div>\r\n<div style=\"clear: both;\"></div>";
	$template_id   = 0;
}

$row = $this->detail[0];
$category = $model->getmanufacturercategory($row->manufacturer_id, $row);

if (strstr($template_desc, '{category_loop_start}') && strstr($template_desc, '{category_loop_end}'))
{
	$template_sdata  = explode('{category_loop_start}', $template_desc);
	$template_start  = $template_sdata[0];
	$template_edata  = explode('{category_loop_end}', $template_sdata[1]);
	$template_end    = $template_edata[1];
	$template_middle = $template_edata[0];

	if ($template_middle != "")
	{
		for ($i = 0; $i < count($category); $i++)
		{
			$cart_mdata .= $template_middle;
			$catlink    = JRoute::_('index.php?option=' . $option . '&view=category&layout=detail&cid=' . $category[$i]->category_id . '&manufacturer_id=' . $row->manufacturer_id . '&Itemid=' . $Itemid);
			$alink      = "<a href='" . $catlink . "'>" . $category[$i]->category_name . "</a>";
			$cart_mdata = str_replace("{category_name_with_link}", $alink, $cart_mdata);
			$cart_mdata = str_replace("{category_desc}", $category[$i]->category_description, $cart_mdata);
			$cart_mdata = str_replace("{category_name}", $category[$i]->category_name, $cart_mdata);
		}
	}

	$template_desc = $template_start . $cart_mdata . $template_end;
}

if (strstr($template_desc, "{manufacturer_image}"))
{
	$mh_thumb    = MANUFACTURER_THUMB_HEIGHT;
	$mw_thumb    = MANUFACTURER_THUMB_WIDTH;
	$thum_image  = "";
	$media_image = $producthelper->getAdditionMediaImage($row->manufacturer_id, "manufacturer");

	for ($m = 0; $m < count($media_image); $m++)
	{
		if ($media_image[$m]->media_name && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "manufacturer/" . $media_image[$m]->media_name))
		{
			$altText = $producthelper->getAltText('manufacturer', $row->manufacturer_id);

			if (!$altText)
			{
				$altText = $row->manufacturer_name;
			}

			if (WATERMARK_MANUFACTURER_IMAGE)
			{
				$manufacturer_img = $redhelper->watermark('manufacturer', $media_image[$m]->media_name, "", "", WATERMARK_MANUFACTURER_IMAGE);
				$maintype         = "watermarked/main";
			}
			else
			{
				$maintype = "manufacturer/";
			}

			if (WATERMARK_MANUFACTURER_THUMB_IMAGE)
			{
				$manufacturer_img = $redhelper->watermark('manufacturer', $media_image[$m]->media_name, "", "", WATERMARK_MANUFACTURER_THUMB_IMAGE);
				$thumbtype        = "watermarked/main";
			}
			else
			{
				$thumbtype = "manufacturer/";
			}

			$thum_image = "<a title='" . $altText . "' class=\"modal\" href='" . REDSHOP_FRONT_IMAGES_ABSPATH . $maintype . $media_image[$m]->media_name . "'   rel=\"{handler: 'image', size: {}}\">
				<img alt='" . $altText . "' title='" . $altText . "' src='" . $url . "/components/" . $option . "/helpers/thumb.php?filename=" . $thumbtype . $media_image[$m]->media_name . "&newxsize=" . $mw_thumb . "&newysize=" . $mh_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "'></a>";
		}
	}

	$template_desc = str_replace("{manufacturer_image}", $thum_image, $template_desc);
}

$manlink = JRoute::_('index.php?option=' . $option . '&view=manufacturers&layout=detail&mid=' . $row->manufacturer_id . '&Itemid=' . $Itemid);

$manproducts = JRoute::_('index.php?option=' . $option . '&view=manufacturers&layout=products&mid=' . $row->manufacturer_id . '&Itemid=' . $Itemid);

$template_desc = str_replace("{manufacturer_name}", $row->manufacturer_name, $template_desc);

// Replace Manufacturer URL
if (strstr($template_desc, "{manufacturer_url}"))
{
	$manufacturer_url = "<a href='" . $row->manufacturer_url . "'>" . $row->manufacturer_url . "</a>";
	$template_desc    = str_replace("{manufacturer_url}", $manufacturer_url, $template_desc);
}

// Extra field display
$extraFieldName = $extraField->getSectionFieldNameArray(10, 1, 1);
$template_desc = $producthelper->getExtraSectionTag($extraFieldName, $row->manufacturer_id, "10", $template_desc);
$template_desc = str_replace("{manufacturer_description}", $row->manufacturer_desc, $template_desc);

if (strstr($template_desc, "{manufacturer_extra_fields}"))
{
	$manufacturer_extra_fields = $extra_field->list_all_field_display(10, $row->manufacturer_id);
	$template_desc             = str_replace("{manufacturer_extra_fields}", $manufacturer_extra_fields, $template_desc);
}

$template_desc = str_replace("{manufacturer_link}", $manlink, $template_desc);
$template_desc = str_replace("{manufacturer_allproductslink}", $manproducts, $template_desc);
$template_desc = str_replace("{manufacturer_allproductslink_lbl}", JText::_('COM_REDSHOP_MANUFACTURER_ALLPRODUCTSLINK_LBL'), $template_desc);
$template_desc = $redTemplate->parseredSHOPplugin($template_desc);

echo "<div style='float:left;'>";
echo eval("?>" . $template_desc . "<?php ");
echo "</div>";
