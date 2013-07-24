<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_redshop/helpers/extra_field.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';

class adminproducthelper
{
	public $_data = null;

	public $_table_prefix = null;

	public $_product_level = 0;

	public function __construct()
	{
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
	}

	public function replaceAccessoryData($product_id = 0, $accessory = array(), $user_id = 0, $uniqueid = "")
	{
		$uri = JURI::getInstance();
		$url = $uri->root();
		$redconfig = new Redconfiguration;
		$producthelper = new producthelper;

		$product = $producthelper->getProductById($product_id);
		$totalAccessory = count($accessory);
		$accessorylist = "";

		if ($totalAccessory > 0)
		{
			$accessorylist .= "<tr><th>" . JText::_('COM_REDSHOP_ACCESSORY_PRODUCT') . "</th></tr>";

			for ($a = 0; $a < count($accessory); $a++)
			{
				$ac_id = $accessory [$a]->child_product_id;
				$c_p_data = $producthelper->getProductById($ac_id);

				$accessory_name = $redconfig->maxchar($accessory [$a]->product_name, ACCESSORY_PRODUCT_TITLE_MAX_CHARS, ACCESSORY_PRODUCT_TITLE_END_SUFFIX);

				// Get accessory final price with VAT rules
				$accessorypricelist = $producthelper->getAccessoryPrice($product_id, $accessory[$a]->newaccessory_price, $accessory[$a]->accessory_main_price);
				$accessory_price = $accessorypricelist[0];

				$accessoryprice_withoutvat = $producthelper->getAccessoryPrice(
					$product_id, $accessory[$a]->newaccessory_price,
					$accessory[$a]->accessory_main_price, 1
				);
				$accessory_price_withoutvat = $accessoryprice_withoutvat[0];
				$accessory_price_vat = $accessory_price - $accessory_price_withoutvat;

				$commonid = $product_id . '_' . $accessory [$a]->accessory_id . $uniqueid;

				// Accessory attribute  Start
				$attributes_set = array();

				if ($c_p_data->attribute_set_id > 0)
				{
					$attributes_set = $producthelper->getProductAttribute(0, $c_p_data->attribute_set_id);
				}

				$attributes = $producthelper->getProductAttribute($ac_id);
				$attributes = array_merge($attributes, $attributes_set);

				$accessory_checkbox = "<input onClick='calculateOfflineTotalPrice(\"" . $uniqueid . "\");' type='checkbox' name='accessory_id_"
					. $product_id . $uniqueid . "[]' totalattributs='" . count($attributes) . "' accessoryprice='"
					. $accessory_price . "' accessorypricevat='" . $accessory_price_vat . "' id='accessory_id_"
					. $commonid . "' value='" . $accessory [$a]->accessory_id . "' />";

				$accessorylist .= "<tr><td>" . $accessory_checkbox . "&nbsp;" . $accessory_name . ' : '
					. $producthelper->getProductFormattedPrice($accessory_price) . "</td></tr>";

				$accessorylist .= $this->replaceAttributeData($product_id, $accessory [$a]->accessory_id, $attributes, $user_id, $uniqueid);
			}
		}

		return $accessorylist;
	}

