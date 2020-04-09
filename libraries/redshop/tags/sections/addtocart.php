<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since  2.0.4
 */
class RedshopTagsSectionsAddToCart extends RedshopTagsAbstract
{
    public function init()
    {
    }

    public function replace()
    {
        $productId          = $this->data['productId'];
        $totalAttr          = $this->data['totalAttr'];
        $accessoryId        = $this->data['accessoryId'];
        $relatedProductId   = $this->data['relatedProductId'];
        $productPreOrder    = $this->data['productPreOrder'];
        $product            = $this->data['product'];
        $userId             = $this->data['userId'];
        $giftCardId         = $this->data['giftcardId'];
        $totalAccessory     = $this->data['totalAccessory'];
        $countNoUserField   = $this->data['countNoUserField'];
        $cartTemplate       = $this->data['cartTemplate'];
        $categoryId         = $this->data['categoryId'];
        $isChild            = $this->data['isChild'];
        $taxExemptAddToCart = $this->data['taxExemptAddToCart'];
        $userFields         = $this->data['userFields'];
        $content            = $this->data['content'];
        $fieldSection       = $this->data['fieldSection'];
        $template           = $this->template;

        $input                = JFactory::getApplication()->input;
        $itemId               = $input->getInt('Itemid');
        $productQuantity      = $input->get('product_quantity');
        $layout               = $input->getCmd('layout');
        $cart                 = \Redshop\Cart\Helper::getCart();
        $isAjax               = 0;
        $prePrefix            = "";
        $preSelectedAttrImage = "";

        if ($layout == "viewajaxdetail") {
            $isAjax    = 1;
            $prePrefix = "ajax_";
        }

        $prefix = $prePrefix . "prd_";

        if ($accessoryId != 0) {
            $prefix = $prePrefix . "acc_";
        } elseif ($relatedProductId != 0) {
            $prefix = $prePrefix . "rel_";
        }

        if (!empty($moduleId)) {
            $prefix = $prefix . $moduleId . "_";
        }

        $totalRequiredAttributes = "";
        $totalRequiredProperties = '';
        $isPreOrderStockExists   = '';

        if ($giftCardId != 0) {
            $productPrice      = $product->giftcard_price;
            $productPriceNoVat = 0;
            $productOldPrice   = 0;
            $isStockExist      = true;
            $maxQuantity       = 0;
            $minQuantity       = 0;
        } else {
            // IF PRODUCT CHILD IS EXISTS THEN DONT SHOW PRODUCT ATTRIBUTES
            if ($isChild) {
                $content = str_replace("{form_addtocart:$cartTemplate->name}", "", $content);

                return $content;
            } elseif (\RedshopHelperProduct::isProductDateRange($userFields, $productId)) {
                // New type custom field - Selection based on selected conditions
                $content = str_replace(
                    "{form_addtocart:$cartTemplate->name}",
                    \JText::_('COM_REDSHOP_PRODUCT_DATE_FIELD_EXPIRED'),
                    $content
                );

                return $content;
            } elseif ($product->not_for_sale) {
                $content = str_replace("{form_addtocart:$cartTemplate->name}", '', $content);

                return $content;
            } elseif (!$taxExemptAddToCart) {
                $content = str_replace("{form_addtocart:$cartTemplate->name}", '', $content);

                return $content;
            } elseif (!\Redshop::getConfig()->get('SHOW_PRICE')) {
                $content = str_replace("{form_addtocart:$cartTemplate->name}", '', $content);

                return $content;
            } elseif ($product->expired == 1) {
                $content = str_replace(
                    "{form_addtocart:$cartTemplate->name}",
                    \Redshop::getConfig()->get('PRODUCT_EXPIRE_TEXT'),
                    $content
                );

                return $content;
            }

            // Get stock for Product
            $isStockExist = \RedshopHelperStockroom::isStockExists($productId);

            if ($totalAttr > 0 && !$isStockExist) {
                $properties  = \RedshopHelperProduct_Attribute::getAttributeProperties(0, 0, $productId);
                $propertyIds = array();

                foreach ($properties as $attributeProperties) {
                    $isSubPropertyStock = false;
                    $subProperties      = \RedshopHelperProduct_Attribute::getAttributeSubProperties(
                        0,
                        $attributeProperties->property_id
                    );

                    if (!empty($subProperties)) {
                        $subPropertyIds = array();

                        foreach ($subProperties as $subProperty) {
                            $subPropertyIds[] = $subProperty->subattribute_color_id;
                        }

                        $isSubPropertyStock = \RedshopHelperStockroom::isStockExists(
                            implode(',', $subPropertyIds),
                            'subproperty'
                        );

                        if ($isSubPropertyStock) {
                            $isStockExist = $isSubPropertyStock;
                            break;
                        }
                    }

                    if ($isSubPropertyStock) {
                        $isStockExist = $isSubPropertyStock;

                        break;
                    }

                    $propertyIds[] = $attributeProperties->property_id;
                }

                if (!$isStockExist) {
                    $isStockExist = (boolean)\RedshopHelperStockroom::isStockExists(
                        implode(',', $propertyIds),
                        'property'
                    );
                }
            }

            $defaultQuantity = \Redshop\Cart\Helper::getDefaultQuantity($productId, $content);

            $productNetPrice = \RedshopHelperProductPrice::getNetPrice(
                $productId,
                $userId,
                $defaultQuantity,
                $content
            );

            $productPrice      = $productNetPrice['product_price'] * $defaultQuantity;
            $productPriceNoVat = $productNetPrice['product_price_novat'] * $defaultQuantity;
            $productOldPrice   = $productNetPrice['product_old_price'] * $defaultQuantity;

            if ($product->not_for_sale) {
                $productPrice = 0;
            }

            $maxQuantity = $product->max_order_product_quantity;
            $minQuantity = $product->min_order_product_quantity;
        }

        $stockDisplay    = false;
        $preOrderDisplay = false;
        $cartDisplay     = false;
        $displayText     = \JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');

        if (!$isStockExist) {
            if (($productPreOrder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
                || ($productPreOrder == "yes")
                || ($productPreOrder == "" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER'))) {
                // Get preorder stock for Product
                $isPreOrderStockExists = \RedshopHelperStockroom::isPreorderStockExists($productId);

                if ($totalAttr > 0 && !$isPreOrderStockExists) {
                    $attributeProperties = \RedshopHelperProduct_Attribute::getAttributeProperties(0, 0, $productId);

                    foreach ($attributeProperties as $attributeProperty) {
                        $isSubPropertyStock     = false;
                        $attributeSubProperties = \RedshopHelperProduct_Attribute::getAttributeSubProperties(
                            0,
                            $attributeProperty->property_id
                        );

                        foreach ($attributeSubProperties as $attributeSubProperty) {
                            $isSubPropertyStock = \RedshopHelperStockroom::isPreorderStockExists(
                                $attributeSubProperty->subattribute_color_id,
                                'subproperty'
                            );

                            if ($isSubPropertyStock) {
                                $isPreOrderStockExists = $isSubPropertyStock;
                                break;
                            }
                        }

                        if ($isSubPropertyStock) {
                            break;
                        }

                        $isPropertyStockExist = \RedshopHelperStockroom::isPreorderStockExists(
                            $attributeProperty->property_id,
                            "property"
                        );

                        if ($isPropertyStockExist) {
                            $isPreOrderStockExists = $isPropertyStockExist;
                            break;
                        }
                    }
                }

                // Check preorder stock
                if (!$isPreOrderStockExists) {
                    $stockDisplay = true;
                    $addCartFlag  = true;
                    $displayText  = \JText::_('COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE');
                } else {
                    //$pre_order_value = 1;
                    $preOrderDisplay      = true;
                    $addCartFlag          = true;
                    $productAvailableDate = "";

                    if ($product->product_availability_date != "") {
                        $productAvailableDate = \RedshopHelperDatetime::convertDateFormat(
                            $product->product_availability_date
                        );
                    }
                }
            } else {
                $stockDisplay = true;
                $addCartFlag  = true;
            }
        } else {
            $cartDisplay = true;
            $addCartFlag = true;
        }

        $productAvailableDate = "";
        $preOrderLabel        = \JText::_('COM_REDSHOP_PRE_ORDER');
        $allowPreOrderLabel   = str_replace(
            "{availability_date}",
            $productAvailableDate,
            \Redshop::getConfig()->get('ALLOW_PRE_ORDER_MESSAGE')
        );
        $preOrderImage        = \Redshop::getConfig()->get('PRE_ORDER_IMAGE');
        $tooltip              = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ?
            \JText::_('COM_REDSHOP_REQUEST_A_QUOTE_TOOLTIP') : \JText::_('COM_REDSHOP_ADD_TO_CART_TOOLTIP');
        $requestLabel         = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ?
            \JText::_('COM_REDSHOP_REQUEST_A_QUOTE') : \JText::_('COM_REDSHOP_ADD_TO_CART');
        $requestImage         = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ?
            \Redshop::getConfig()->get('REQUESTQUOTE_IMAGE') : \Redshop::getConfig()->get('ADDTOCART_IMAGE');
        $requestBackground    = (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ?
            \Redshop::getConfig()->get('REQUESTQUOTE_BACKGROUND') : \Redshop::getConfig()->get('ADDTOCART_BACKGROUND');

        if ($totalAttr > 0) {
            $attributeSets = array();

            if ($product->attribute_set_id > 0) {
                $attributeSets = \Redshop\Product\Attribute::getProductAttribute(
                    0,
                    $product->attribute_set_id,
                    0,
                    1,
                    1
                );
            }

            $requiredAttributes = \Redshop\Product\Attribute::getProductAttribute($productId, 0, 0, 1, 1);
            $requiredAttributes = array_merge($requiredAttributes, $attributeSets);

            foreach ($requiredAttributes as $requiredAttribute) {
                $totalRequiredAttributes .= \JText::_('COM_REDSHOP_ATTRIBUTE_IS_REQUIRED') . " "
                    . urldecode($requiredAttribute->attribute_name) . "\n";
            }

            $requiredProperties = \RedshopHelperProduct_Attribute::getAttributeProperties(0, 0, $productId, 0, 1);

            foreach ($requiredProperties as $requiredProperty) {
                $totalRequiredProperties .= \JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED') . " "
                    . urldecode($requiredProperty->property_name) . "\n";
            }
        }

        $stockId = $prefix . $productId;
        $cartId  = 0;

        if ($addCartFlag) {
            if ($giftCardId == 0 && $categoryId == 0) {
                $categoryId = \RedshopHelperProduct::getCategoryProduct($productId);
            }


            if (count($userFields) > 0) {
                $productHiddenUserFields = '<table>';
                $idx                     = 0;

                if (isset($cart['idx'])) {
                    $idx = (int)($cart['idx']);
                }

                for ($j = 0; $j < $idx; $j++) {
                    if ($giftCardId != 0) {
                        if ($cart[$j]['giftcard_id'] == $productId) {
                            $cartId = $j;
                        }
                    } else {
                        if ($cart[$j]['product_id'] == $productId) {
                            $cartId = $j;
                        }
                    }
                }

                foreach ($userFields as $userField) {
                    $result = \Redshop\Fields\SiteHelper::listAllUserFields(
                        $userField,
                        $fieldSection,
                        "hidden",
                        $cartId,
                        $isAjax,
                        $productId
                    );

                    $productHiddenUserFields .= $result[1];
                }

                $productHiddenUserFields .= '</table>';
            }

            // Start Hidden attribute image in cart
            $attributes = \Redshop\Product\Attribute::getProductAttribute($productId);

            if (count($attributes) > 0) {
                $selectedPropertyId    = 0;
                $selectedSubPropertyId = 0;

                foreach ($attributes as $attribute) {
                    $selectedId          = array();
                    $attributeProperties = \RedshopHelperProduct_Attribute::getAttributeProperties(
                        0,
                        $attribute->attribute_id,
                        $productId
                    );

                    if ($attribute->text != "" && count($attributeProperties) > 0) {
                        foreach ($attributeProperties as $attributeProperty) {
                            if ($attributeProperty->setdefault_selected) {
                                $selectedId[] = $attributeProperty->property_id;
                            }
                        }

                        if (count($selectedId) > 0) {
                            $selectedPropertyId     = $selectedId[count($selectedId) - 1];
                            $attributeSubProperties = \RedshopHelperProduct_Attribute::getAttributeSubProperties(
                                0,
                                $selectedPropertyId
                            );
                            $selectedId             = array();

                            foreach ($attributeSubProperties as $attributeSubProperty) {
                                if ($attributeSubProperty->setdefault_selected) {
                                    $selectedId[] = $attributeSubProperty->subattribute_color_id;
                                }
                            }

                            if (count($selectedId) > 0) {
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

            if (!$isStockExist) {
                if (($productPreOrder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
                    || ($productPreOrder == "yes")
                    || ($productPreOrder == "" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER'))) {
                    // Get preorder stock for Product
                    $isPreOrderStockExists = \RedshopHelperStockroom::isPreorderStockExists($productId);

                    if ($totalAttr > 0 && !$isPreOrderStockExists) {
                        $attributeProperties = \RedshopHelperProduct_Attribute::getAttributeProperties(
                            0,
                            0,
                            $productId
                        );

                        foreach ($attributeProperties as $attributeProperty) {
                            $isSubPropertyStock     = false;
                            $attributeSubProperties = \RedshopHelperProduct_Attribute::getAttributeSubProperties(
                                0,
                                $attributeProperty->property_id
                            );

                            foreach ($attributeSubProperties as $attributeSubProperty) {
                                $isSubPropertyStock = \RedshopHelperStockroom::isPreorderStockExists(
                                    $attributeSubProperty->subattribute_color_id,
                                    'subproperty'
                                );

                                if ($isSubPropertyStock) {
                                    $isPreOrderStockExists = $isSubPropertyStock;
                                    break;
                                }
                            }

                            if ($isSubPropertyStock) {
                                break;
                            }

                            $isPropertyStockExist = \RedshopHelperStockroom::isPreorderStockExists(
                                $attributeProperty->property_id,
                                "property"
                            );

                            if ($isPropertyStockExist) {
                                $isPreOrderStockExists = $isPropertyStockExist;
                                break;
                            }
                        }
                    }

                    // Check preorder stock$
                    if (!$isPreOrderStockExists) {
                        $stockDisplay = true;
                        $addCartFlag  = true;
                        $displayText  = \JText::_('COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE');
                    } else {
                        //$pre_order_value = 1;
                        $preOrderDisplay      = true;
                        $addCartFlag          = true;
                        $productAvailableDate = "";

                        if ($product->product_availability_date != "") {
                            $productAvailableDate = \RedshopHelperDatetime::convertDateFormat(
                                $product->product_availability_date
                            );
                        }
                    }
                } else {
                    $stockDisplay = true;
                    $addCartFlag  = true;
                }
            } else {
                $cartDisplay = true;
                $addCartFlag = true;
            }

            $stockStyle    = '';
            $cartStyle     = '';
            $preOrderStyle = '';

            if ($preOrderDisplay) {
                $stockStyle    = 'style="display:none"';
                $cartStyle     = 'style="display:none"';
                $preOrderStyle = '';

                if (\Redshop::getConfig()->get('USE_AS_CATALOG')) {
                    $preOrderStyle = 'style="display:none"';
                }
            }

            if ($stockDisplay) {
                $stockStyle = '';

                if (\Redshop::getConfig()->get('USE_AS_CATALOG')) {
                    $stockStyle = 'style="display:none"';
                }

                $cartStyle     = 'style="display:none"';
                $preOrderStyle = 'style="display:none"';
            }

            if ($cartDisplay) {
                $stockStyle    = 'style="display:none"';
                $cartStyle     = '';
                $preOrderStyle = 'style="display:none"';

                if (
                    \Redshop::getConfig()->get('USE_AS_CATALOG') ||
                    (
                        is_object(\RedshopHelperUser::getShopperGroupData($userId)) &&
                        \RedshopHelperUser::getShopperGroupData($userId)->use_as_catalog == 'yes'
                    )
                ) {
                    $cartStyle = 'style="display:none"';
                }
            }

            $cart         = \Redshop\Cart\Helper::getCart();
            $cartFromName = 'addtocart_' . $prefix . $productId;
            $cartTitle    = ' title="" ';
            $cartIcon     = '';

            // Trigger event which hepl us to add new JS functions to the Add To Cart button onclick
            $addToCartClickJS = \RedshopHelperUtility::getDispatcher()->trigger(
                'onAddToCartClickJS',
                array($product, $cart)
            );

            if (!empty($addToCartClickJS)) {
                $addToCartClickJS = implode('', $addToCartClickJS);
            } else {
                $addToCartClickJS = "";
            }

            if ($giftCardId) {
                $onclick = ' onclick="' . $addToCartClickJS . 'if(validateEmail()){if(displayAddtocartForm(\'' .
                    $cartFromName . '\',\'' .
                    $productId . '\',\'' .
                    $relatedProductId . '\',\'' .
                    $giftCardId . '\', \'user_fields_form\')){checkAddtocartValidation(\'' .
                    $cartFromName . '\',\'' .
                    $productId . '\',\'' .
                    $relatedProductId . '\',\'' .
                    $giftCardId . '\', \'user_fields_form\',\'' .
                    $totalAttr . '\',\'' .
                    $totalAccessory . '\',\'' .
                    $countNoUserField . '\');}}" ';
            } else {
                $onclick = ' onclick="' . $addToCartClickJS . 'if(displayAddtocartForm(\'' . $cartFromName . '\',\'' . $productId
                    . '\',\'' . $relatedProductId . '\',\'' . $giftCardId
                    . '\', \'user_fields_form\')){checkAddtocartValidation(\'' . $cartFromName . '\',\''
                    . $productId . '\',\'' . $relatedProductId . '\',\'' . $giftCardId . '\', \'user_fields_form\',\''
                    . $totalAttr . '\',\'' . $totalAccessory . '\',\'' . $countNoUserField . '\');}" ';
            }

            $class    = '';
            $title    = '';
            $checkTag = false;

            if ($productQuantity) {
                $quantity = $productQuantity;
            } else {
                if ($giftCardId != 0) {
                    $quantity = 1;
                } elseif ($product->min_order_product_quantity > 0) {
                    $quantity = $product->min_order_product_quantity;
                } else {
                    $quantity = 1;
                }
            }

            if ($this->isTagExists('{addtocart_quantity}')) {
                $checkTag = true;
                $this->replaceQuantity($productId, $stockId, $quantity, $template);
            } elseif ($this->isTagExists('{addtocart_quantity_increase_decrease}')) {
                $checkTag = true;
                $this->replaceQuantityIncreaseDecrease($productId, $cartId, $itemId, $quantity, $template);
            } elseif ($this->isTagExists('{addtocart_quantity_selectbox}')) {
                $checkTag       = true;
                $selectBoxValue = ($product->quantity_selectbox_value) ?
                    $product->quantity_selectbox_value : \Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE');
                $quantityBoxes  = explode(",", $selectBoxValue);
                $quantityBoxes  = array_merge(array(), array_unique($quantityBoxes));
                sort($quantityBoxes);

                $this->replaceQuantitySelectbox(
                    $productId,
                    $cartId,
                    $itemId,
                    $quantityBoxes,
                    $quantity,
                    $stockId,
                    $template
                );
            }

            if ($this->isTagExists('{addtocart_tooltip}')) {
                $class                                     = 'class="editlinktip hasTip"';
                $title                                     = ' title="' . $tooltip . '" ';
                $this->replacements["{addtocart_tooltip}"] = '';
                $template                                  = $this->strReplace($this->replacements, $template);
            }

            if ($this->isTagExists('{addtocart_button}')) {
                $this->replaceButton(
                    $class,
                    $stockId,
                    $title,
                    $cartStyle,
                    $onclick,
                    $cartTitle,
                    $requestLabel,
                    $productId,
                    $template
                );
            }

            if ($this->isTagExists('{addtocart_image_aslink}')) {
                $this->replaceImageAslink(
                    $requestImage,
                    $class,
                    $stockId,
                    $title,
                    $cartStyle,
                    $onclick,
                    $cartTitle,
                    $requestLabel,
                    $productId,
                    $template
                );
            }

            if ($this->isTagExists('{addtocart_image}')) {
                $fileExist = \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $requestBackground);
                $this->replaceImage(
                    $requestBackground,
                    $class,
                    $stockId,
                    $title,
                    $cartStyle,
                    $onclick,
                    $cartTitle,
                    $requestLabel,
                    $productId,
                    $fileExist,
                    $template
                );
            }

            if ($this->isTagExists('{addtocart_link}')) {
                $this->replaceLink(
                    $requestLabel,
                    $productId,
                    $title,
                    $stockId,
                    $cartStyle,
                    $onclick,
                    $cartTitle,
                    $class,
                    $template
                );
            }

            if ($this->isTagExists('{quantity_lbl}')) {
                $quantityLbl = RedshopLayoutHelper::render(
                    'tags.common.label',
                    array(
                        'text'  => JText::_('COM_REDSHOP_QUANTITY_LBL'),
                        'id'    => 'quantity' . $productId,
                        'class' => 'ajax_cart_box_title'
                    ),
                    '',
                    RedshopLayoutHelper::$layoutOption
                );

                $this->replacements["{quantity_lbl}"] = $quantityLbl;
                $template                             = $this->strReplace($this->replacements, $template);
            }

            $result = \RedshopHelperUtility::getDispatcher()->trigger(
                'onDisplayText',
                array($product->product_id, $cart)
            );

            if (!empty($result)) {
                $displayText = $result[0];
            }

            $layout = RedshopLayoutHelper::render(
                'tags.addtocart.template',
                array(
                    'requestLabel'            => $requestLabel,
                    'productId'               => $productId,
                    'title'                   => $title,
                    'stockId'                 => $stockId,
                    'cartStyle'               => $cartStyle,
                    'onclick'                 => $onclick,
                    'cartTitle'               => $cartTitle,
                    'content'                 => $template,
                    'cartFromName'            => $cartFromName,
                    'isPreorderStockExists'   => $isPreOrderStockExists,
                    'isStockExist'            => $isStockExist,
                    'productPreOrder'         => $productPreOrder,
                    'categoryId'              => $categoryId,
                    'itemId'                  => $itemId,
                    'productPrice'            => $productPrice,
                    'productOldPrice'         => $productOldPrice,
                    'productPriceNoVat'       => $productPriceNoVat,
                    'minQuantity'             => $minQuantity,
                    'maxQuantity'             => $maxQuantity,
                    'totalRequiredAttributes' => $totalRequiredAttributes,
                    'totalRequiredProperties' => $totalRequiredProperties,
                    'preSelectedAttrImage'    => $preSelectedAttrImage,
                    'giftcardId'              => $giftCardId,
                    'stockStyle'              => $stockStyle,
                    'preOrderImage'           => $preOrderImage,
                    'preOrderStyle'           => $preOrderStyle,
                    'quantity'                => $quantity,
                    'cartIcon'                => $cartIcon,
                    'fileExitPreOrderImage'   => \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $preOrderImage),
                    'preOrderLabel'           => $preOrderLabel,
                    'displayText'             => $displayText,
                    'checkTag'                => $checkTag
                ),
                '',
                array(
                    'component'  => 'com_redshop',
                    'layoutType' => 'Twig',
                    'layoutOf'   => 'library'
                )
            );

            // Trigger event on Add to Cart
            \RedshopHelperUtility::getDispatcher()->trigger('onAddtoCart', array(&$layout, $product, $cartFromName, 0));
        }

        $this->template = $layout;

        return parent::replace();
    }

    public function replaceQuantity($productId, $stockId, $quantity, &$template)
    {
        $quantityAddtocart = RedshopLayoutHelper::render(
            'tags.addtocart.quantity',
            array(
                'productId' => $productId,
                'stockId'   => $stockId,
                'quantity'  => $quantity
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $this->replacements["{addtocart_quantity}"]                   = $quantityAddtocart;
        $this->replacements["{addtocart_quantity_increase_decrease}"] = '';
        $template                                                     = $this->strReplace(
            $this->replacements,
            $template
        );
    }

    public function replaceQuantityIncreaseDecrease($productId, $cartId, $itemId, $quantity, &$template)
    {
        $quantityIncreaseDecrease = RedshopLayoutHelper::render(
            'tags.addtocart.quantity_increase_decrease',
            array(
                'productId' => $productId,
                'cartId'    => $cartId,
                'itemId'    => $itemId,
                'quantity'  => $quantity
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $this->replacements["{addtocart_quantity_increase_decrease}"] = $quantityIncreaseDecrease;
        $this->replacements["{addtocart_quantity}"]                   = '';
        $template                                                     = $this->strReplace(
            $this->replacements,
            $template
        );
    }

    public function replaceQuantitySelectbox(
        $productId,
        $cartId,
        $itemId,
        $quantityBoxes,
        $quantity,
        $stockId,
        &$template
    ) {
        $quantitySelectbox = RedshopLayoutHelper::render(
            'tags.addtocart.addtocart_quantity_selectbox',
            array(
                'productId'     => $productId,
                'cartId'        => $cartId,
                'itemId'        => $itemId,
                'quantityBoxes' => $quantityBoxes,
                'quantity'      => $quantity,
                'stockId'       => $stockId
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $this->replacements["{'addtocart_quantity_selectbox'}"]       = $quantitySelectbox;
        $this->replacements["{addtocart_quantity_increase_decrease}"] = '';
        $this->replacements["{addtocart_quantity}"]                   = '';
        $template                                                     = $this->strReplace(
            $this->replacements,
            $template
        );
    }

    public function replaceButton(
        $class,
        $stockId,
        $title,
        $cartStyle,
        $onclick,
        $cartTitle,
        $requestLabel,
        $productId,
        &$template
    ) {
        $button = RedshopLayoutHelper::render(
            'tags.addtocart.button',
            array(
                'class'        => $class,
                'stockId'      => $stockId,
                'title'        => $title,
                'cartStyle'    => $cartStyle,
                'onclick'      => $onclick,
                'cartTitle'    => $cartTitle,
                'requestLabel' => $requestLabel,
                'productId'    => $productId,
                'template'     => $template
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $this->replacements['{addtocart_button}'] = $button;
        $template                                 = $this->strReplace($this->replacements, $template);
    }

    public function replaceImageAslink(
        $requestImage,
        $class,
        $stockId,
        $title,
        $cartStyle,
        $onclick,
        $cartTitle,
        $requestLabel,
        $productId,
        &$template
    ) {
        $imageAsLinks = RedshopLayoutHelper::render(
            'tags.addtocart.image_aslink',
            array(
                'requestImage' => $requestImage,
                'class'        => $class,
                'stockId'      => $stockId,
                'title'        => $title,
                'cartStyle'    => $cartStyle,
                'onclick'      => $onclick,
                'cartTitle'    => $cartTitle,
                'requestLabel' => $requestLabel,
                'productId'    => $productId
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $this->replacements["{addtocart_image_aslink}"] = $imageAsLinks;
        $template                                       = $this->strReplace($this->replacements, $template);
    }

    public function replaceImage(
        $requestBackground,
        $class,
        $stockId,
        $title,
        $cartStyle,
        $onclick,
        $cartTitle,
        $requestLabel,
        $productId,
        $fileExist,
        &$template
    ) {
        $image = RedshopLayoutHelper::render(
            'tags.addtocart.image',
            array(
                'requestBackground' => $requestBackground,
                'class'             => $class,
                'stockId'           => $stockId,
                'title'             => $title,
                'cartStyle'         => $cartStyle,
                'onclick'           => $onclick,
                'cartTitle'         => $cartTitle,
                'requestLabel'      => $requestLabel,
                'productId'         => $productId,
                'fileExist'         => $fileExist
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $this->replacements["{addtocart_image}"] = $image;
        $template                                = $this->strReplace($this->replacements, $template);
    }

    public function replaceLink(
        $requestLabel,
        $productId,
        $title,
        $stockId,
        $cartStyle,
        $onclick,
        $cartTitle,
        $class,
        &$template
    ) {
        $link = RedshopLayoutHelper::render(
            'tags.addtocart.link',
            array(
                'requestLabel' => $requestLabel,
                'productId'    => $productId,
                'title'        => $title,
                'stockId'      => $stockId,
                'cartStyle'    => $cartStyle,
                'onclick'      => $onclick,
                'cartTitle'    => $cartTitle,
                'class'        => $class
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $this->replacements['{addtocart_link}'] = $link;
        $template                               = $this->strReplace($this->replacements, $template);
    }
}