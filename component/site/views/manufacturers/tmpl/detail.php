<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.modal');

$itemId = JFactory::getApplication()->input->getInt('Itemid', 0);

/** @var RedshopModelManufacturers $model */
$model = $this->getModel('manufacturers');

// Page Title Start
$pageTitle = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL');

if ($this->pageheadingtag != '')
{
	$pageTitle = $this->pageheadingtag;
}

if ($this->params->get('show_page_heading', 1))
{
	?>
    <h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php
		if ($this->params->get('page_title') != $pageTitle)
		{
			echo $this->escape($this->params->get('page_title'));
		}
		else
		{
			echo $pageTitle;
		} ?>
    </h1>
	<?php
}
// Page title End

$manufacturerTemplate = RedshopHelperTemplate::getTemplate('manufacturer_detail');

if (count($manufacturerTemplate) > 0 && $manufacturerTemplate[0]->template_desc != "")
{
	$templateHtml = $manufacturerTemplate[0]->template_desc;
	$templateId   = $manufacturerTemplate[0]->id;
}
else
{
	$templateHtml = "<div style=\"clear: both;\"></div>\r\n<div class=\"manufacturer_name\">{manufacturer_name}</div>\r\n<div class=\"manufacturer_image\">{manufacturer_image}</div>\r\n<div class=\"manufacturer_description\">{manufacturer_description}</div>\r\n<div class=\"manufacturer_product_link\"><a href=\"{manufacturer_allproductslink}\">{manufacturer_allproductslink_lbl}</a></div>\r\n<div style=\"clear: both;\"></div>";
	$templateId   = 0;
}

$row            = !empty($this->detail) ? $this->detail[0] : null;
$manufacturerId = null !== $row ? $row->id : 0;
$category       = $model->getmanufacturercategory($manufacturerId, $row);

if (strstr($templateHtml, '{category_loop_start}') && strstr($templateHtml, '{category_loop_end}'))
{
	$templateCategoryStart = explode('{category_loop_start}', $templateHtml);
	$templateCategoryHtml  = $templateCategoryStart[0];
	$templateCategoryEnd   = explode('{category_loop_end}', $templateCategoryStart[1]);
	$template_end          = $templateCategoryEnd[1];
	$template_middle       = $templateCategoryEnd[0];

	if ($template_middle != "")
	{
		$cart_mdata = '';

		for ($i = 0, $in = count($category); $i < $in; $i++)
		{
			$cart_mdata .= $template_middle;
			$catlink    = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $category[$i]->id . '&manufacturer_id=' . $manufacturerId . '&Itemid=' . $itemId);
			$alink      = "<a href='" . $catlink . "'>" . $category[$i]->name . "</a>";
			$cart_mdata = str_replace("{category_name_with_link}", $alink, $cart_mdata);
			$cart_mdata = str_replace("{category_desc}", $category[$i]->description, $cart_mdata);
			$cart_mdata = str_replace("{category_name}", $category[$i]->name, $cart_mdata);
			$thumbUrl   = RedshopHelperMedia::getImagePath(
				$category[$i]->category_full_image,
				'',
				'thumb',
				'category',
				200,
				200,
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
			);

			$categoryImage = "<img src='" . $thumbUrl . "' />";
			$cart_mdata    = str_replace("{category_thumb_image}", $categoryImage, $cart_mdata);
		}
	}

	$templateHtml = $templateCategoryHtml . $cart_mdata . $template_end;
}

if (strpos($templateHtml, "{manufacturer_image}") !== false)
{
	$thumbImage = '';
	$media      = null !== $row ? RedshopEntityManufacturer::getInstance($manufacturerId)->getMedia() : null;

	if (null !== $media)
	{
		$mediaImagePath = $media->getAbsImagePath();

		if (!empty($mediaImagePath))
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
				$imagePath = $media->generateThumb($thumbWidth, $thumbHeight);
			}

			$altText = $media->get('media_alternate_text', $row->name);

			$thumbImage = '<a title="' . $altText . '" class="modal"'
				. 'href="' . $mediaImagePath . '" rel="{handler: \'image\', size: {}}\">'
				. '<img alt="' . $altText . '" title="' . $altText . '" src="' . $imagePath['abs'] . '"></a>';
		}
	}

	$templateHtml = str_replace("{manufacturer_image}", $thumbImage, $templateHtml);
}

$manufacturerLink     = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $manufacturerId . '&Itemid=' . $itemId);
$manufacturerProducts = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $manufacturerId . '&Itemid=' . $itemId);
$templateHtml         = str_replace("{manufacturer_name}", null !== $row ? $row->name : '', $templateHtml);

// Replace Manufacturer URL
if (strstr($templateHtml, "{manufacturer_url}"))
{
	$templateHtml = str_replace(
		"{manufacturer_url}",
		"<a href='" . $row->manufacturer_url . "'>" . $row->manufacturer_url . "</a>",
		$templateHtml
	);
}

// Extra field display
$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(10, 1, 1);
$templateHtml   = RedshopHelperProductTag::getExtraSectionTag(
	$extraFieldName, $manufacturerId, RedshopHelperExtrafields::SECTION_MANUFACTURER, $templateHtml
);
$templateHtml   = str_replace("{manufacturer_description}", null !== $row ? $row->description : '', $templateHtml);

if (strstr($templateHtml, "{manufacturer_extra_fields}"))
{
	$manufacturerExtraFields = RedshopHelperExtrafields::listAllFieldDisplay(RedshopHelperExtrafields::SECTION_MANUFACTURER, $manufacturerId);
	$templateHtml            = str_replace("{manufacturer_extra_fields}", $manufacturerExtraFields, $templateHtml);
}

$templateHtml = str_replace("{manufacturer_link}", $manufacturerLink, $templateHtml);
$templateHtml = str_replace("{manufacturer_allproductslink}", $manufacturerProducts, $templateHtml);
$templateHtml = str_replace("{manufacturer_allproductslink_lbl}", JText::_('COM_REDSHOP_MANUFACTURER_ALLPRODUCTSLINK_LBL'), $templateHtml);
$templateHtml = RedshopHelperTemplate::parseRedshopPlugin($templateHtml);

echo eval("?>" . $templateHtml . "<?php ");