	function replaceAttributeData($product_id = 0, $accessory_id = 0, $attributes = array(), $user_id, $uniqueid = "")
	{
		$uri = JURI::getInstance();
		$url = $uri->root();
		$producthelper = new producthelper;
		$attributelist = "";

		$product = $producthelper->getProductById($product_id);

		if ($accessory_id != 0)
		{
			$prefix = $uniqueid . "acc_";
		}
		else
		{
			$prefix = $uniqueid . "prd_";
		}

		$attributelist .= '<span id="att_lebl" style="display:none;">' . JText::_('COM_REDSHOP_ATTRIBUTE_IS_REQUIRED') . '</span>';

		for ($a = 0; $a < count($attributes); $a++)
		{
			$property = $producthelper->getAttibuteProperty(0, $attributes[$a]->attribute_id);

			if ($attributes[$a]->text != "" && count($property) > 0)
			{
				$commonid = $prefix . $product_id . '_' . $accessory_id . '_' . $attributes[$a]->attribute_id;
				$hiddenattid = 'attribute_id_' . $prefix . $product_id . '_' . $accessory_id;

				$propertyid = 'property_id_' . $commonid;

				for ($i = 0; $i < count($property); $i++)
				{
					$attributes_property_vat = 0;

					if ($property [$i]->property_price > 0)
					{
						$propertyOprand = $property[$i]->oprand;

						$propertyPrice = $producthelper->getProductFormattedPrice($property[$i]->property_price);

						// Get product vat to include.
						$attributes_property_vat = $producthelper->getProducttax($product_id, $property [$i]->property_price, $user_id);
						$property[$i]->property_price += $attributes_property_vat;

						$propertyPriceWithVat = $producthelper->getProductFormattedPrice($property[$i]->property_price);

						$property[$i]->text = urldecode($property[$i]->property_name) . " ($propertyOprand $propertyPrice excl. vat / $propertyPriceWithVat)";
					}
					else
					{
						$property[$i]->text = urldecode($property[$i]->property_name);
					}

					$attributelist .= '<input type="hidden" id="' . $propertyid . '_oprand' . $property [$i]->value . '" value="' . $property [$i]->oprand . '" />';
					$attributelist .= '<input type="hidden" id="' . $propertyid . '_protax' . $property [$i]->value . '" value="'
						. $attributes_property_vat . '" />';
					$attributelist .= '<input type="hidden" id="' . $propertyid . '_proprice' . $property [$i]->value . '" value="'
						. $property [$i]->property_price . '" />';
				}

				$tmp_array = array();
				$tmp_array[0] = new stdClass;
				$tmp_array[0]->value = 0;
				$tmp_array[0]->text = JText::_('COM_REDSHOP_SELECT') . " " . urldecode($attributes[$a]->text);

				$new_property = array_merge($tmp_array, $property);
				$at_id = $product->product_id;
				$chklist = "";

				if ($attributes [$a]->allow_multiple_selection)
				{
					for ($chk = 0; $chk < count($property); $chk++)
					{
						if ($attributes[$a]->attribute_required == 1)
						{
							$required = "required='" . $attributes[$a]->attribute_required . "'";
						}
						else
						{
							$required = "";
						}

						$chklist .= "<br /><input type='checkbox' value='" . $property[$chk]->value . "' name='"
							. $propertyid . "[]' id='" . $propertyid . "' class='inputbox' attribute_name='"
							. $attributes [$a]->attribute_name . "' required='" . $attributes[$a]->attribute_required
							. "' onchange='javascript:changeOfflinePropertyDropdown(\"" . $product_id . "\",\"" . $accessory_id
							. "\",\"" . $attributes[$a]->attribute_id . "\",\"" . $uniqueid . "\");'  />&nbsp;" . $property[$chk]->text;
					}
				}
				else
				{
					$chklist = JHTML::_('select.genericlist', $new_property, $propertyid . '[]', 'id="' . $propertyid
						. '"  class="inputbox" size="1" attribute_name="' . $attributes[$a]->attribute_name . '" required="'
						. $attributes [$a]->attribute_required . '" onchange="javascript:changeOfflinePropertyDropdown(\''
						. $product_id . '\',\'' . $accessory_id . '\',\'' . $attributes[$a]->attribute_id . '\',\'' . $uniqueid
						. '\');" ', 'value', 'text', '');
				}

				$lists ['property_id'] = $chklist;

				$attributelist .= "<input type='hidden' name='" . $hiddenattid . "[]' value='" . $attributes [$a]->value . "' />";

				if ($attributes [$a]->attribute_required > 0)
				{
					$pos = ASTERISK_POSITION > 0 ? urldecode($attributes [$a]->text)
						. "<span id='asterisk_right'> * " : "<span id='asterisk_left'>* </span>"
						. urldecode($attributes[$a]->text);
					$attr_title = $pos;
				}
				else
				{
					$attr_title = urldecode($attributes[$a]->text);
				}

				$attributelist .= "<tr><td>" . $attr_title . " : " . $lists ['property_id'] . "</td></tr>";
				$attributelist .= "<tr><td><div id='property_responce" . $commonid . "' style='display:none;'></td></tr>";
			}
		}

		return $attributelist;
	}

