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
 * @since 3.0
 */
class Helper
{
    /**
     * @param $question
     * @param null $default
     * @return mixed|null
     * @since __DEPLOY_VERSION__
     */
    public static function is($question, $default = null) {
        return \Redshop::getConfig()->get($question, $default);
    }

    /**
     * @param   int  $cartItemId
     * @param   int  $productId
     * @param   int  $quantity
     *
     * @return array
     * @since 3.0
     */
    public static function generateAccessoryFromCart($cartItemId = 0, $productId = 0, $quantity = 1)
    {
        $accessoryCart = array();
        $cartItemData  = self::getCartItemAccessoryDetail($cartItemId);
        $in            = count($cartItemData);

        for ($i = 0; $i < $in; $i++) {
            $accessory          = \RedshopHelperAccessory::getProductAccessories($cartItemData[$i]->product_id);
            $accessoryPriceList = \Redshop\Product\Accessory::getPrice(
                $productId,
                $accessory[0]->newaccessory_price,
                $accessory[0]->accessory_main_price,
                1
            );
            $accessoryPrice     = $accessoryPriceList[0];

            $accessoryCart[$i]['accessory_id']     = $cartItemData[$i]->product_id;
            $accessoryCart[$i]['accessory_name']   = $accessory[0]->product_name;
            $accessoryCart[$i]['accessory_oprand'] = $accessory[0]->oprand;
            $accessoryCart[$i]['accessory_price']  = $accessoryPrice;
            $accessoryCart[$i]['accessory_childs'] = \RedshopHelperCart::generateAttributeFromCart(
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
     * @since 3.0
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
     * @since 3.0
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
                $accessory          = \RedshopHelperAccessory::getProductAccessories($accessoryData[$i]);
                $accessoryPriceList = \Redshop\Product\Accessory::getPrice(
                    $data['product_id'],
                    $accessory[0]->newaccessory_price,
                    $accessory[0]->accessory_main_price,
                    1,
                    $userId
                );
                $accessoryPrice     = $accessoryPriceList[0];
                $accessoryQuantity  = (isset($accQuantityData[$i]) && $accQuantityData[$i]) ?
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
                        $accAttributeData        = explode('##', $accAttributeData[$i]);
                        $countAccessoryAttribute = count($accAttributeData);

                        for ($ia = 0; $ia < $countAccessoryAttribute; $ia++) {
                            $accPropertyCart                         = array();
                            $attribute                               = \Redshop\Product\Attribute::getProductAttribute(
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
                                        $property           = \RedshopHelperProduct_Attribute::getAttributeProperties(
                                            $accPropertyData[$ip]
                                        );
                                        $priceList          = \RedshopHelperProduct_Attribute::getPropertyPrice(
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
                                                $accSubPropertyData        = explode('::', $accSubPropertyData[$ip]);
                                                $countAccessorySubProperty = count($accSubPropertyData);

                                                for ($isp = 0; $isp < $countAccessorySubProperty; $isp++) {
                                                    $subProperty = \RedshopHelperProduct_Attribute::getAttributeSubProperties(
                                                        $accSubPropertyData[$isp]
                                                    );
                                                    $priceList   = \RedshopHelperProduct_Attribute::getPropertyPrice(
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
                    $attributeSetId         = \RedshopEntityProduct::getInstance($accessory[0]->child_product_id)
                        ->get('attribute_set_id');
                    $attributesAccessorySet = array();

                    if ($attributeSetId > 0) {
                        $attributesAccessorySet = self::getProductAccAttribute(
                            $accessory[0]->child_product_id,
                            $attributeSetId,
                            0,
                            0,
                            1
                        );
                    }

                    $requireAttribute = \Redshop\Product\Attribute::getProductAttribute(
                        $accessory[0]->child_product_id,
                        0,
                        0,
                        0,
                        1
                    );
                    $requireAttribute = array_merge($requireAttribute, $attributesAccessorySet);

                    if (count($requireAttribute) > 0) {
                        $requiredAttributes = array();

                        for ($re = 0, $countAttribute = count($requireAttribute); $re < $countAttribute; $re++) {
                            $requiredAttributes[$re] = urldecode($requireAttribute[$re]->attribute_name);
                        }

                        $requied_attribute_name = implode(", ", $requiredAttributes);

                        // Throw an error as first attribute is required
                        $msg = urldecode($requied_attribute_name) . " " . \JText::_('IS_REQUIRED');
                        \JFactory::getApplication()->enqueueMessage($msg);

                        return false;
                    }
                }

                $accessoryCart[$i]['accessory_childs'] = $accAttributeCart;
            }
        }

        return $accessoryCart;
    }

    /**
     * @param   int  $productId
     * @param   int  $attributeSetId
     * @param   int  $attributeId
     * @param   int  $published
     * @param   int  $requiredAttribute
     * @param   int  $notAttributeId
     *
     * @return mixed
     * @since 3.0
     */
    public static function getProductAccAttribute(
        $productId = 0,
        $attributeSetId = 0,
        $attributeId = 0,
        $published = 0,
        $requiredAttribute = 0,
        $notAttributeId = 0
    ) {
        $db    = \JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select(
            $db->qn('a.attribute_id', 'value'),
            $db->qn('a.attribute_name', 'text'),
            'a.*',
            $db->qn('ast.attribute_set_name')
        )
            ->from($db->qn('#__redshop_product_attribute', 'a'))
            ->leftJoin(
                $db->qn('#__redshop_attribute_set', 'ast')
                . 'ON' . $db->qn('a.attribute_set_id')
                . '=' . $db->qn('ast.attribute_set_id')
            )
            ->leftJoin(
                $db->qn('#__redshop_product', 'p')
                . 'ON' . $db->qn('p.attribute_set_id')
                . '=' . $db->qn('a.attribute_set_id')
            )
            ->where($db->qn('a.attribute_name') . "!= ''")
            ->where($db->qn('attribute_published') . '=' . $db->q('1'))
            ->order($db->qn('a.ordering'));

        if ($productId != 0) {
            // Secure productsIds
            if ($productIds = explode(',', $productId)) {
                $productIds = \Joomla\Utilities\ArrayHelper::toInteger($productIds);

                $query->where($db->qn('p.product_id') . 'IN' . $db->q(implode(',', $productIds)));
            }
        }

        if ($attributeSetId != 0) {
            $query->where($db->qn('a.attribute_set_id') . '=' . $db->q((int)$attributeSetId));
        }

        if ($published != 0) {
            $query->where($db->qn('ast.published') . "=" . $db->q((int)$published));
        }

        if ($requiredAttribute != 0) {
            $query->where($db->qn('a.attribute_required') . "=" . $db->q((int)$requiredAttribute));
        }

        if ($notAttributeId != 0) {
            // Secure notAttributeId
            if ($notAttributeIds = explode(',', $notAttributeId)) {
                $notAttributeIds = \Joomla\Utilities\ArrayHelper::toInteger($notAttributeIds);

                $query->where($db->qn('a.attribute_id') . 'NOT IN' . $db->q(implode(',', $notAttributeIds)));
            }
        }

        $db->setQuery($query);

        return $db->loadObjectlist();
    }

    /**
     * @param   array  $accessories
     *
     * @return array
     * @since 3.0
     */
    public static function getSelectedCartAccessoryArray($accessories = array())
    {
        $selectedAccessory   = array();
        $selectedProperty    = array();
        $selectedSubProperty = array();

        for ($i = 0, $in = count($accessories); $i < $in; $i++) {
            $selectedAccessory[] = $accessories[$i]['accessory_id'];
            $acessoryChilds      = $accessories[$i]['accessory_childs'];

            for ($j = 0, $jn = count($acessoryChilds); $j < $jn; $j++) {
                $properties = $acessoryChilds[$j]['attribute_childs'];

                for ($k = 0, $kn = count($properties); $k < $kn; $k++) {
                    $selectedProperty[] = $properties[$k]['property_id'];
                    $subProperties      = $properties[$k]['property_childs'];

                    for ($l = 0, $ln = count($subProperties); $l < $ln; $l++) {
                        $selectedSubProperty[] = $subProperties[$l]['subproperty_id'];
                    }
                }
            }
        }

        return array($selectedAccessory, $selectedProperty, $selectedSubProperty);
    }

    /**
     * @param   int  $orderItemId
     * @param   int  $productId
     * @param   int  $quantity
     *
     * @return array
     * @since 3.0
     */
    public function generateAccessoryFromOrder($orderItemId = 0, $productId = 0, $quantity = 1)
    {
        $generateAccessoryCart = array();

        $orderItemData = \RedshopHelperOrder::getOrderItemAccessoryDetail($orderItemId);

        foreach ($orderItemData as $index => $orderItem) {
            $accessory       = \RedshopHelperAccessory::getProductAccessories($orderItem->product_id);
            $accessoryPrices = \Redshop\Product\Accessory::getPrice(
                $productId,
                $accessory[0]->newaccessory_price,
                $accessory[0]->accessory_main_price,
                1
            );
            $accessoryPrice  = $accessoryPrices[0];

            $generateAccessoryCart[$index]['accessory_id']       = $orderItem->product_id;
            $generateAccessoryCart[$index]['accessory_name']     = $accessory[0]->product_name;
            $generateAccessoryCart[$index]['accessory_oprand']   = $accessory[0]->oprand;
            $generateAccessoryCart[$index]['accessory_price']    = $accessoryPrice;
            $generateAccessoryCart[$index]['accessory_quantity'] = $orderItem->product_quantity;
            $generateAccessoryCart[$index]['accessory_childs']   = \Redshop\Attribute\Helper::generateAttributeFromOrder(
                $orderItemId,
                1,
                $orderItem->product_id,
                $quantity
            );
        }

        return $generateAccessoryCart;
    }
}