<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Stock;

defined('_JEXEC') or die;

/**
 * Wrapper Helper
 *
 * @since 3.0
 */
class Helper
{
    /**
     * @param   array  $data
     * @param   int    $newQuantity
     * @param   int    $minQuantity
     *
     * @return int
     * @since 3.0
     */
    public static function checkQuantityInStock($data = array(), $newQuantity = 1, $minQuantity = 0)
    {
        \JPluginHelper::importPlugin('redshop_product');
        $result = \RedshopHelperUtility::getDispatcher()->trigger(
            'onCheckQuantityInStock',
            array(&$data, &$newQuantity, &$minQuantity)
        );

        if (in_array(true, $result, true)) {
            return $newQuantity;
        }

        $productData     = \Redshop\Product\Product::getProductById($data['product_id']);
        $productPreOrder = $productData->preorder;

        if ($productData->min_order_product_quantity > 0 && $productData->min_order_product_quantity > $newQuantity) {
            $msg = $productData->product_name . " " . \JText::_('COM_REDSHOP_WARNING_MSG_MINIMUM_QUANTITY');
            $msg = sprintf($msg, $productData->min_order_product_quantity);
            /** @scrutinizer ignore-deprecated */
            \JError::raiseWarning('', $msg);
            $newQuantity = $productData->min_order_product_quantity;
        }

        if (!\Redshop::getConfig()->getBool('USE_STOCKROOM')) {
            return $newQuantity;
        }

        $productStock  = 0;
        $allowPreOrder = \Redshop::getConfig()->getBool('ALLOW_PRE_ORDER');

        if (($productPreOrder == 'global' && !$allowPreOrder)
            || $productPreOrder == 'no'
            || ($productPreOrder == "" && !$allowPreOrder)) {
            $productStock = \RedshopHelperStockroom::getStockroomTotalAmount($data['product_id']);
        }

        if (($productPreOrder == "global" && $allowPreOrder)
            || $productPreOrder == "yes"
            || ($productPreOrder == "" && $allowPreOrder)) {
            $productStock = \RedshopHelperStockroom::getStockroomTotalAmount($data['product_id']);
            $productStock += \RedshopHelperStockroom::getPreorderStockroomTotalAmount($data['product_id']);
        }

        $ownProductReserveStock = \RedshopHelperStockroom::getCurrentUserReservedStock($data['product_id']);
        $attributes             = $data['cart_attribute'];

        if (count($attributes) <= 0) {
            if ($productStock >= 0) {
                if ($newQuantity > $ownProductReserveStock && $productStock < ($newQuantity - $ownProductReserveStock)) {
                    $newQuantity = $productStock + $ownProductReserveStock;
                }
            } else {
                $newQuantity = $productStock + $ownProductReserveStock;
            }

            if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newQuantity) {
                $msg = $productData->product_name . " " . \JText::_('COM_REDSHOP_WARNING_MSG_MAXIMUM_QUANTITY');
                $msg = sprintf($msg, $productData->max_order_product_quantity);
                /** @scrutinizer ignore-deprecated */
                \JError::raiseWarning('', $msg);
                $newQuantity = $productData->max_order_product_quantity;
            }

            if (array_key_exists('quantity', $data)) {
                $productReservedQuantity = $ownProductReserveStock + $newQuantity - $data['quantity'];
            } else {
                $productReservedQuantity = $newQuantity;
            }

            \RedshopHelperStockroom::addReservedStock($data['product_id'], $productReservedQuantity, 'product');
        } else {
            for ($i = 0, $in = count($attributes); $i < $in; $i++) {
                $properties = $attributes[$i]['attribute_childs'];

                for ($k = 0, $kn = count($properties); $k < $kn; $k++) {
                    // Get subproperties from add to cart tray.
                    $subProperties           = $properties[$k]['property_childs'];
                    $totalSubProperty        = count($subProperties);
                    $ownReservePropertyStock = \RedshopHelperStockroom::getCurrentUserReservedStock(
                        $properties[$k]['property_id'],
                        'property'
                    );
                    $propertyStock           = 0;

                    if (($productPreOrder == "global" && !\Redshop::getConfig()->get(
                                'ALLOW_PRE_ORDER'
                            )) || ($productPreOrder == "no") || ($productPreOrder == "" && !\Redshop::getConfig()->get(
                                'ALLOW_PRE_ORDER'
                            ))) {
                        $propertyStock = \RedshopHelperStockroom::getStockroomTotalAmount(
                            $properties[$k]['property_id'],
                            "property"
                        );
                    }

                    if (($productPreOrder == "global" && \Redshop::getConfig()->get(
                                'ALLOW_PRE_ORDER'
                            )) || ($productPreOrder == "yes") || ($productPreOrder == "" && \Redshop::getConfig()->get(
                                'ALLOW_PRE_ORDER'
                            ))) {
                        $propertyStock = \RedshopHelperStockroom::getStockroomTotalAmount(
                            $properties[$k]['property_id'],
                            "property"
                        );
                        $propertyStock += \RedshopHelperStockroom::getPreorderStockroomTotalAmount(
                            $properties[$k]['property_id'],
                            "property"
                        );
                    }

                    // Get Property stock only when SubProperty is not in cart
                    if ($totalSubProperty <= 0) {
                        if ($propertyStock >= 0) {
                            if ($newQuantity > $ownReservePropertyStock && $propertyStock < ($newQuantity - $ownReservePropertyStock)) {
                                $newQuantity = $propertyStock + $ownReservePropertyStock;
                            }
                        } else {
                            $newQuantity = $propertyStock + $ownReservePropertyStock;
                        }

                        if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newQuantity) {
                            $newQuantity = $productData->max_order_product_quantity;
                        }

                        if (array_key_exists('quantity', $data)) {
                            $propertyReservedQuantity = $ownReservePropertyStock + $newQuantity - $data['quantity'];
                            $newProductQuantity       = $ownProductReserveStock + $newQuantity - $data['quantity'];
                        } else {
                            $propertyReservedQuantity = $newQuantity;
                            $newProductQuantity       = $ownProductReserveStock + $newQuantity;
                        }

                        \RedshopHelperStockroom::addReservedStock(
                            $properties[$k]['property_id'],
                            $propertyReservedQuantity,
                            "property"
                        );
                        \RedshopHelperStockroom::addReservedStock($data['product_id'], $newProductQuantity, 'product');
                    } else {
                        // Get SubProperty Stock here.
                        for ($l = 0; $l < $totalSubProperty; $l++) {
                            $subPropertyStock = 0;

                            if (($productPreOrder == "global" && !\Redshop::getConfig()->get(
                                        'ALLOW_PRE_ORDER'
                                    )) || ($productPreOrder == "no") || ($productPreOrder == "" && !\Redshop::getConfig(
                                    )->get('ALLOW_PRE_ORDER'))) {
                                $subPropertyStock = \RedshopHelperStockroom::getStockroomTotalAmount(
                                    $subProperties[$l]['subproperty_id'],
                                    "subproperty"
                                );
                            }

                            if (($productPreOrder == "global" && \Redshop::getConfig()->get(
                                        'ALLOW_PRE_ORDER'
                                    )) || ($productPreOrder == "yes") || ($productPreOrder == "" && \Redshop::getConfig(
                                    )->get('ALLOW_PRE_ORDER'))) {
                                $subPropertyStock = \RedshopHelperStockroom::getStockroomTotalAmount(
                                    $subProperties[$l]['subproperty_id'],
                                    "subproperty"
                                );
                                $subPropertyStock += \RedshopHelperStockroom::getPreorderStockroomTotalAmount(
                                    $subProperties[$l]['subproperty_id'],
                                    "subproperty"
                                );
                            }

                            $ownSubPropReserveStock = RedshopHelperStockroom::getCurrentUserReservedStock(
                                $subProperties[$l]['subproperty_id'],
                                "subproperty"
                            );

                            if ($subPropertyStock >= 0) {
                                if ($newQuantity > $ownSubPropReserveStock && $subPropertyStock < ($newQuantity - $ownSubPropReserveStock)) {
                                    $newQuantity = $subPropertyStock + $ownSubPropReserveStock;
                                }
                            } else {
                                $newQuantity = $subPropertyStock + $ownSubPropReserveStock;
                            }

                            if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newQuantity) {
                                $newQuantity = $productData->max_order_product_quantity;
                            }

                            if (array_key_exists('quantity', $data)) {
                                $subPropertyReservedQuantity = $ownSubPropReserveStock + $newQuantity - $data['quantity'];
                                $newPropertyQuantity         = $ownReservePropertyStock + $newQuantity - $data['quantity'];
                                $newProductQuantity          = $ownProductReserveStock + $newQuantity - $data['quantity'];
                            } else {
                                $subPropertyReservedQuantity = $newQuantity;
                                $newPropertyQuantity         = $ownReservePropertyStock + $newQuantity;
                                $newProductQuantity          = $ownProductReserveStock + $newQuantity;
                            }

                            RedshopHelperStockroom::addReservedStock(
                                $subProperties[$l]['subproperty_id'],
                                $subPropertyReservedQuantity,
                                'subproperty'
                            );
                            RedshopHelperStockroom::addReservedStock(
                                $properties[$k]['property_id'],
                                $newPropertyQuantity,
                                'property'
                            );
                            RedshopHelperStockroom::addReservedStock(
                                $data['product_id'],
                                $newProductQuantity,
                                'product'
                            );
                        }
                    }
                }
            }
        }

        return $newQuantity;
    }
}