<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Redshop\Economic\RedshopEconomic;

class RedshopModelOrder_detail extends RedshopModel
{
    public $_id = null;

    public $_data = null;

    public $_table_prefix = null;

    public $_copydata = null;

    private $_dispatcher = null;

    public function __construct()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';

        $array = JFactory::getApplication()->input->get('cid', 0, 'array');

        $this->setId((int)$array[0]);

        JPluginHelper::importPlugin('redshop');

        $this->_dispatcher = RedshopHelperUtility::getDispatcher();
    }

    public function setId($id)
    {
        $this->_id   = $id;
        $this->_data = null;
    }

    public function &getData()
    {
        if ($this->_loadData()) {
        } else {
            $this->_initData();
        }

        return $this->_data;
    }

    public function _loadData()
    {
        if (empty($this->_data)) {
            $this->_data = RedshopEntityOrder::getInstance($this->_id)->getItem();

            return (boolean)$this->_data;
        }

        return true;
    }

    public function _initData()
    {
        if (empty($this->_data)) {
            $detail                     = new stdClass;
            $detail->order_id           = 0;
            $detail->user_id            = null;
            $detail->order_number       = null;
            $detail->user_info_id       = null;
            $detail->order_total        = null;
            $detail->order_subtotal     = null;
            $detail->order_tax          = null;
            $detail->order_tax_details  = null;
            $detail->order_shipping     = null;
            $detail->order_shipping_tax = null;
            $detail->coupon_discount    = null;
            $detail->payment_discount   = null;
            $detail->order_discount     = null;
            $detail->order_status       = null;
            $detail->cdate              = null;
            $detail->mdate              = null;
            $detail->ship_method_id     = null;
            $detail->customer_note      = null;
            $detail->ip_address         = null;
            $this->_data                = $detail;

            return (boolean)$this->_data;
        }

        return true;
    }

    public function store($data)
    {
        $row = $this->getTable();

        if (!$row->bind($data)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        if (!$row->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        return true;
    }

    public function delete($cid = array())
    {
        if (count($cid)) {
            if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1) {
                for ($i = 0, $in = count($cid); $i < $in; $i++) {
                    $orderdata = $this->getTable('order_detail');
                    $orderdata->load($cid[$i]);
                    RedshopEconomic::deleteInvoiceInEconomic($orderdata);
                }
            }

            $cids       = implode(',', $cid);
            $order_item = RedshopHelperOrder::getOrderItemDetail($cids);

            for ($i = 0, $in = count($order_item); $i < $in; $i++) {
                $quntity = $order_item[$i]->product_quantity;

                $order_id     = $order_item[$i]->order_id;
                $order_detail = RedshopEntityOrder::getInstance($order_id)->getItem();

                if ($order_detail->order_payment_status == "Unpaid") {
                    // Update stock roommanageStockAmount
                    RedshopHelperStockroom::manageStockAmount(
                        $order_item[$i]->product_id,
                        $quntity,
                        $order_item[$i]->stockroom_id
                    );
                }

                RedshopHelperProduct::makeAttributeOrder(
                    $order_item[$i]->order_item_id,
                    0,
                    $order_item[$i]->product_id,
                    1
                );
                $query = "DELETE FROM `" . $this->_table_prefix . "order_attribute_item` "
                    . "WHERE `order_item_id` = " . $order_item[$i]->order_item_id;
                $this->_db->setQuery($query);
                $this->_db->execute();

                $query = "DELETE FROM `" . $this->_table_prefix . "order_acc_item` "
                    . "WHERE `order_item_id` = " . $order_item[$i]->order_item_id;
                $this->_db->setQuery($query);
                $this->_db->execute();
            }

            $query = 'DELETE FROM ' . $this->_table_prefix . 'orders WHERE order_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            $query = 'DELETE FROM ' . $this->_table_prefix . 'order_item WHERE order_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            $query = 'DELETE FROM ' . $this->_table_prefix . 'order_payment WHERE order_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            $query = 'DELETE FROM ' . $this->_table_prefix . 'order_users_info WHERE order_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            $quotation = RedshopHelperQuotation::getQuotationWithOrder($cids);

            for ($q = 0, $qn = count($quotation); $q < $qn; $q++) {
                $quotation_item = RedshopHelperQuotation::getQuotationProduct($quotation[$q]->quotation_id);

                for ($j = 0, $jn = count($quotation_item); $j < $jn; $j++) {
                    $query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_fields_data '
                        . 'WHERE quotation_item_id=' . $quotation_item[$j]->quotation_item_id;
                    $this->_db->setQuery($query);

                    if (!$this->_db->execute()) {
                        /** @scrutinizer ignore-deprecated */
                        $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                        return false;
                    }
                }

                $query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_item '
                    . 'WHERE quotation_id=' . $quotation[$q]->quotation_id;
                $this->_db->setQuery($query);

                if (!$this->_db->execute()) {
                    /** @scrutinizer ignore-deprecated */
                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                    return false;
                }
            }

            $query = 'DELETE FROM ' . $this->_table_prefix . 'quotation WHERE order_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }
        }

        return true;
    }

    public function getProducts($order_id)
    {
        $query = "SELECT DISTINCT( p.product_id ) as value,p.product_name as text,oi.order_id FROM "
            . $this->_table_prefix . "product as p ," . $this->_table_prefix
            . "order_item as oi WHERE  oi.product_id != p.product_id AND oi.order_id = " . $order_id;
        $this->_db->setQuery($query);
        $products = $this->_db->loadObjectlist();

        return $products;
    }

    public function neworderitem($data, $quantity, $order_item_id)
    {
        // Get Order Info
        $orderdata = $this->getTable('order_detail');
        $orderdata->load($this->_id);

        $item = $data['order_item'];

        // Get product Info

        // Set Order Item Info
        $orderitemdata = $this->getTable('order_item_detail');
        $orderitemdata->load($order_item_id);

        $user_id = $orderdata->user_id;

        for ($i = 0, $in = count($item); $i < $in; $i++) {
            $productId          = $item[$i]->product_id;
            $product_excl_price = $item[$i]->prdexclprice;
            $product_price      = $item[$i]->productprice;

            // Attribute price added
            $generateAttributeCart = \Redshop\Cart\Helper::generateAttribute((array)$item[$i], $user_id);
            $retAttArr             = \RedshopHelperProduct::makeAttributeCart(
                $generateAttributeCart,
                $productId,
                $user_id,
                0,
                $quantity
            );
            $product_attribute     = $retAttArr[0];

            // Accessory price
            $generateAccessoryCart = \Redshop\Accessory\Helper::generateAccessoryArray((array)$item[$i], $user_id);
            $retAccArr             = \RedshopHelperProduct::makeAccessoryCart(
                $generateAccessoryCart,
                $productId,
                $user_id
            );
            $product_accessory     = $retAccArr[0];

            $wrapper_price = 0;
            $wrapper_vat   = 0;

            if ($item[$i]->wrapper_data != 0 && $item[$i]->wrapper_data != '') {
                $wrapper = RedshopHelperProduct::getWrapper($productId, $item[$i]->wrapper_data);

                if (count($wrapper) > 0) {
                    if ($wrapper[0]->wrapper_price > 0) {
                        $wrapper_vat = RedshopHelperProduct::getProductTax(
                            $productId,
                            $wrapper[0]->wrapper_price,
                            $user_id
                        );
                    }

                    $wrapper_price = $wrapper[0]->wrapper_price + $wrapper_vat;
                }
            }

            $product = Redshop::product((int)$productId);

            $updatestock             = RedshopHelperStockroom::updateStockroomQuantity($productId, $quantity);
            $stockroom_id_list       = $updatestock['stockroom_list'];
            $stockroom_quantity_list = $updatestock['stockroom_quantity_list'];

            $orderitemdata->stockroom_id                = $stockroom_id_list;
            $orderitemdata->stockroom_quantity          = $stockroom_quantity_list;
            $orderitemdata->order_item_id               = 0;
            $orderitemdata->order_id                    = $this->_id;
            $orderitemdata->user_info_id                = $orderdata->user_info_id;
            $orderitemdata->supplier_id                 = $product->manufacturer_id;
            $orderitemdata->product_id                  = $productId;
            $orderitemdata->order_item_sku              = $product->product_number;
            $orderitemdata->order_item_name             = $product->product_name;
            $orderitemdata->product_quantity            = $quantity;
            $orderitemdata->product_item_price          = $product_price;
            $orderitemdata->product_item_price_excl_vat = $product_excl_price;
            $orderitemdata->product_final_price         = $product_price * $quantity;
            $orderitemdata->order_item_currency         = Redshop::getConfig()->get('REDCURRENCY_SYMBOL');
            $orderitemdata->order_status                = "P";
            $orderitemdata->cdate                       = time();
            $orderitemdata->mdate                       = time();
            $orderitemdata->product_attribute           = $product_attribute;
            $orderitemdata->product_accessory           = $product_accessory;
            $orderitemdata->wrapper_id                  = $item[$i]->wrapper_data;
            $orderitemdata->wrapper_price               = $wrapper_price;

            if (RedshopHelperProductDownload::checkDownload($productId)) {
                $medianame = RedshopHelperProduct::getProductMediaName($productId);

                for ($j = 0, $jn = count($medianame); $j < $jn; $j++) {
                    $sql = "INSERT INTO " . $this->_table_prefix . "product_download "
                        . "(product_id, user_id, order_id, end_date, download_max, download_id, file_name) "
                        . "VALUES('" . $productId . "', '" . $user_id . "', '" . $this->_id . "', "
                        . "'" . (time() + (Redshop::getConfig()->get(
                                    'PRODUCT_DOWNLOAD_DAYS'
                                ) * 23 * 59 * 59)) . "', '" . Redshop::getConfig()->get(
                            'PRODUCT_DOWNLOAD_LIMIT'
                        ) . "', "
                        . "'" . md5(uniqid(mt_rand(), true)) . "', '" . $medianame[$j]->media_name . "')";
                    $this->_db->setQuery($sql);
                    $this->_db->execute();
                }
            }

            if (!$orderitemdata->store()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            /** my accessory save in table start */
            if (count($generateAccessoryCart) > 0) {
                $attArr = $generateAccessoryCart;

                for ($a = 0, $an = count($attArr); $a < $an; $a++) {
                    $accessory_vat_price = 0;
                    $accessory_attribute = "";
                    $accessoryId         = $attArr[$a]['accessory_id'];
                    $accessory_name      = $attArr[$a]['accessory_name'];
                    $accessory_price     = $attArr[$a]['accessory_price'];
                    $accessory_org_price = $accessory_price;

                    if ($accessory_price > 0) {
                        $accessory_vat_price = RedshopHelperProduct::getProductTax(
                            $productId,
                            $accessory_price,
                            $user_id
                        );
                    }

                    $attchildArr = $attArr[$a]['accessory_childs'];

                    for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++) {
                        $attributeId         = $attchildArr[$j]['attribute_id'];
                        $accessory_attribute .= urldecode($attchildArr[$j]['attribute_name']) . ":<br/>";

                        $rowattitem                    = $this->getTable('order_attribute_item');
                        $rowattitem->order_att_item_id = 0;
                        $rowattitem->order_item_id     = $orderitemdata->order_item_id;
                        $rowattitem->section_id        = $attributeId;
                        $rowattitem->section           = "attribute";
                        $rowattitem->parent_section_id = $accessoryId;
                        $rowattitem->section_name      = $attchildArr[$j]['attribute_name'];
                        $rowattitem->is_accessory_att  = 1;

                        if ($attributeId > 0) {
                            if (!$rowattitem->store()) {
                                /** @scrutinizer ignore-deprecated */
                                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                                return false;
                            }
                        }

                        $propArr = $attchildArr[$j]['attribute_childs'];

                        for ($k = 0, $kn = count($propArr); $k < $kn; $k++) {
                            $section_vat = 0;

                            if ($propArr[$k]['property_price'] > 0) {
                                $section_vat = RedshopHelperProduct::getProducttax(
                                    $productId,
                                    $propArr[$k]['property_price'],
                                    $user_id
                                );
                            }

                            $propertyId          = $propArr[$k]['property_id'];
                            $accessory_attribute .= urldecode(
                                    $propArr[$k]['property_name']
                                ) . " (" . $propArr[$k]['property_oprand']
                                . RedshopHelperProductPrice::formattedPrice(
                                    $propArr[$k]['property_price'] + $section_vat
                                ) . ")<br/>";
                            $subpropArr          = $propArr[$k]['property_childs'];

                            $rowattitem                    = $this->getTable('order_attribute_item');
                            $rowattitem->order_att_item_id = 0;
                            $rowattitem->order_item_id     = $orderitemdata->order_item_id;
                            $rowattitem->section_id        = $propertyId;
                            $rowattitem->section           = "property";
                            $rowattitem->parent_section_id = $attributeId;
                            $rowattitem->section_name      = $propArr[$k]['property_name'];
                            $rowattitem->section_price     = $propArr[$k]['property_price'];
                            $rowattitem->section_vat       = $section_vat;
                            $rowattitem->section_oprand    = $propArr[$k]['property_oprand'];
                            $rowattitem->is_accessory_att  = 1;

                            if ($propertyId > 0) {
                                if (!$rowattitem->store()) {
                                    /** @scrutinizer ignore-deprecated */
                                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                                    return false;
                                }
                            }

                            for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++) {
                                $section_vat = 0;

                                if ($subpropArr[$l]['subproperty_price'] > 0) {
                                    $section_vat = RedshopHelperProduct::getProducttax(
                                        $productId,
                                        $subpropArr[$l]['subproperty_price'],
                                        $user_id
                                    );
                                }

                                $subPropertyId       = $subpropArr[$l]['subproperty_id'];
                                $accessory_attribute .= urldecode($subpropArr[$l]['subproperty_name']) . " ("
                                    . $subpropArr[$l]['subproperty_oprand']
                                    . RedshopHelperProductPrice::formattedPrice(
                                        $subpropArr[$l]['subproperty_price'] + $section_vat
                                    )
                                    . ")<br/>";

                                $rowattitem                    = $this->getTable('order_attribute_item');
                                $rowattitem->order_att_item_id = 0;
                                $rowattitem->order_item_id     = $orderitemdata->order_item_id;
                                $rowattitem->section_id        = $subPropertyId;
                                $rowattitem->section           = "subproperty";
                                $rowattitem->parent_section_id = $propertyId;
                                $rowattitem->section_name      = $subpropArr[$l]['subproperty_name'];
                                $rowattitem->section_price     = $subpropArr[$l]['subproperty_price'];
                                $rowattitem->section_vat       = $section_vat;
                                $rowattitem->section_oprand    = $subpropArr[$l]['subproperty_oprand'];
                                $rowattitem->is_accessory_att  = 1;

                                if ($subPropertyId > 0) {
                                    if (!$rowattitem->store()) {
                                        /** @scrutinizer ignore-deprecated */
                                        $this->setError(
                                        /** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg()
                                        );

                                        return false;
                                    }
                                }
                            }
                        }
                    }

                    $accdata = $this->getTable('accessory_detail');

                    if ($accessoryId > 0) {
                        $accdata->load($accessoryId);
                    }

                    $accessoryproduct                    = Redshop::product((int)$accdata->child_product_id);
                    $rowaccitem                          = $this->getTable('order_acc_item');
                    $rowaccitem->order_item_acc_id       = 0;
                    $rowaccitem->order_item_id           = $orderitemdata->order_item_id;
                    $rowaccitem->product_id              = $accessoryId;
                    $rowaccitem->order_acc_item_sku      = $accessoryproduct->product_number;
                    $rowaccitem->order_acc_item_name     = $accessory_name;
                    $rowaccitem->order_acc_price         = $accessory_org_price;
                    $rowaccitem->order_acc_vat           = $accessory_vat_price;
                    $rowaccitem->product_quantity        = $quantity;
                    $rowaccitem->product_acc_item_price  = $accessory_price;
                    $rowaccitem->product_acc_final_price = ($accessory_price * $quantity);
                    $rowaccitem->product_attribute       = $accessory_attribute;

                    if ($accessoryId > 0) {
                        if (!$rowaccitem->store()) {
                            /** @scrutinizer ignore-deprecated */
                            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                            return false;
                        }
                    }
                }
            }

            /** my attribute save in table start */
            if (count($generateAttributeCart) > 0) {
                $attArr = $generateAttributeCart;

                for ($j = 0, $jn = count($attArr); $j < $jn; $j++) {
                    $attributeId = $attArr[$j]['attribute_id'];

                    $rowattitem                    = $this->getTable('order_attribute_item');
                    $rowattitem->order_att_item_id = 0;
                    $rowattitem->order_item_id     = $orderitemdata->order_item_id;
                    $rowattitem->section_id        = $attributeId;
                    $rowattitem->section           = "attribute";
                    $rowattitem->parent_section_id = $productId;
                    $rowattitem->section_name      = $attArr[$j]['attribute_name'];
                    $rowattitem->is_accessory_att  = 0;

                    if ($attributeId > 0) {
                        if (!$rowattitem->store()) {
                            /** @scrutinizer ignore-deprecated */
                            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                            return false;
                        }
                    }

                    $propArr = $attArr[$j]['attribute_childs'];

                    for ($k = 0, $kn = count($propArr); $k < $kn; $k++) {
                        $section_vat = 0;

                        if ($propArr[$k]['property_price'] > 0) {
                            $section_vat = RedshopHelperProduct::getProducttax(
                                $productId,
                                $propArr[$k]['property_price']
                            );
                        }

                        $propertyId = $propArr[$k]['property_id'];
                        /** product property STOCKROOM update start */
                        RedshopHelperStockroom::updateStockroomQuantity($propertyId, $quantity, "property");

                        $rowattitem                    = $this->getTable('order_attribute_item');
                        $rowattitem->order_att_item_id = 0;
                        $rowattitem->order_item_id     = $orderitemdata->order_item_id;
                        $rowattitem->section_id        = $propertyId;
                        $rowattitem->section           = "property";
                        $rowattitem->parent_section_id = $attributeId;
                        $rowattitem->section_name      = $propArr[$k]['property_name'];
                        $rowattitem->section_price     = $propArr[$k]['property_price'];
                        $rowattitem->section_vat       = $section_vat;
                        $rowattitem->section_oprand    = $propArr[$k]['property_oprand'];
                        $rowattitem->is_accessory_att  = 0;

                        if ($propertyId > 0) {
                            if (!$rowattitem->store()) {
                                /** @scrutinizer ignore-deprecated */
                                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                                return false;
                            }
                        }

                        $subpropArr = $propArr[$k]['property_childs'];

                        for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++) {
                            $section_vat = 0;

                            if ($subpropArr[$l]['subproperty_price'] > 0) {
                                $section_vat = RedshopHelperProduct::getProducttax(
                                    $productId,
                                    $subpropArr[$l]['subproperty_price'],
                                    $user_id
                                );
                            }

                            $subPropertyId = $subpropArr[$l]['subproperty_id'];
                            /** product subproperty STOCKROOM update start */
                            $updatestock = RedshopHelperStockroom::updateStockroomQuantity(
                                $subPropertyId,
                                $quantity,
                                "subproperty"
                            );

                            $rowattitem                    = $this->getTable('order_attribute_item');
                            $rowattitem->order_att_item_id = 0;
                            $rowattitem->order_item_id     = $orderitemdata->order_item_id;
                            $rowattitem->section_id        = $subPropertyId;
                            $rowattitem->section           = "subproperty";
                            $rowattitem->parent_section_id = $propertyId;
                            $rowattitem->section_name      = $subpropArr[$l]['subproperty_name'];
                            $rowattitem->section_price     = $subpropArr[$l]['subproperty_price'];
                            $rowattitem->section_vat       = $section_vat;
                            $rowattitem->section_oprand    = $subpropArr[$l]['subproperty_oprand'];
                            $rowattitem->is_accessory_att  = 0;

                            if ($subPropertyId > 0) {
                                if (!$rowattitem->store()) {
                                    /** @scrutinizer ignore-deprecated */
                                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                                    return false;
                                }
                            }
                        }
                    }
                }
            }

            // Store userfields
            $userfields    = $item[$i]->extrafieldname;
            $userfields_id = $item[$i]->extrafieldId;

            for ($ui = 0, $countUserField = count($userfields); $ui < $countUserField; $ui++) {
                RedshopHelperProduct::insertProductUserField(
                    $userfields_id[$ui],
                    $orderitemdata->order_item_id,
                    12,
                    $userfields[$ui]
                );
            }
        }

        if ($orderitemdata->order_item_id > 0) {
            $totalItemVat = $orderitemdata->product_item_price - $orderitemdata->product_item_price_excl_vat;

            $orderdata->order_tax      = $orderdata->order_tax + ($totalItemVat * $orderitemdata->product_quantity);
            $orderdata->order_total    = $orderdata->order_total + $orderitemdata->product_final_price;
            $orderdata->order_subtotal = $orderdata->order_subtotal + $orderitemdata->product_final_price;
            $orderdata->mdate          = time();

            // Update order detail
            if (!$orderdata->store()) {
                return false;
            }

            if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1) {
                RedshopEconomic::renewInvoiceInEconomic($orderdata);
            }

            // Send mail from template
            Redshop\Mail\Order::sendSpecialDiscountMail($this->_id);
        } else {
            return false;
        }

        $this->_dispatcher->trigger('onAfterAddNewOrderItem', array($orderdata));

        return true;
    }

    /**
     * Method for delete single order item from order.
     *
     * @param   array  $data  Array of data
     *
     * @return  boolean
     */
    public function delete_item($data)
    {
        $productId   = $data['productid'];
        $orderItemId = $data['order_item_id'];

        // Get Order Item Info
        $orderItem         = RedshopEntityOrder_Item::getInstance($orderItemId);
        $orderItemQuantity = $orderItem->get('product_quantity');

        // Get Order Info
        $order = RedshopEntityOrder::getInstance($this->_id);

        // Update stock room
        RedshopHelperStockroom::manageStockAmount(
            $productId,
            $orderItem->get('product_quantity'),
            $orderItem->get('stockroom_id')
        );

        $db = $this->_db;

        // Delete order item
        $query = $db->getQuery(true)
            ->delete($db->qn('#__redshop_order_item'))
            ->where($db->qn('order_item_id') . ' = ' . $orderItem->getId());
        $db->setQuery($query);

        unset($orderItem);

        if (!$db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $db->getErrorMsg());

            return false;
        }

        $this->updateAttributeItem($orderItemId, $orderItemQuantity);

        // Remove order item attribute
        $query->clear()
            ->delete($db->qn('#__redshop_order_attribute_item'))
            ->where($db->qn('order_item_id') . ' = ' . $orderItemId);
        $db->setQuery($query)->execute();

        // Remove accessory of order item
        $query->clear()
            ->delete($db->qn('#__redshop_order_acc_item'))
            ->where($db->qn('order_item_id') . ' = ' . $orderItemId);
        $db->setQuery($query)->execute();

        $this->/** @scrutinizer ignore-call */
        special_discount(
            array('order_item_id' => $orderItemId, 'special_discount' => $order->get('special_discount')),
            true
        );

        // Economic Integration start for invoice generate
        if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1) {
            RedshopEconomic::renewInvoiceInEconomic($order->getItem());
        }

        // Send mail from template ********************/
        Redshop\Mail\Order::sendSpecialDiscountMail($this->_id);

        return true;
    }

    public function updateAttributeItem($order_item_id, $quantity = 0)
    {
        $attArr = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, 0, "attribute");

        /** my attribute save in table start */
        for ($j = 0, $jn = count($attArr); $j < $jn; $j++) {
            $propArr = RedshopHelperOrder::getOrderItemAttributeDetail(
                $order_item_id,
                0,
                "property",
                $attArr[$j]->section_id
            );

            for ($k = 0, $kn = count($propArr); $k < $kn; $k++) {
                $propitemdata = $this->getTable('order_attribute_item');
                $propitemdata->load($propArr[$k]->order_att_item_id);

                /** product property STOCKROOM update start */
                if ($quantity > 0) {
                    RedshopHelperStockroom::manageStockAmount(
                        $propitemdata->section_id,
                        $quantity,
                        $propArr[$k]->stockroom_id,
                        "property"
                    );
                } elseif ($quantity < 0) {
                    RedshopHelperStockroom::updateStockroomQuantity(
                        $propitemdata->section_id,
                        (-$quantity),
                        "property"
                    );
                }

                $subpropArr = RedshopHelperOrder::getOrderItemAttributeDetail(
                    $order_item_id,
                    0,
                    "subproperty",
                    $propitemdata->section_id
                );

                for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++) {
                    $subpropitemdata = $this->getTable('order_attribute_item');
                    $subpropitemdata->load($subpropArr[$l]->order_att_item_id);

                    if ($quantity > 0) {
                        RedshopHelperStockroom::manageStockAmount(
                            $subpropitemdata->section_id,
                            $quantity,
                            $subpropArr[$l]->stockroom_id,
                            "subproperty"
                        );
                    } elseif ($quantity < 0) {
                        RedshopHelperStockroom::updateStockroomQuantity(
                            $subpropitemdata->section_id,
                            (-$quantity),
                            "subproperty"
                        );
                    }
                }
            }
        }

        return true;
    }

    /**
     * Method for re-calculate price of order when update order item.
     *
     * @param   array    $data  Array data of updated order
     * @param   boolean  $chk   True for check
     *
     * @return  boolean
     */
    public function special_discount($data, $chk = false)
    {
        $orderData = $this->getTable('order_detail');
        $orderData->load($this->_id);

        $orderItems  = RedshopHelperOrder::getOrderItemDetail($this->_id, 0, 0, true);
        $orderItemId = isset($data['order_item_id']) ? $data['order_item_id'] : 0;

        if (!$orderData->special_discount) {
            $orderData->special_discount = 0;
        }

        if (!$orderData->special_discount_amount) {
            $orderData->special_discount_amount = 0;
        }

        if ($data['special_discount'] == $orderData->special_discount && $chk != true) {
            return false;
        }

        $specialDiscount    = $data['special_discount'];
        $orderSubTotal      = 0;
        $orderSubTotalNoVat = 0;
        $orderTax           = $orderData->order_tax;
        $orderDetailTax     = array();

        foreach ($orderItems as $orderItem) {
            if ($orderItemId != $orderItem->order_item_id) {
                $orderSubTotalNoVat += $orderItem->product_item_price_excl_vat * $orderItem->product_quantity;
                $orderSubTotal      += $orderItem->product_item_price * $orderItem->product_quantity;
            }

            $orderDetailTax[] = ((float)$orderItem->product_item_price - (float)$orderItem->product_item_price_excl_vat) * $orderItem->product_quantity;
        }

        if (!empty($orderDetailTax)) {
            $orderTax = array_sum($orderDetailTax);
        }

        $discountPrice                      = ($orderSubTotal * $specialDiscount) / 100;
        $orderData->special_discount        = $specialDiscount;
        $orderData->special_discount_amount = $discountPrice;

        $orderData->order_total = $orderSubTotal + $orderData->order_shipping - $discountPrice - $orderData->order_discount;
        $post                   = array();

        $paymentmethod                            = RedshopHelperOrder::getPaymentMethodInfo(
            $data['payment_method_class']
        );
        $paymentmethod                            = $paymentmethod[0];
        $paymentparams                            = new Registry($paymentmethod->params);
        $paymentinfo                              = new stdclass;
        $paymentinfo->payment_price               = $paymentparams->get('payment_price', '');
        $paymentinfo->is_creditcard               = $post['economic_is_creditcard'] = $paymentparams->get(
            'is_creditcard',
            ''
        );
        $paymentinfo->payment_oprand              = $paymentparams->get('payment_oprand', '');
        $paymentinfo->accepted_credict_card       = $paymentparams->get("accepted_credict_card");
        $paymentinfo->payment_discount_is_percent = $paymentparams->get('payment_discount_is_percent', '');


        $paymentMethod = RedshopHelperPayment::calculate(
            $orderData->order_total,
            $paymentinfo,
            $orderData->order_subtotal
        );

        $orderData->payment_discount = $paymentMethod[1];

        $orderData->order_total = $orderData->order_total - $orderData->payment_discount;

        $orderData->order_subtotal = $orderSubTotal;
        $orderData->order_tax      = $orderTax;
        $orderData->mdate          = time();

        if (!$orderData->store()) {
            return false;
        }

        $this->_dispatcher->trigger('onAfterUpdateSpecialDiscount', array($orderData));

        if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1) {
            RedshopEconomic::renewInvoiceInEconomic($orderData);
        }

        // Send mail from template
        Redshop\Mail\Order::sendSpecialDiscountMail($this->_id);

        return true;
    }

    public function updateItem($data)
    {
        $order_item_id = $data['order_item_id'];
        $orderitemdata = $this->getTable('order_item_detail');
        $orderitemdata->load($order_item_id);
        $orderdata = $this->getTable('order_detail');
        $orderdata->load($this->_id);
        $order_id         = $this->_id;
        $productId        = $orderitemdata->product_id;
        $currentStock     = RedshopHelperStockroom::getStockroomTotalAmount($productId);
        $user_id          = $orderdata->user_id;
        $productPrice     = $data['update_price'];
        $productPrice_new = 0;

        if ($productPrice < 0) {
            $productPrice_new = $productPrice;
            $productPrice     = $productPrice * -1;
        }

        $customer_note = $data['customer_note'];

        $product_tax = 0;

        if ($productPrice > 0) {
            $product_tax = RedshopHelperProduct::getProductTax($productId, $productPrice, $user_id);
        }

        if ($productPrice_new < 0) {
            $product_tax  = $product_tax * -1;
            $productPrice = $productPrice_new;
        }

        $new_added_qty = $data['quantity'] - $orderitemdata->product_quantity;

        if ($currentStock >= $new_added_qty || Redshop::getConfig()->get('USE_STOCKROOM') == 0) {
            $quantity = (int)$data['quantity'];
        } else {
            $quantity = (int)$orderitemdata->product_quantity;
        }

        $product_item_price          = $productPrice + $product_tax;
        $product_item_price_excl_vat = $productPrice;
        $product_final_price         = $product_item_price * $quantity;
        $subtotal                    = $product_item_price * $quantity;

        $OrderItems = RedshopHelperOrder::getOrderItemDetail($order_id);
        $totalTax   = $product_tax * $quantity;

        for ($i = 0, $in = count($OrderItems); $i < $in; $i++) {
            if ($order_item_id != $OrderItems[$i]->order_item_id) {
                $itemtax  = $OrderItems[$i]->product_item_price - $OrderItems[$i]->product_item_price_excl_vat;
                $totalTax = $totalTax + ($itemtax * $OrderItems[$i]->product_quantity);
                $subtotal = $subtotal + ($OrderItems[$i]->product_item_price * $OrderItems[$i]->product_quantity);
            }

            if ($order_item_id == $OrderItems[$i]->order_item_id) {
                $newquantity = $OrderItems[$i]->product_quantity - $quantity;

                if ($newquantity > 0) {
                    RedshopHelperStockroom::manageStockAmount($productId, $newquantity, $orderitemdata->stockroom_id);
                } elseif ($newquantity < 0) {
                    $updatestock = RedshopHelperStockroom::updateStockroomQuantity($productId, (-$newquantity));

                    $stockroom_id_list                 = $updatestock['stockroom_list'];
                    $stockroom_quantity_list           = $updatestock['stockroom_quantity_list'];
                    $orderitemdata->stockroom_id       = $stockroom_id_list;
                    $orderitemdata->stockroom_quantity = $stockroom_quantity_list;
                }

                $this->updateAttributeItem($order_item_id, $newquantity);
            }
        }

        $total                                      = $subtotal + $orderdata->order_shipping - abs(
                $orderdata->order_discount
            );
        $orderitemdata->product_item_price          = $product_item_price;
        $orderitemdata->product_item_price_excl_vat = $product_item_price_excl_vat;
        $orderitemdata->product_final_price         = $product_final_price;
        $orderitemdata->product_quantity            = $quantity;
        $orderitemdata->customer_note               = $customer_note;
        $orderdata->order_tax                       = $totalTax;
        $orderdata->order_total                     = $total;
        $orderdata->order_subtotal                  = $subtotal;

        if ($orderitemdata->store()) {
            $this->_dispatcher->trigger('onAfterUpdateOrderItem', array($orderitemdata));

            if (!$orderdata->store()) {
                return false;
            }

            if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1) {
                RedshopEconomic::renewInvoiceInEconomic($orderdata);
            }

            $tmpArr['special_discount']     = $orderdata->special_discount;
            $tmpArr['payment_method_class'] = $data['payment_method_class'];
            $this->/** @scrutinizer ignore-call */ special_discount($tmpArr, true);
        } else {
            return false;
        }

        RedshopHelperOrder::updateStatus();

        return true;
    }

    public function update_discount($data)
    {
        // Get Order Info
        $orderData = $this->getTable('order_detail');
        $orderData->load($this->_id);

        $orderItems      = RedshopHelperOrder::getOrderItemDetail($this->_id);
        $update_discount = abs($data['update_discount']);

        if ($update_discount == $orderData->order_discount) {
            return false;
        }

        $subtotal = 0;

        if ($orderItems) {
            for ($i = 0, $in = count($orderItems); $i < $in; $i++) {
                $subtotal = $subtotal + ($orderItems[$i]->product_item_price * $orderItems[$i]->product_quantity);
            }
        }

        $temporder_total = $subtotal + $orderData->order_discount + $orderData->special_discount_amount;

        if ($update_discount > $temporder_total) {
            $update_discount = $subtotal;
        }

        if (Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') == '0' && Redshop::getConfig()->get(
                'VAT_RATE_AFTER_DISCOUNT'
            ) && $update_discount != "0.00" && $orderData->order_tax && !empty($update_discount)) {
            $Discountvat     = (Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $update_discount);
            $update_discount = $update_discount + $Discountvat;
        }

        if (abs($data['update_discount']) == 0) {
            $order_total = ($subtotal + $orderData->order_shipping) - ($orderData->special_discount_amount);
        } else {
            $order_total = ($subtotal + $orderData->order_shipping) - ($update_discount) - ($orderData->special_discount_amount);
        }

        $orderData->order_total = $order_total;
        $post                   = array();

        $paymentmethod                            = RedshopHelperOrder::getPaymentMethodInfo(
            $data['payment_method_class']
        );
        $paymentmethod                            = $paymentmethod[0];
        $paymentparams                            = new Registry($paymentmethod->params);
        $paymentinfo                              = new stdclass;
        $paymentinfo->payment_price               = $paymentparams->get('payment_price', '');
        $paymentinfo->is_creditcard               = $post['economic_is_creditcard'] = $paymentparams->get(
            'is_creditcard',
            ''
        );
        $paymentinfo->payment_oprand              = $paymentparams->get('payment_oprand', '');
        $paymentinfo->accepted_credict_card       = $paymentparams->get("accepted_credict_card");
        $paymentinfo->payment_discount_is_percent = $paymentparams->get('payment_discount_is_percent', '');

        $paymentMethod = RedshopHelperPayment::calculate(
            $orderData->order_total,
            $paymentinfo,
            $orderData->order_subtotal
        );

        $orderData->payment_discount = $paymentMethod[1];

        $orderData->order_total = $orderData->order_total - $orderData->payment_discount;

        $orderData->order_tax          = $orderData->order_tax + $orderData->order_discount_vat - $Discountvat;
        $orderData->order_discount_vat = $Discountvat;
        $orderData->order_discount     = $update_discount;
        $orderData->mdate              = time();

        if (!$orderData->store()) {
            return false;
        }

        $this->_dispatcher->trigger('onAfterUpdateDiscount', array($orderData));

        // Economic Integration start for invoice generate
        if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1) {
            RedshopEconomic::renewInvoiceInEconomic($orderData);
        }

        // Send mail from template
        Redshop\Mail\Order::sendSpecialDiscountMail($this->_id);

        return true;
    }

    public function update_shippingrates($data)
    {
        // Get Order Info
        $orderdata = $this->getTable('order_detail');
        $orderdata->load($this->_id);

        if ($data['shipping_rate_id'] != "") {
            // Get Shipping rate info Info
            $neworder_shipping = Redshop\Shipping\Rate::decrypt($data['shipping_rate_id']);

            if ($data['shipping_rate_id'] != $orderdata->ship_method_id || $neworder_shipping[0] == 'plgredshop_shippingdefault_shipping_gls') {
                if (count($neworder_shipping) > 4) {
                    // Shipping_rate_value
                    $orderdata->order_total        = $orderdata->order_total - $orderdata->order_shipping + $neworder_shipping[3];
                    $orderdata->order_shipping     = $neworder_shipping[3];
                    $orderdata->ship_method_id     = $data['shipping_rate_id'];
                    $orderdata->order_shipping_tax = (isset($neworder_shipping[6]) && $neworder_shipping[6]) ? $neworder_shipping[6] : 0;
                    $orderdata->mdate              = time();
                    $orderdata->shop_id            = $data['shop_id'] . "###" . $data['gls_mobile'] . "###" . $data['gls_zipcode'];

                    if (!$orderdata->store()) {
                        return false;
                    }

                    // Economic Integration start for invoice generate
                    if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1) {
                        RedshopEconomic::renewInvoiceInEconomic($orderdata);
                    }
                }
            }
        }

        $this->_dispatcher->trigger('onAfterUpdateShippingRates', array($orderdata));

        return true;
    }

    public function updateShippingAdd($data)
    {
        $row = $this->getTable('order_user_detail');
        $row->load($data['order_info_id']);

        $row->bind($data);

        if ($row->store()) {
            // Field_section 14 :Customer Address Section
            $fieldSection = 14;

            if ($row->is_company == 1) {
                // Field_section 15 :Company Address Section
                $fieldSection = 15;
            }

            RedshopHelperExtrafields::extraFieldSave($data, $fieldSection, $row->users_info_id);

            $this->_dispatcher->trigger('onAfterUpdateShippingAddress', array($data));

            return true;
        } else {
            return false;
        }
    }

    public function updateBillingAdd($data)
    {
        $row = $this->getTable('order_user_detail');
        $row->load($data['order_info_id']);

        $row->bind($data);

        if ($row->store()) {
            // Field_section 7 :Customer Address Section
            $fieldSection = 7;

            if ($row->is_company == 1) {
                // Field_section 8 :Company Address Section
                $fieldSection = 8;
            }

            RedshopHelperExtrafields::extraFieldSave($data, $fieldSection, $row->users_info_id);

            $this->_dispatcher->trigger('onAfterUpdateBillingAddress', array($data));

            return true;
        } else {
            return false;
        }
    }

    // Get order stats log
    public function getOrderLog($order_id)
    {
        $database = JFactory::getDbo();
        $sql      = "SELECT log.*,order_status_name "
            . " FROM " . $this->_table_prefix . "order_status_log AS log , " . $this->_table_prefix . "order_status ros"
            . " WHERE log.order_id=" . $order_id . " AND log.order_status=ros.order_status_code";
        $database->setQuery($sql);

        return $database->loadObjectList();
    }

    // Get Product subscription price
    public function getProductSubscriptionDetail($productId, $subscription_id)
    {
        $db = JFactory::getDbo();

        $query = "SELECT * "
            . " FROM " . $this->_table_prefix . "product_subscription"
            . " WHERE "
            . " product_id = " . $productId . " And subscription_id = " . $subscription_id;
        $db->setQuery($query);

        return $db->loadObject();
    }

    // Get User Product subscription detail
    public function getUserProductSubscriptionDetail($order_item_id)
    {
        $db    = JFactory::getDbo();
        $query = "SELECT * "
            . " FROM " . $this->_table_prefix . "product_subscribe_detail"
            . " WHERE "
            . " order_item_id = " . $order_item_id;
        $db->setQuery($query);

        return $db->loadObject();
    }

    // Get credit card detail
    public function getccdetail($order_id)
    {
        $db    = JFactory::getDbo();
        $query = "SELECT * "
            . " FROM " . $this->_table_prefix . "order_payment  "
            . " WHERE "
            . " order_id = " . $order_id
            . " AND  payment_method_class='rs_payment_localcreditcard'";
        $db->setQuery($query);

        return $db->loadObject();
    }

    public function getvar($name)
    {
        global $_GET, $_POST;

        if (isset($_GET[$name])) {
            return $_GET[$name];
        } elseif (isset($_POST[$name])) {
            return $_POST[$name];
        } else {
            return false;
        }
    }

    public function update_ccdata($order_id, $payment_transaction_id)
    {
        $db = JFactory::getDbo();

        $session = JFactory::getSession();
        $ccdata  = $session->get('ccdata');

        $order_payment_code     = $ccdata['creditcard_code'];
        $order_payment_cardname = base64_encode($ccdata['order_payment_name']);
        $order_payment_number   = base64_encode($ccdata['order_payment_number']);
        $order_payment_ccv      = base64_encode($ccdata['credit_card_code']);
        $order_payment_expire   = $ccdata['order_payment_expire_month'] . $ccdata['order_payment_expire_year'];

        $payment_update = "UPDATE " . $this->_table_prefix . "order_payment "
            . " SET order_payment_code  = '" . $order_payment_code . "' ,"
            . " order_payment_cardname  = '" . $order_payment_cardname . "' ,"
            . " order_payment_number  = '" . $order_payment_number . "' ,"
            . " order_payment_ccv  = '" . $order_payment_ccv . "' ,"
            . " order_payment_expire  = '" . $order_payment_expire . "' ,"
            . " order_payment_trans_id  = '" . $payment_transaction_id . "' "
            . " WHERE order_id  = '" . $order_id . "'";

        $db->setQuery($payment_update);

        if (!$db->execute()) {
            return false;
        }
    }

    /**
     * @return  void
     */
    public function resetcart()
    {
        RedshopHelperCartSession::reset();
        $session = JFactory::getSession();
        $session->set('ccdata', null);
        $session->set('issplit', null);
        $session->set('userfield', null);

        unset($_SESSION ['ccdata']);
    }
}
