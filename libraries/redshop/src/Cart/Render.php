<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

defined('_JEXEC') or die;

/**
 * Render class
 *
 * @since  2.1.0
 */
class Render
{
	/**
	 * Method for render cart, replace tag in template
	 *
	 * @param   integer $productId        Product Id
	 * @param   integer $categoryId       Category Id
	 * @param   integer $accessoryId      Accessory Id
	 * @param   integer $relatedProductId Related product Id
	 * @param   string  $content          Template content
	 * @param   boolean $isChild          Is child product?
	 * @param   array   $userFields       User fields
	 * @param   integer $totalAttr        Total attributes
	 * @param   integer $totalAccessory   Total accessories
	 * @param   integer $countNoUserField Total user fields
	 * @param   integer $moduleId         Module Id
	 * @param   integer $giftcardId       Giftcard Id
	 *
	 * @return  mixed|string
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function replace($productId = 0, $categoryId = 0, $accessoryId = 0, $relatedProductId = 0, $content = "", $isChild = false, $userFields = array(), $totalAttr = 0, $totalAccessory = 0, $countNoUserField = 0, $moduleId = 0, $giftcardId = 0)
	{
		\JPluginHelper::importPlugin('redshop_product');

		$input           = \JFactory::getApplication()->input;
		$productQuantity = $input->get('product_quantity');
		$itemId          = $input->getInt('Itemid');
		$productPreOrder = '';
		$userId          = \JFactory::getUser()->id;
		$fieldSection    = \RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD;

		if ($relatedProductId != 0)
		{
			$productId = $relatedProductId;
		}
		elseif ($giftcardId != 0)
		{
			$productId = $giftcardId;
		}

		if ($giftcardId != 0)
		{
			$product      = \RedshopEntityGiftcard::getInstance($giftcardId)->getItem();
			$fieldSection = \RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD;
		}
		else
		{
			$product = \RedshopHelperProduct::getProductById($productId);

			if (isset($product->preorder))
			{
				$productPreOrder = $product->preorder;
			}
		}

		$taxExemptAddToCart = \RedshopHelperCart::taxExemptAddToCart($userId, true);

		$cartTemplate = \Redshop\Template\Helper::getAddToCart($content);

		if (null === $cartTemplate)
		{
			if (!empty($content))
			{
				$cartTemplate                = new \stdClass;
				$cartTemplate->name          = "";
				$cartTemplate->template_desc = "";
			}
			else
			{
				$cartTemplate                = new \stdClass;
				$cartTemplate->name          = "notemplate";
				$cartTemplate->template_desc = "<div>{addtocart_image_aslink}</div>";
				$content                     = "{form_addtocart:$cartTemplate->name}";
			}
		}

		$layout = $input->getCmd('layout');
		$cart   = \RedshopHelperCartSession::getCart();

		$isAjax               = 0;
		$prePrefix            = "";
		$preSelectedAttrImage = "";

		if ($layout == "viewajaxdetail")
		{
			$isAjax    = 1;
			$prePrefix = "ajax_";
		}

		$prefix = $prePrefix . "prd_";

		if ($accessoryId != 0)
		{
			$prefix = $prePrefix . "acc_";
		}
		elseif ($relatedProductId != 0)
		{
			$prefix = $prePrefix . "rel_";
		}

		if (!empty($moduleId))
		{
			$prefix = $prefix . $moduleId . "_";
		}

		$totalRequiredAttributes = "";
		$totalRequiredProperties = '';

		$isPreorderStockExists = '';

		if ($giftcardId != 0)
		{
			$productPrice      = $product->giftcard_price;
			$productPriceNoVat = 0;
			$productOldPrice   = 0;
			$isStockExist      = true;
			$maxQuantity       = 0;
			$minQuantity       = 0;
		}
		else
		{
			// IF PRODUCT CHILD IS EXISTS THEN DONT SHOW PRODUCT ATTRIBUTES
			if ($isChild)
			{
				$content = str_replace("{form_addtocart:$cartTemplate->name}", "", $content);

				return $content;
			}
			elseif (\productHelper::getInstance()->isProductDateRange($userFields, $productId))
			{
				// New type custom field - Selection based on selected conditions
				$content = str_replace("{form_addtocart:$cartTemplate->name}", \JText::_('COM_REDSHOP_PRODUCT_DATE_FIELD_EXPIRED'), $content);

				return $content;
			}
			elseif ($product->not_for_sale)
			{
				$content = str_replace("{form_addtocart:$cartTemplate->name}", '', $content);

				return $content;
			}
			elseif (!$taxExemptAddToCart)
			{
				$content = str_replace("{form_addtocart:$cartTemplate->name}", '', $content);

				return $content;
			}
			elseif (!\Redshop::getConfig()->get('SHOW_PRICE'))
			{
				$content = str_replace("{form_addtocart:$cartTemplate->name}", '', $content);

				return $content;
			}
			elseif ($product->expired == 1)
			{
				$content = str_replace("{form_addtocart:$cartTemplate->name}", \Redshop::getConfig()->get('PRODUCT_EXPIRE_TEXT'), $content);

				return $content;
			}

			// Get stock for Product
			$isStockExist = \RedshopHelperStockroom::isStockExists($productId);

			if ($totalAttr > 0 && !$isStockExist)
			{
				$properties  = \RedshopHelperProduct_Attribute::getAttributeProperties(0, 0, $productId);
				$propertyIds = array();

				foreach ($properties as $attributeProperties)
				{
					$isSubpropertyStock = false;
					$subProperties      = \RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $attributeProperties->property_id);

					if (!empty($subProperties))
					{
						$subPropertyIds = array();

						foreach ($subProperties as $subProperty)
						{
							$subPropertyIds[] = $subProperty->subattribute_color_id;
						}

						$isSubpropertyStock = \RedshopHelperStockroom::isStockExists(
							implode(',', $subPropertyIds),
							'subproperty'
						);

						if ($isSubpropertyStock)
						{
							$isStockExist = $isSubpropertyStock;
							break;
						}
					}

					if ($isSubpropertyStock)
					{
						$isStockExist = $isSubpropertyStock;

						break;
					}

					$propertyIds[] = $attributeProperties->property_id;
				}

				if (!$isStockExist)
				{
					$isStockExist = (boolean) \RedshopHelperStockroom::isStockExists(
						implode(',', $propertyIds), 'property'
					);
				}
			}

			$defaultQuantity = \Redshop\Cart\Helper::getDefaultQuantity($productId, $content);

			$productNetPrice   = \RedshopHelperProductPrice::getNetPrice($productId, $userId, $defaultQuantity, $content);
			$productPrice      = $productNetPrice['product_price'] * $defaultQuantity;
			$productPriceNoVat = $productNetPrice['product_price_novat'] * $defaultQuantity;
			$productOldPrice   = $productNetPrice['product_old_price'] * $defaultQuantity;

			if ($product->not_for_sale)
			{
				$productPrice = 0;
			}

			$maxQuantity = $product->max_order_product_quantity;
			$minQuantity = $product->min_order_product_quantity;

		}

		$stockDisplay    = false;
		$preOrderDisplay = false;
		$cartDisplay     = false;

		$displayText = \JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');

		if (!$isStockExist)
		{
			if (($productPreOrder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
				|| ($productPreOrder == "yes")
				|| ($productPreOrder == "" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
			{
				// Get preorder stock for Product
				$isPreorderStockExists = \RedshopHelperStockroom::isPreorderStockExists($productId);

				if ($totalAttr > 0 && !$isPreorderStockExists)
				{
					$attributeProperties = \RedshopHelperProduct_Attribute::getAttributeProperties(0, 0, $productId);

					foreach ($attributeProperties as $attributeProperty)
					{
						$isSubpropertyStock     = false;
						$attributeSubProperties = \RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $attributeProperty->property_id);

						foreach ($attributeSubProperties as $attributeSubProperty)
						{
							$isSubpropertyStock = \RedshopHelperStockroom::isPreorderStockExists(
								$attributeSubProperty->subattribute_color_id,
								'subproperty'
							);

							if ($isSubpropertyStock)
							{
								$isPreorderStockExists = $isSubpropertyStock;
								break;
							}
						}

						if ($isSubpropertyStock)
						{
							break;
						}

						$isPropertyStockExist = \RedshopHelperStockroom::isPreorderStockExists(
							$attributeProperty->property_id,
							"property"
						);

						if ($isPropertyStockExist)
						{
							$isPreorderStockExists = $isPropertyStockExist;
							break;
						}
					}
				}

				// Check preorder stock
				if (!$isPreorderStockExists)
				{
					$stockDisplay      = true;
					$addCartFlag       = true;
					$displayText       = \JText::_('COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE');

				}
				else
				{
					//$pre_order_value = 1;
					$preOrderDisplay      = true;
					$addCartFlag          = true;
					$productAvailableDate = "";

					if ($product->product_availability_date != "")
					{
						$productAvailableDate = \RedshopHelperDatetime::convertDateFormat($product->product_availability_date);
					}
				}

			}
			else
			{
				$stockDisplay = true;
				$addCartFlag  = true;
			}
		}
		else
		{
			$cartDisplay = true;
			$addCartFlag = true;
		}

		$productAvailableDate = "";
		$preOrderLabel        = \JText::_('COM_REDSHOP_PRE_ORDER');
		$allowPreOrderLabel   = str_replace("{availability_date}", $productAvailableDate, \Redshop::getConfig()->get('ALLOW_PRE_ORDER_MESSAGE'));
		$preOrderImage        = \Redshop::getConfig()->get('PRE_ORDER_IMAGE');
		$tooltip              = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ?
			\JText::_('COM_REDSHOP_REQUEST_A_QUOTE_TOOLTIP') : \JText::_('COM_REDSHOP_ADD_TO_CART_TOOLTIP');
		$requestLabel         = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ?
			\JText::_('COM_REDSHOP_REQUEST_A_QUOTE') : \JText::_('COM_REDSHOP_ADD_TO_CART');
		$requestImage         = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ?
			\Redshop::getConfig()->get('REQUESTQUOTE_IMAGE') : \Redshop::getConfig()->get('ADDTOCART_IMAGE');
		$requestBackground    = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ?
			\Redshop::getConfig()->get('REQUESTQUOTE_BACKGROUND') : \Redshop::getConfig()->get('ADDTOCART_BACKGROUND');
		$requestTooltip       = "";

		if ($totalAttr > 0)
		{
			$attributeSets = array();

			if ($product->attribute_set_id > 0)
			{
				$attributeSets = \RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1, 1);
			}

			$requiredAttributes = \RedshopHelperProduct_Attribute::getProductAttribute($productId, 0, 0, 1, 1);
			$requiredAttributes = array_merge($requiredAttributes, $attributeSets);

			foreach ($requiredAttributes as $requiredAttribute)
			{
				$totalRequiredAttributes .= \JText::_('COM_REDSHOP_ATTRIBUTE_IS_REQUIRED') . " "
					. urldecode($requiredAttribute->attribute_name) . "\n";
			}

			$requiredProperties = \RedshopHelperProduct_Attribute::getAttributeProperties(0, 0, $productId, 0, 1);

			foreach ($requiredProperties as $requiredProperty)
			{
				$totalRequiredProperties .= \JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED') . " "
					. urldecode($requiredProperty->property_name) . "\n";
			}
		}

		$stockId = $prefix . $productId;
		$cartId  = 0;

		if ($addCartFlag)
		{
			if ($giftcardId == 0 && $categoryId == 0)
			{
				$categoryId = \productHelper::getInstance()->getCategoryProduct($productId);
			}

			// $cartFromName = 'addtocart_' . $prefix . $productId . $categoryId;
			$cartFromName = 'addtocart_' . $prefix . $productId;
			$cartForm     = "<form name='" . $cartFromName . "' id='" . $cartFromName
				. "' class='addtocart_formclass' action='' method='post'>";

			$cartForm .= $cartTemplate->template_desc;

			if (count($userFields) > 0)
			{
				$productHiddenUserFields = '<table>';
				$idx                     = 0;

				if (isset($cart['idx']))
				{
					$idx = (int) ($cart['idx']);
				}

				for ($j = 0; $j < $idx; $j++)
				{
					if ($giftcardId != 0)
					{
						if ($cart[$j]['giftcard_id'] == $productId)
						{
							$cartId = $j;
						}
					}
					else
					{
						if ($cart[$j]['product_id'] == $productId)
						{
							$cartId = $j;
						}
					}
				}

				foreach ($userFields as $userField)
				{
					$result = \Redshop\Fields\SiteHelper::listAllUserFields(
						$userField,
						$fieldSection,
						"hidden",
						$cartId,
						$isAjax, $productId
					);

					$productHiddenUserFields .= $result[1];
				}

				$productHiddenUserFields .= '</table>';
				$cartForm                .= $productHiddenUserFields;
			}

			// Start Hidden attribute image in cart
			$attributes = \RedshopHelperProduct_Attribute::getProductAttribute($productId);

			if (count($attributes) > 0)
			{
				$selectedPropertyId    = 0;
				$selectedSubPropertyId = 0;

				foreach ($attributes as $attribute)
				{
					$selectedId          = array();
					$attributeProperties = \RedshopHelperProduct_Attribute::getAttributeProperties(0, $attribute->attribute_id, $productId);

					if ($attribute->text != "" && count($attributeProperties) > 0)
					{
						foreach ($attributeProperties as $attributeProperty)
						{
							if ($attributeProperty->setdefault_selected)
							{
								$selectedId[] = $attributeProperty->property_id;
							}
						}

						if (count($selectedId) > 0)
						{
							$selectedPropertyId     = $selectedId[count($selectedId) - 1];
							$attributeSubProperties = \RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $selectedPropertyId);
							$selectedId             = array();

							foreach ($attributeSubProperties as $attributeSubProperty)
							{
								if ($attributeSubProperty->setdefault_selected)
								{
									$selectedId[] = $attributeSubProperty->subattribute_color_id;
								}
							}

							if (count($selectedId) > 0)
							{
								$selectedSubPropertyId = $selectedId[count($selectedId) - 1];
							}
						}
					}
				}

				$preSelectedAttrImage = \Redshop\Product\Image\Image::getHiddenAttributeCartImage(
					$productId,
					$selectedPropertyId,
					$selectedSubPropertyId
				);

			}

			//End

			$cartForm .= "
				<input type='hidden' name='preorder_product_stock' id='preorder_product_stock" . $productId .
				"' value='" . $isPreorderStockExists . "'>
		        <input type='hidden' name='product_stock' id='product_stock" . $productId . "' value='" .
				$isStockExist . "'>
				<input type='hidden' name='product_preorder' id='product_preorder" . $productId . "' value='" .
				$productPreOrder . "'>
				<input type='hidden' name='product_id' id='product_id' value='" . $productId . "'>
				<input type='hidden' name='category_id' value='" . $categoryId . "'>
				<input type='hidden' name='view' value='cart'>
				<input type='hidden' name='task' value='add'>
				<input type='hidden' name='option' value='com_redshop'>
				<input type='hidden' name='Itemid' id='Itemid' value='" . $itemId . "'>
				<input type='hidden' name='sel_wrapper_id' id='sel_wrapper_id' value='0'>

				<input type='hidden' name='main_price' id='main_price" . $productId . "' value='" . $productPrice .
				"' />
				<input type='hidden' name='tmp_product_price' id='tmp_product_price' value='0'>

				<input type='hidden' name='product_old_price' id='product_old_price" . $productId . "' value='" .
				$productOldPrice . "' />
				<input type='hidden' name='tmp_product_old_price' id='tmp_product_old_price' value='0'>

				<input type='hidden' name='product_price_no_vat' id='product_price_no_vat" . $productId . "' value='" .
				$productPriceNoVat . "' />
				<input type='hidden' name='productprice_notvat' id='productprice_notvat' value='0'>

				<input type='hidden' name='min_quantity' id='min_quantity' value='" . $minQuantity .
				"' requiredtext='" . \JText::_('COM_REDSHOP_MINIMUM_QUANTITY_SHOULD_BE') . "'>
				<input type='hidden' name='max_quantity' id='max_quantity' value='" . $maxQuantity .
				"' requiredtext='" . \JText::_('COM_REDSHOP_MAXIMUM_QUANTITY_SHOULD_BE') . "'>

				<input type='hidden' name='accessory_data' id='accessory_data' value='0'>
				<input type='hidden' name='acc_attribute_data' id='acc_attribute_data' value='0'>
				<input type='hidden' name='acc_quantity_data' id='acc_quantity_data' value='0'>
				<input type='hidden' name='acc_property_data' id='acc_property_data' value='0'>
				<input type='hidden' name='acc_subproperty_data' id='acc_subproperty_data' value='0'>
				<input type='hidden' name='accessory_price' id='accessory_price' value='0'>
				<input type='hidden' name='accessory_price_withoutvat' id='accessory_price_withoutvat' value='0'>

				<input type='hidden' name='attribute_data' id='attribute_data' value='0'>
				<input type='hidden' name='property_data' id='property_data' value='0'>
				<input type='hidden' name='subproperty_data' id='subproperty_data' value='0'>
				<input type='hidden' name='attribute_price' id='attribute_price' value='0'>
				<input type='hidden' name='requiedAttribute' id='requiedAttribute' value='' reattribute='" . $totalRequiredAttributes . "'>
				<input type='hidden' name='requiedProperty' id='requiedProperty' value='' reproperty='" . $totalRequiredProperties . "'>

				<input type='hidden' name='calcHeight' id='hidden_calc_height' value='' />
				<input type='hidden' name='calcWidth' id='hidden_calc_width' value='' />
				<input type='hidden' name='calcDepth' id='hidden_calc_depth' value='' />
				<input type='hidden' name='calcRadius' id='hidden_calc_radius' value='' >
				<input type='hidden' name='calcUnit' id='hidden_calc_unit' value='' />
				<input type='hidden' name='pdcextraid' id='hidden_calc_extraid' value='' />
				<input type='hidden' name='hidden_attribute_cartimage' id='hidden_attribute_cartimage" . $productId
				. "' value='" . $preSelectedAttrImage . "' />";

			if ($giftcardId != 0)
			{
				$cartForm .= "<input type='hidden' name='giftcard_id' id= 'giftcard_id' value='" . $giftcardId . "' />"
					. "<input type='hidden' name='reciver_email' id='reciver_email'"
					. " value='" . (isset($cart['reciver_email']) ? $cart['reciver_email'] : '') . "' />"
					. "<input type='hidden' name='reciver_name' id='reciver_name'"
					. " value='" . (isset($cart['reciver_name']) ? $cart['reciver_name'] : '') . "' />";

				if ($product->customer_amount == 1)
				{
					$cartForm .= "<input type='hidden' name='customer_amount' id='customer_amount'"
						. " value='" . (isset($cart['customer_amount']) ? $cart['customer_amount'] : '') . "'>";
				}
			}
			else
			{
				if ($product->product_type == "subscription")
				{
					$subscriptionId = $input->getInt('subscription_id', 0);

					$cartForm .= "<input type='hidden' name='subscription_id' id='hidden_subscription_id' value='"
						. $subscriptionId . "' />";
					$cartForm .= "<input type='hidden' name='subscription_prize' id='hidden_subscription_prize' value='0' />";
				}

				$ajaxDetailTemplate = \Redshop\Template\Helper::getAjaxDetailBox($product);

				if (null !== $ajaxDetailTemplate)
				{
					$ajaxCartDetailDesc = $ajaxDetailTemplate->template_desc;

					/*
					 * Attribute, accessory, userfield check for ajax detail template
					 * make attribute count 0. if there is no tag in ajax detail template
					 */
					if (strpos($ajaxCartDetailDesc, "{attribute_template:") === false)
					{
						$totalAttr = 0;
					}