	public function replaceWrapperData($product_id = 0, $user_id, $uniqueid = "")
	{
		$producthelper = new producthelper;
		$wrapperlist = "";

		$wrapper = $producthelper->getWrapper($product_id, 0, 1);

		if (count($wrapper) > 0)
		{
			$warray = array();
			$warray[0] = new stdClass;
			$warray[0]->wrapper_id = 0;
			$warray[0]->wrapper_name = JText::_('COM_REDSHOP_SELECT');
			$commonid = $product_id . $uniqueid;

			for ($i = 0; $i < count($wrapper); $i++)
			{
				$wrapper_vat = 0;

				if ($wrapper[$i]->wrapper_price > 0)
				{
					$wrapper_vat = $producthelper->getProducttax($product_id, $wrapper[$i]->wrapper_price, $user_id);
				}

				$wrapper[$i]->wrapper_price += $wrapper_vat;
				$wrapper [$i]->wrapper_name = $wrapper [$i]->wrapper_name . " ("
					. $producthelper->getProductFormattedPrice($wrapper[$i]->wrapper_price) . ")";
				$wrapperlist .= "<input type='hidden' id='wprice_" . $commonid . "_"
					. $wrapper [$i]->wrapper_id . "' value='" . $wrapper[$i]->wrapper_price . "' />";
				$wrapperlist .= "<input type='hidden' id='wprice_tax_" . $commonid . "_"
					. $wrapper [$i]->wrapper_id . "' value='" . $wrapper_vat . "' />";
			}

			$wrapper = array_merge($warray, $wrapper);
			$lists ['wrapper_id'] = JHTML::_('select.genericlist', $wrapper, 'wrapper_id_' . $commonid . '[]',
				'id="wrapper_id_' . $commonid . '" class="inputbox" onchange="calculateOfflineTotalPrice(\'' . $uniqueid . '\');" ',
				'wrapper_id', 'wrapper_name', 0);

			$wrapperlist .= "<tr><td>" . JText::_('COM_REDSHOP_WRAPPER') . " : " . $lists ['wrapper_id'] . "</td></tr>";
		}

		return $wrapperlist;
	}

	public function getProductItemInfo($product_id = 0, $quantity = 1, $unique_id = "", $user_id = 0, $newproduct_price = 0)
	{
		$producthelper = new producthelper;

		$wrapperlist = "";
		$accessorylist = "";
		$attributelist = "";
		$productuserfield = "";
		$product_price = 0;
		$product_price_excl_vat = 0;
		$producttax = 0;

		if ($product_id)
		{
			$productInfo = $producthelper->getProductById($product_id);

			if ($newproduct_price != 0)
			{
				$product_price_excl_vat = $newproduct_price;
				$producttax = $producthelper->getProductTax($product_id, $newproduct_price, $user_id);
			}

			else
			{
				$productArr = $producthelper->getProductNetPrice($product_id, $user_id, $quantity);
				$product_price_excl_vat = $productArr['productPrice'];
				$producttax = $productArr['productVat'];

				// Attribute start
				$attributes_set = array();

				if ($productInfo->attribute_set_id > 0)
				{
					$attributes_set = $producthelper->getProductAttribute(0, $productInfo->attribute_set_id, 0, 1);
				}

				$attributes = $producthelper->getProductAttribute($product_id);
				$attributes = array_merge($attributes, $attributes_set);
				$attributelist = $this->replaceAttributeData($product_id, 0, $attributes, $user_id, $unique_id);

				// Accessory start
				$accessory = $producthelper->getProductAccessory(0, $product_id);
				$accessorylist = $this->replaceAccessoryData($product_id, $accessory, $user_id, $unique_id);

				// Wrapper selection box generate
				$wrapperlist = $this->replaceWrapperData($product_id, $user_id, $unique_id);
				$productuserfield = $this->replaceUserfield($product_id, $productInfo->product_template, $unique_id);
			}
		}

		$product_price = $product_price_excl_vat + $producttax;
		$total_price = $product_price * $quantity;
		$totaltax = $producttax * $quantity;

		$displayrespoce = "";
		$displayrespoce .= "<div id='product_price_excl_vat'>" . $product_price_excl_vat . "</div>";
		$displayrespoce .= "<div id='product_tax'>" . $producttax . "</div>";
		$displayrespoce .= "<div id='product_price'>" . $product_price . "</div>";
		$displayrespoce .= "<div id='total_price'>" . $total_price . "</div>";
		$displayrespoce .= "<div id='total_tax'>" . $totaltax . "</div>";
		$displayrespoce .= "<div id='attblock'><table>" . $attributelist . "</table></div>";
		$displayrespoce .= "<div id='productuserfield'><table>" . $productuserfield . "</table></div>";
		$displayrespoce .= "<div id='accessoryblock'><table>" . $accessorylist . "</table></div>";
		$displayrespoce .= "<div id='noteblock'>" . $wrapperlist . "</div>";

		return $displayrespoce;
	}

