<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergroup_product
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$uri = JURI::getInstance();
$url = $uri->root();

$Itemid    = JRequest::getInt('Itemid');
$user      = JFactory::getUser();
$option    = 'com_redshop';
$view      = JRequest::getCmd('view');
$getoption = JRequest::getCmd('option');

$document = JFactory::getDocument();
JHTML::Stylesheet('products.css', 'modules/mod_redshop_shoppergroup_product/css/');
// 	include redshop js file.
JLoader::load('RedshopHelperRedshop.js');

JHtml::script('com_redshop/attribute.js', false, true);
JHtml::script('com_redshop/common.js', false, true);
// lightbox Javascript
JHTML::script('com_redshop/redbox.js', false, true);

JHtml::stylesheet('com_redshop/fetchscript.css', array(), true);
$producthelper = new producthelper;
$redhelper     = new redhelper;
$redTemplate   = new Redtemplate;
$extraField    = new extraField;

echo "<div class='mod_redshop_shoppergroup_product_wrapper'>";

$module_id = "mod_" . $module->id;
for ($i = 0; $i < count($rows); $i++)
{
	$row = $rows[$i];

	/////////////////////////////////// Product attribute  Start /////////////////////////////////
	$attributes_set = array();
	if ($row->attribute_set_id > 0)
	{
		$attributes_set = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1);
	}
	$attributes = $producthelper->getProductAttribute($row->product_id);
	$attributes = array_merge($attributes, $attributes_set);
	$totalatt   = count($attributes);
	//$data_add = $producthelper->replaceAttributeData($row->product_id,0,0,$attributes,$data_add);
	/////////////////////////////////// Product attribute  End /////////////////////////////////


	/////////////////////////////////// Product accessory Start /////////////////////////////////
	$accessory      = $producthelper->getProductAccessory(0, $row->product_id);
	$totalAccessory = count($accessory);

	//$data_add = $producthelper->replaceAccessoryData($row->product_id,0,$accessory,$data_add,$isChilds);
	/////////////////////////////////// Product accessory End /////////////////////////////////


	/*
	 * collecting extra fields
	 */
	$count_no_user_field = 0;
	$hidden_userfield    = "";
	$userfieldArr        = array();
	if (AJAX_CART_BOX)
	{
		$ajax_detail_template_desc = "";
		$ajax_detail_template      = $producthelper->getAjaxDetailboxTemplate($row->product_id);
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
				$product_userfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id);
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
				$hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $row->product_id . "' name='user_fields_form_" . $row->product_id . "'>" . $template_userfield . "</form></div>";
			}
		}
	}

	// end

	$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);
	if (count($ItemData) > 0)
	{
		$Itemid = $ItemData->id;
	}
	else
	{
		$Itemid = $redhelper->getItemid($row->product_id);
	}

	$link       = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $row->product_id . '&Itemid=' . $Itemid);
	$thum_image = $producthelper->getProductImage($row->product_id, $link, $thumbwidth, $thumbheight);

	echo "<div class='mod_redshop_shoppergroup_product'>";
	if ($image)
	{
		echo "<div class='mod_redshop_shoppergroup_product_image'>" . $thum_image . "</div>";
	}
	echo "<div class='mod_redshop_shoppergroup_product_title'><a href='" . $link . "' title=''>";
	echo ($params->get('crop_title_length') == 0) ? $row->product_name : trim(substr($row->product_name, 0, $params->get('crop_title_length'))) . $params->get('post_text');
	echo "</a></div>";

	if ($show_short_description)
	{
		echo "<div class='mod_redshop_shoppergroup_product_desc'>" . $row->product_s_desc . "</div>";
	}

	$product_price = $producthelper->getProductPrice($row->product_id, $show_vat);
	//$product_price_discount 	= $producthelper->getProductNetPrice($row->product_id);
	$productArr             = $producthelper->getProductNetPrice($row->product_id);
	$product_price_discount = $productArr['productPrice'] + $productArr['productVat'];

	if (!$row->not_for_sale && $show_price)
	{
		if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
		{
			if (!$product_price)
			{
				$product_price_dis = $producthelper->getPriceReplacement($product_price);
			}
			else
			{
				$product_price_dis = $producthelper->getProductFormattedPrice($product_price);
			}
			$disply_text = "<div class='mod_redshop_shoppergroup_product_price'>" . $product_price_dis . "</div>";

			if ($row->product_on_sale && $product_price_discount > 0)
			{
				if ($product_price > $product_price_discount)
				{
					$disply_text = "";
					$s_price     = $product_price - $product_price_discount;
					if ($show_discountpricelayout)
					{
						echo "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>" . $producthelper->getProductFormattedPrice($product_price) . "</span></div>";
						$product_price = $product_price_discount;
						echo "<div id='mod_redmainprice' class='mod_redmainprice'>" . $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
						echo "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) . "</div>";
					}
					else
					{
						$product_price = $product_price_discount;
						echo "<div class='mod_redshop_shoppergroup_product_price'>" . $producthelper->getProductFormattedPrice($product_price) . "</div>";
					}
				}
			}
			echo $disply_text;
		} /*else {
			$product_price_dis = $producthelper->getPriceReplacement($product_price);
			echo "<div class='mod_redproducts_price'>".$product_price_dis."</div>";
		}*/
	}

	if ($show_readmore)
	{
		echo "<div class='mod_redshop_shoppergroup_product_readmore'><a href='" . $link . "'>" . JText::_('COM_REDSHOP_TXT_READ_MORE') . "</a>&nbsp;</div>";
	}

	if ($show_addtocart)
	{
		$addtocart = $producthelper->replaceCartTemplate($row->product_id, $row->category_id, 0, 0, "", false, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $module_id);
		echo "<div class='mod_redshop_shoppergroup_product_addtocart'>" . $addtocart . $hidden_userfield . "</div>";
	}
	echo "</div>";
}
echo "</div>";