					// Make accessory count 0. if there is no tag in ajax detail template
					if (strpos($ajaxCartDetailDesc, "{accessory_template:") === false)
					{
						$totalAccessory = 0;
					}

					// Make userfields 0.if there is no tag available in ajax detail template
					if (strpos($ajaxCartDetailDesc, "{if product_userfield}") !== false)
					{
						$ajaxExtraField1      = explode("{if product_userfield}", $ajaxCartDetailDesc);
						$ajaxExtraField2      = explode("{product_userfield end if}", $ajaxExtraField1 [1]);
						$ajaxExtraFieldCenter = $ajaxExtraField2 [0];

						if (strpos($ajaxExtraFieldCenter, "{") === false)
						{
							$countNoUserField = 0;
						}
					}
					else
					{
						$countNoUserField = 0;
					}
				}
			}

			if ($productQuantity)
			{
				$quantity = $productQuantity;
			}
			else
			{
				if ($giftcardId != 0)
				{
					$quantity = 1;
				}
				elseif ($product->min_order_product_quantity > 0)
				{
					$quantity = $product->min_order_product_quantity;
				}
				else
				{
					$quantity = 1;
				}
			}

			$addToCartQuantity = '';

			if (strpos($cartForm, "{addtocart_quantity}") !== false)
			{
				$addToCartQuantity = "<span id='stockQuantity" . $stockId
					. "'><input class='quantity inputbox input-mini' type='text' name='quantity' id='quantity" . $productId . "' value='" . $quantity
					. "' maxlength='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY')
					. "' onblur='validateInputNumber(this.id);' onkeypress='return event.keyCode!=13'></span>";
				$cartForm          = str_replace("{addtocart_quantity}", $addToCartQuantity, $cartForm);
				$cartForm          = str_replace("{quantity_lbl}", \JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartForm);
			}
			elseif (strpos($cartForm, "{addtocart_quantity_increase_decrease}") !== false)
			{
				$addToCartQuantity .= '<input class="quantity" type="text"  id="quantity' . $productId
					. '" name="quantity" size="1"  value="' . $quantity . '" onkeypress="return event.keyCode!=13"/>';
				$addToCartQuantity .= '<input type="button" class="myupbutton" onClick="quantity' . $productId
					. '.value = (+quantity' . $productId . '.value+1)">';
				$addToCartQuantity .= '<input type="button" class="mydownbutton" onClick="quantity' . $productId
					. '.value = (quantity' . $productId . '.value); var qty1 = quantity' . $productId
					. '.value; if( !isNaN( qty1 ) &amp;&amp; qty1 > 1 ) quantity' . $productId . '.value--;return false;">';
				$addToCartQuantity .= '<input type="hidden" name="product_id" value="' . $productId . '">
				<input type="hidden" name="cart_index" value="' . $cartId . '">
				<input type="hidden" name="Itemid" value="' . $itemId . '">
				<input type="hidden" name="task" value="">';

				$cartForm = str_replace("{addtocart_quantity_increase_decrease}", $addToCartQuantity, $cartForm);
				$cartForm = str_replace("{quantity_lbl}", \JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartForm);
			}
			elseif (strpos($cartForm, "{addtocart_quantity_selectbox}") !== false)
			{
				$addToCartQuantity = "<input class='quantity_select' type='hidden' name='quantity' id='quantity" . $productId . "'"
					. " value='" . $quantity . "' maxlength='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'"
					. " size='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'>";

				if ((\Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE') != "" && $product->quantity_selectbox_value == '')
					|| $product->quantity_selectbox_value != '')
				{
					$selectBoxValue = ($product->quantity_selectbox_value) ?
						$product->quantity_selectbox_value : \Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE');
					$quantityBoxes  = explode(",", $selectBoxValue);
					$quantityBoxes  = array_merge(array(), array_unique($quantityBoxes));
					sort($quantityBoxes);
					$quantityComboBox = "<select name='quantity' id='quantity" . $productId
						. "'  OnChange='calculateTotalPrice(" . $productId . "," . $relatedProductId . ");'>";

					foreach ($quantityBoxes as $quantityBox)
					{
						if (intVal($quantityBox) && intVal($quantityBox) != 0)
						{
							$quantityselect    = ($quantity == intval($quantityBox)) ? "selected" : "";
							$quantityComboBox .= "<option value='" . intVal($quantityBox) . "' " . $quantityselect . ">"
								. intVal($quantityBox) . "</option>";
						}
					}

					$quantityComboBox .= "</select>";
					$addToCartQuantity = "<span id='stockQuantity" . $stockId . "'>" . $quantityComboBox . "</span>";
				}

				$cartForm = str_replace("{addtocart_quantity_selectbox}", $addToCartQuantity, $cartForm);
				$cartForm = str_replace("{quantity_lbl}", \JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartForm);
			}
			else
			{
				$cartForm .= "<input class='quantity_select' type='hidden' name='quantity' id='quantity" . $productId . "' value='"
					. $quantity . "' maxlength='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'"
					. " size='" . \Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'>";
			}

			$stockStyle    = '';
			$cartStyle     = '';
			$preOrderStyle = '';

			if ($preOrderDisplay)
			{
				$stockStyle    = 'style="display:none"';
				$cartStyle     = 'style="display:none"';
				$preOrderStyle = '';

				if (\Redshop::getConfig()->get('USE_AS_CATALOG'))
				{
					$preOrderStyle = 'style="display:none"';

				}
			}

			if ($stockDisplay)
			{
				$stockStyle = '';

				if (\Redshop::getConfig()->get('USE_AS_CATALOG'))
				{
					$stockStyle = 'style="display:none"';

				}

				$cartStyle     = 'style="display:none"';
				$preOrderStyle = 'style="display:none"';

			}

			if ($cartDisplay)
			{
				$stockStyle    = 'style="display:none"';
				$cartStyle     = '';
				$preOrderStyle = 'style="display:none"';

				if (\Redshop::getConfig()->get('USE_AS_CATALOG') || \RedshopHelperUser::getShopperGroupData($userId)->use_as_catalog == 'yes')
				{
					$cartStyle = 'style="display:none"';

				}
			}

			$cartTag   = '';
			$cartIcon  = '';
			$cartTitle = ' title="' . $requestTooltip . '" ';

			// Trigger event which hepl us to add new JS functions to the Add To Cart button onclick
			$addToCartClickJS = \RedshopHelperUtility::getDispatcher()->trigger('onAddToCartClickJS', array($product, $cart));

			if (!empty($addToCartClickJS))
			{
				$addToCartClickJS = implode('', $addToCartClickJS);
			}
			else
			{
				$addToCartClickJS = "";
			}

			if ($giftcardId)
			{
				$onclick = ' onclick="' . $addToCartClickJS . 'if(validateEmail()){if(displayAddtocartForm(\'' .
					$cartFromName . '\',\'' .
					$productId . '\',\'' .
					$relatedProductId . '\',\'' .
					$giftcardId . '\', \'user_fields_form\')){checkAddtocartValidation(\'' .
					$cartFromName . '\',\'' .
					$productId . '\',\'' .
					$relatedProductId . '\',\'' .
					$giftcardId . '\', \'user_fields_form\',\'' .
					$totalAttr . '\',\'' .
					$totalAccessory . '\',\'' .
					$countNoUserField . '\');}}" ';
			}
			else
			{
				$onclick = ' onclick="' . $addToCartClickJS . 'if(displayAddtocartForm(\'' . $cartFromName . '\',\'' . $productId
					. '\',\'' . $relatedProductId . '\',\'' . $giftcardId
					. '\', \'user_fields_form\')){checkAddtocartValidation(\'' . $cartFromName . '\',\''
					. $productId . '\',\'' . $relatedProductId . '\',\'' . $giftcardId . '\', \'user_fields_form\',\''
					. $totalAttr . '\',\'' . $totalAccessory . '\',\'' . $countNoUserField . '\');}" ';
			}

			$class = '';
			$title = '';

			if (strpos($cartForm, "{addtocart_tooltip}") !== false)
			{
				$class    = 'class="editlinktip hasTip"';
				$title    = ' title="' . $tooltip . '" ';
				$cartForm = str_replace("{addtocart_tooltip}", "", $cartForm);
			}

			if (strpos($cartForm, "{addtocart_button}") !== false)
			{
				$cartTag = "{addtocart_button}";

				if (\Redshop::getConfig()->get('AJAX_CART_BOX') != 1)
				{
					$cartIcon = '<span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . ' ' . $cartStyle
						. ' class="pdaddtocart"><input type="button" ' . $onclick . $cartTitle . ' name="addtocart_button" value="'
						. $requestLabel . '" /></span>';
				}
				else
				{
					$cartIcon = '<a class="ajaxcartcolorbox' . $productId . '"  href="javascript:;" ' . $onclick
						. ' ><span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . ' ' . $cartStyle
						. ' class="pdaddtocart"><input type="button" ' . $cartTitle . ' name="addtocart_button" value="' . $requestLabel
						. '" /></span></a>';
				}
			}

			if (strpos($cartForm, "{addtocart_link}") !== false)
			{
				$cartTag = "{addtocart_link}";

				if (\Redshop::getConfig()->get('AJAX_CART_BOX') != 1)
				{
					$cartIcon = '<span ' . $class . ' ' . $title . ' ' . $cartStyle . ' id="pdaddtocart' . $stockId . '" '
						. $onclick . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_link btn btn-primary">' . $requestLabel . '</span>';
				}
				else
				{
					$cartIcon = '<a class="ajaxcartcolorbox' . $productId . '"  href="javascript:;" ' . $onclick
						. ' ><span ' . $class . ' ' . $title . ' ' . $cartStyle . ' id="pdaddtocart' . $stockId
						. '" ' . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_link btn btn-primary">' . $requestLabel . '</span></a>';
				}
			}

			if (strpos($cartForm, "{addtocart_image_aslink}") !== false)
			{
				$cartTag = "{addtocart_image_aslink}";

				if (\Redshop::getConfig()->get('AJAX_CART_BOX') != 1)
				{
					if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $requestImage))
					{
						$cartIcon = '<span ' . $class . ' ' . $title . ' ' . $cartStyle . ' id="pdaddtocart' . $stockId
							. '" class="pdaddtocart_img_link"><img ' . $onclick . $cartTitle
							. ' alt="' . $requestLabel . '" style="cursor: pointer;"'
							. ' src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $requestImage . '" /></span>';
					}
					else
					{
						$cartIcon = '<a class="ajaxcartcolorbox' . $productId . '"  href="javascript:;" ' . $onclick . '>'
							. '<span ' . $class . ' ' . $title . ' ' . $cartStyle . ' id="pdaddtocart' . $stockId . '" '
							. $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_img_link">' . $requestLabel . '</span></a>';
					}
				}
				else
				{
					if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $requestImage))
					{
						$cartIcon = '<a class="ajaxcartcolorbox' . $productId . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartStyle . ' id="pdaddtocart' . $stockId
							. '" class="pdaddtocart_img_link"><img ' . $cartTitle . ' alt="' . $requestLabel . '" style="cursor: pointer;" src="'
							. REDSHOP_FRONT_IMAGES_ABSPATH . $requestImage . '" /></span></a>';
					}
					else
					{
						$cartIcon = '<a class="ajaxcartcolorbox' . $productId . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartStyle . ' id="pdaddtocart' . $stockId
							. '" ' . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_img_link">' . $requestLabel . '</span></a>';
					}
				}

			}

			if (strpos($cartForm, "{addtocart_image}") !== false)
			{
				$cartTag = "{addtocart_image}";

				if (\Redshop::getConfig()->get('AJAX_CART_BOX') != 1)
				{
					if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $requestBackground))
					{
						$cartIcon = '<span ' . $class . ' ' . $title . ' ' . $cartStyle . ' id="pdaddtocart' . $stockId
							. '" class="pdaddtocart_imgage"><div ' . $onclick . $cartTitle
							. ' align="center" style="cursor:pointer;background:url(' . REDSHOP_FRONT_IMAGES_ABSPATH
							. $requestBackground . ');background-position:bottom;background-repeat:no-repeat;">'
							. $requestLabel . '</div></span>';
					}
					else
					{
						$cartIcon = '<a class="ajaxcartcolorbox' . $productId . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartStyle . ' id="pdaddtocart' . $stockId
							. '" ' . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_imgage">' . $requestLabel . '</span></a>';
					}
				}
				else
				{
					if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $requestBackground))
					{
						$cartIcon = '<a class="ajaxcartcolorbox' . $productId . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartStyle . ' id="pdaddtocart' . $stockId
							. '" class="pdaddtocart_imgage"><div ' . $cartTitle . ' align="center" style="cursor:pointer;background:url('
							. REDSHOP_FRONT_IMAGES_ABSPATH . $requestBackground
							. ');background-position:bottom;background-repeat:no-repeat;">' . $requestLabel . '</div></span></a>';
					}
					else
					{
						$cartIcon = '<a class="ajaxcartcolorbox' . $productId . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartStyle . ' id="pdaddtocart' . $stockId
							. '" ' . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_imgage">' . $requestLabel . '</span></a>';
					}
				}
			}

			// Pre-Order
			if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $preOrderImage))
			{
				$cartIconPreorder = '<span class="preordercart_order" id="preordercart' . $stockId . '" ' . $preOrderStyle . '><img ' . $onclick
					. $cartTitle . ' alt="' . $preOrderLabel . '" style="cursor: pointer;" src="'
					. REDSHOP_FRONT_IMAGES_ABSPATH . $preOrderImage . '" /></span>';
			}
			else
			{
				$cartIconPreorder = '<span class="preordercart_order_m" id="preordercart' . $stockId . '" ' . $preOrderStyle
					. '><a href="javascript:;" ' . $onclick . '>' . \JText::_('COM_REDSHOP_PREORDER_BTN') . '</a></span>';
			}

			$cartForm = str_replace(
				$cartTag,
				'<span class="stockaddtocart" id="stockaddtocart' . $stockId . '" ' . $stockStyle
				. '>' . $displayText . '</span>' . $cartIconPreorder . $cartIcon,
				$cartForm
			);

			// Trigger event on Add to Cart
			\RedshopHelperUtility::getDispatcher()->trigger('onAddtoCart', array(&$cartForm, $product, $cartFromName, 0));

			$cartForm .= "</form>";

			$content = str_replace("{form_addtocart:$cartTemplate->name}", $cartForm, $content);
		}

		return $content;
	}
}
