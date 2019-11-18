<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */
defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Cart - Tag replacer
 *
 * @since  2.0.7
 */
class RedshopHelperCartTag
{
	/**
	 * @param   string $template Template
	 * @param   string $beginTag Begin tag
	 * @param   string $closeTag Close tag
	 *
	 * @return  boolean
	 *
	 * @since   2.0.7
	 */
	public static function isBlockTagExists($template, $beginTag, $closeTag)
	{
		return (strpos($template, $beginTag) !== false && strpos($template, $closeTag) !== false);
	}

	/**
	 * replace Conditional tag from Redshop tax
	 *
	 * @param   string $template      Template
	 * @param   int    $amount        Amount
	 * @param   int    $discount      Discount
	 * @param   int    $check         Check
	 * @param   int    $quotationMode Quotation mode
	 *
	 * @return  string
	 * @since   2.0.7
	 */
	public static function replaceTax($template = '', $amount = 0, $discount = 0, $check = 0, $quotationMode = 0)
	{
		if (!self::isBlockTagExists($template, '{if vat}', '{vat end if}'))
		{
			return $template;
		}

		$cart = RedshopHelperCartSession::getCart();

		if ($amount <= 0)
		{
			$templateVatSdata = explode('{if vat}', $template);
			$templateVatEdata = explode('{vat end if}', $templateVatSdata[1]);
			$template         = $templateVatSdata[0] . $templateVatEdata[1];

			return $template;
		}

		if ($quotationMode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
		{
			$template = str_replace("{tax}", "", $template);
			$template = str_replace("{order_tax}", "", $template);
		}
		else
		{
			$template = str_replace("{tax}", RedshopHelperProductPrice::formattedPrice($amount, true), $template);
			$template = str_replace("{order_tax}", RedshopHelperProductPrice::formattedPrice($amount, true), $template);
		}

		if (strpos($template, '{tax_after_discount}') !== false)
		{
			if (Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') && (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
			{
				if ($check)
				{
					$taxAfterDiscount = $discount;
				}
				else
				{
					if (!isset($cart['tax_after_discount']))
					{
						$taxAfterDiscount = RedshopHelperCart::calculateTaxAfterDiscount($amount, $discount);
					}
					else
					{
						$taxAfterDiscount = $cart['tax_after_discount'];
					}
				}

				if ($taxAfterDiscount > 0)
				{
					$template = str_replace("{tax_after_discount}", RedshopHelperProductPrice::formattedPrice($taxAfterDiscount), $template);
				}
				else
				{
					$template = str_replace("{tax_after_discount}", RedshopHelperProductPrice::formattedPrice($cart['tax']), $template);
				}
			}
			else
			{
				$template = str_replace("{tax_after_discount}", RedshopHelperProductPrice::formattedPrice($cart['tax']), $template);
			}
		}

		$template = str_replace("{vat_lbl}", JText::_('COM_REDSHOP_CHECKOUT_VAT_LBL'), $template);
		$template = str_replace("{if vat}", '', $template);
		$template = str_replace("{vat end if}", '', $template);

		return $template;
	}

	/**
	 * @param   string $template      Template
	 * @param   int    $discount      Discount
	 * @param   int    $subTotal      Subtotal
	 * @param   int    $quotationMode Quotation mode
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replaceDiscount($template = '', $discount = 0, $subTotal = 0, $quotationMode = 0)
	{
		if (!self::isBlockTagExists($template, '{if discount}', '{discount end if}'))
		{
			return $template;
		}

		$percentage = '';

		if ($discount <= 0)
		{
			$templateDiscountSdata = explode('{if discount}', $template);
			$templateDiscountEdata = explode('{discount end if}', $templateDiscountSdata[1]);
			$template              = $templateDiscountSdata[0] . $templateDiscountEdata[1];
		}
		else
		{
			$template = str_replace("{if discount}", '', $template);

			if ($quotationMode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
			{
				$template = str_replace("{discount}", "", $template);
				$template = str_replace("{discount_in_percentage}", $percentage, $template);
			}
			else
			{
				$template = str_replace("{discount}", RedshopHelperProductPrice::formattedPrice($discount, true), $template);
				$template = str_replace("{order_discount}", RedshopHelperProductPrice::formattedPrice($discount, true), $template);

				if (!empty($subTotal) && $subTotal > 0)
				{
					$percentage = round(($discount * 100 / $subTotal), 2) . " %";
				}

				$template = str_replace("{discount_in_percentage}", $percentage, $template);
			}

			$template = str_replace("{discount_lbl}", JText::_('COM_REDSHOP_CHECKOUT_DISCOUNT_LBL'), $template);
			$template = str_replace("{discount end if}", '', $template);
		}

		return $template;
	}

	/**
	 * Method for replace cart item
	 *
	 * @param   string  $data            Template Html
	 * @param   array   $cart            Cart data
	 * @param   boolean $isReplaceButton Is replace button?
	 * @param   integer $quotationMode   Is in Quotation Mode
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public static function replaceCartItem($data, $cart = array(), $isReplaceButton = false, $quotationMode = 0)
	{
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		$input  = JFactory::getApplication()->input;
		$itemId = RedshopHelperRouter::getCheckoutItemId();
		$view   = $input->getCmd('view');

		if ($itemId == 0)
		{
			$itemId = $input->getInt('Itemid');
		}

		$cartResponse = '';
		$idx          = $cart['idx'];
		$fieldArray   = RedshopHelperExtrafields::getSectionFieldList(
			RedshopHelperExtrafields::SECTION_PRODUCT_FINDER_DATE_PICKER, 0, 0
		);

		if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_DELETE')))
		{
			$deleteImg = Redshop::getConfig()->get('ADDTOCART_DELETE');
		}
		else
		{
			$deleteImg = "defaultcross.png";
		}

		for ($i = 0; $i < $idx; $i++)
		{
			$cartHtml = $data;

			// Plugin support: Process the product plugin for cart item
			$dispatcher->trigger('onCartItemDisplay', array(&$cartHtml, $cart, $i));

			$quantity = $cart[$i]['quantity'];

			if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'])
			{
				$giftCardId    = $cart[$i]['giftcard_id'];
				$giftcard      = RedshopEntityGiftcard::getInstance($giftCardId)->getItem();
				$link          = JRoute::_('index.php?option=com_redshop&view=giftcard&gid=' . $giftCardId . '&Itemid=' . $itemId);
				$receiverInfor = '<div class="reciverInfo">' . JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_NAME_LBL') . ': ' . $cart[$i]['reciver_name']
					. '<br />' . JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_EMAIL_LBL') . ': ' . $cart[$i]['reciver_email'] . '</div>';
				$productName   = "<div  class='product_name'><a href='" . $link . "'>" . $giftcard->giftcard_name . "</a></div>" . $receiverInfor;

				if (strpos($cartHtml, "{product_name_nolink}") !== false)
				{
					$productNameNoLink = "<div  class=\"product_name\">" . $giftcard->giftcard_name . "</div><" . $receiverInfor;
					$cartHtml          = str_replace("{product_name_nolink}", $productNameNoLink, $cartHtml);

					if (strpos($cartHtml, "{product_name}") !== false)
					{
						$cartHtml = str_replace("{product_name}", "", $cartHtml);
					}
				}
				else
				{
					$cartHtml = str_replace("{product_name}", $productName, $cartHtml);
				}

				$cartHtml = str_replace("{product_attribute}", '', $cartHtml);
				$cartHtml = str_replace("{product_accessory}", '', $cartHtml);
				$cartHtml = str_replace("{product_wrapper}", '', $cartHtml);
				$cartHtml = str_replace("{product_old_price}", '', $cartHtml);
				$cartHtml = str_replace("{vat_info}", '', $cartHtml);
				$cartHtml = str_replace("{product_number_lbl}", '', $cartHtml);
				$cartHtml = str_replace("{product_number}", '', $cartHtml);
				$cartHtml = str_replace("{attribute_price_without_vat}", '', $cartHtml);
				$cartHtml = str_replace("{attribute_price_with_vat}", '', $cartHtml);

				if ($quotationMode && !Redshop::getConfig()->getBool('SHOW_QUOTATION_PRICE'))
				{
					$cartHtml = str_replace("{product_total_price}", "", $cartHtml);
					$cartHtml = str_replace("{product_price}", "", $cartHtml);
				}
				else
				{
					$cartHtml = str_replace("{product_price}", RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price']), $cartHtml);
					$cartHtml = str_replace(
						"{product_total_price}",
						RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price'] * $cart[$i]['quantity'], true),
						$cartHtml
					);
				}

				$cartHtml = str_replace("{if product_on_sale}", '', $cartHtml);
				$cartHtml = str_replace("{product_on_sale end if}", '', $cartHtml);

				$thumbUrl = RedshopHelperMedia::getImagePath(
					$giftcard->giftcard_image,
					'',
					'thumb',
					'giftcard',
					Redshop::getConfig()->get('CART_THUMB_WIDTH'),
					Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);

				$giftCardImage = "&nbsp;";

				if ($thumbUrl)
				{
					$giftCardImage = "<div  class='giftcard_image'><img src='" . $thumbUrl . "'></div>";
				}

				$cartHtml          = str_replace("{product_thumb_image}", $giftCardImage, $cartHtml);
				$productUserFields = productHelper::getInstance()->GetProdcutUserfield($i, 13);
				$cartHtml          = str_replace("{product_userfields}", $productUserFields, $cartHtml);
				$cartHtml          = str_replace(
					"{product_price_excl_vat}",
					RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price']),
					$cartHtml
				);
				$cartHtml          = str_replace(
					"{product_total_price_excl_vat}",
					RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price'] * $cart[$i]['quantity']),
					$cartHtml
				);
				$cartHtml          = str_replace("{attribute_change}", '', $cartHtml);
				$cartHtml          = str_replace("{product_attribute_price}", "", $cartHtml);
				$cartHtml          = str_replace("{product_attribute_number}", "", $cartHtml);
				$cartHtml          = str_replace("{product_tax}", "", $cartHtml);

				// ProductFinderDatepicker Extra Field
				$cartHtml = productHelper::getInstance()->getProductFinderDatepickerValue($cartHtml, $giftCardId, $fieldArray, 1);

				$removeProduct = '<form style="" class="rs_hiddenupdatecart" name="delete_cart' . $i . '" method="POST" >
				<input type="hidden" name="giftcard_id" value="' . $cart[$i]['giftcard_id'] . '">
				<input type="hidden" name="cart_index" value="' . $i . '">
				<input type="hidden" name="task" value="">
				<input type="hidden" name="Itemid" value="' . $itemId . '">
				<img class="delete_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $deleteImg
					. '" title="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL')
					. '" alt="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL')
					. '" onclick="document.delete_cart' . $i . '.task.value=\'delete\';document.delete_cart' . $i . '.submit();"></form>';

				if (Redshop::getConfig()->getBool('QUANTITY_TEXT_DISPLAY'))
				{
					$cartHtml = str_replace("{remove_product}", $removeProduct, $cartHtml);
				}
				else
				{
					$cartHtml = str_replace("{remove_product}", $removeProduct, $cartHtml);
				}

				// Replace attribute tags to empty on giftcard
				if (strpos($cartHtml, "{product_attribute_loop_start}") !== false && strpos($cartHtml, "{product_attribute_loop_end}") !== false)
				{
					$templateAttrStart  = explode('{product_attribute_loop_start}', $cartHtml);
					$templateAttrEnd    = explode('{product_attribute_loop_end}', $templateAttrStart[1]);
					$templateAttrMiddle = $templateAttrEnd[0];

					$cartHtml = str_replace($templateAttrMiddle, "", $cartHtml);
				}

				$cartItem = 'giftCardId';
			}
			else
			{
				$productId     = $cart[$i]['product_id'];
				$product       = RedshopHelperProduct::getProductById($productId);
				$catId         = $product->cat_in_sefurl;
				$attributeCart = productHelper::getInstance()->makeAttributeCart(
					$cart[$i]['cart_attribute'], $productId, 0, 0, $quantity, $cartHtml
				);
				$cartAttribute = $attributeCart[0];
				$retAccArr     = productHelper::getInstance()->makeAccessoryCart($cart [$i] ['cart_accessory'], $productId, $cartHtml);
				$cartAccessory = $retAccArr[0];

				$itemData = productHelper::getInstance()->getMenuInformation(0, 0, '', 'product&pid=' . $productId);

				if (!empty($itemData))
				{
					$itemId = $itemData->id;
				}
				else
				{
					$itemId = RedshopHelperUtility::getCategoryItemid($catId);
				}

				$link = JRoute::_('index.php?option=com_redshop&view=product&cid='. $catId .'&pid=' . $productId . '&Itemid=' . $itemId);

				// Trigger to change product link.
				$dispatcher->trigger('onSetCartOrderItemProductLink', array(&$cart, &$link, $product, $i));

				$productName  = "<div  class='product_name'><a href='" . $link . "'>" . $product->product_name . "</a></div>";
				$productImage = "";
				$productImg   = '';
				$type         = 'product';

				if (Redshop::getConfig()->get('WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART') && isset($cart[$i]['hidden_attribute_cartimage']))
				{
					$imagePath    = REDSHOP_FRONT_IMAGES_ABSPATH;
					$productImage = str_replace($imagePath, '', $cart[$i]['hidden_attribute_cartimage']);
				}

				if ($productImage && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $productImage))
				{
					$val        = explode("/", $productImage);
					$productImg = $val[1];
					$type       = $val[0];
				}
				elseif ($product->product_full_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
				{
					$productImg = $product->product_full_image;
					$type       = 'product';
				}
				elseif (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
				{
					$productImg = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
					$type       = 'product';
				}

				$isAttributeImage = false;

				if (isset($cart[$i]['attributeImage']))
				{
					$isAttributeImage = JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $cart[$i]['attributeImage']);
				}

				if ($isAttributeImage)
				{
					$productImg = $cart[$i]['attributeImage'];
					$type       = 'mergeImages';
				}

				if ($productImg !== '')
				{
					if (Redshop::getConfig()->getBool('WATERMARK_CART_THUMB_IMAGE')
						&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('WATERMARK_IMAGE')))
					{
						$productCartImg = RedshopHelperMedia::watermark(
							$type,
							$productImg,
							Redshop::getConfig()->getInt('CART_THUMB_WIDTH'),
							Redshop::getConfig()->getInt('CART_THUMB_HEIGHT'),
							Redshop::getConfig()->get('WATERMARK_CART_THUMB_IMAGE')
						);

						$productImage = "<div  class='product_image'><a href='" . $link . "'><img src='" . $productCartImg . "'></a></div>";
					}
					else
					{
						$thumbUrl = RedshopHelperMedia::getImagePath(
							$productImg,
							'',
							'thumb',
							$type,
							Redshop::getConfig()->getInt('CART_THUMB_WIDTH'),
							Redshop::getConfig()->getInt('CART_THUMB_HEIGHT'),
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);

						$productImage = "<div  class='product_image'><a href='" . $link . "'><img src='" . $thumbUrl . "'></a></div>";
					}
				}
				else
				{
					$productImage = "<div  class='product_image'></div>";
				}

				// Trigger to change product image.
				$dispatcher->trigger('onSetCartOrderItemImage', array(&$cart, &$productImage, $product, $i));

				$isApplyVAT        = \Redshop\Template\Helper::isApplyVat($data);
				$productTotalPrice = "<div class='product_price'>";

				if (!$quotationMode || ($quotationMode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					if (!$isApplyVAT)
					{
						$productTotalPrice .= RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price_excl_vat'] * $quantity);
					}
					else
					{
						$productTotalPrice .= RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price'] * $quantity);
					}
				}

				$productTotalPrice .= "</div>";

				$productOldPrice         = "";
				$productOldPriceNoFormat = '';
				$productPrice            = "<div class='product_price'>";

				if (!$quotationMode || ($quotationMode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					if (!$isApplyVAT)
					{
						$productPrice .= RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price_excl_vat'], true);
					}
					else
					{
						$productPrice .= RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price'], true);
					}

					if (isset($cart[$i]['product_old_price']))
					{
						$productOldPrice = $cart[$i]['product_old_price'];

						if (!$isApplyVAT)
						{
							$productOldPrice = $cart[$i]['product_old_price_excl_vat'];
						}

						// Set Product Old Price without format
						$productOldPriceNoFormat = $productOldPrice;

						$productOldPrice = RedshopHelperProductPrice::formattedPrice($productOldPrice, true);
					}
				}

				$productPrice .= "</div>";
				$wrapperName  = "";

				if ((array_key_exists('wrapper_id', $cart[$i])) && $cart[$i]['wrapper_id'])
				{
					$wrapper = productHelper::getInstance()->getWrapper($productId, $cart[$i]['wrapper_id']);

					if (count($wrapper) > 0)
					{
						$wrapperName = JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper[0]->wrapper_name;

						if (!$quotationMode || ($quotationMode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
						{
							$wrapperName .= "(" . RedshopHelperProductPrice::formattedPrice($cart[$i]['wrapper_price'], true) . ")";
						}
					}
				}

				if (strpos($cartHtml, "{product_name_nolink}") !== false)
				{
					$productNameNoLink = "<div  class='product_name'>$product->product_name</a></div>";
					$cartHtml          = str_replace("{product_name_nolink}", $productNameNoLink, $cartHtml);

					if (strpos($cartHtml, "{product_name}") !== false)
					{
						$cartHtml = str_replace("{product_name}", "", $cartHtml);
					}
				}
				else
				{
					$cartHtml = str_replace("{product_name}", $productName, $cartHtml);
				}

				$cartHtml = str_replace("{product_s_desc}", $product->product_s_desc, $cartHtml);

				// Replace Attribute data
				if (strpos($cartHtml, "{product_attribute_loop_start}") !== false && strpos($cartHtml, "{product_attribute_loop_end}") !== false)
				{
					$templateAttrStart  = explode('{product_attribute_loop_start}', $cartHtml);
					$templateAttrEnd    = explode('{product_attribute_loop_end}', $templateAttrStart[1]);
					$templateAttrMiddle = $templateAttrEnd[0];
					$productDetail      = '';
					$sumTotal           = count($cart[$i]['cart_attribute']);
					$tempAttribute      = $cart[$i]['cart_attribute'];

					if ($sumTotal > 0)
					{
						$propertyCalculatedPriceSum = $productOldPriceNoFormat;

						for ($tpi = 0; $tpi < $sumTotal; $tpi++)
						{
							$productAttributeValue      = "";
							$productAttributeValuePrice = "";
							$productAttributeName       = $tempAttribute[$tpi]['attribute_name'];

							$productAttributeCalculatedPrice = '';

							if (count($tempAttribute[$tpi]['attribute_childs']) > 0)
							{
								$productAttributeValue = ": " . $tempAttribute[$tpi]['attribute_childs'][0]['property_name'];

								if (count($tempAttribute[$tpi]['attribute_childs'][0]['property_childs']) > 0)
								{
									$productAttributeValue .= ": "
										. $tempAttribute[$tpi]['attribute_childs'][0]['property_childs'][0]['subattribute_color_title']
										. ": " . $tempAttribute[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_name'];
								}

								$productAttributeValuePrice = $tempAttribute[$tpi]['attribute_childs'][0]['property_price'];
								$propertyOperand            = $tempAttribute[$tpi]['attribute_childs'][0]['property_oprand'];

								if (count($tempAttribute[$tpi]['attribute_childs'][0]['property_childs']) > 0)
								{
									$productAttributeValuePrice = $productAttributeValuePrice
										+ $tempAttribute[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_price'];

									$propertyOperand = $tempAttribute[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_oprand'];
								}

								// Show actual productive price
								if ($productAttributeValuePrice > 0)
								{
									$productAttributeCalculatedPriceBase = RedshopHelperUtility::setOperandForValues(
										$propertyCalculatedPriceSum, $propertyOperand, $productAttributeValuePrice
									);

									$productAttributeCalculatedPrice = $productAttributeCalculatedPriceBase - $propertyCalculatedPriceSum;
									$propertyCalculatedPriceSum      = $productAttributeCalculatedPriceBase;
								}

								$productAttributeValuePrice = RedshopHelperProductPrice::formattedPrice((double) $productAttributeValuePrice);
							}

							$productAttributeCalculatedPrice = RedshopHelperProductPrice::formattedPrice((double) $productAttributeCalculatedPrice);
							$productAttributeCalculatedPrice = JText::sprintf(
								'COM_REDSHOP_CART_PRODUCT_ATTRIBUTE_CALCULATED_PRICE',
								$productAttributeCalculatedPrice
							);

							$productHtml = $templateAttrMiddle;
							$productHtml = str_replace("{product_attribute_name}", $productAttributeName, $productHtml);
							$productHtml = str_replace("{product_attribute_value}", $productAttributeValue, $productHtml);
							$productHtml = str_replace("{product_attribute_value_price}", $productAttributeValuePrice, $productHtml);
							$productHtml = str_replace(
								"{product_attribute_calculated_price}",
								$productAttributeCalculatedPrice,
								$productHtml
							);

							$productDetail .= $productHtml;
						}
					}

					$cartHtml = str_replace($templateAttrMiddle, $productDetail, $cartHtml);
				}

				if (count($cart [$i] ['cart_attribute']) > 0)
				{
					$cartHtml = str_replace("{attribute_label}", JText::_("COM_REDSHOP_ATTRIBUTE"), $cartHtml);
				}
				else
				{
					$cartHtml = str_replace("{attribute_label}", "", $cartHtml);
				}

				$cartHtml           = str_replace("{product_number}", $product->product_number, $cartHtml);
				$cartHtml           = str_replace("{product_vat}", $cart[$i]['product_vat'] * $cart[$i]['quantity'], $cartHtml);
				$productUserFields  = productHelper::getInstance()->GetProdcutUserfield($i);
				$cartHtml           = str_replace("{product_userfields}", $productUserFields, $cartHtml);
				$productFields      = productHelper::getInstance()->GetProdcutfield($i);
				$cartHtml           = str_replace("{product_customfields}", $productFields, $cartHtml);
				$cartHtml           = str_replace("{product_customfields_lbl}", JText::_("COM_REDSHOP_PRODUCT_CUSTOM_FIELD"), $cartHtml);
				$discountCalcOutput = isset($cart[$i]['discount_calc_output']) && $cart[$i]['discount_calc_output']
					? $cart[$i]['discount_calc_output'] . "<br />" : "";

				$cartHtml = RedshopTagsReplacer::_(
					'attribute',
					$cartHtml,
					array(
						'product_attribute' => $discountCalcOutput . $cartAttribute,
					)
				);

				$cartHtml = str_replace("{product_accessory}", $cartAccessory, $cartHtml);
				$cartHtml = str_replace("{product_attribute_price}", "", $cartHtml);
				$cartHtml = str_replace("{product_attribute_number}", "", $cartHtml);
				$cartHtml = productHelper::getInstance()->getProductOnSaleComment($product, $cartHtml);
				$cartHtml = str_replace("{product_old_price}", $productOldPrice, $cartHtml);
				$cartHtml = str_replace("{product_wrapper}", $wrapperName, $cartHtml);
				$cartHtml = str_replace("{product_thumb_image}", $productImage, $cartHtml);
				$cartHtml = str_replace("{attribute_price_without_vat}", '', $cartHtml);
				$cartHtml = str_replace("{attribute_price_with_vat}", '', $cartHtml);

				// ProductFinderDatepicker Extra Field Start
				$cartHtml = productHelper::getInstance()->getProductFinderDatepickerValue($cartHtml, $productId, $fieldArray);

				$productPriceNoVAT = $cart[$i]['product_price_excl_vat'];

				if (!$quotationMode || ($quotationMode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					$cartHtml = str_replace(
						"{product_price_excl_vat}",
						RedshopHelperProductPrice::formattedPrice($productPriceNoVAT),
						$cartHtml
					);

					$cartHtml = str_replace(
						"{product_total_price_excl_vat}",
						RedshopHelperProductPrice::formattedPrice($productPriceNoVAT * $quantity),
						$cartHtml
					);
				}
				else
				{
					$cartHtml = str_replace("{product_price_excl_vat}", "", $cartHtml);
					$cartHtml = str_replace("{product_total_price_excl_vat}", "", $cartHtml);
				}

				if ($product->product_type == 'subscription')
				{
					$subscriptionDetail   = productHelper::getInstance()->getProductSubscriptionDetail(
						$product->product_id, $cart[$i]['subscription_id']
					);
					$selectedSubscription = $subscriptionDetail->subscription_period . " " . $subscriptionDetail->period_type;
					$cartHtml             = str_replace("{product_subscription_lbl}", JText::_('COM_REDSHOP_SUBSCRIPTION'), $cartHtml);
					$cartHtml             = str_replace("{product_subscription}", $selectedSubscription, $cartHtml);
				}
				else
				{
					$cartHtml = str_replace("{product_subscription_lbl}", "", $cartHtml);
					$cartHtml = str_replace("{product_subscription}", "", $cartHtml);
				}

				if ($isReplaceButton)
				{
					$updateAttribute = '';

					if ($view == 'cart')
					{
						$attrChange      = JURI::root() . 'index.php?option=com_redshop&view=cart&layout=change_attribute&tmpl=component&pid=' . $productId . '&cart_index=' . $i;
						$updateAttribute = '<a class="modal" rel="{handler: \'iframe\', size: {x: 550, y: 400}}" href="' . $attrChange . '">' . JText::_('COM_REDSHOP_CHANGE_ATTRIBUTE') . '</a>';
					}

					if ($cartAttribute != "")
					{
						$cartHtml = str_replace("{attribute_change}", $updateAttribute, $cartHtml);
					}
					else
					{
						$cartHtml = str_replace("{attribute_change}", "", $cartHtml);
					}
				}
				else
				{
					$cartHtml = str_replace("{attribute_change}", '', $cartHtml);
				}

				// Product extra fields.
				$cartHtml = RedshopHelperProductTag::getExtraSectionTag(
					Redshop\Helper\ExtraFields::getSectionFieldNames(RedshopHelperExtrafields::SECTION_PRODUCT), $productId, "1", $cartHtml
				);

				$cartItem = 'productId';
				$cartHtml = RedshopHelperTax::replaceVatInformation($cartHtml);
				$cartHtml = str_replace("{product_price}", $productPrice, $cartHtml);
				$cartHtml = str_replace("{product_total_price}", $productTotalPrice, $cartHtml);
			}

			if ($isReplaceButton)
			{
				$updateCartNone = '<label>' . $quantity . '</label>';
				$updateImage    = '';

				if ($view == 'checkout')
				{
					$updateCart = $quantity;
				}
				else
				{
					$updateCart = '<form style="padding:0px;margin:0px;" name="update_cart' . $i . '" method="POST" >'
						. '<input class="inputbox input-mini" type="text" value="' . $quantity . '" name="quantity" '
						. 'id="quantitybox' . $i . '" size="' . Redshop::getConfig()->get('DEFAULT_QUANTITY') . '"'
						. ' maxlength="' . Redshop::getConfig()->get('DEFAULT_QUANTITY') . '" onchange="validateInputNumber(this.id);">'
						. '<input type="hidden" name="' . $cartItem . '" value="' . ${$cartItem} . '" />'
						. '<input type="hidden" name="cart_index" value="' . $i . '" />'
						. '<input type="hidden" name="Itemid" value="' . $itemId . '" />'
						. '<input type="hidden" name="task" value="" />';

					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_UPDATE')))
					{
						$updateImage = Redshop::getConfig()->get('ADDTOCART_UPDATE');
					}
					else
					{
						$updateImage = "defaultupdate.png";
					}

					$updateCart .= '<img class="update_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $updateImage
						. '" title="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" alt="'
						. JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL')
						. '" onclick="document.update_cart' . $i . '.task.value=\'update\';document.update_cart'
						. $i . '.submit();">';

					$updateCart .= '</form>';
				}

				$updateCartMinusPlus = '<form name="update_cart' . $i . '" method="POST">';

				$updateCartMinusPlus .= '<input type="text" id="quantitybox' . $i . '" name="quantity"  size="1"'
					. ' value="' . $quantity . '" /><input type="button" id="minus" value="-"'
					. ' onClick="quantity.value = (quantity.value) ; var qty1 = quantity.value; if( !isNaN( qty1 ) &amp;&amp; qty1 > 1 ) quantity.value--;return false;">';

				$updateCartMinusPlus .= '<input type="button" value="+"
						onClick="quantity.value = (+quantity.value+1)"><input type="hidden" name="' . $cartItem . '" value="' . ${$cartItem} . '">
						<input type="hidden" name="cart_index" value="' . $i . '">
						<input type="hidden" name="Itemid" value="' . $itemId . '">
						<input type="hidden" name="task" value=""><img class="update_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $updateImage . '" title="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" onclick="document.update_cart' . $i . '.task.value=\'update\';document.update_cart' . $i . '.submit();">
						</form>';

				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_DELETE')))
				{
					$deleteImg = Redshop::getConfig()->get('ADDTOCART_DELETE');
				}
				else
				{
					$deleteImg = "defaultcross.png";
				}

				if ($view == 'checkout')
				{
					$removeProduct = '';
				}
				else
				{
					$removeProduct = '<form name="delete_cart' . $i . '" method="POST" >
							<input type="hidden" name="' . $cartItem . '" value="' . ${$cartItem} . '">
							<input type="hidden" name="cart_index" value="' . $i . '">
							<input type="hidden" name="task" value="">
							<input type="hidden" name="Itemid" value="' . $itemId . '">
							<img class="delete_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $deleteImg . '" title="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL') . '" onclick="document.delete_cart' . $i . '.task.value=\'delete\';document.delete_cart' . $i . '.submit();"></form>';
				}

				if (Redshop::getConfig()->get('QUANTITY_TEXT_DISPLAY'))
				{
					if (strstr($cartHtml, "{quantity_increase_decrease}") && $view != 'checkout')
					{
						$cartHtml = str_replace("{quantity_increase_decrease}", $updateCartMinusPlus, $cartHtml);
						$cartHtml = str_replace("{update_cart}", '', $cartHtml);
					}
					else
					{
						$cartHtml = str_replace("{quantity_increase_decrease}", $updateCart, $cartHtml);
						$cartHtml = str_replace("{update_cart}", $updateCart, $cartHtml);
					}

					$cartHtml = str_replace("{remove_product}", $removeProduct, $cartHtml);
				}
				else
				{
					$cartHtml = str_replace("{quantity_increase_decrease}", $updateCartMinusPlus, $cartHtml);
					$cartHtml = str_replace("{update_cart}", $updateCartNone, $cartHtml);
					$cartHtml = str_replace("{remove_product}", $removeProduct, $cartHtml);
				}
			}
			else
			{
				$cartHtml = str_replace("{update_cart}", $quantity, $cartHtml);
				$cartHtml = str_replace("{remove_product}", '', $cartHtml);
			}

			$cartResponse .= $cartHtml;
		}

		return $cartResponse;
	}
}
