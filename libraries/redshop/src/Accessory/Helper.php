<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Accessory;

defined('_JEXEC') or die;

/**
 * Accessory Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Helper
{
    public static function generateAccessoryFromCart($cartItemId = 0, $product_id = 0, $quantity = 1)
    {
        $accessoryCart = array();
        $cartItemData          = self::getCartItemAccessoryDetail($cartItemId);
        $in                    = count($cartItemData);

        for ($i = 0; $i < $in; $i++) {
            $accessory          = RedshopHelperAccessory::getProductAccessories($cartItemData[$i]->product_id);
            $accessoryPriceList = \Redshop\Product\Accessory::getPrice(
                $product_id,
                $accessory[0]->newaccessory_price,
                $accessory[0]->accessory_main_price,
                1
            );
            $accessoryPrice     = $accessoryPriceList[0];

            $accessoryCart[$i]['accessory_id']     = $cartItemData[$i]->product_id;
            $accessoryCart[$i]['accessory_name']   = $accessory[0]->product_name;
            $accessoryCart[$i]['accessory_oprand'] = $accessory[0]->oprand;
            $accessoryCart[$i]['accessory_price']  = $accessoryPrice;
            $accessoryCart[$i]['accessory_childs'] = RedshopHelperCart::generateAttributeFromCart(
                $cartItemId,
                1,
                $cartItemData[$i]->product_id,
                $quantity
            );
        }

        return $accessoryCart;
    }

    /**
     * @param   int  $cartItemId
     *
     * @return null
     * @since __DEPLOY_VERSION__
     */
    public static function getCartItemAccessoryDetail($cartItemId = 0)
    {
        $list  = null;
        $db    = \JFactory::getDbo();
        $query = $db->getQuery(true);

        if ($cartItemId != 0) {
            $query->select('*')
                ->from($db->qn('#__redshop_usercart_accessory_item'))
                ->where($db->qn('cart_item_id') . '=' . $db->q((int)$cartItemId));

            $db->setQuery($query);
            $list = $db->loadObjectlist();
        }

        return $list;
    }

    /**
     * @param   array  $data
     * @param   int    $userId
     *
     * @return  array|bool
     *
     * @throws  Exception
     * @since __DEPLOY_VERSION__
     */
    public static function generateAccessoryArray($data, $userId = 0)
    {
        $accessoryCart = array();

        if (!empty($data['accessory_data'])) {
            $accessoryData   = explode("@@", $data['accessory_data']);
            $accQuantityData = array();

            if (isset($data['acc_quantity_data'])) {
                $accQuantityData = explode("@@", $data['acc_quantity_data']);
            }

            for ($i = 0, $in = count($accessoryData); $i < $in; $i++) {
                $accessory          = RedshopHelperAccessory::getProductAccessories($accessoryData[$i]);
                $accessoryPriceList = \Redshop\Product\Accessory::getPrice(
                    $data['product_id'],
                    $accessory[0]->newaccessory_price,
                    $accessory[0]->accessory_main_price,
                    1,
                    $userId
                );
                $accessoryPrice    = $accessoryPriceList[0];
                $accessoryQuantity = (isset($accQuantityData[$i]) && $accQuantityData[$i]) ?
                    $accQuantityData[$i] : $data['quantity'];

                $accessoryCart[$i]['accessory_id']       = $accessoryData[$i];
                $accessoryCart[$i]['accessory_name']     = $accessory[0]->product_name;
                $accessoryCart[$i]['accessory_oprand']   = $accessory[0]->oprand;
                $accessoryCart[$i]['accessory_price']    = $accessoryPrice * $accessoryQuantity;
                $accessoryCart[$i]['accessory_quantity'] = $accessoryQuantity;

                $accAttributeCart = array();

                if (!empty($data['acc_attribute_data'])) {
                    $accAttributeData = explode('@@', $data['acc_attribute_data']);

                    if ($accAttributeData[$i] != "") {
                        $accAttributeData      = explode('##', $accAttributeData[$i]);
                        $countAccessoryAttribute = count($accAttributeData);

                        for ($ia = 0; $ia < $countAccessoryAttribute; $ia++) {
                            $accPropertyCart = array();
                            $attribute       = RedshopHelperProduct_Attribute::getProductAttribute(
                                0,
                                0,
                                $accAttributeData[$ia]
                            );
                            $accAttributeCart[$ia]['attribute_id']   = $accAttributeData[$ia];
                            $accAttributeCart[$ia]['attribute_name'] = $attribute[0]->text;

                            if ($attribute[0]->text != "" && !empty($data['acc_property_data'])) {
                                $accPropertyData = explode('@@', $data['acc_property_data']);
                                $accPropertyData = explode('##', $accPropertyData[$i]);

                                if (empty($accPropertyData[$ia]) && $attribute[0]->attribute_required == 1) {
                                    return array();
                                }

                                if (!empty($accPropertyData[$ia])) {
                                    $accPropertyData        = explode(',,', $accPropertyData[$ia]);
                                    $countAccessoryProperty = count($accPropertyData);

                                    for ($ip = 0; $ip < $countAccessoryProperty; $ip++) {
                                        $accSubPropertyCart = array();
                                        $propertyPrice      = 0;
                                        $property           = RedshopHelperProduct_Attribute::getAttributeProperties(
                                            $accPropertyData[$ip]
                                        );
                                        $priceList          = RedshopHelperProduct_Attribute::getPropertyPrice(
                                            $accPropertyData[$ip],
                                            $data['quantity'],
                                            'property',
                                            $userId
                                        );

                                        if (count($priceList) > 0) {
                                            $propertyPrice = $priceList->product_price;
                                        } else {
                                            $propertyPrice = $property[0]->property_price;
                                        }

                                        $accPropertyCart[$ip]['property_id']     = $accPropertyData[$ip];
                                        $accPropertyCart[$ip]['property_name']   = $property[0]->text;
                                        $accPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
                                        $accPropertyCart[$ip]['property_price']  = $propertyPrice;

                                        if (!empty($data['acc_subproperty_data'])) {
                                            $accSubPropertyData = explode('@@', $data['acc_subproperty_data']);
                                            $accSubPropertyData = @explode('##', $accSubPropertyData[$i]);
                                            $accSubPropertyData = @explode(',,', $accSubPropertyData[$ia]);


                                            if (!empty($accSubPropertyData[$ip])) {
                                                $accSubPropertyData      = explode('::', $accSubPropertyData[$ip]);
                                                $countAccessorySubProperty = count($accSubPropertyData);

                                                for ($isp = 0; $isp < $countAccessorySubProperty; $isp++) {
                                                    $subProperty = RedshopHelperProduct_Attribute::getAttributeSubProperties(
                                                        $accSubPropertyData[$isp]
                                                    );
                                                    $priceList   = RedshopHelperProduct_Attribute::getPropertyPrice(
                                                        $accSubPropertyData[$isp],
                                                        $data['quantity'],
                                                        'subproperty',
                                                        $userId
                                                    );

                                                    if (count($priceList) > 0) {
                                                        $subPropertyPrice = $priceList->product_price;
                                                    } else {
                                                        $subPropertyPrice = $subProperty[0]->subattribute_color_price;
                                                    }

                                                    $accSubPropertyCart[$isp]['subproperty_id']     = $accSubPropertyData[$isp];
                                                    $accSubPropertyCart[$isp]['subproperty_name']   = $subProperty[0]->text;
                                                    $accSubPropertyCart[$isp]['subproperty_oprand'] = $subProperty[0]->oprand;
                                                    $accSubPropertyCart[$isp]['subproperty_price']  = $subPropertyPrice;
                                                }
                                            }
                                        }

                                        $accPropertyCart[$ip]['property_childs'] = $accSubPropertyCart;
                                    }
                                }
                            }

                            $accAttributeCart[$ia]['attribute_childs'] = $accPropertyCart;
                        }
                    }
                } else {
                    $attributeSetId   = RedshopEntityProduct::getInstance($accessory[0]->child_product_id)
                                            ->get('attribute_set_id');
                    $attributes_acc_set = array();

                    if ($attributeSetId > 0) {
                        $attributes_acc_set = self::getProductAccAttribute(
                            $accessory[0]->child_product_id,
                            $attributeSetId,
                            0,
                            0,
                            1
                        );
                    }

                    $requireAttribute = RedshopHelperProduct_Attribute::getProductAttribute(
                        $accessory[0]->child_product_id,
                        0,
                        0,
                        0,
                        1
                    );
                    $requireAttribute = array_merge($requireAttribute, $attributes_acc_set);

                    if (count($requireAttribute) > 0) {
                        $requied_attributeArr = array();

                        for ($re = 0, $countAttribute = count($requireAttribute); $re < $countAttribute; $re++) {
                            $requied_attributeArr[$re] = urldecode($requireAttribute[$re]->attribute_name);
                        }

                        $requied_attribute_name = implode(", ", $requied_attributeArr);

                        // Throw an error as first attribute is required
                        $msg = urldecode($requied_attribute_name) . " " . JText::_('IS_REQUIRED');
                        JFactory::getApplication()->enqueueMessage($msg);

                        return false;
                    }
                }

                $accessoryCart[$i]['accessory_childs'] = $accAttributeCart;
            }
        }

        return $accessoryCart;
    }
}