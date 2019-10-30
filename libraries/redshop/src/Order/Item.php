<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Order;

defined('_JEXEC') or die;

/**
 * Order item helper
 *
 * @since  2.1.0
 */
class Item
{
	/**
	 * replace Order Items
	 *
	 * @param   string  $content  Template
	 * @param   array   $items    Order item list
	 * @param   boolean $sendMail Is send mail
	 *
	 * @return  array
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function replaceItems($content, $items = array(), $sendMail = false)
	{
		if (empty($items))
		{
			return array('', 0);
		}

		\JPluginHelper::importPlugin('redshop_product');

		$dispatcher    = \RedshopHelperUtility::getDispatcher();
		$view          = \JFactory::getApplication()->input->getCmd('view');
		$fieldArray    = \RedshopHelperExtrafields::getSectionFieldList(\RedshopHelperExtrafields::SECTION_PRODUCT_FINDER_DATE_PICKER, 0, 0);
		$subTotalNoVat = 0;
		$cart          = '';
		$url           = \JUri::root();
		$wrapperName   = '';
		$orderDetail   = \RedshopEntityOrder::getInstance((int) $items[0]->order_id)->getItem();
		$orderCount    = count($items);
		$thumbWidth    = \Redshop::getConfig()->getInt('CART_THUMB_WIDTH');
		$thumbHeight   = \Redshop::getConfig()->getInt('CART_THUMB_HEIGHT');
		$useSizeSwap   = \Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');

		for ($i = 0; $i < $orderCount; $i++)
		{
			$cartHtmlContent = $content;

			// Process the product plugin for cart item
			$dispatcher->trigger('onOrderItemDisplay', array(&$cartHtmlContent, &$items, $i));

			$productId = $items[$i]->product_id;
			$quantity  = $items[$i]->product_quantity;
			$itemData  = \productHelper::getInstance()->getMenuInformation(0, 0, '', 'product&pid=' . $productId);
			$itemId    = !empty($itemData) ? $itemData->id : \RedshopHelperRouter::getItemId($productId);
			$link      = \JRoute::_('index.php?option=com_redshop&view=product&pid=' . $productId . '&Itemid=' . $itemId, false);

			if ($items[$i]->is_giftcard)
			{
				$giftcardData     = \RedshopEntityGiftcard::getInstance($productId)->getItem();
				$productName      = $giftcardData->giftcard_name;
				$userFieldSection = \RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD;
				$product          = new \stdClass;
			}
			else
			{
				$product          = \RedshopHelperProduct::getProductById($productId);
				$productName      = $items[$i]->order_item_name;
				$userFieldSection = \RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD;
				$giftcardData     = new \stdClass;
			}

			$path           = \JPath::clean(JPATH_COMPONENT_SITE . '/assets/images/orderMergeImages/' . $items[$i]->attribute_image);
			$attributeImage = '';

			if (\JFile::exists($path))
			{
				$attributeImagePath = \RedshopHelperMedia::getImagePath(
					$items[$i]->attribute_image, '', 'thumb', 'orderMergeImages', $thumbWidth, $thumbHeight, $useSizeSwap
				);
				$attributeImage     = '<img src="' . $attributeImagePath . '">';
			}
			else
			{
				if (\JFile::exists(JPATH_COMPONENT_SITE . '/assets/images/product_attributes/' . $items[$i]->attribute_image)
					&& \Redshop::getConfig()->get('WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART'))
				{
					$attributeImagePath = \RedshopHelperMedia::getImagePath(
						$items[$i]->attribute_image, '', 'thumb', 'product_attributes', $thumbWidth, $thumbHeight, $useSizeSwap
					);
					$attributeImage     = '<img src="' . $attributeImagePath . '">';
				}
				else
				{
					if ($items[$i]->is_giftcard)
					{
						$productFullImg = $giftcardData->giftcard_image;
						$productType    = 'giftcard';
					}
					else
					{
						$productFullImg = $product->product_full_image;
						$productType    = 'product';
					}

					if ($productFullImg)
					{
						if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $productType . "/" . $productFullImg))
						{
							$attributeImagePath = \RedshopHelperMedia::getImagePath(
								$productFullImg,
								'',
								'thumb',
								$productType,
								$thumbWidth,
								$thumbHeight,
								$useSizeSwap
							);
							$attributeImage     = '<img src="' . $attributeImagePath . '">';
						}
						else
						{
							if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . \Redshop::getConfig()->getString('PRODUCT_DEFAULT_IMAGE')))
							{
								$attributeImagePath = \RedshopHelperMedia::getImagePath(
									\Redshop::getConfig()->getString('PRODUCT_DEFAULT_IMAGE'),
									'',
									'thumb',
									'product',
									$thumbWidth,
									$thumbHeight,
									$useSizeSwap
								);
								$attributeImage     = '<img src="' . $attributeImagePath . '">';
							}
						}
					}
					else
					{
						if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
						{
							$attributeImagePath = \RedshopHelperMedia::getImagePath(
								\Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE'),
								'',
								'thumb',
								'product',
								$thumbWidth,
								\Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
								\Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);
							$attributeImage     = '<img src="' . $attributeImagePath . '">';
						}
					}
				}
			}

			if (!$sendMail)
			{
				$productName    = '<a href="' . $link . '">' . $productName . '</a>';
				$attributeImage = '<a href="' . $link . '">' . $attributeImage . '</a>';
			}

			$productName       = "<div class='product_name'>" . $productName . "</div>";
			$productTotalPrice = "<div class='product_price'>";

			if (!\Redshop\Template\Helper::isApplyVat($content))
			{
				$productTotalPrice .= \RedshopHelperProductPrice::formattedPrice($items[$i]->product_item_price_excl_vat * $quantity);
			}
			else
			{
				$productTotalPrice .= \RedshopHelperProductPrice::formattedPrice($items[$i]->product_item_price * $quantity);
			}

			$productTotalPrice .= "</div>";

			$productPrice = "<div class='product_price'>";

			if (!\Redshop\Template\Helper::isApplyVat($content))
			{
				$productPrice .= \RedshopHelperProductPrice::formattedPrice($items[$i]->product_item_price_excl_vat);
			}
			else
			{
				$productPrice .= \RedshopHelperProductPrice::formattedPrice($items[$i]->product_item_price);
			}

			$productPrice    .= "</div>";
			$productOldPrice = \RedshopHelperProductPrice::formattedPrice($items[$i]->product_item_old_price);
			$productQuantity = '<div class="update_cart">' . $quantity . '</div>';

			if ($items[$i]->wrapper_id)
			{
				$wrapper = \productHelper::getInstance()->getWrapper($productId, $items[$i]->wrapper_id);

				if (count($wrapper) > 0)
				{
					$wrapperName = $wrapper [0]->wrapper_name;
				}

				$wrapperPrice = \RedshopHelperProductPrice::formattedPrice($items[$i]->wrapper_price);
				$wrapperName  = \JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapperName . "(" . $wrapperPrice . ")";
			}

			$cartHtmlContent = str_replace("{product_name}", $productName, $cartHtmlContent);

			$categoryId   = \productHelper::getInstance()->getCategoryProduct($productId);
			$category     = \RedshopEntityCategory::getInstance((int) $categoryId)->getItem();
			$categoryLink = '';

			if (!empty($category))
			{
				$categoryLink = "<a href='"
					. \JRoute::_($url . 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $categoryId) . "'>"
					. $category->name . "</a>";
			}

			if (strpos($cartHtmlContent, '{stock_status}') !== false)
			{
				$isStockExists         = \RedshopHelperStockroom::isStockExists($items[$i]->product_id);
				$isPreorderStockExists = false;

				if (!$isStockExists)
				{
					$isPreorderStockExists = \RedshopHelperStockroom::isPreorderStockExists($items[$i]->product_id);
				}

				if (!$isStockExists)
				{
					$productPreorder = $product->preorder;

					if (($productPreorder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
						|| ($productPreorder == "yes") || ($productPreorder == "" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
					{
						$stockStatus = !$isPreorderStockExists ? \JText::_('COM_REDSHOP_OUT_OF_STOCK') : \JText::_('COM_REDSHOP_PRE_ORDER');
					}
					else
					{
						$stockStatus = \JText::_('COM_REDSHOP_OUT_OF_STOCK');
					}
				}
				else
				{
					$stockStatus = \JText::_('COM_REDSHOP_AVAILABLE_STOCK');
				}

				$cartHtmlContent = str_replace("{stock_status}", $stockStatus, $cartHtmlContent);
			}

			$cartHtmlContent = str_replace("{category_name}", $categoryLink, $cartHtmlContent);
			$cartHtmlContent = \RedshopHelperTax::replaceVatInformation($cartHtmlContent);
			$productNote     = "<div class='product_note'>" . $wrapperName . "</div>";
			$cartHtmlContent = str_replace("{product_wrapper}", $productNote, $cartHtmlContent);

			// Make attribute order template output
			$attributeData = \productHelper::getInstance()->makeAttributeOrder($items[$i]->order_item_id, 0, $productId, 0, 0, $content);

			// Assign template output into {product_attribute} tag
			$cartHtmlContent = \RedshopTagsReplacer::_(
				'attribute',
				$cartHtmlContent,
				array(
					'product_attribute' => $attributeData->product_attribute,
				)
			);

			// Assign template output into {attribute_middle_template} tag
			$cartHtmlContent = str_replace(
				$attributeData->attribute_middle_template_core, $attributeData->attribute_middle_template, $cartHtmlContent
			);

			if (strpos($cartHtmlContent, '{remove_product_attribute_title}') !== false)
			{
				$cartHtmlContent = str_replace("{remove_product_attribute_title}", "", $cartHtmlContent);
			}

			if (strpos($cartHtmlContent, '{remove_product_subattribute_title}') !== false)
			{
				$cartHtmlContent = str_replace("{remove_product_subattribute_title}", "", $cartHtmlContent);
			}

			if (strpos($cartHtmlContent, '{product_attribute_number}') !== false)
			{
				$cartHtmlContent = str_replace("{product_attribute_number}", "", $cartHtmlContent);
			}

			$cartHtmlContent   = str_replace(
				"{product_accessory}", \Redshop\Order\Helper::generateAccessories($items[$i]->order_item_id), $cartHtmlContent
			);
			$productUserFields = \productHelper::getInstance()->getuserfield($items[$i]->order_item_id, $userFieldSection);
			$cartHtmlContent   = str_replace("{product_userfields}", $productUserFields, $cartHtmlContent);
			$userCustomFields  = \productHelper::getInstance()->GetProdcutfield_order($items[$i]->order_item_id);
			$cartHtmlContent   = str_replace("{product_customfields}", $userCustomFields, $cartHtmlContent);
			$cartHtmlContent   = str_replace("{product_customfields_lbl}", \JText::_("COM_REDSHOP_PRODUCT_CUSTOM_FIELD"), $cartHtmlContent);

			if ($items[$i]->is_giftcard)
			{
				$cartHtmlContent = str_replace(
					array('{product_sku}', '{product_number}', '{product_s_desc}', '{product_subscription}', '{product_subscription_lbl}'),
					'',
					$cartHtmlContent
				);
			}
			else
			{
				$cartHtmlContent = str_replace("{product_sku}", $items[$i]->order_item_sku, $cartHtmlContent);
				$cartHtmlContent = str_replace("{product_number}", $items[$i]->order_item_sku, $cartHtmlContent);
				$cartHtmlContent = str_replace("{product_s_desc}", $product->product_s_desc, $cartHtmlContent);

				if ($product->product_type == 'subscription')
				{
					$userSubscribeDetail  = \productHelper::getInstance()->getUserProductSubscriptionDetail($items[$i]->order_item_id);
					$subscription         = \productHelper::getInstance()->getProductSubscriptionDetail(
						$product->product_id, $userSubscribeDetail->subscription_id
					);
					$selectedSubscription = $subscription->subscription_period . " " . $subscription->period_type;

					$cartHtmlContent = str_replace("{product_subscription_lbl}", \JText::_('COM_REDSHOP_SUBSCRIPTION'), $cartHtmlContent);
					$cartHtmlContent = str_replace("{product_subscription}", $selectedSubscription, $cartHtmlContent);
				}
				else
				{
					$cartHtmlContent = str_replace("{product_subscription_lbl}", "", $cartHtmlContent);
					$cartHtmlContent = str_replace("{product_subscription}", "", $cartHtmlContent);
				}
			}

			$cartHtmlContent = str_replace("{product_number_lbl}", \JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $cartHtmlContent);
			$productVat      = ($items[$i]->product_item_price - $items[$i]->product_item_price_excl_vat) * $items [$i]->product_quantity;
			$cartHtmlContent = str_replace("{product_vat}", $productVat, $cartHtmlContent);
			$cartHtmlContent = \productHelper::getInstance()->getProductOnSaleComment($product, $cartHtmlContent);
			$cartHtmlContent = str_replace("{attribute_price_without_vat}", '', $cartHtmlContent);
			$cartHtmlContent = str_replace("{attribute_price_with_vat}", '', $cartHtmlContent);

			// ProductFinderDatepicker Extra Field Start
			$cartHtmlContent = \productHelper::getInstance()->getProductFinderDatepickerValue($cartHtmlContent, $productId, $fieldArray);

			// Change order item image based on plugin
			$prepareCartAttributes[$i]               = get_object_vars($attributeData);
			$prepareCartAttributes[$i]['product_id'] = $items[$i]->product_id;

			$dispatcher->trigger(
				'OnSetCartOrderItemImage',
				array(
					&$prepareCartAttributes,
					&$attributeImage,
					$items[$i],
					$i
				)
			);

			$cartHtmlContent = str_replace(
				"{product_thumb_image}",
				"<div class='product_image'>" . $attributeImage . "</div>",
				$cartHtmlContent
			);

			$cartHtmlContent = str_replace("{product_price}", $productPrice, $cartHtmlContent);
			$cartHtmlContent = str_replace("{product_old_price}", $productOldPrice, $cartHtmlContent);
			$cartHtmlContent = str_replace("{product_quantity}", $productQuantity, $cartHtmlContent);
			$cartHtmlContent = str_replace("{product_total_price}", $productTotalPrice, $cartHtmlContent);
			$cartHtmlContent = str_replace(
				"{product_price_excl_vat}", \RedshopHelperProductPrice::formattedPrice($items [$i]->product_item_price_excl_vat), $cartHtmlContent
			);
			$cartHtmlContent = str_replace(
				"{product_total_price_excl_vat}",
				\RedshopHelperProductPrice::formattedPrice($items [$i]->product_item_price_excl_vat * $quantity),
				$cartHtmlContent
			);

			$subTotalNoVat += $items [$i]->product_item_price_excl_vat * $quantity;

			\JPluginHelper::importPlugin('redshop_stockroom');
			$dispatcher->trigger('onReplaceStockStatus', array($items[$i], &$cartHtmlContent));

			if ($view == "order_detail")
			{
				$itemId = \RedshopHelperRouter::getCartItemId();

				$copyToCart      = '<a href="'
					. \JRoute::_(
						'index.php?option=com_redshop&view=order_detail&task=copyorderitemtocart&order_item_id='
						. $items[$i]->order_item_id . '&Itemid=' . $itemId,
						false
					)
					. '"><img src="' . REDSHOP_MEDIA_IMAGES_ABSPATH . 'add.jpg" '
					. 'title="' . \JText::_("COM_REDSHOP_COPY_TO_CART") . '" '
					. 'alt="' . \JText::_("COM_REDSHOP_COPY_TO_CART") . '" /></a>';
				$cartHtmlContent = str_replace("{copy_orderitem}", $copyToCart, $cartHtmlContent);
			}
			else
			{
				$cartHtmlContent = str_replace("{copy_orderitem}", "", $cartHtmlContent);
			}

			// Get Downloadable Products
			$downloadProducts        = \RedshopHelperOrder::getDownloadProduct($items[$i]->order_id);
			$prepareDownloadProducts = array();

			foreach ($downloadProducts as $downloadProduct)
			{
				$prepareDownloadProducts[$downloadProduct->product_id][$downloadProduct->download_id] = $downloadProduct;
			}

			// Get Downloadable Products Logs
			$downloadProductLogs         = \RedshopHelperOrder::getDownloadProductLog($items[$i]->order_id);
			$preparedDownloadProductLogs = array();

			foreach ($downloadProductLogs as $downloadProductLog)
			{
				$preparedDownloadProductLogs[$downloadProductLog->product_id][] = $downloadProductLog;
			}

			// Download Product Tag Replace
			if (isset($prepareDownloadProducts[$productId]) && count($prepareDownloadProducts[$productId]) > 0
				&& $orderDetail->order_status == "C" && $orderDetail->order_payment_status == "Paid")
			{
				$downloads    = $prepareDownloadProducts[$productId];
				$downloadHtml = "<table class='download_token'>";
				$limit        = $downloadHtml;
				$endHtml      = $downloadHtml;
				$g            = 1;

				foreach ($downloads as $download)
				{
					$fileName    = substr(basename($download->file_name), 11);
					$productName = $download->product_name;
					$downloadId  = $download->download_id;
					$downloadMax = $download->download_max;
					$endDate     = $download->end_date;
					$mailToken   = "<a href='"
						. \JRoute::_(\JUri::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $downloadId, false)
						. "'>" . $fileName . "</a>";

					$downloadHtml .= "</tr>";
					$downloadHtml .= "<td>(" . $g . ") " . $productName . ": " . $mailToken . "</td>";
					$downloadHtml .= "</tr>";
					$limit        .= "</tr>";
					$limit        .= "<td>(" . $g . ") " . $downloadMax . "</td>";
					$limit        .= "</tr>";
					$endHtml      .= "</tr>";
					$endHtml      .= "<td>(" . $g . ") " . date("d-m-Y H:i", $endDate) . "</td>";
					$endHtml      .= "</tr>";
					$g++;
				}

				$downloadHtml .= "</table>";
				$limit        .= "</table>";
				$endHtml      .= "</table>";

				$cartHtmlContent = str_replace("{download_token_lbl}", \JText::_('COM_REDSHOP_DOWNLOAD_TOKEN'), $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_token}", $downloadHtml, $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_counter_lbl}", \JText::_('COM_REDSHOP_DOWNLOAD_LEFT'), $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_counter}", $limit, $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_date_lbl}", \JText::_('COM_REDSHOP_DOWNLOAD_ENDDATE'), $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_date}", $endHtml, $cartHtmlContent);
			}
			else
			{
				$cartHtmlContent = str_replace("{download_token_lbl}", "", $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_token}", "", $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_counter_lbl}", "", $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_counter}", "", $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_date_lbl}", "", $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_date}", "", $cartHtmlContent);
			}

			// Download Product log Tags Replace
			if (isset($preparedDownloadProductLogs[$productId])
				&& count($preparedDownloadProductLogs[$productId]) > 0 && $orderDetail->order_status == "C")
			{
				$downloadsLog = $preparedDownloadProductLogs[$productId];
				$downloadHtml = "<table class='download_token'>";
				$g            = 1;

				foreach ($downloadsLog as $download)
				{
					$fileName = substr(basename($download->file_name), 11);

					$downloadId   = $download->download_id;
					$downloadTime = $download->download_time;
					$downloadDate = date("d-m-Y H:i:s", $downloadTime);
					$ip           = $download->ip;

					$mailToken = "<a href='"
						. \JRoute::_(
							\JUri::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $downloadId,
							false
						)
						. "'>" . $fileName . "</a>";

					$downloadHtml .= "</tr>";
					$downloadHtml .= "<td>(" . $g . ") " . $mailToken . " "
						. \JText::_('COM_REDSHOP_ON') . " " . $downloadDate . " "
						. \JText::_('COM_REDSHOP_FROM') . " " . $ip . "</td>";
					$downloadHtml .= "</tr>";
					$g++;
				}

				$downloadHtml    .= "</table>";
				$cartHtmlContent = str_replace("{download_date_list_lbl}", \JText::_('COM_REDSHOP_DOWNLOAD_LOG'), $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_date_list}", $downloadHtml, $cartHtmlContent);
			}
			else
			{
				$cartHtmlContent = str_replace("{download_date_list_lbl}", "", $cartHtmlContent);
				$cartHtmlContent = str_replace("{download_date_list}", "", $cartHtmlContent);
			}

			$cart .= $cartHtmlContent;
		}

		return array($cart, $subTotalNoVat);
	}
}
