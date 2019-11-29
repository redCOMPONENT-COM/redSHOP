<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Product Accessory
 *
 * @since  2.1.0
 */
class RedshopHelperProductAccessory
{
	/**
	 * Method for replace accessory data.
	 *
	 * @param   integer $productId           Product ID
	 * @param   integer $relProductId        Related product ID
	 * @param   array   $accessory           Accessories data.
	 * @param   string  $templateContent     Template content
	 * @param   boolean $isChild             True for accessory products is child.
	 * @param   array   $selectedAccessories Selected accessory.
	 *
	 * @return  mixed|string
	 *
	 * @since   2.1.0
	 *
	 * @throws Exception
	 */
	public static function replaceAccessoryData($productId = 0, $relProductId = 0, $accessory = array(), $templateContent = '', $isChild = false, $selectedAccessories = array())
	{
		$userId = 0;

		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		$input   = JFactory::getApplication()->input;
		$viewAcc = $input->get('viewacc', 1);
		$layout  = $input->get('layout');
		$itemId  = $input->get('Itemid');
		$isAjax  = 0;
		$prefix  = "";

		if ($layout == "viewajaxdetail")
		{
			$isAjax = 1;
			$prefix = "ajax_";
		}

		$productId = $relProductId != 0 ? $relProductId : $productId;

		$selectedAccessory    = array();
		$selectedAccessoryQua = array();
		$selectAtt            = array();

		if (count($selectedAccessories) > 0)
		{
			$selectedAccessory    = $selectedAccessories[0];
			$selectedAccessoryQua = $selectedAccessories[3];
			$selectAtt            = array($selectedAccessories[1], $selectedAccessories[2]);
		}

		$product           = RedshopHelperProduct::getProductById($productId);
		$accessoryTemplate = \Redshop\Template\Helper::getAccessory($templateContent);

		if (null === $accessoryTemplate)
		{
			return $templateContent;
		}

		$accessoryTemplateData = $accessoryTemplate->template_desc;
		$attributeTemplate     = (object) \Redshop\Template\Helper::getAttribute($accessoryTemplateData);

		if (empty($accessory))
		{
			$templateContent = str_replace("{accessory_template:" . $accessoryTemplate->name . "}", "", $templateContent);

			return $templateContent;
		}

		$accessoryTemplateData2 = $accessoryTemplateData;
		$productPrices          = array();

		self::replaceMainAccessory($accessoryTemplateData2, $templateContent, $product, $userId);

		$accessoryWrapper = '';

		if (strpos($accessoryTemplateData2, "{accessory_product_start}") !== false
			&& strpos($accessoryTemplateData2, "{accessory_product_end}") !== false)
		{
			$accessoryTemplateData2 = explode('{accessory_product_start}', $accessoryTemplateData2);
			$accessoryWrapperStart  = $accessoryTemplateData2 [0];
			$accessoryTemplateData2 = explode('{accessory_product_end}', $accessoryTemplateData2 [1]);
			$accessoryWrapperEnd    = $accessoryTemplateData2[1];

			$accessoryWrapperMiddle = $accessoryTemplateData2[0];

			for ($a = 0, $an = count($accessory); $a < $an; $a++)
			{
				$accessoryId      = $accessory[$a]->child_product_id;
				$accessoryProduct = RedshopHelperProduct::getProductById($accessoryId);

				$commonId          = $prefix . $productId . '_' . $accessory[$a]->accessory_id;
				$accessoryWrapper .= "<div id='divaccstatus" . $commonId . "' class='accessorystatus'>" . $accessoryWrapperMiddle . "</div>";

				$accessoryProductName = RedshopHelperUtility::maxChars(
					$accessory[$a]->product_name,
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
				);

				$accessoryWrapper = str_replace("{accessory_title}", $accessoryProductName, $accessoryWrapper);
				$accessoryWrapper = str_replace("{product_number}", $accessory[$a]->product_number, $accessoryWrapper);

				$accessoryImage = $accessory[$a]->product_full_image;
				$accessoryImg   = '';

				if (strpos($accessoryWrapper, "{accessory_image_3}") !== false)
				{
					$accessoryImgTag      = '{accessory_image_3}';
					$accessoryHeightThumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_3');
					$accessoryWidthThumb  = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_3');
				}
				elseif (strpos($accessoryWrapper, "{accessory_image_2}") !== false)
				{
					$accessoryImgTag      = '{accessory_image_2}';
					$accessoryHeightThumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_2');
					$accessoryWidthThumb  = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_2');
				}
				elseif (strpos($accessoryWrapper, "{accessory_image_1}") !== false)
				{
					$accessoryImgTag      = '{accessory_image_1}';
					$accessoryHeightThumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
					$accessoryWidthThumb  = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
				}
				else
				{
					$accessoryImgTag      = '{accessory_image}';
					$accessoryHeightThumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
					$accessoryWidthThumb  = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
				}

				$accessoryProductLink = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $accessoryId . '&Itemid=' . $itemId, false);
				$hiddenThumbImage     = "<input type='hidden' name='acc_main_imgwidth' id='acc_main_imgwidth' value='"
					. $accessoryWidthThumb . "'><input type='hidden' name='acc_main_imgheight' id='acc_main_imgheight' value='"
					. $accessoryHeightThumb . "'>";

				// Trigger to change product image.
				$dispatcher->trigger(
					'changeProductImage',
					array(
						&$accessoryImg,
						$accessory[$a],
						$accessoryProductLink,
						$accessoryWidthThumb,
						$accessoryHeightThumb,
						Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX'),
						''
					)
				);

				if (empty($accessoryImg))
				{
					if (Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX') == 1)
					{
						if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessoryImage))
						{
							$thumbUrl = RedshopHelperMedia::getImagePath(
								$accessoryImage,
								'',
								'thumb',
								'product',
								$accessoryWidthThumb,
								$accessoryHeightThumb,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);

							$accessoryImg = "<a id='a_main_image" . $accessory[$a]->accessory_id
								. "' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $accessoryImage
								. "' title='' class=\"modal\" rel=\"{handler: 'image', size: {}}\">"
								. "<img id='main_image" . $accessory[$a]->accessory_id . "' class='redAttributeImage' src='" . $thumbUrl . "' />"
								. "</a>";
						}
						else
						{
							$thumbUrl = RedshopHelperMedia::getImagePath(
								'noimage.jpg',
								'',
								'thumb',
								'',
								$accessoryWidthThumb,
								$accessoryHeightThumb,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);

							$accessoryImg = "<a id='a_main_image" . $accessory[$a]->accessory_id
								. "' href='" . REDSHOP_FRONT_IMAGES_ABSPATH
								. "noimage.jpg' title='' class=\"modal\" rel=\"{handler: 'image', size: {}}\">"
								. "<img id='main_image" . $accessory[$a]->accessory_id . "' class='redAttributeImage' src='" . $thumbUrl . "' /></a>";
						}
					}
					else
					{
						if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessoryImage))
						{
							$thumbUrl = RedshopHelperMedia::getImagePath(
								$accessoryImage,
								'',
								'thumb',
								'product',
								$accessoryWidthThumb,
								$accessoryHeightThumb,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);

							$accessoryImg = "<a href='$accessoryProductLink'><img id='main_image" . $accessory[$a]->accessory_id
								. "' class='redAttributeImage' src='" . $thumbUrl . "' /></a>";
						}
						else
						{
							$thumbUrl = RedshopHelperMedia::getImagePath(
								'noimage.jpg',
								'',
								'thumb',
								'',
								$accessoryWidthThumb,
								$accessoryHeightThumb,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);

							$accessoryImg = "<a href='$accessoryProductLink'><img id='main_image" . $accessory[$a]->accessory_id
								. "' class='redAttributeImage' src='" . $thumbUrl . "' /></a>";
						}
					}
				}

				$accessoryWrapper   = str_replace($accessoryImgTag, $accessoryImg . $hiddenThumbImage, $accessoryWrapper);
				$accessoryShortDesc = RedshopHelperUtility::maxChars(
					$accessory[$a]->product_s_desc,
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_MAX_CHARS'),
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_END_SUFFIX')
				);
				$accessoryWrapper   = str_replace("{accessory_short_desc}", $accessoryShortDesc, $accessoryWrapper);

				// Add manufacturer
				if (strpos($accessoryWrapper, "{manufacturer_name}") !== false || strpos($accessoryWrapper, "{manufacturer_link}") !== false)
				{
					$manufacturer = RedshopEntityManufacturer::getInstance($accessory[$a]->manufacturer_id)->getItem();

					if (count($manufacturer) > 0)
					{
						$manufacturerUrl = JRoute::_(
							'index.php?option=com_redshop&view=manufacturers&layout=products&mid='
							. $accessory[$a]->manufacturer_id . '&Itemid=' . $itemId
						);

						$manufacturerLink = "<a class='btn btn-primary' href='" . $manufacturerUrl . "'>"
							. JText::_("VIEW_ALL_MANUFACTURER_PRODUCTS") . "</a>";
						$accessoryWrapper = str_replace("{manufacturer_name}", $manufacturer->name, $accessoryWrapper);
						$accessoryWrapper = str_replace("{manufacturer_link}", $manufacturerLink, $accessoryWrapper);
					}
					else
					{
						$accessoryWrapper = str_replace("{manufacturer_name}", '', $accessoryWrapper);
						$accessoryWrapper = str_replace("{manufacturer_link}", '', $accessoryWrapper);
					}
				}

				// Get accessory final price with VAT rules
				$accessoryPriceWithoutVAT = \Redshop\Product\Accessory::getPrice(
					$productId,
					$accessory[$a]->newaccessory_price,
					$accessory[$a]->accessory_main_price,
					1
				);

				if (strpos($accessoryWrapper, "{without_vat}") === false)
				{
					$accessoryPrices = \Redshop\Product\Accessory::getPrice(
						$productId,
						$accessory[$a]->newaccessory_price,
						$accessory[$a]->accessory_main_price
					);
				}
				else
				{
					$accessoryPrices = $accessoryPriceWithoutVAT;
				}

				$accessoryPriceWithoutVAT = $accessoryPriceWithoutVAT[0];

				$accessoryPrice      = $accessoryPrices[0];
				$accessoryMainPrice  = $accessoryPrices[1];
				$accessorySavedPrice = $accessoryPrices[2];

				// Get Formatted prices
				$accessorySavedPrice = RedshopHelperProductPrice::formattedPrice($accessorySavedPrice);
				$accessoryMainPrice  = RedshopHelperProductPrice::formattedPrice($accessoryMainPrice);
				$accessoryShowPrice  = RedshopHelperProductPrice::formattedPrice($accessoryPrice);

				if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
						|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))))
				{
					$accessoryWrapper = str_replace("{accessory_price}", $accessoryShowPrice, $accessoryWrapper);
					$accessoryWrapper = str_replace("{accessory_main_price}", $accessoryMainPrice, $accessoryWrapper);
					$accessoryWrapper = str_replace("{accessory_price_saving}", $accessorySavedPrice, $accessoryWrapper);
				}
				else
				{
					$accessoryWrapper = str_replace("{accessory_price}", '', $accessoryWrapper);
					$accessoryWrapper = str_replace("{accessory_main_price}", '', $accessoryWrapper);
					$accessoryWrapper = str_replace("{accessory_price_saving}", '', $accessoryWrapper);
				}

				$readMoreLink = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $accessoryId . '&Itemid=' . $itemId, false);

				$accessoryProductDetail = "<a href='" . $readMoreLink . "' title='" . $accessory[$a]->product_name
					. "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";

				$accessoryWrapper = str_replace("{accessory_readmore}", $accessoryProductDetail, $accessoryWrapper);
				$accessoryWrapper = str_replace("{accessory_readmore_link}", $readMoreLink, $accessoryWrapper);

				// Accessory attribute  Start
				$attributeSet = array();

				if ($accessoryProduct->attribute_set_id > 0)
				{
					$attributeSet = RedshopHelperProduct_Attribute::getProductAttribute(0, $accessoryProduct->attribute_set_id);
				}

				$attributes = RedshopHelperProduct_Attribute::getProductAttribute($accessoryId);
				$attributes = array_merge($attributes, $attributeSet);

				$accessoryWrapper = RedshopHelperAttribute::replaceAttributeData(
					$productId,
					$accessory[$a]->accessory_id,
					$relProductId,
					$attributes,
					$accessoryWrapper,
					$attributeTemplate,
					$isChild,
					$selectAtt
				);

				$accessoryWrapper = Redshop\Product\Stock::replaceInStock($accessory[$a]->child_product_id, $accessoryWrapper);

				// Accessory attribute  End
				$accessoryChecked = "";

				if (($isAjax == 1 && in_array($accessory[$a]->accessory_id, $selectedAccessory))
					|| ($isAjax == 0 && $accessory[$a]->setdefault_selected))
				{
					$accessoryChecked = "checked";
				}

				$accessory_checkbox = "<input type='checkbox' name='accessory_id_" . $prefix . $productId
					. "[]' onClick='calculateTotalPrice(\"" . $productId . "\",\"" . $relProductId . "\");' totalattributs='"
					. count($attributes) . "' accessoryprice='" . $accessoryPrice
					. "' accessorywithoutvatprice='" . $accessoryPriceWithoutVAT . "' id='accessory_id_"
					. $commonId . "' value='" . $accessory[$a]->accessory_id . "' " . $accessoryChecked . " />";
				$accessoryWrapper   = str_replace("{accessory_add_chkbox}", $accessory_checkbox, $accessoryWrapper);
				$accessoryWrapper   = str_replace(
					"{accessory_add_chkbox_lbl}",
					JText::_('COM_REDSHOP_ACCESSORY_ADD_CHKBOX_LBL') . '&nbsp;' . $accessory[$a]->product_name,
					$accessoryWrapper
				);

				if (strpos($accessoryWrapper, "{accessory_quantity}") !== false)
				{
					if (Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE'))
					{
						$key                = array_search($accessory[$a]->accessory_id, $selectedAccessory);
						$accqua             = ($accessoryChecked != "" && isset($selectedAccessoryQua[$key]) && $selectedAccessoryQua[$key])
							? $selectedAccessoryQua[$key] : 1;
						$accessory_quantity = "<input type='text' name='accquantity_" . $prefix . $productId . "[]' id='accquantity_"
							. $commonId . "' value='" . $accqua . "' maxlength='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'"
							. " size='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' onchange='validateInputNumber(this.id);'>";
						$accessoryWrapper   = str_replace("{accessory_quantity}", $accessory_quantity, $accessoryWrapper);
						$accessoryWrapper   = str_replace("{accessory_quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY'), $accessoryWrapper);
					}
					else
					{
						$accessoryWrapper = str_replace("{accessory_quantity}", "", $accessoryWrapper);
						$accessoryWrapper = str_replace("{accessory_quantity_lbl}", "", $accessoryWrapper);
					}
				}

				$fields = RedshopHelperExtrafields::getSectionFieldList(
					RedshopHelperExtrafields::SECTION_PRODUCT, 1, 1
				);

				if (count($fields) > 0)
				{
					foreach ($fields as $field)
					{
						$fieldValues = RedshopHelperExtrafields::getSectionFieldDataList(
							$field->id, 1, $accessory[$a]->child_product_id
						);

						if ($fieldValues && $fieldValues->data_txt != ""
							&& $field->show_in_front == 1 && $field->published == 1)
						{
							$accessoryWrapper = str_replace('{' . $field->name . '}', $fieldValues->data_txt, $accessoryWrapper);
							$accessoryWrapper = str_replace('{' . $field->name . '_lbl}', $field->title, $accessoryWrapper);
						}
						else
						{
							$accessoryWrapper = str_replace('{' . $field->name . '}', "", $accessoryWrapper);
							$accessoryWrapper = str_replace('{' . $field->name . '_lbl}', "", $accessoryWrapper);
						}
					}
				}
			}

			$accessoryWrapper = $accessoryWrapperStart . $accessoryWrapper . $accessoryWrapperEnd;
		}

		// Attribute ajax change
		if ($viewAcc != 1 && Redshop::getConfig()->getInt('AJAX_CART_BOX') != 0)
		{
			$accessoryWrapper = '';
		}

		$templateContent = str_replace(
			"{accessory_template:" . $accessoryTemplate->name . "}",
			$accessoryWrapper,
			$templateContent
		);

		$selectedAccessoriesHtml = '';

		if (strpos($templateContent, "{selected_accessory_price}") !== false && $isAjax == 0)
		{
			$selectedAccessoryPrice  = RedshopHelperProductPrice::priceReplacement($productPrices['product_price']);
			$selectedAccessoriesHtml = "<div id='rs_selected_accessory_price' class='rs_selected_accessory_price'>"
				. $selectedAccessoryPrice . "</div>";
		}

		$templateContent = str_replace("{selected_accessory_price}", $selectedAccessoriesHtml, $templateContent);

		// New tags replacement for accessory template section
		$templateContent = RedshopTagsReplacer::_('accessory', $templateContent, array('accessory' => $accessory));
		$templateContent = str_replace("{accessory_product_start}", "", $templateContent);
		$templateContent = str_replace("{accessory_product_end}", "", $templateContent);

		return $templateContent;
	}

	/**
	 * Method for get image width height from tags in template
	 *
	 * @param   string   $template  Template content
	 * @param   string   $imageTag  Accessory image tag
	 * @param   integer  $width     Return variable width
	 * @param   integer  $height    Return variable height
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public static function getWidthHeight($template, &$imageTag, &$width, &$height)
	{
		if (strpos($template, "{accessory_main_image_3}") !== false)
		{
			$imageTag = '{accessory_main_image_3}';
			$height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_3');
			$width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_3');
		}
		elseif (strpos($template, "{accessory_main_image_2}") !== false)
		{
			$imageTag = '{accessory_main_image_2}';
			$height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_2');
			$width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_2');
		}
		elseif (strpos($template, "{accessory_main_image_1}") !== false)
		{
			$imageTag = '{accessory_main_image_1}';
			$height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
			$width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
		}
		else
		{
			$imageTag = '{accessory_main_image}';
			$height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
			$width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
		}
	}

	/**
	 * Method for replace main accessory tags.
	 *
	 * @param   string  $accessoryTemplate Accessory template data
	 * @param   string  $templateContent   Template content
	 * @param   object  $product           Product Data
	 * @param   integer $userId            User ID
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public static function replaceMainAccessory(&$accessoryTemplate, $templateContent, $product, $userId)
	{
		if (strpos($accessoryTemplate, "{if accessory_main}") === false
			|| strpos($accessoryTemplate, "{accessory_main end if}") === false)
		{
			return;
		}

		$accessoryTemplate = explode('{if accessory_main}', $accessoryTemplate);
		$accessoryStart    = $accessoryTemplate[0];
		$accessoryTemplate = explode('{accessory_main end if}', $accessoryTemplate[1]);
		$accessoryEnd      = $accessoryTemplate[1];
		$accessoryMiddle   = $accessoryTemplate[0];

		if (strpos($accessoryMiddle, "{accessory_main_short_desc}") !== false)
		{
			$accessoryMiddle = str_replace(
				"{accessory_main_short_desc}",
				RedshopHelperUtility::limitText(
					$product->product_s_desc,
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_MAX_CHARS'),
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_END_SUFFIX')
				),
				$accessoryMiddle
			);
		}

		if (strpos($accessoryMiddle, "{accessory_main_title}") !== false)
		{
			$accessoryMiddle = str_replace(
				"{accessory_main_title}",
				RedshopHelperUtility::limitText(
					$product->product_name,
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
				),
				$accessoryMiddle
			);
		}

		$accessoryProductDetail = "<a href='#' title='" . $product->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
		$accessoryMiddle        = str_replace("{accessory_main_readmore}", $accessoryProductDetail, $accessoryMiddle);
		$accessoryMainImage     = $product->product_full_image;
		$accessoryMainImage2    = '';

		self::getWidthHeight($accessoryMiddle, $accessoryImgTag, $accessoryWidthThumb, $accessoryHeightThumb);

		if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessoryMainImage))
		{
			$thumbUrl = RedshopHelperMedia::getImagePath(
				$accessoryMainImage,
				'',
				'thumb',
				'product',
				$accessoryWidthThumb,
				$accessoryHeightThumb,
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
			);

			if (Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX') == 1)
			{
				$accessoryMainImage2 = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH
					. "product/" . $accessoryMainImage
					. "' title='' class=\"modal\" rel=\"{handler: 'image', size: {}}\">"
					. "<img id='main_image' class='redAttributeImage' src='" . $thumbUrl . "' /></a>";
			}
			else
			{
				$accessoryMainImage2 = "<img id='main_image' class='redAttributeImage' src='" . $thumbUrl . "' />";
			}
		}

		$accessoryMiddle = str_replace($accessoryImgTag, $accessoryMainImage2, $accessoryMiddle);
		$productPrices   = array();

		if (strpos($accessoryMiddle, "{accessory_mainproduct_price}") !== false
			|| strpos($templateContent, "{selected_accessory_price}") !== false)
		{
			$productPrices = RedshopHelperProductPrice::getNetPrice($product->product_id, $userId, 1, $templateContent);
		}

		if (strpos($accessoryMiddle, "{accessory_mainproduct_price}") !== false)
		{
			if (Redshop::getConfig()->get('SHOW_PRICE')
				&& (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
				|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
				&& Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))))
			{
				$accessoryMainProductPrice = RedshopHelperProductPrice::priceReplacement($productPrices['product_price']);

				$accessoryMiddle = str_replace("{accessory_mainproduct_price}", $accessoryMainProductPrice, $accessoryMiddle);
			}
		}

		$accessoryMiddle   = Redshop\Product\Stock::replaceInStock($product->product_id, $accessoryMiddle);
		$accessoryTemplate = $accessoryStart . $accessoryMiddle . $accessoryEnd;
	}
}