	public function replaceShippingMethod($d = array(), $shipp_users_info_id = 0, $shipping_rate_id = 0, $shipping_box_post_id = 0)
	{
		$producthelper = new producthelper;
		$order_functions = new order_functions;

		if ($shipp_users_info_id > 0)
		{
			$shippingmethod = $order_functions->getShippingMethodInfo();

			JPluginHelper::importPlugin('redshop_shipping');
			$dispatcher = JDispatcher::getInstance();
			$shippingrate = $dispatcher->trigger('onListRates', array(&$d));

			$ratearr = array();
			$r = 0;

			for ($s = 0; $s < count($shippingmethod); $s++)
			{
				if (isset($shippingrate[$s]) === false)
				{
					continue;
				}

				$rate = $shippingrate[$s];

				if (count($rate) > 0)
				{
					$rs = $shippingmethod[$s];

					for ($i = 0; $i < count($rate); $i++)
					{
						$displayrate = ($rate[$i]->rate > 0) ? " (" . $producthelper->getProductFormattedPrice($rate[$i]->rate) . " )" : "";
						$ratearr[$r] = new stdClass;
						$ratearr[$r]->text = $rs->name . " - " . $rate[$i]->text . $displayrate;
						$ratearr[$r]->value = $rate[$i]->value;
						$r++;
					}
				}
			}

			if (count($ratearr) > 0)
			{
				if (!$shipping_rate_id)
				{
					$shipping_rate_id = $ratearr[0]->value;
				}

				$displayrespoce = JHTML::_('select.genericlist', $ratearr, 'shipping_rate_id',
					'class="inputbox" onchange="calculateOfflineShipping();" ', 'value', 'text', $shipping_rate_id);
			}

			else
			{
				$displayrespoce = JText::_('COM_REDSHOP_NO_SHIPPING_METHODS_TO_DISPLAY');
			}
		}

		else
		{
			$displayrespoce = '<div class="shipnotice">' . JText::_('COM_REDSHOP_FILL_SHIPPING_ADDRESS') . '</div>';
		}

		return $displayrespoce;
	}

	public function redesignProductItem($post = array())
	{
		$orderItem = array();
		$i = 0;

		foreach ($post as $key => $value)
		{
			if (!strcmp("product", substr($key, 0, 7)) && strlen($key) < 10)
			{
				$orderItem[$i]->product_id = $value;
			}

			if (!strcmp("attribute_dataproduct", substr($key, 0, 21)))
			{
				$orderItem[$i]->attribute_data = $value;
			}

			if (!strcmp("property_dataproduct", substr($key, 0, 20)))
			{
				$orderItem[$i]->property_data = $value;
			}

			if (!strcmp("subproperty_dataproduct", substr($key, 0, 23)))
			{
				$orderItem[$i]->subproperty_data = $value;
			}

			if (!strcmp("accessory_dataproduct", substr($key, 0, 21)))
			{
				$orderItem[$i]->accessory_data = $value;
			}

			if (!strcmp("acc_attribute_dataproduct", substr($key, 0, 25)))
			{
				$orderItem[$i]->acc_attribute_data = $value;
			}

			if (!strcmp("acc_property_dataproduct", substr($key, 0, 24)))
			{
				$orderItem[$i]->acc_property_data = $value;
			}

			if (!strcmp("acc_subproperty_dataproduct", substr($key, 0, 27)))
			{
				$orderItem[$i]->acc_subproperty_data = $value;
			}

			if (!strcmp("extrafieldId", substr($key, 0, 12)))
			{
				$orderItem[$i]->extrafieldId = $value;
			}

			if (!strcmp("extrafieldname", substr($key, 0, 14)))
			{
				$orderItem[$i]->extrafieldname = $value;
			}

			if (!strcmp("wrapper_dataproduct", substr($key, 0, 19)))
			{
				$orderItem[$i]->wrapper_data = $value;
			}

			if (!strcmp("quantityproduct", substr($key, 0, 15)))
			{
				$orderItem[$i]->quantity = $value;
			}

			if (!strcmp("prdexclpriceproduct", substr($key, 0, 19)))
			{
				$orderItem[$i]->prdexclprice = $value;
			}

			if (!strcmp("taxpriceproduct", substr($key, 0, 15)))
			{
				$orderItem[$i]->taxprice = $value;
			}

			if (!strcmp("productpriceproduct", substr($key, 0, 19)))
			{
				$orderItem[$i]->productprice = $value;
			}

			if (!strcmp("requiedAttributeproduct", substr($key, 0, 23)))
			{
				$orderItem[$i]->requiedAttributeproduct = $value;
				$i++;
			}
		}

		return $orderItem;
	}

