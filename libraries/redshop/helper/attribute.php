<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Attribute Helper
 *
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 * @since       2.0.3
 */
class RedshopHelperAttribute
{
    /**
     * Method for replace attribute data in template.
     *
     * @param   int     $productId           Product ID
     * @param   int     $accessoryId         Accessory ID
     * @param   int     $relatedProductId    Related product ID
     * @param   array   $attributes          List of attribute data.
     * @param   string  $templateContent     HTML content of template.
     * @param   object  $attributeTemplate   List of attribute templates.
     * @param   bool    $isChild             Is child?
     * @param   array   $selectedAttributes  Preselected attribute list.
     * @param   int     $displayIndCart      Display in cart?
     * @param   bool    $onlySelected        True for just render selected / pre-selected attribute. False as normal.
     *
     * @return  string                       HTML content with replaced data.
     *
     * @throws  Exception
     * @since      2.0.3
     *
     * @deprecated use RedshopTagsReplacer with section is attributes
     *
     */
    public static function replaceAttributeData(
        $productId = 0,
        $accessoryId = 0,
        $relatedProductId = 0,
        $attributes = array(),
        $templateContent = '',
        $attributeTemplate = null,
        $isChild = false,
        $selectedAttributes = array(),
        $displayIndCart = 1,
        $onlySelected = false
    ) {
        return RedshopTagsReplacer::_(
            'attributes',
            $templateContent,
            array(
                'productId'          => $productId,
                'accessoryId'        => $accessoryId,
                'relatedProductId'   => $relatedProductId,
                'attributes'         => $attributes,
                'attributeTemplate'  => $attributeTemplate,
                'isChild'            => $isChild,
                'selectedAttributes' => $selectedAttributes,
                'displayIndCart'     => $displayIndCart,
                'onlySelected'       => $onlySelected,
            )
        );
    }

