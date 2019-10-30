<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product;

defined('_JEXEC') or die;

/**
 * Product property helper
 *
 * @since  2.1.0
 */
class Property
{
	/**
	 * Method for replace product properties add to cart
	 *
	 * @param   integer      $productId     Product ID
	 * @param   integer      $propertyId    Property ID
	 * @param   integer      $categoryId    Category ID
	 * @param   string       $commonId      DOM ID
	 * @param   integer      $propertyStock Property stock
	 * @param   string       $propertyData  Property Data
	 * @param   array|object $cartTemplate  Cart template
	 * @param   string       $content       Template content
	 *
	 * @return  mixed|string
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function replaceAddToCart($productId = 0, $propertyId = 0, $categoryId = 0, $commonId = "", $propertyStock = 0, $propertyData = "", $cartTemplate = null, $content = "")
	{
		$input  = \JFactory::getApplication()->input;
		$itemId = $input->getInt('Itemid');

		$product = \RedshopHelperProduct::getProductById($productId);

		// Process the product plugin for property
		\JPluginHelper::importPlugin('redshop_product');
		\RedshopHelperUtility::getDispatcher()->trigger(
			'onPropertyAddtoCart',
			array(&$propertyData, &$cartTemplate, &$propertyStock, $propertyId, $product)
		);

		if ($propertyStock <= 0)
		{
			$propertyData = str_replace("{form_addtocart:$cartTemplate->name}", \JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'), $propertyData);

			return $propertyData;
		}

		// IF PRODUCT CHILD IS EXISTS THEN DONT SHOW PRODUCT ATTRIBUTES
		$selectedQuantity  = 1;
		$productNetPrice   = \RedshopHelperProductPrice::getNetPrice($productId, \JFactory::getUser()->id, $selectedQuantity, $content);
		$productPrice      = $productNetPrice['product_price'] * $selectedQuantity;
		$productPriceNoVat = $productNetPrice['product_price_novat'] * $selectedQuantity;
		$productOldPrice   = $productNetPrice['product_old_price'] * $selectedQuantity;

		if ($product->not_for_sale)
		{
			$productPrice = 0;
		}

		$quantityMax = $product->max_order_product_quantity;
		$quantityMin = $product->min_order_product_quantity;

		$addToCartFormName = 'addtocart_' . $commonId . '_' . $propertyId;
		$stockId           = $commonId . '_' . $propertyId;
		$attributeId       = 0;
		$attributes        = explode("_", $commonId);

		if (count($attributes) > 0)
		{
			$attributeId = $attributes[count($attributes) - 1];
		}

		$cartForm = "<form name='" . $addToCartFormName . "' id='" . $addToCartFormName
			. "' class='addtocart_formclass' action='' method='post'>";

		$cartForm .= $cartTemplate->template_desc;

		$cartForm .= "
			<input type='hidden' name='product_id' id='product_id' value='" . $productId . "'>
			<input type='hidden' name='category_id' value='" . $categoryId . "'>
			<input type='hidden' name='view' value='cart'>
			<input type='hidden' name='task' value='add'>
			<input type='hidden' name='option' value='com_redshop'>
			<input type='hidden' name='Itemid' id='Itemid' value='" . $itemId . "'>
			<input type='hidden' name='sel_wrapper_id' id='sel_wrapper_id' value='0'>

			<input type='hidden' name='accessory_data' id='accessory_data' value='0'>
			<input type='hidden' name='acc_attribute_data' id='acc_attribute_data' value='0'>
			<input type='hidden' name='acc_quantity_data' id='acc_quantity_data' value='0'>
			<input type='hidden' name='acc_property_data' id='acc_property_data' value='0'>
			<input type='hidden' name='acc_subproperty_data' id='acc_subproperty_data' value='0'>
			<input type='hidden' name='accessory_price' id='accessory_price' value='0'>

			<input type='hidden' name='requiedAttribute' id='requiedAttribute' value='' reattribute=''>
			<input type='hidden' name='requiedProperty' id='requiedProperty' value='' reproperty=''>

			<input type='hidden' name='main_price' id='main_price" . $productId . "' value='" . $productPrice . "' />
			<input type='hidden' name='tmp_product_price' id='tmp_product_price' value='0'>

			<input type='hidden' name='product_old_price' id='product_old_price" . $productId . "' value='"
			. $productOldPrice . "' />
			<input type='hidden' name='tmp_product_old_price' id='tmp_product_old_price' value='0'>

			<input type='hidden' name='product_price_no_vat' id='product_price_no_vat" . $productId . "' value='"
			. $productPriceNoVat . "' />
			<input type='hidden' name='productprice_notvat' id='productprice_notvat' value='0'>

			<input type='hidden' name='min_quantity' id='min_quantity' value='" . $quantityMin . "' requiredtext='"
			. \JText::_('COM_REDSHOP_MINIMUM_QUANTITY_SHOULD_BE') . "'>
			<input type='hidden' name='max_quantity' id='max_quantity' value='" . $quantityMax . "' requiredtext='"
			. \JText::_('COM_REDSHOP_MAXIMUM_QUANTITY_SHOULD_BE') . "'>

			<input type='hidden' name='attribute_data' id='attribute_data' value='" . $attributeId . "'>
			<input type='hidden' name='property_data' id='property_data' value='" . $propertyId . "'>
			<input type='hidden' name='subproperty_data' id='subproperty_data' value='0'>

			<input type='hidden' name='calcHeight' id='hidden_calc_height' value='' />
			<input type='hidden' name='calcWidth' id='hidden_calc_width' value='' />
			<input type='hidden' name='calcDepth' id='hidden_calc_depth' value='' />
			<input type='hidden' name='calcRadius' id='hidden_calc_radius' value='' >
			<input type='hidden' name='calcUnit' id='hidden_calc_unit' value='' />
			<input type='hidden' name='pdcextraid' id='hidden_calc_extraid' value='' />
			<input type='hidden' name='hidden_attribute_cartimage' id='hidden_attribute_cartimage" . $productId .
			"' value='' />";

		if ($product->product_type == "subscription")
		{
			$subscriptionId = $input->getInt('subscription_id', 0);

			$cartForm .= "<input type='hidden' name='subscription_id' id='hidden_subscription_id' value='" . $subscriptionId .
				"' />";
			$cartForm .= "<input type='hidden' name='subscription_prize' id='hidden_subscription_prize' value='0' />";
		}

		if ($product->min_order_product_quantity > 0)
		{
			$productQuantity = $product->min_order_product_quantity;
		}
		else
		{
			$productQuantity = 1;
		}

		if (strpos($cartForm, "{addtocart_quantity}") !== false)
		{
			$addToCartQuantity = "<span id='stockQuantity" . $stockId . "'><input class='quantity inputbox input-mini' type='text' name='quantity' id='quantity" .
				$productId . "' value='" . $productQuantity . "' maxlength='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') .
				"' onchange='validateInputNumber(this.id);' onkeypress='return event.keyCode!=13'></span>";
			$cartForm          = str_replace("{addtocart_quantity}", $addToCartQuantity, $cartForm);
			$cartForm          = str_replace("{quantity_lbl}", \JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartForm);
		}
		elseif (strpos($cartForm, "{addtocart_quantity_selectbox}") !== false)
		{
			$addToCartQuantity = "<input class='quantity' type='hidden' name='quantity' id='quantity" . $productId . "' value='" .
				$productQuantity . "' maxlength='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'>";

			if ((\Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE') != ""
					&& $product->quantity_selectbox_value == '')
				|| $product->quantity_selectbox_value != '')
			{
				$selectBoxValue = ($product->quantity_selectbox_value) ? $product->quantity_selectbox_value : \Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE');
				$quantityBox    = explode(",", $selectBoxValue);
				$quantityBox    = array_merge(array(), array_unique($quantityBox));
				sort($quantityBox);
				$quantityCombobox = "<select name='quantity' id='quantity" . $productId . "'  OnChange='calculateTotalPrice("
					. $productId . ",0);'>";

				for ($q = 0, $qn = count($quantityBox); $q < $qn; $q++)
				{
					if (intVal($quantityBox[$q]) && intVal($quantityBox[$q]) != 0)
					{
						$quantitySelect   = ($productQuantity == intval($quantityBox[$q])) ? "selected" : "";
						$quantityCombobox .= "<option value='" . intVal($quantityBox[$q]) . "' " . $quantitySelect . ">"
							. intVal($quantityBox[$q]) . "</option>";
					}
				}

				$quantityCombobox  .= "</select>";
				$addToCartQuantity = "<span id='stockQuantity" . $stockId . "'>" . $quantityCombobox . "</span>";
			}

			$cartForm = str_replace("{addtocart_quantity_selectbox}", $addToCartQuantity, $cartForm);
			$cartForm = str_replace("{quantity_lbl}", \JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartForm);
		}
		else
		{
			$cartForm .= "<input class='quantity' type='hidden' name='quantity' id='quantity" . $productId . "' value='" . $productQuantity
				. "' maxlength='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'>";
		}

		$tooltip                = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? \JText::_('COM_REDSHOP_REQUEST_A_QUOTE_TOOLTIP') : \JText::_('COM_REDSHOP_ADD_TO_CART_TOOLTIP');
		$requestQuoteLabel      = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? \JText::_('COM_REDSHOP_REQUEST_A_QUOTE') : \JText::_('COM_REDSHOP_ADD_TO_CART');
		$requestQuoteImage      = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? \Redshop::getConfig()->get('REQUESTQUOTE_IMAGE') : \Redshop::getConfig()->get('ADDTOCART_IMAGE');
		$requestQuoteBackground = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? \Redshop::getConfig()->get('REQUESTQUOTE_BACKGROUND') : \Redshop::getConfig()->get('ADDTOCART_BACKGROUND');

		$cartTag   = '';
		$cartIcon  = '';
		$cartTitle = ' title="' . $requestQuoteLabel . '" ';

		$onclick = 'onclick="if(displayAddtocartProperty(\'' . $addToCartFormName . '\',\'' . $productId . '\',\'' .
			$attributeId . '\',\'' . $propertyId . '\')){checkAddtocartValidation(\'' . $addToCartFormName . '\',\'' .
			$productId . '\',0,0,\'\',0,0,0);}" ';
		$class   = 'class=""';
		$title   = 'title=""';

		if (strpos($cartForm, "{addtocart_tooltip}") !== false)
		{
			$class    = 'class="editlinktip hasTip"';
			$title    = ' title="' . $tooltip . '" ';
			$cartForm = str_replace("{addtocart_tooltip}", $cartForm, "");
		}

		if (strpos($cartForm, "{addtocart_button}") !== false)
		{
			$class    = 'class="icon_cart"';
			$cartTag  = "{addtocart_button}";
			$cartIcon = '<span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . '><input type="button" ' .
				$onclick . $cartTitle . ' name="addtocart_button" value="' . $requestQuoteLabel . '" /></span>';
		}

		if (strpos($cartForm, "{addtocart_link}") !== false)
		{
			$class    = 'class="tag_cart"';
			$cartTag  = "{addtocart_link}";
			$cartIcon = '<span ' . $class . ' ' . $title . ' id="pdaddtocart' . $stockId . '" ' . $onclick . $cartTitle .
				' style="cursor: pointer;">' . $requestQuoteLabel . '</span>';
		}

		if (strpos($cartForm, "{addtocart_image_aslink}") !== false)
		{
			$class    = 'class="img_linkcart"';
			$cartTag  = "{addtocart_image_aslink}";
			$cartIcon = '<span ' . $class . ' ' . $title . ' id="pdaddtocart' . $stockId . '"><img ' . $onclick .
				$cartTitle . ' alt="' . $requestQuoteLabel . '" style="cursor: pointer;" src="' . REDSHOP_FRONT_IMAGES_ABSPATH .
				$requestQuoteImage . '" /></span>';
		}

		if (strpos($cartForm, "{addtocart_image}") !== false)
		{
			$cartTag  = "{addtocart_image}";
			$cartIcon = '<span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . '><div ' . $onclick .
				$cartTitle . ' align="center" style="cursor:pointer;background:url(' . REDSHOP_FRONT_IMAGES_ABSPATH .
				$requestQuoteBackground . ');background-position:bottom;background-repeat:no-repeat;" class="img_cart">' . $requestQuoteLabel .
				'</div></span>';
		}

		$cartForm = str_replace($cartTag, '<span id="stockaddtocart' . $stockId . '"></span>' . $cartIcon, $cartForm);

		// Trigger event on Add to Cart
		\RedshopHelperUtility::getDispatcher()->trigger('onAddtoCart', array(&$cartForm, $product, $addToCartFormName, $propertyId));

		$cartForm .= "</form>";

		$propertyData = str_replace("{form_addtocart:$cartTemplate->name}", $cartForm, $propertyData);

		return $propertyData;
	}
}
