<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$producthelper = productHelper::getInstance();
$config        = Redconfiguration::getInstance();

$related_product  = $producthelper->getRelatedProduct($this->pid);
$template         = $this->input->getString('template', '');
$relptemplate     = $this->redTemplate->getTemplate("related_product", 0, $template);
$related_template = $relptemplate[0]->template_desc;

if (count($relptemplate) > 0)
{
	$related_template_data = '';
	$product_start         = explode("{related_product_start}", $related_template);
	$product_end           = explode("{related_product_end}", $product_start [1]);

	$tempdata_div_start  = $product_start [0];
	$tempdata_div_middle = $product_end [0];
	$tempdata_div_end    = $product_end [1];

	$extra_field = extraField::getInstance();
	$fieldArray  = $extra_field->getSectionFieldList(17, 0, 0);

	$attribute_template = $producthelper->getAttributeTemplate($tempdata_div_middle);
	/************************************************************ **********************************************/
	for ($r = 0, $rn = count($related_product); $r < $rn; $r++)
	{
		$related_template_data .= $tempdata_div_middle;

		$rlink = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $related_product[$r]->product_id . '&Itemid=' . $this->itemId);

		if (strstr($related_template_data, "{relproduct_image_3}"))
		{
			$rpimg_tag = '{relproduct_image_3}';
			$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT_3');
			$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH_3');
		}
		elseif (strstr($related_template_data, "{relproduct_image_2}"))
		{
			$rpimg_tag = '{relproduct_image_2}';
			$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT_2');
			$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH_2');
		}
		elseif (strstr($related_template_data, "{relproduct_image_1}"))
		{
			$rpimg_tag = '{relproduct_image_1}';
			$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT');
			$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH');
		}
		else
		{
			$rpimg_tag = '{relproduct_image}';
			$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT');
			$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH');
		}

		$hidden_thumb_image    = "<input type='hidden' name='rel_main_imgwidth' id='rel_main_imgwidth' value='" . $rpw_thumb . "'><input type='hidden' name='rel_main_imgheight' id='rel_main_imgheight' value='" . $rph_thumb . "'>";
		$relimage              = $producthelper->getProductImage($related_product [$r]->product_id, $rlink, $rpw_thumb, $rph_thumb);
		$related_template_data = str_replace($rpimg_tag, $relimage . $hidden_thumb_image, $related_template_data);

		if (strstr($related_template_data, "{relproduct_link}"))
		{
			$rpname = "<a href='" . $rlink . "' title='" . $related_product [$r]->product_name . "'>" . $config->maxchar($related_product [$r]->product_name, Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_MAX_CHARS'), Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_END_SUFFIX')) . "</a>";
		}
		else
		{
			$rpname = $config->maxchar($related_product [$r]->product_name, Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_MAX_CHARS'), Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_END_SUFFIX'));
		}

		$rpdesc       = $config->maxchar($related_product [$r]->product_desc, Redshop::getConfig()->get('RELATED_PRODUCT_DESC_MAX_CHARS'), Redshop::getConfig()->get('RELATED_PRODUCT_DESC_END_SUFFIX'));
		$rp_shortdesc = $config->maxchar($related_product [$r]->product_s_desc, Redshop::getConfig()->get('RELATED_PRODUCT_SHORT_DESC_MAX_CHARS'), Redshop::getConfig()->get('RELATED_PRODUCT_SHORT_DESC_END_SUFFIX'));

		$related_template_data = str_replace("{relproduct_link}", '', $related_template_data);

		if (strstr($related_template_data, "{relproduct_link}"))
		{
			$related_template_data = str_replace("{relproduct_name}", "", $related_template_data);
		}
		else
		{
			$related_template_data = str_replace("{relproduct_name}", $rpname, $related_template_data);
		}

		$related_template_data = str_replace("{relproduct_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $related_template_data);
		$related_template_data = str_replace("{relproduct_number}", $related_product [$r]->product_number, $related_template_data);
		$related_template_data = str_replace("{relproduct_s_desc}", $rp_shortdesc, $related_template_data);
		$related_template_data = str_replace("{relproduct_desc}", $rpdesc, $related_template_data);

		$manufacturer = $producthelper->getSection("manufacturer", $related_product [$r]->manufacturer_id);

		if (count($manufacturer) > 0)
		{
			$man_url               = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $related_product[$r]->manufacturer_id . '&Itemid=' . $this->itemId);
			$manufacturerLink      = "<a href='" . $man_url . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . "</a>";
			$related_template_data = str_replace("{manufacturer_name}", $manufacturer->manufacturer_name, $related_template_data);
			$related_template_data = str_replace("{manufacturer_link}", $manufacturerLink, $related_template_data);
		}
		else
		{
			$related_template_data = str_replace("{manufacturer_name}", '', $related_template_data);
			$related_template_data = str_replace("{manufacturer_link}", '', $related_template_data);
		}

		// Show Price
		if (!$related_product [$r]->not_for_sale)
		{
			$related_template_data = RedshopHelperProductPrice::getShowPrice($related_product[$r]->product_id, $related_template_data, '', 0, 1);
		}
		else
		{
			$related_template_data = str_replace("{price_excluding_vat}", '', $related_template_data);
			$related_template_data = str_replace("{relproduct_price_table}", '', $related_template_data);
			$related_template_data = str_replace("{relproduct_price_novat}", '', $related_template_data);
			$related_template_data = str_replace("{relproduct_old_price}", '', $related_template_data);
			$related_template_data = str_replace("{relproduct_old_price_lbl}", '', $related_template_data);
			$related_template_data = str_replace("{relproduct_price_saving_lbl}", '', $related_template_data);
			$related_template_data = str_replace("{relproduct_price_saving}", '', $related_template_data);
			$related_template_data = str_replace("{relproduct_price}", '', $related_template_data);
		}

		// End Show Price

		$relmorelinkhref       = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $related_product [$r]->product_id . '&cid=' . $related_product[$r]->cat_in_sefurl . '&Itemid=' . $this->itemId);
		$relmorelink           = 'javascript:window.parent.SqueezeBox.close();window.parent.location.href="' . $relmorelinkhref . '"';
		$rmore                 = "<a href='" . $relmorelink . "' title='" . $related_product [$r]->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
		$related_template_data = str_replace("{read_more}", $rmore, $related_template_data);
		$related_template_data = str_replace("{read_more_link}", $relmorelink, $related_template_data);
		/*
		 *  related product Required Attribute start
		 * 	this will parse only Required Attributes
		 */
		$relid          = $related_product [$r]->product_id;
		$attributes_set = array();

		if ($related_product [$r]->attribute_set_id > 0)
		{
			$attributes_set = $producthelper->getProductAttribute(0, $related_product [$r]->attribute_set_id);
		}

		$attributes = $producthelper->getProductAttribute($relid);
		$attributes = array_merge($attributes, $attributes_set);

		$related_template_data = $producthelper->replaceAttributeData($related_product[$r]->mainproduct_id, 0, $related_product[$r]->product_id, $attributes, $related_template_data, $attribute_template);
		$related_template_data = $producthelper->replaceCartTemplate($related_product[$r]->mainproduct_id, $this->data->category_id, 0, $related_product[$r]->product_id, $related_template_data, false, 0, count($attributes), 0, 0);
		$related_template_data = $producthelper->replaceCompareProductsButton($related_product[$r]->product_id, $this->data->category_id, $related_template_data, 1);
		$related_template_data = $producthelper->replaceProductInStock($related_product[$r]->product_id, $related_template_data);

		$related_template_data = $producthelper->replaceAttributePriceList($related_product[$r]->product_id, $related_template_data);

		$related_template_data = $producthelper->getProductFinderDatepickerValue($related_template_data, $related_product[$r]->product_id, $fieldArray);
	}

	$reltemplate = $tempdata_div_start . $related_template_data . $tempdata_div_end;
}

echo eval("?>" . $reltemplate . "<?php ");