    /**
     * Method for replace attribute data with allow add to cart in template.
     *
     * @param   int     $productId          Product ID
     * @param   int     $accessoryId        Accessory ID
     * @param   int     $relatedProductId   Related product ID
     * @param   array   $attributes         List of attribute data.
     * @param   string  $templateContent    HTML content of template.
     * @param   object  $attributeTemplate  List of attribute templates.
     * @param   bool    $isChild            Is child?
     * @param   bool    $onlySelected       True for just render selected / pre-selected attribute. False as normal.
     *
     * @return  string                      HTML content with replaced data.
     *
     * @since   2.0.3
     */
    public static function replaceAttributeWithCartData(
        $productId = 0,
        $accessoryId = 0,
        $relatedProductId = 0,
        $attributes = array(),
        $templateContent = '',
        $attributeTemplate = null,
        $isChild = false,
        $onlySelected = false
    ) {
        $user_id = 0;

        if (empty($attributeTemplate)) {
            return $templateContent;
        }

        if ($isChild || !count($attributes)) {
            return str_replace("{attributewithcart_template:$attributeTemplate->name}", "", $templateContent);
        }

        $layout    = JFactory::getApplication()->input->getCmd('layout', '');
        $prePrefix = "";
        $isAjax    = false;

        if ($layout == 'viewajaxdetail') {
            $prePrefix = "ajax_";
            $isAjax    = true;
        }

        if ($accessoryId != 0) {
            $prefix = $prePrefix . "acc_";
        } elseif ($relatedProductId != 0) {
            $prefix = $prePrefix . "rel_";
        } else {
            $prefix = $prePrefix . "prd_";
        }

        if ($relatedProductId != 0) {
            $productId = $relatedProductId;
        }

        $product         = \Redshop\Product\Product::getProductById($productId);
        $productTemplate = RedshopHelperTemplate::getTemplate("product", $product->product_template);
        $productTemplate = $productTemplate[0];

        if (strpos($productTemplate->template_desc, "{more_images_3}") !== false) {
            $mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3');
            $mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_3');
        } elseif (strpos($productTemplate->template_desc, "{more_images_2}") !== false) {
            $mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2');
            $mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_2');
        } elseif (strpos($productTemplate->template_desc, "{more_images_1}") !== false) {
            $mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
            $mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
        } else {
            $mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
            $mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
        }

        $cartTemplate   = array();
        $attributeTable = "";

        foreach ($attributes as $attribute) {
            $attributeTable .= $attributeTemplate->template_desc;

            $attributeTable = str_replace(
                "{property_image_lbl}",
                JText::_('COM_REDSHOP_PROPERTY_IMAGE_LBL'),
                $attributeTable
            );
            $attributeTable = str_replace(
                "{virtual_number_lbl}",
                JText::_('COM_REDSHOP_VIRTUAL_NUMBER_LBL'),
                $attributeTable
            );
            $attributeTable = str_replace(
                "{property_name_lbl}",
                JText::_('COM_REDSHOP_PROPERTY_NAME_LBL'),
                $attributeTable
            );
            $attributeTable = str_replace(
                "{property_price_lbl}",
                JText::_('COM_REDSHOP_PROPERTY_PRICE_LBL'),
                $attributeTable
            );
            $attributeTable = str_replace(
                "{property_stock_lbl}",
                JText::_('COM_REDSHOP_PROPERTY_STOCK_LBL'),
                $attributeTable
            );
            $attributeTable = str_replace(
                "{add_to_cart_lbl}",
                JText::_('COM_REDSHOP_ADD_TO_CART_LBL'),
                $attributeTable
            );

            if (empty($attribute->properties)) {
                $properties = RedshopHelperProduct_Attribute::getAttributeProperties(0, $attribute->attribute_id);
            } else {
                $properties = $attribute->properties;
            }

            if (empty($attribute->text) || empty($properties)
                || strpos($attributeTable, "{property_start}") === false || strpos(
                    $attributeTable,
                    "{property_start}"
                ) === false) {
                continue;
            }

            $start            = explode("{property_start}", $attributeTable);
            $end              = explode("{property_end}", $start[1]);
            $propertyTemplate = $end[0];

            $commonId   = $prefix . $productId . '_' . $accessoryId . '_' . $attribute->value;
            $propertyId = 'property_id_' . $commonId;

            $propertyData = "";

            foreach ($properties as $property) {
                // Skip if "onlySelected" is true and this property not set as selected.
                if ($onlySelected && !$property->setdefault_selected) {
                    continue;
                }

                $propertyData .= $propertyTemplate;

                $priceWithVat          = 0;
                $priceWithoutVat       = 0;
                $propertyStock         = RedshopHelperStockroom::getStockAmountWithReserve(
                    $property->value,
                    "property"
                );
                $preOrderPropertyStock = RedshopHelperStockroom::getPreorderStockAmountwithReserve(
                    $property->value,
                    "property"
                );

                $propertyData = str_replace("{property_name}", urldecode($property->property_name), $propertyData);
                $propertyData = str_replace("{virtual_number}", $property->property_number, $propertyData);

                // Replace {property_stock}
                if (strpos($propertyData, '{property_stock}') !== false) {
                    $displayStock = ($propertyStock) ? JText::_('COM_REDSHOP_IN_STOCK') : JText::_(
                        'COM_REDSHOP_NOT_IN_STOCK'
                    );
                    $propertyData = str_replace("{property_stock}", $displayStock, $propertyData);
                }

                // Replace {property_image}
                if (strpos($propertyData, '{property_image}') !== false) {
                    $propertyImage = "";

                    if ($property->property_image
                        && JFile::exists(
                            REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property->property_image
                        )) {
                        $thumbUrl      = RedshopHelperMedia::getImagePath(
                            $property->property_image,
                            '',
                            'thumb',
                            'product_attributes',
                            $mpw_thumb,
                            $mph_thumb,
                            Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
                        );
                        $propertyImage = "<img title='" . urldecode(
                                $property->property_name
                            ) . "' src='" . $thumbUrl . "'>";
                    }

                    $propertyData = str_replace("{property_image}", $propertyImage, $propertyData);
                }

                if (strpos($propertyData, '{property_oprand}') !== false || strpos(
                        $propertyData,
                        '{property_price}'
                    ) !== false) {
                    $price  = '';
                    $opRand = '';

                    if ($property->property_price > 0) {
                        $prices = RedshopHelperProduct_Attribute::getPropertyPrice($property->value, 1, 'property');

                        if (count($prices) > 0) {
                            $property->property_price = $prices->product_price;
                        }

                        $priceWithoutVat = $property->property_price;

                        if (\Redshop\Template\Helper::isApplyAttributeVat($propertyData)) {
                            $priceWithVat = RedshopHelperProduct::getProducttax(
                                $productId,
                                $property->property_price,
                                $user_id
                            );
                        }

                        $priceWithVat += $property->property_price;

                        if (Redshop::getConfig()->get('SHOW_PRICE')
                            && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
                                || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get(
                                        'SHOW_QUOTATION_PRICE'
                                    )))
                            && !$attribute->hide_attribute_price) {
                            $opRand = $property->oprand;
                            $price  = RedshopHelperProductPrice::formattedPrice($priceWithVat);
                        }
                    }

                    $propertyData = str_replace("{property_oprand}", $opRand, $propertyData);
                    $propertyData = str_replace("{property_price}", $price, $propertyData);
                }

                if (empty($cartTemplate)) {
                    $cartTemplate = \Redshop\Template\Helper::getAddToCart($propertyData);
                }

                if (null !== $cartTemplate) {
                    $propertyData = Redshop\Product\Property::replaceAddToCart(
                        $productId,
                        $property->value,
                        0,
                        $propertyId,
                        $propertyStock,
                        $propertyData,
                        $cartTemplate,
                        $templateContent
                    );
                }

                $propertyData .= '<input type="hidden" id="' . $propertyId . '_oprand' . $property->value
                    . '" value="' . $property->oprand . '" />';
                $propertyData .= '<input type="hidden" id="' . $propertyId . '_proprice' . $property->value
                    . '" value="' . $priceWithVat . '" />';
                $propertyData .= '<input type="hidden" id="' . $propertyId . '_proprice_withoutvat'
                    . $property->value . '" value="' . $priceWithoutVat . '" />';

                $propertyData .= '<input type="hidden" id="' . $propertyId . '_stock' . $property->value
                    . '" value="' . $propertyStock . '" />';
                $propertyData .= '<input type="hidden" id="' . $propertyId . '_preorderstock'
                    . $property->value . '" value="' . $preOrderPropertyStock . '" />';

                $formId = 'addtocart_' . $propertyId . '_' . $property->value;

                $propertyData = RedshopHelperWishlist::replaceWishlistTag($productId, $propertyData, $formId);
            }

            $attributeTitle = urldecode($attribute->text);

            if ($attribute->attribute_required > 0) {
                $pos            = Redshop::getConfig()->get('ASTERISK_POSITION') > 0 ? urldecode($attribute->text)
                    . "<span id='asterisk_right'> * " : "<span id='asterisk_left'>* </span>"
                    . urldecode($attribute->text);
                $attributeTitle = $pos;
            }

            $attributeTable = str_replace("{attribute_title}", $attributeTitle, $attributeTable);
            $attributeTable = str_replace("{property_start}", "", $attributeTable);
            $attributeTable = str_replace("{property_end}", "", $attributeTable);
            $attributeTable = str_replace($propertyTemplate, $propertyData, $attributeTable);
        }

        if ($attributeTable != "") {
            $cart_template = \Redshop\Template\Helper::getAddToCart($templateContent);

            if (null !== $cart_template) {
                $templateContent = str_replace("{form_addtocart:$cart_template->name}", "", $templateContent);
            }
        }

        return str_replace("{attributewithcart_template:$attributeTemplate->name}", $attributeTable, $templateContent);
    }
}
