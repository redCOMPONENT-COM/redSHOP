<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/text_library.php';

JHTML::_('behavior.tooltip');
JLoader::import('joomla.application.module.helper');
JHTMLBehavior::modal();
$url       = JURI::base();
$option    = JRequest::getVar('option');
$config    = new Redconfiguration;
$Itemid    = JRequest::getVar('Itemid');
$letter    = JRequest::getVar('letter');
$objhelper = new redhelper;
$model     = $this->getModel('category');
$view      = JRequest::getVar('view');
$user      = JFactory::getUser();
$session   = JFactory::getSession();
$document  = JFactory::getDocument();

$extraField      = new extraField;
$texts           = new text_library;
$producthelper   = new producthelper;
$redshopconfig   = new Redconfiguration;
$redTemplate     = new Redtemplate;
$stockroomhelper = new rsstockroomhelper;

$module         = JModuleHelper::isEnabled('redshop_lettersearch');
$module_data    = JModuleHelper::getModule('redshop_lettersearch');
$params         = new JRegistry($module_data->params);
$list_of_fields = $params->get('list_of_fields', '');

if (!$module)
{
	$msg = JText::_('COM_REDSHOP_PUBLISHED_LETTER_SEARCH_MODULE');

	JError::raiseWarning('', $msg);

	return false;
}

$mod_title = urldecode(JRequest::getVar('modulename'));

$module = JModuleHelper::getModule('redshop_lettersearch', $mod_title);
$params = $module->params;

$param                   = new JRegistry($params);
$lettersearchtemplate_id = $param->get('lettersearchtemplate');

$getAllproductArrayListwithfirst = $model->getAllproductArrayListwithfirst($letter, $list_of_fields);
$loadCategorytemplate            = $redTemplate->getTemplate('searchletter', $lettersearchtemplate_id);

if (count($loadCategorytemplate) > 0)
{
	$template_desc = $loadCategorytemplate[0]->template_desc;
}
else
{
	$template_desc = "<table cellspacing='0' cellpadding='0' border='0' width='100%'>{product_loop_start}<tr><td>{product_thumb_image}</td><td>{product_name}<td><td>{product_price}<td><td><div>{form_addtocart:add_to_cart1}</div></td></tr>{product_loop_end}<tr></tr><tr><td>{pagination}</td></tr></table>";
}