	public function replaceUserfield($product_id = 0, $template_id = 0, $unique_id = "")
	{
		$producthelper = new producthelper;
		$redTemplate = new Redtemplate;
		$extraField = new extra_field;
		$template_desc = $redTemplate->getTemplate("product", $template_id);
		$returnArr = $producthelper->getProductUserfieldFromTemplate($template_desc[0]->template_desc);

		$commonid = $product_id . $unique_id;
		$product_userfileds = "<table>";

		for ($ui = 0; $ui < count($returnArr[1]); $ui++)
		{
			$result_arr = $extraField->list_all_user_fields($returnArr[1][$ui], 12, "", $commonid);
			$hidden_arr = $extraField->list_all_user_fields($returnArr[1][$ui], 12, "hidden", $commonid);

			if ($result_arr[0] != "")
			{
				$product_userfileds .= "<tr><td>" . $result_arr[0] . "</td><td>" . $result_arr[1] . $hidden_arr[1] . "</td></tr>";
			}
		}

		$product_userfileds .= "</table>";

		return $product_userfileds;
	}

	public function admin_insertProdcutUserfield($field_id = 0, $order_item_id = 0, $section_id = 12, $value = '')
	{
		$db = JFactory::getDbo();
		$sql = "INSERT INTO " . $this->_table_prefix . "fields_data "
			. "(fieldid,data_txt,itemid,section) "
			. "value ('" . $field_id . "','" . $value . "','" . $order_item_id . "','" . $section_id . "')";
		$db->setQuery($sql);
		$db->query();
	}

	public function getProductrBySortedList()
	{
		$product_data = array();
		$product_data[0] = new stdClass;
		$product_data[0]->value = "0";
		$product_data[0]->text = JText::_('COM_REDSHOP_SELECT');

		$product_data[1] = new stdClass;
		$product_data[1]->value = "p.published";
		$product_data[1]->text = JText::_('COM_REDSHOP_PRODUCT_PUBLISHED');

		$product_data[2] = new stdClass;
		$product_data[2]->value = "p.unpublished";
		$product_data[2]->text = JText::_('COM_REDSHOP_PRODUCT_UNPUBLISHED');

		$product_data[3] = new stdClass;
		$product_data[3]->value = "p.product_on_sale";
		$product_data[3]->text = JText::_('COM_REDSHOP_PRODUCT_ON_SALE');

		$product_data[4] = new stdClass;
		$product_data[4]->value = "p.product_not_on_sale";
		$product_data[4]->text = JText::_('COM_REDSHOP_PRODUCT_NOT_ON_SALE');

		$product_data[5] = new stdClass;
		$product_data[5]->value = "p.product_special";
		$product_data[5]->text = JText::_('COM_REDSHOP_PRODUCT_SPECIAL');

		$product_data[6] = new stdClass;
		$product_data[6]->value = "p.expired";
		$product_data[6]->text = JText::_('COM_REDSHOP_PRODUCT_EXPIRED');

		$product_data[7] = new stdClass;
		$product_data[7]->value = "p.not_for_sale";
		$product_data[7]->text = JText::_('COM_REDSHOP_PRODUCT_NOT_FOR_SALE');

		$product_data[8] = new stdClass;
		$product_data[8]->value = "p.sold_out";
		$product_data[8]->text = JText::_('COM_REDSHOP_PRODUCT_SOLD_OUT');

		return $product_data;
	}
}
