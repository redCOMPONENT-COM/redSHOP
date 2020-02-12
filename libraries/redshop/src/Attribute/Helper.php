<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Attribute;

defined('_JEXEC') or die;

/**
 * Attribute Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param   int     $cartItemId
     * @param   int     $isAccessory
     * @param   string  $section
     * @param   int     $parentSectionId
     *
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public static function getCartItemAttributeDetail(
        $cartItemId = 0,
        $isAccessory = 0,
        $section = "attribute",
        $parentSectionId = 0
    ) {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->qn('#__redshop_usercart_attribute_item'))
            ->where($db->qn('is_accessory_att') . '=' . $db->q((int)$isAccessory))
            ->where($db->qn('section') . '=' . $db->q($section));

        if ($cartItemId != 0) {
            $query->where($db->qn('cart_item_id') . '=' . $db->q((int)$cartItemId));
        }

        if ($parentSectionId != 0) {
            $query->where($db->qn('parent_section_id') . '=' . $db->q((int)$parentSectionId));
        }


        $db->setQuery($query);
        $list = $db->loadObjectlist();

        return $list;
    }

    /**
     * @param   array  $atttributes
     *
     * @return array
     * @since __DEPLOY_VERSION__
     */
    public static function getSelectedCartAttributeArray($atttributes = array())
    {
        $selectedProperty    = array();
        $selectedSubProperty = array();

        for ($i = 0, $in = count($atttributes); $i < $in; $i++) {
            $properties = $atttributes[$i]['attribute_childs'];

            for ($k = 0, $kn = count($properties); $k < $kn; $k++) {
                $selectedProperty[] = $properties[$k]['property_id'];
                $subProperties      = $properties[$k]['property_childs'];

                for ($l = 0, $ln = count($subProperties); $l < $ln; $l++) {
                    $selectedSubProperty[] = $subProperties[$l]['subproperty_id'];
                }
            }
        }

        return array($selectedProperty, $selectedSubProperty);
    }

    /**
     * @param   int  $orderItemId
     * @param   int  $isAccessory
     * @param   int  $parentSectionId
     * @param   int  $quantity
     *
     * @return array
     * @since __DEPLOY_VERSION__
     */
    public static function generateAttributeFromOrder(
        $orderItemId = 0,
        $isAccessory = 0,
        $parentSectionId = 0,
        $quantity = 1
    ) {
        $generateAttributeCart = array();

        $orderItemAttData = RedshopHelperOrder::getOrderItemAttributeDetail(
            $orderItemId,
            $isAccessory,
            "attribute",
            $parentSectionId
        );

        for ($i = 0, $in = count($orderItemAttData); $i < $in; $i++) {
            $accPropertyCart                             = array();
            $generateAttributeCart[$i]['attribute_id']   = $orderItemAttData[$i]->section_id;
            $generateAttributeCart[$i]['attribute_name'] = $orderItemAttData[$i]->section_name;

            $orderPropData = RedshopHelperOrder::getOrderItemAttributeDetail(
                $orderItemId,
                $isAccessory,
                "property",
                $orderItemAttData[$i]->section_id
            );

            for ($p = 0, $pn = count($orderPropData); $p < $pn; $p++) {
                $accSubPropertyCart = array();
                $property           = RedshopHelperProduct_Attribute::getAttributeProperties(
                    $orderPropData[$p]->section_id
                );
                $prices          = RedshopHelperProduct_Attribute::getPropertyPrice(
                    $orderPropData[$p]->section_id,
                    $quantity,
                    'property'
                );

                if (isset($prices) && count($prices) > 0) {
                    $propertyPrice = $prices->product_price;
                } else {
                    $propertyPrice = $property[0]->property_price;
                }

                $accPropertyCart[$p]['property_id']     = $orderPropData[$p]->section_id;
                $accPropertyCart[$p]['property_name']   = $property[0]->text;
                $accPropertyCart[$p]['property_oprand'] = $property[0]->oprand;
                $accPropertyCart[$p]['property_price']  = $propertyPrice;

                $orderSubPropData = RedshopHelperOrder::getOrderItemAttributeDetail(
                    $orderItemId,
                    $isAccessory,
                    "subproperty",
                    $orderPropData[$p]->section_id
                );

                for ($sp = 0, $countSubproperty = count($orderSubPropData); $sp < $countSubproperty; $sp++) {
                    $subProperty = RedshopHelperProduct_Attribute::getAttributeSubProperties(
                        $orderSubPropData[$sp]->section_id
                    );
                    $prices   = RedshopHelperProduct_Attribute::getPropertyPrice(
                        $orderSubPropData[$sp]->section_id,
                        $quantity,
                        'subproperty'
                    );

                    if (count($prices) > 0) {
                        $subProperty_price = $prices->product_price;
                    } else {
                        $subProperty_price = $subProperty[0]->subattribute_color_price;
                    }

                    $accSubPropertyCart[$sp]['subproperty_id']     = $orderSubPropData[$sp]->section_id;
                    $accSubPropertyCart[$sp]['subproperty_name']   = $subProperty[0]->text;
                    $accSubPropertyCart[$sp]['subproperty_oprand'] = $subProperty[0]->oprand;
                    $accSubPropertyCart[$sp]['subproperty_price']  = $subProperty_price;
                }

                $accPropertyCart[$p]['property_childs'] = $accSubPropertyCart;
            }

            $generateAttributeCart[$i]['attribute_childs'] = $accPropertyCart;
        }

        return $generateAttributeCart;
    }


    /**
     * @param $data
     * @param $attributeTemplate
     * @param $selectedAttrId
     * @param $selectedPropId
     * @param $unSelectedSubPropId
     *
     * @return string|void
     * @throws \Exception
     * @since __DEPLOY_VERSION
     */
    public static function handleRequiredSelectedAttributeCartMessage(
        $data,
        $attributeTemplate,
        $selectedAttrId,
        $selectedPropId,
        $unSelectedSubPropId
    ) {
        if (\Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE')) {
            return;
        }

        // Check if required attribute is filled or not ...
        $attributeTemplateArray = \Redshop\Template\Helper::getAttribute($attributeTemplate);

        if (!empty($attributeTemplateArray)) {
            $selectedAttributeId = 0;

            if (count($selectedAttrId) > 0) {
                $selectedAttributeId = implode(",", $selectedAttrId);
            }

            $requiredAttribute = \Redshop\Product\Attribute::getProductAttribute(
                $data['product_id'],
                0,
                0,
                0,
                1,
                $selectedAttributeId
            );

            if (!empty($requiredAttribute)) {
                $requiredAttributeArray = array();

                for ($re = 0, $countAttribute = count($requiredAttribute); $re < $countAttribute; $re++) {
                    $requiredAttributeArray[$re] = urldecode($requiredAttribute[$re]->attribute_name);
                }

                $requiredAttributeName = implode(", ", $requiredAttributeArray);

                // Error message if first attribute is required
                return $requiredAttributeName . " " . JText::_('COM_REDSHOP_IS_REQUIRED');
            }

            $selectedPropertyId = 0;

            if (!empty($selectedPropId)) {
                $selectedPropertyId = implode(",", $selectedPropId);
            }

            $unSelectedSubPropId = 0;

            if (is_array($unSelectedSubPropId) && count($unSelectedSubPropId) > 0) {
                $unSelectedSubPropId = implode(",", $unSelectedSubPropId);
            }

            $requiredProperty = \RedshopHelperProduct_Attribute::getAttributeProperties(
            /** @scrutinizer ignore-type */ $selectedPropertyId,
                /** @scrutinizer ignore-type */ $selectedAttributeId,
                                            $data['product_id'],
                                            0,
                                            1,
                /** @scrutinizer ignore-type */ $unSelectedSubPropId
            );

            if (!empty($requiredProperty)) {
                $requiredSubAttributeArray = array();

                for ($re1 = 0, $countProperty = count($requiredProperty); $re1 < $countProperty; $re1++) {
                    $requiredSubAttributeArray[$re1] = urldecode($requiredProperty[$re1]->property_name);
                }

                $requiredSubAttributeName = implode(",", $requiredSubAttributeArray);

                // Give error as second attribute is required
                if ($data['reorder'] != 1) {
                    return $requiredSubAttributeName . " " . JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED');
                }
            }
        }

        return;
    }
}