if (strstr($template_desc, "{product_loop_start}") && strstr($template_desc, "{product_loop_end}"))
{
	$template_d1      = explode("{product_loop_start}", $template_desc);
	$template_d2      = explode("{product_loop_end}", $template_d1 [1]);
	$template_product = $template_d2 [0];

	$attribute_template = $producthelper->getAttributeTemplate($template_product);

	$product_data = '';
	$prddata_add  = "";

	for ($j = 0; $j < count($getAllproductArrayListwithfirst); $j++)
	{
		$product = $getAllproductArrayListwithfirst[$j];
		$catid   = $producthelper->getCategoryProduct($product->product_id);

		if (!is_object($product))
		{
			break;
		}

		$count_no_user_field = 0;

		// Counting accessory
		$accessorylist = $producthelper->getProductAccessory(0, $product->product_id);
		$totacc        = count($accessorylist);

		$prddata_add .= $template_product;

		// Product User Field Start
		$hidden_userfield = "";
		$returnArr        = $producthelper->getProductUserfieldFromTemplate($prddata_add);

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

				$prddata_add = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $product_userfileds[0], $prddata_add);
				$prddata_add = str_replace('{' . $userfieldArr[$ui] . '}', $product_userfileds[1], $prddata_add);
			}

			$product_userfileds_form = "<form method='post' action='' id='user_fields_form_" . $product->product_id . "' name='user_fields_form_" . $product->product_id . "'>";

			if ($ufield != "")
			{
				$prddata_add = str_replace("{if product_userfield}", $product_userfileds_form, $prddata_add);
				$prddata_add = str_replace("{product_userfield end if}", "</form>", $prddata_add);
			}
			else
			{
				$prddata_add = str_replace("{if product_userfield}", "", $prddata_add);
				$prddata_add = str_replace("{product_userfield end if}", "", $prddata_add);
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

		$prddata_add = $prddata_add . $hidden_userfield;
		/************** end user fields ***************************/

		$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);

		if (count($ItemData) > 0)
		{
			$pItemid = $ItemData->id;
		}
		else
		{
			$pItemid = $objhelper->getItemid($product->product_id);
		}

		$prddata_add = str_replace("{product_id_lbl}", JText::_('COM_REDSHOP_PRODUCT_ID_LBL'), $prddata_add);
		$prddata_add = str_replace("{product_id}", $product->product_id, $prddata_add);
		$prddata_add = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $prddata_add);

		$product_number_output = '<span id="product_number_variable' . $product->product_id . '">' . $product->product_number . '</span>';
		$prddata_add           = str_replace("{product_number}", $product_number_output, $prddata_add);

		$product_volume_unit = '<span class="product_unit_variable">' . DEFAULT_VOLUME_UNIT . "3" . '</span>';
		$prddata_add         = str_replace("{product_size}", $producthelper->redunitDecimal($product->product_volume) . "&nbsp;" . $product_volume_unit, $prddata_add);

		$product_unit = '<span class="product_unit_variable">' . DEFAULT_VOLUME_UNIT . '</span>';
		$prddata_add  = str_replace("{product_length}", $producthelper->redunitDecimal($product->product_length) . "&nbsp;" . $product_unit, $prddata_add);
		$prddata_add  = str_replace("{product_width}", $producthelper->redunitDecimal($product->product_width) . "&nbsp;" . $product_unit, $prddata_add);
		$prddata_add  = str_replace("{product_height}", $producthelper->redunitDecimal($product->product_height) . "&nbsp;" . $product_unit, $prddata_add);

		$prddata_add = str_replace('{searched_tag}', $product->data_txt, $prddata_add);

		$prddata_add    = $producthelper->replaceVatinfo($prddata_add);
		$extraFieldName = $extraField->getSectionFieldNameArray(1, 1, 1);
		$prddata_add    = $producthelper->getExtraSectionTag($extraFieldName, $product->product_id, "1", $prddata_add);

		//
		$link = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $product->product_id . '&cid=' . $catid . '&Itemid=' . $pItemid);

		if (strstr($prddata_add, '{product_name}'))
		{
			$pname       = $config->maxchar($product->product_name, CATEGORY_PRODUCT_TITLE_MAX_CHARS, CATEGORY_PRODUCT_TITLE_END_SUFFIX);
			$pname       = "<a href='" . $link . "' title='" . $product->product_name . "'>" . $pname . "</a>";
			$prddata_add = str_replace("{product_name}", $pname, $prddata_add);
		}

		if (strstr($prddata_add, '{read_more}'))
		{
			$rmore       = "<a href='" . $link . "' title='" . $product->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
			$prddata_add = str_replace("{read_more}", $rmore, $prddata_add);
		}

		if (strstr($prddata_add, '{read_more_link}'))
		{
			$prddata_add = str_replace("{read_more_link}", $link, $prddata_add);
		}

		if (strstr($prddata_add, '{product_s_desc}'))
		{
			$p_s_desc    = $config->maxchar($product->product_s_desc, CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS, CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX);
			$prddata_add = str_replace("{product_s_desc}", $p_s_desc, $prddata_add);
		}

		if (strstr($prddata_add, '{product_desc}'))
		{
			$p_desc      = $config->maxchar($product->product_desc, CATEGORY_PRODUCT_DESC_MAX_CHARS, CATEGORY_PRODUCT_DESC_END_SUFFIX);
			$prddata_add = str_replace("{product_desc}", $p_desc, $prddata_add);
		}

		if (strstr($prddata_add, '{product_rating_summary}'))
		{
			// Product Review/Rating Fetching reviews
			$final_avgreview_data = $producthelper->getProductRating($product->product_id);
			$prddata_add          = str_replace("{product_rating_summary}", $final_avgreview_data, $prddata_add);
		}

		if (strstr($prddata_add, '{manufacturer_link}'))
		{
			$manufacturer_link_href = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $product->manufacturer_id . '&Itemid=' . ktemid);
			$manufacturer_link      = '<a href="' . $manufacturer_link_href . '" title="' . $product->manufacturer_name . '">' . $product->manufacturer_name . '</a>';
			$prddata_add            = str_replace("{manufacturer_link}", $manufacturer_link, $prddata_add);

			if (strstr($prddata_add, "{manufacturer_link}"))
			{
				$prddata_add = str_replace("{manufacturer_name}", "", $prddata_add);
			}
		}

		if (strstr($prddata_add, '{manufacturer_product_link}'))
		{
			$manufacturerPLink = "<a href='" . JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $product->manufacturer_id . '&Itemid=' . $Itemid) . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . " " . $product->manufacturer_name . "</a>";
			$prddata_add       = str_replace("{manufacturer_product_link}", $manufacturerPLink, $prddata_add);
		}

		if (strstr($prddata_add, '{manufacturer_name}'))
		{
			$prddata_add = str_replace("{manufacturer_name}", $product->manufacturer_name, $prddata_add);
		}

		if (strstr($prddata_add, "{product_thumb_image_3}"))
		{
			$pimg_tag = '{product_thumb_image_3}';
			$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_3;
			$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_3;
		}
		elseif (strstr($prddata_add, "{product_thumb_image_2}"))
		{
			$pimg_tag = '{product_thumb_image_2}';
			$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_2;
			$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_2;
		}
		elseif (strstr($prddata_add, "{product_thumb_image_1}"))
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
		$prddata_add        = str_replace($pimg_tag, $thum_image . $hidden_thumb_image, $prddata_add);

		$prddata_add = $producthelper->getJcommentEditor($product, $prddata_add);

		/*
		 * product loop template extra field
		 * lat arg set to "1" for indetify parsing data for product tag loop in category
		 * last arg will parse {producttag:NAMEOFPRODUCTTAG} nameing tags.
		 * "1" is for section as product
		 */
		if (count($loadCategorytemplate) > 0)
		{
			$prddata_add = $producthelper->getExtraSectionTag($extraFieldName, $product->product_id, "1", $prddata_add, 1);
		}

		/************************************
		 *  Conditional tag
		 *  if product on discount : Yes
		 *  {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
		 *  NO : // OUTPUT : Display blank
		 ************************************/
		$prddata_add = $producthelper->getProductOnSaleComment($product, $prddata_add);

		// Replace wishlistbutton
		$prddata_add = $producthelper->replaceWishlistButton($product->product_id, $prddata_add);

		// Replace compare product button
		$prddata_add = $producthelper->replaceCompareProductsButton($product->product_id, $catid, $prddata_add);

		$prddata_add = $stockroomhelper->replaceStockroomAmountDetail($prddata_add, $product->product_id);

		// Checking for child products
		$childproduct = $producthelper->getChildProduct($product->product_id);

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

			if ($product->attribute_set_id > 0)
			{
				$attributes_set = $producthelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}

			$attributes = $producthelper->getProductAttribute($product->product_id);
			$attributes = array_merge($attributes, $attributes_set);
		}

		/////////////////////////////////// Product attribute  Start /////////////////////////////////
		$totalatt = count($attributes);

		// Check product for not for sale
		$prddata_add = $producthelper->getProductNotForSaleComment($product, $prddata_add, $attributes);

		$prddata_add = $producthelper->replaceProductInStock($product->product_id, $prddata_add, $attributes, $attribute_template);

		$prddata_add = $producthelper->replaceAttributeData($product->product_id, 0, 0, $attributes, $prddata_add, $attribute_template, $isChilds);

		// Get cart tempalte
		$prddata_add = $producthelper->replaceCartTemplate($product->product_id, $catid, 0, 0, $prddata_add, $isChilds, $userfieldArr, $totalatt, $totacc, $count_no_user_field);
	}

	$template_desc = str_replace("{product_loop_start}", "", $template_desc);
	$template_desc = str_replace("{product_loop_end}", "", $template_desc);

	$template_desc = str_replace($template_product, $prddata_add, $template_desc);

}

if (strstr($template_desc, "{pagination}"))
{
	$pagination    = $model->getfletterPagination($letter, $list_of_fields);
	$template_desc = str_replace("{pagination}", $pagination->getPagesLinks(), $template_desc);
}

$template_desc = str_replace("{with_vat}", "", $template_desc);
$template_desc = str_replace("{without_vat}", "", $template_desc);
$template_desc = str_replace("{attribute_price_with_vat}", "", $template_desc);
$template_desc = str_replace("{attribute_price_without_vat}", "", $template_desc);

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
echo eval("?>" . $template_desc . "<?php ");
