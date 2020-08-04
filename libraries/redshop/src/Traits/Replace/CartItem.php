<?php
/**
 * @package     Redshop.Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Traits\Replace;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

defined('_JEXEC') || die;

/**
 * For classes extends class RedshopTagsAbstract
 *
 * @since  3.0
 */
trait CartItem
{
    /**
     * Method for replace cart item
     *
     * @param   string   $data             Template Html
     * @param   array    $cart             Cart data
     * @param   boolean  $isReplaceButton  Is replace button?
     * @param   integer  $quotationMode    Is in Quotation Mode
     *
     * @return  string
     * @throws  Exception
     *
     * @since   3.0
     */
    public function replaceCartItem($data, $cart = array(), $isReplaceButton = false, $quotationMode = 0)
    {
        PluginHelper::importPlugin('redshop_product');
        $dispatcher = \RedshopHelperUtility::getDispatcher();

        $input  = Factory::getApplication()->input;
        $itemId = \RedshopHelperRouter::getCheckoutItemId();
        $view   = $input->getCmd('view');
        $token  = Session::getFormToken();

        if ($itemId == 0) {
            $itemId = $input->getInt('Itemid');
        }

        $cartResponse = '';
        $idx          = $cart['idx'];
        $fieldArray   = \RedshopHelperExtrafields::getSectionFieldList(
            \RedshopHelperExtrafields::SECTION_PRODUCT_FINDER_DATE_PICKER,
            0,
            0
        );

        if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . \Redshop::getConfig()->get('ADDTOCART_DELETE'))) {
            $deleteImg = \Redshop::getConfig()->get('ADDTOCART_DELETE');
        } else {
            $deleteImg = "defaultcross.png";
        }

        for ($i = 0; $i < $idx; $i++) {
            $cartResponse .= $this->replaceLoopCart(
                $data,
                $cart,
                $i,
                $dispatcher,
                $quotationMode,
                $fieldArray,
                $isReplaceButton,
                $view,
                $token,
                $itemId,
                $deleteImg
            );
        }

        return $cartResponse;
    }

    public function replaceLoopCart(
        $data,
        $cart,
        $i,
        $dispatcher,
        $quotationMode,
        $fieldArray,
        $isReplaceButton,
        $view,
        $token,
        $itemId,
        $deleteImg
    ) {
        $cartHtml     = $data;
        $replaceData  = [];
        $optionLayout = \RedshopLayoutHelper::$layoutOption;
        // Plugin support: Process the product plugin for cart item
        $dispatcher->trigger('onCartItemDisplay', array(&$cartHtml, $cart, $i));

        $quantity = $cart[$i]['quantity'] ?? 0;

        if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id']) {
            $giftCardId = $cart[$i]['giftcard_id'];
            $giftCard   = \RedshopEntityGiftcard::getInstance($giftCardId)->getItem();
            $link       = Route::_(
                'index.php?option=com_redshop&view=giftcard&gid=' . $giftCardId . '&Itemid=' . $itemId
            );

            $receiverInfor = \RedshopLayoutHelper::render(
                'tags.common.tag',
                [
                    'tag'   => 'div',
                    'class' => 'reciverInfo',
                    'text'  => \JText::_(
                            'LIB_REDSHOP_GIFTCARD_RECIVER_NAME_LBL'
                        ) . ': ' . $cart[$i]['reciver_name'] . '<br />' . \JText::_(
                            'LIB_REDSHOP_GIFTCARD_RECIVER_EMAIL_LBL'
                        ) . ': ' . $cart[$i]['reciver_email']
                ],
                '',
                $optionLayout
            );

            if (strpos($cartHtml, "{product_name_nolink}") !== false) {
                $replaceData['{product_name_nolink}'] = \RedshopLayoutHelper::render(
                        'tags.common.tag',
                        [
                            'tag'   => 'div',
                            'class' => 'product_name',
                            'text'  => $giftCard->giftcard_name
                        ],
                        '',
                        $optionLayout
                    ) . $receiverInfor;

                if (strpos($cartHtml, "{product_name}") !== false) {
                    $replaceData['product_name'] = '';
                }
            } else {
                $replaceData['{product_name}'] = \RedshopLayoutHelper::render(
                        'tags.product.name',
                        [
                            'link' => $link,
                            'text' => isset($giftCard->giftcard_name) ? $giftCard->giftcard_name : ''
                        ],
                        '',
                        $optionLayout
                    ) . $receiverInfor;
            }

            $replaceData['{product_attribute}']           = '';
            $replaceData['{product_accessory}']           = '';
            $replaceData['{product_wrapper}']             = '';
            $replaceData['{product_old_price}']           = '';
            $replaceData['{vat_info}']                    = '';
            $replaceData['{product_number_lbl}']          = '';
            $replaceData['{product_number}']              = '';
            $replaceData['{attribute_price_without_vat}'] = '';
            $replaceData['{attribute_price_with_vat}']    = '';

            if ($quotationMode && !\Redshop::getConfig()->getBool('SHOW_QUOTATION_PRICE')) {
                $replaceData['{product_total_price}'] = '';
                $replaceData['{product_price}']       = '';
            } else {
                $replaceData['{product_price}']       = \RedshopHelperProductPrice::formattedPrice(
                    $cart[$i]['product_price']
                );
                $replaceData['{product_total_price}'] = \RedshopHelperProductPrice::formattedPrice(
                    $cart[$i]['product_price'] * $cart[$i]['quantity'],
                    true
                );
            }

            $replaceData['{if product_on_sale}']     = '';
            $replaceData['{product_on_sale end if}'] = '';

            $thumbUrl = \RedshopHelperMedia::getImagePath(
                isset($giftCard->giftcard_image) ? $giftCard->giftcard_image : '',
                '',
                'thumb',
                'giftcard',
                \Redshop::getConfig()->get('CART_THUMB_WIDTH'),
                \Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
                \Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
            );

            $giftCardImage = "&nbsp;";

            if ($thumbUrl) {
                $giftCardImage = \RedshopLayoutHelper::render(
                    'tags.common.tag',
                    [
                        'tag'   => 'div',
                        'class' => 'giftcard_image',
                        'text'  => '<img src="' . $thumbUrl . '">'
                    ],
                    '',
                    $optionLayout
                );
            }

            $replaceData['{product_thumb_image}'] = $giftCardImage;

            $productUserFields                             = \RedshopHelperProduct::getProductUserField($i, 13);
            $replaceData['{product_userfields}']           = $productUserFields;
            $replaceData['{product_price_excl_vat}']       = \RedshopHelperProductPrice::formattedPrice(
                $cart[$i]['product_price']
            );
            $replaceData['{product_total_price_excl_vat}'] = \RedshopHelperProductPrice::formattedPrice(
                $cart[$i]['product_price'] * $cart[$i]['quantity']
            );
            $replaceData['{attribute_change}']             = '';
            $replaceData['{product_attribute_price}']      = '';
            $replaceData['{product_attribute_number}']     = '';
            $replaceData['{product_tax}']                  = '';

            // ProductFinderDatepicker Extra Field
            $cartHtml = \RedshopHelperProduct::getProductFinderDatepickerValue($cartHtml, $giftCardId, $fieldArray, 1);

            $removeProduct = \RedshopLayoutHelper::render(
                'tags.giftcard.delete',
                [
                    'i'           => $i,
                    'giftCartId'  => $cart[$i]['giftcard_id'],
                    'itemId'      => $itemId,
                    'sourceImage' => REDSHOP_FRONT_IMAGES_ABSPATH . $deleteImg
                ],
                '',
                \RedshopLayoutHelper::$layoutOption
            );

            $replaceData['{remove_product}'] = $removeProduct;

            // Replace attribute tags to empty on giftcard
            if (strpos($cartHtml, "{product_attribute_loop_start}") !== false && strpos(
                    $cartHtml,
                    "{product_attribute_loop_end}"
                ) !== false) {
                $templateAttr                           = $this->getTemplateBetweenLoop(
                    '{product_attribute_loop_start}',
                    '{product_attribute_loop_end}',
                    $cartHtml
                );
                $replaceData[$templateAttr['template']] = '';
            }

            $cartItem = 'giftCardId';
        } else {
            $productId     = $cart[$i]['product_id'] ?? 0;
            $product       = \Redshop\Product\Product::getProductById($productId);
            $catId         = $product->cat_in_sefurl ?? 0;
            $attributeCart = \RedshopHelperProduct::makeAttributeCart(
                $cart[$i]['cart_attribute'] ?? [],
                $productId,
                0,
                0,
                $quantity,
                $cartHtml
            );
            $cartAttribute = $attributeCart[0];
            $retAccArr     = \RedshopHelperProduct::makeAccessoryCart(
                $cart[$i]['cart_accessory'] ?? [],
                $productId,
                $cartHtml
            );
            $cartAccessory = $retAccArr[0];

            $itemData = \RedshopHelperProduct::getMenuInformation(
                0,
                0,
                '',
                'product&pid=' . $productId
            );

            if (isset($itemData->id)) {
                $itemId = $itemData->id;
            } else {
                $itemId = \RedshopHelperUtility::getCategoryItemid($catId);
            }

            $link = Route::_(
                'index.php?option=com_redshop&view=product&cid=' . $catId . '&pid=' . $productId . '&Itemid=' . $itemId
            );

            // Trigger to change product link.
            $dispatcher->trigger('onSetCartOrderItemProductLink', array(&$cart, &$link, $product, $i));

            $productName = '';

            if (isset($product->product_name)) {
                $productName = \RedshopLayoutHelper::render(
                    'tags.product.name',
                    [
                        'link' => $link,
                        'text' => $product->product_name
                    ],
                    '',
                    $optionLayout
                );
            }

            $productImage = "";
            $productImg   = '';
            $type         = 'product';

            if (\Redshop::getConfig()->get('WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART')
                && isset($cart[$i]['hidden_attribute_cartimage'])) {
                $imagePath    = REDSHOP_FRONT_IMAGES_ABSPATH;
                $productImage = str_replace($imagePath, '', $cart[$i]['hidden_attribute_cartimage']);
            }

            if ($productImage && \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $productImage)) {
                $val        = explode("/", $productImage);
                $productImg = $val[1];
                $type       = $val[0];
            } elseif (isset($product->product_full_image)
                && \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image)) {
                $productImg = $product->product_full_image;
                $type       = 'product';
            } elseif (\JFile::exists(
                REDSHOP_FRONT_IMAGES_RELPATH . "product/" . \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')
            )) {
                $productImg = \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
                $type       = 'product';
            }

            $isAttributeImage = false;

            if (isset($cart[$i]['attributeImage'])) {
                $isAttributeImage = \JFile::exists(
                    REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $cart[$i]['attributeImage']
                );
            }

            if ($isAttributeImage) {
                $productImg = $cart[$i]['attributeImage'];
                $type       = 'mergeImages';
            }

            if ($productImg !== '') {
                if (\Redshop::getConfig()->getBool('WATERMARK_CART_THUMB_IMAGE')
                    && \JFile::exists(
                        REDSHOP_FRONT_IMAGES_RELPATH . "product/" . \Redshop::getConfig()->get('WATERMARK_IMAGE')
                    )) {
                    $productCartImg = \RedshopHelperMedia::watermark(
                        $type,
                        $productImg,
                        \Redshop::getConfig()->getInt('CART_THUMB_WIDTH'),
                        \Redshop::getConfig()->getInt('CART_THUMB_HEIGHT'),
                        \Redshop::getConfig()->get('WATERMARK_CART_THUMB_IMAGE')
                    );
                } else {
                    $productCartImg = \RedshopHelperMedia::getImagePath(
                        $productImg,
                        '',
                        'thumb',
                        $type,
                        \Redshop::getConfig()->getInt('CART_THUMB_WIDTH'),
                        \Redshop::getConfig()->getInt('CART_THUMB_HEIGHT'),
                        \Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
                    );
                }

                $productImage = \RedshopLayoutHelper::render(
                    'tags.cart.thumb_image',
                    [
                        'link'           => $link,
                        'productCartImg' => $productCartImg
                    ],
                    '',
                    $optionLayout
                );
            } else {
                $productImage = \RedshopLayoutHelper::render(
                    'tags.common.tag',
                    array(
                        'tag'   => 'div',
                        'class' => 'product_image'
                    ),
                    '',
                    $optionLayout
                );
            }

            // Trigger to change product image.
            $dispatcher->trigger('onSetCartOrderItemImage', array(&$cart, &$productImage, $product, $i));

            $isApplyVAT = \Redshop\Template\Helper::isApplyVat($data);

            if (!$quotationMode || ($quotationMode && \Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))) {
                if (!$isApplyVAT) {
                    $productTotalPrice = \RedshopHelperProductPrice::formattedPrice(
                        ($cart[$i]['product_price_excl_vat'] ?? 0) * $quantity
                    );
                } else {
                    $productTotalPrice = \RedshopHelperProductPrice::formattedPrice(
                        ($cart[$i]['product_price'] ?? 0) * $quantity
                    );
                }
            }

            $totalPriceHtml = \RedshopLayoutHelper::render(
                'tags.common.price',
                [
                    'class'     => 'product_price',
                    'htmlPrice' => $productTotalPrice
                ],
                '',
                $optionLayout
            );

            $productOldPrice         = "";
            $productOldPriceNoFormat = '';

            if (!$quotationMode || ($quotationMode && \Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))) {
                if (!$isApplyVAT) {
                    $productPrice = \RedshopHelperProductPrice::formattedPrice(
                        $cart[$i]['product_price_excl_vat'] ?? 0,
                        true
                    );
                } else {
                    $productPrice = \RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price'] ?? 0, true);
                }

                if (isset($cart[$i]['product_old_price'])) {
                    $productOldPrice = $cart[$i]['product_old_price'];

                    if (!$isApplyVAT) {
                        $productOldPrice = $cart[$i]['product_old_price_excl_vat'];
                    }

                    // Set Product Old Price without format
                    $productOldPriceNoFormat = $productOldPrice;

                    $productOldPrice = \RedshopHelperProductPrice::formattedPrice($productOldPrice, true);
                }
            }

            $priceHtml = \RedshopLayoutHelper::render(
                'tags.common.price',
                [
                    'class'     => 'product_price',
                    'htmlPrice' => $productPrice
                ],
                '',
                $optionLayout
            );

            $wrapperName = "";

            if (isset($cart[$i]['wrapper_id'])) {
                $wrapper = \RedshopHelperProduct::getWrapper($productId, $cart[$i]['wrapper_id']);

                if (count($wrapper) > 0) {
                    $wrapperName = \JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper[0]->wrapper_name;

                    if (!$quotationMode || ($quotationMode && \Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))) {
                        $wrapperName .= "(" . \RedshopHelperProductPrice::formattedPrice(
                                $wrapper[0]->wrapper_price,
                                true
                            ) . ")";
                    }
                }
            }

            if (strpos($cartHtml, "{product_name_nolink}") !== false) {
                $replaceData['{product_name_nolink}'] = \RedshopLayoutHelper::render(
                    'tags.common.tag',
                    [
                        'tag'   => 'div',
                        'class' => 'product_name',
                        'text'  => $product->product_name
                    ],
                    '',
                    $optionLayout
                );

                if (strpos($cartHtml, "{product_name}") !== false) {
                    $replaceData['{product_name}'] = '';
                }
            } else {
                $replaceData['{product_name}'] = $productName;
            }

            $replaceData['{product_s_desc}'] = $product->product_s_desc ?? '';

            // Replace Attribute data
            if (strpos($cartHtml, "{product_attribute_loop_start}") !== false && strpos(
                    $cartHtml,
                    "{product_attribute_loop_end}"
                ) !== false) {
                $templateAttrData   = $this->getTemplateBetweenLoop(
                    '{product_attribute_loop_start}',
                    '{product_attribute_loop_end}',
                    $cartHtml
                );
                $templateAttrMiddle = $templateAttrData['template'];
                $productDetail      = '';
                $sumTotal           = count($cart[$i]['cart_attribute']);
                $tempAttribute      = $cart[$i]['cart_attribute'];

                if ($sumTotal > 0) {
                    $replaceAttrDdata           = [];
                    $propertyCalculatedPriceSum = $productOldPriceNoFormat;

                    for ($tpi = 0; $tpi < $sumTotal; $tpi++) {
                        $productAttributeValue      = "";
                        $productAttributeValuePrice = "";
                        $productAttributeName       = $tempAttribute[$tpi]['attribute_name'];

                        $productAttributeCalculatedPrice = '';

                        if (count($tempAttribute[$tpi]['attribute_childs']) > 0) {
                            $productAttributeValue = ": " . $tempAttribute[$tpi]['attribute_childs'][0]['property_name'];

                            if (count($tempAttribute[$tpi]['attribute_childs'][0]['property_childs']) > 0) {
                                $productAttributeValue .= ": "
                                    . $tempAttribute[$tpi]['attribute_childs'][0]['property_childs'][0]['subattribute_color_title']
                                    . ": " . $tempAttribute[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_name'];
                            }

                            $productAttributeValuePrice = $tempAttribute[$tpi]['attribute_childs'][0]['property_price'];
                            $propertyOperand            = $tempAttribute[$tpi]['attribute_childs'][0]['property_oprand'];

                            if (count($tempAttribute[$tpi]['attribute_childs'][0]['property_childs']) > 0) {
                                $productAttributeValuePrice = $productAttributeValuePrice
                                    + $tempAttribute[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_price'];

                                $propertyOperand = $tempAttribute[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_oprand'];
                            }

                            // Show actual productive price
                            if ($productAttributeValuePrice > 0) {
                                $productAttributeCalculatedPriceBase = \RedshopHelperUtility::setOperandForValues(
                                    $propertyCalculatedPriceSum,
                                    $propertyOperand,
                                    $productAttributeValuePrice
                                );

                                $productAttributeCalculatedPrice = $productAttributeCalculatedPriceBase - $propertyCalculatedPriceSum;
                                $propertyCalculatedPriceSum      = $productAttributeCalculatedPriceBase;
                            }

                            $productAttributeValuePrice = \RedshopHelperProductPrice::formattedPrice(
                                (double)$productAttributeValuePrice
                            );
                        }

                        $productAttributeCalculatedPrice = \RedshopHelperProductPrice::formattedPrice(
                            (double)$productAttributeCalculatedPrice
                        );
                        $productAttributeCalculatedPrice = \JText::sprintf(
                            'COM_REDSHOP_CART_PRODUCT_ATTRIBUTE_CALCULATED_PRICE',
                            $productAttributeCalculatedPrice
                        );

                        $replaceAttrDdata['{product_attribute_name}']             = $productAttributeName;
                        $replaceAttrDdata['{product_attribute_value}']            = $productAttributeValue;
                        $replaceAttrDdata['{product_attribute_value_price}']      = $productAttributeValuePrice;
                        $replaceAttrDdata['{product_attribute_calculated_price}'] = $productAttributeCalculatedPrice;

                        $productDetail .= $this->strReplace($replaceAttrDdata, $templateAttrMiddle);
                    }
                }

                $cartHtml = $templateAttrData['begin'] . $productDetail . $templateAttrData['end'];
            }

            if (isset($cart[$i]['cart_attribute'])) {
                $replaceData['{attribute_label}'] = \JText::_("COM_REDSHOP_ATTRIBUTE");
            } else {
                $replaceData['{attribute_label}'] = '';
            }

            $replaceData['{product_number}']           = $product->product_number ?? '';
            $replaceData['{product_vat}']              = ($cart[$i]['product_vat'] ?? 0) * ($cart[$i]['quantity'] ?? 0);
            $replaceData['{product_userfields}']       = \RedshopHelperProduct::getProductUserField($i);
            $replaceData['{product_customfields}']     = \RedshopHelperProduct::getProductField($i);
            $replaceData['{product_customfields_lbl}'] = \JText::_("COM_REDSHOP_PRODUCT_CUSTOM_FIELD");
            $discountCalcOutput                        = isset($cart[$i]['discount_calc_output']) && $cart[$i]['discount_calc_output']
                ? $cart[$i]['discount_calc_output'] . "<br />" : "";

            $cartHtml = \RedshopTagsReplacer::_(
                'attribute',
                $cartHtml,
                array(
                    'product_attribute' => $discountCalcOutput . $cartAttribute,
                )
            );

            $replaceData['{product_accessory}']           = $cartAccessory;
            $replaceData['{product_attribute_price}']     = '';
            $replaceData['{product_attribute_number}']    = '';
            $cartHtml                                     = \RedshopHelperProduct::getProductOnSaleComment(
                $product,
                $cartHtml
            );
            $replaceData['{product_old_price}']           = $productOldPrice;
            $replaceData['{product_wrapper}']             = $wrapperName;
            $replaceData['{product_thumb_image}']         = $productImage;
            $replaceData['{attribute_price_without_vat}'] = '';
            $replaceData['{attribute_price_with_vat}']    = '';

            // ProductFinderDatepicker Extra Field Start
            $cartHtml = \RedshopHelperProduct::getProductFinderDatepickerValue($cartHtml, $productId, $fieldArray);

            $productPriceNoVAT = $cart[$i]['product_price_excl_vat'] ?? 0;

            if (!$quotationMode || ($quotationMode && \Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))) {
                $replaceData['{product_price_excl_vat}']       = \RedshopHelperProductPrice::formattedPrice(
                    $productPriceNoVAT
                );
                $replaceData['{product_total_price_excl_vat}'] = \RedshopHelperProductPrice::formattedPrice(
                    $productPriceNoVAT * $quantity
                );
            } else {
                $replaceData['{product_price_excl_vat}']       = '';
                $replaceData['{product_total_price_excl_vat}'] = '';
            }

            if (isset($product->product_type) && ($product->product_type == 'subscription')) {
                $subscriptionDetail                        = \RedshopHelperProduct::getProductSubscriptionDetail(
                    $product->product_id,
                    $cart[$i]['subscription_id']
                );
                $selectedSubscription                      = $subscriptionDetail->subscription_period . " " . $subscriptionDetail->period_type;
                $replaceData['{product_subscription_lbl}'] = \JText::_('COM_REDSHOP_SUBSCRIPTION');
                $replaceData['{product_subscription}']     = $selectedSubscription;
            } else {
                $replaceData['{product_subscription_lbl}'] = '';
                $replaceData['{product_subscription}']     = '';
            }

            if ($isReplaceButton) {
                $updateAttribute = '';

                if ($view == 'cart') {
                    $updateAttribute = \RedshopLayoutHelper::render(
                        'tags.common.modal',
                        [
                            'class' => 'modal',
                            'x'     => '550',
                            'y'     => '400',
                            'link'  => \JURI::root(
                                ) . 'index.php?option=com_redshop&view=cart&layout=change_attribute&tmpl=component&pid=' . $productId . '&cart_index=' . $i,
                            'text'  => \JText::_('COM_REDSHOP_CHANGE_ATTRIBUTE')
                        ],
                        '',
                        $optionLayout
                    );
                }

                if ($cartAttribute != "") {
                    $replaceData['{attribute_change}'] = $updateAttribute;
                } else {
                    $replaceData['{attribute_change}'] = '';
                }
            } else {
                $replaceData['{attribute_change}'] = '';
            }

            // Product extra fields.
            $cartHtml = \RedshopHelperProductTag::getExtraSectionTag(
                \Redshop\Helper\ExtraFields::getSectionFieldNames(\RedshopHelperExtrafields::SECTION_PRODUCT),
                $productId,
                "1",
                $cartHtml
            );

            $cartItem                             = 'productId';
            $cartHtml                             = \RedshopHelperTax::replaceVatInformation($cartHtml);
            $replaceData['{product_price}']       = $priceHtml;
            $replaceData['{product_total_price}'] = $totalPriceHtml;
        }

        if ($isReplaceButton) {
            $updateImage = '';

            if ($view == 'checkout') {
                $updateCart = $quantity;
            } else {
                if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . \Redshop::getConfig()->get('ADDTOCART_UPDATE'))) {
                    $updateImage = \Redshop::getConfig()->get('ADDTOCART_UPDATE');
                } else {
                    $updateImage = "defaultupdate.png";
                }

                $updateCart = \RedshopLayoutHelper::render(
                    'tags.cart.update_cart',
                    [
                        'i'           => $i,
                        'token'       => $token,
                        'quantity'    => $quantity,
                        'cartItem'    => $cartItem,
                        'productId'   => ${$cartItem},
                        'itemId'      => $itemId,
                        'updateImage' => $updateImage
                    ],
                    '',
                    $optionLayout
                );
            }

            $updateCartMinusPlus = \RedshopLayoutHelper::render(
                'tags.cart.quantity_increase_decrease',
                [
                    'i'           => $i,
                    'quantity'    => $quantity,
                    'cartItem'    => $cartItem,
                    'productId'   => ${$cartItem},
                    'itemId'      => $itemId,
                    'updateImage' => $updateImage
                ],
                '',
                $optionLayout
            );

            if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . \Redshop::getConfig()->get('ADDTOCART_DELETE'))) {
                $deleteImg = \Redshop::getConfig()->get('ADDTOCART_DELETE');
            } else {
                $deleteImg = "defaultcross.png";
            }

            if ($view == 'checkout') {
                $removeProduct = '';
            } else {
                $removeProduct = \RedshopLayoutHelper::render(
                    'tags.cart.remove_product',
                    [
                        'i'         => $i,
                        'cartItem'  => $cartItem,
                        'productId' => ${$cartItem},
                        'deleteImg' => $deleteImg
                    ],
                    '',
                    $optionLayout
                );
            }

            if (\Redshop::getConfig()->get('QUANTITY_TEXT_DISPLAY')) {
                if (strstr($cartHtml, "{quantity_increase_decrease}") && $view != 'checkout') {
                    $replaceData['{quantity_increase_decrease}'] = $updateCartMinusPlus;
                    $replaceData['{update_cart}']                = '';
                } else {
                    $replaceData['{quantity_increase_decrease}'] = $updateCart;
                    $replaceData['{update_cart}']                = $updateCart;
                }

                $replaceData['{remove_product}'] = $removeProduct;
            } else {
                $replaceData['{quantity_increase_decrease}'] = $updateCartMinusPlus;
                $replaceData['{update_cart}']                = $quantity;
                $replaceData['{remove_product}']             = $removeProduct;
            }
        } else {
            $replaceData['{update_cart}']    = $quantity;
            $replaceData['{remove_product}'] = '';
        }

        return $this->strReplace($replaceData, $cartHtml);
    }
}