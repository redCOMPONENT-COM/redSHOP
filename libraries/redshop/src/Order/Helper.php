<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Order;

defined('_JEXEC') or die;

/**
 * Order helper
 *
 * @since  2.0.7
 */
class Helper
{
    /**
     * Method for generate accessory of order.
     *
     * @param integer $orderItemId Order item ID.
     *
     * @return  string
     *
     * @since   2.0.7
     */
    public static function generateAccessories($orderItemId = 0)
    {
        $accessoryHtml = '';
        $orderItemData = \RedshopHelperOrder::getOrderItemAccessoryDetail($orderItemId);

        if (count($orderItemData) > 0) {
            $accessoryHtml .= '<div class="checkout_accessory_static">' . \JText::_(
                    "COM_REDSHOP_ACCESSORY"
                ) . ':</div>';

            foreach ($orderItemData as $orderItemDatum) {
                $accessoryQuantity = " [" . \JText::_(
                        'COM_REDSHOP_ACCESSORY_QUANTITY_LBL'
                    ) . " " . $orderItemDatum->product_quantity . "] ";
                $accessoryHtml .= "<div class='checkout_accessory_title'>"
                    . urldecode($orderItemDatum->order_acc_item_name)
                    . " ("
                    . \RedshopHelperProductPrice::formattedPrice(
                        $orderItemDatum->order_acc_price + $orderItemDatum->order_acc_vat
                    )
                    . ")" . $accessoryQuantity . "</div>";
                $makeAttributeOrder = \RedshopHelperProduct::makeAttributeOrder(
                    $orderItemId,
                    1,
                    $orderItemDatum->product_id
                );
                $accessoryHtml .= $makeAttributeOrder->product_attribute;
            }
        } else {
            $orderItemData = \RedshopHelperOrder::getOrderItemDetail(0, 0, $orderItemId);
            $orderItemData = !empty($orderItemData) ? reset($orderItemData) : array();
            $accessoryHtml = !empty($orderItemData) ? $orderItemData->product_accessory : '';
        }

        return $accessoryHtml;
    }

    /**
     * Redesign product item
     *
     * @param array $post Data
     *
     * @return  array
     *
     * @since   2.1.0
     */
    public static function redesignProductItem($post = array())
    {
        if (empty($post)) {
            return array();
        }

        $orderItem = array();
        $i = -1;

        foreach ($post as $key => $value) {
            if (!strcmp("product", substr($key, 0, 7)) && strlen($key) < 10) {
                $i++;

                if (!isset($orderItem[$i])) {
                    $orderItem[$i] = new \stdClass;
                }

                $orderItem[$i]->product_id = $value;
            }

            if (!strcmp("attribute_dataproduct", substr($key, 0, 21))) {
                $orderItem[$i]->attribute_data = $value;
            }

            if (!strcmp("property_dataproduct", substr($key, 0, 20))) {
                $orderItem[$i]->property_data = $value;
            }

            if (!strcmp("subproperty_dataproduct", substr($key, 0, 23))) {
                $orderItem[$i]->subproperty_data = $value;
            }

            if (!strcmp("accessory_dataproduct", substr($key, 0, 21))) {
                $orderItem[$i]->accessory_data = $value;
            }

            if (!strcmp("acc_attribute_dataproduct", substr($key, 0, 25))) {
                $orderItem[$i]->acc_attribute_data = $value;
            }

            if (!strcmp("acc_property_dataproduct", substr($key, 0, 24))) {
                $orderItem[$i]->acc_property_data = $value;
            }

            if (!strcmp("acc_subproperty_dataproduct", substr($key, 0, 27))) {
                $orderItem[$i]->acc_subproperty_data = $value;
            }

            if (!strcmp("extrafieldId", substr($key, 0, 12))) {
                $orderItem[$i]->extrafieldId = $value;
            }

            if (!strcmp("extrafieldname", substr($key, 0, 14))) {
                $orderItem[$i]->extrafieldname = $value;
            }

            if (!strcmp("wrapper_dataproduct", substr($key, 0, 19))) {
                $orderItem[$i]->wrapper_data = $value;
            }

            if (!strcmp("quantityproduct", substr($key, 0, 15))) {
                $orderItem[$i]->quantity = $value;
            }

            if (!strcmp("prdexclpriceproduct", substr($key, 0, 19))) {
                $orderItem[$i]->prdexclprice = $value;
            }

            if (!strcmp("taxpriceproduct", substr($key, 0, 15))) {
                $orderItem[$i]->taxprice = $value;
            }

            if (!strcmp("productpriceproduct", substr($key, 0, 19))) {
                $orderItem[$i]->productprice = $value;
            }

            if (!strcmp("requiedAttributeproduct", substr($key, 0, 23))) {
                $orderItem[$i]->requiedAttributeproduct = $value;
            }
        }

        return $orderItem;
    }

    /**
     * @param $userId
     *
     * @return float|mixed
     * @throws \Exception
     */
    public static function getOrderTotalAmountByUserId($userId)
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('SUM(' . $db->qn('o.order_total') . ') AS order_total')
            ->from($db->qn('#__redshop_orders', 'o'))
            ->leftJoin(
                $db->qn('#__redshop_user_info', 'uf')
                . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id')
            )
            ->where($db->qn('address_type') . ' = ' . $db->q('BT'))
            ->where($db->qn('o.user_id') . ' = ' . $db->q($userId))
            ->where(
                $db->qn('o.order_status') . ' IN ('
                . $db->q(implode(',', ['C', 'PR', 'S'])) . ')'
            );

        return \Redshop\DB\Tool::safeSelect($db, $query, false, 0.0);
    }

    /**
     * @param $userId
     *
     * @return float
     * @throws \Exception
     */
    public static function getAvgAmountById($userId)
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(
            '(SUM(' . $db->qn('o.order_total') . ')/COUNT(DISTINCT('
            . $db->qn('o.user_id') . ')) AS avg_order'
        )
            ->from($db->qn('#__redshop_orders', 'o'))
            ->where($db->qn('o.user_id') . ' = ' . $db->q($userId))
            ->where(
                $db->qn('o.order_status') . ' IN ('
                . $db->q(implode(',', ['C', 'PR', 'S'])) . ')'
            );

        return \Redshop\DB\Tool::safeSelect($db, $query, false, 0.0);
    }

    /**
     * @param int $id
     *
     * @return null
     * @throws \Exception
     */
    public static function getTotalOrderById($id = 0)
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(
            'SUM(' . $db->qn('order_total') . ') AS ' . $db->qn('order_total')
            . ', count(*) AS ' . $db->qn('tot_order')
        )
            ->from($db->qn('#__redshop_orders'))
            ->where($db->qn('user_info_id') . ' = ' . $db->q((int)$id));

        return \Redshop\DB\Tool::safeSelect($db, $query, false, 0.0);
    }

    /**
     * @return null
     * @throws \Exception
     */
    public static function getNewOrders()
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select(
            array(
                $db->qn('o.order_id'),
                $db->qn('o.order_total'),
                $db->qn('o.order_status'),
                $db->qn('o.order_payment_status'),
                $db->qn('os.order_status_name'),
                'CONCAT(' . $db->qn('u.firstname') . '," ",' . $db->qn('u.lastname') . ') AS name'
            )
        )
            ->from($db->qn('#__redshop_order_users_info', 'u'))
            ->innerJoin(
                $db->qn('#__redshop_orders', 'o') .
                ' ON ' . $db->qn('u.order_id') . '=' . $db->qn('o.order_id')
                . ' AND ' . $db->qn('u.address_type') . '="BT"'
            )
            ->innerJoin(
                $db->qn('#__redshop_order_status', 'os')
                . ' ON ' . $db->qn('os.order_status_code') . '=' . $db->qn('o.order_status')
            )
            ->order($db->qn('o.order_id') . ' DESC')
            ->setLimit(10);

        return \Redshop\DB\Tool::safeSelect($db, $query, true, []);
    }

    /**
     * @param $data
     *
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    public static function updateOrderPaymentMethod($data)
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $orderId = (int)$data['cid'][0];
        $paymentClass = $data['payment_method_class'];

        $paymentMethod = \RedshopHelperOrder::getPaymentMethodInfo($paymentClass, false)[0];

        if (empty($paymentMethod->extension_id)) {
            return false;
        }

        $fields = array(
            $db->qn('payment_method_class') . ' = ' . $db->q($paymentClass),
            $db->qn('order_payment_name') . ' = ' . $db->q($paymentMethod->name)
        );

        $conditions = array(
            $db->qn('order_id') . ' = ' . $db->q($orderId)
        );

        $query->update($db->qn('#__redshop_order_payment'))
            ->set($fields)
            ->where($conditions);

        $result = \Redshop\DB\Tool::safeExecute($db, $query);

        if ($result) {
            $app = \JFactory::getApplication();

            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('o.*, ol.*')
                ->from($db->qn('#__redshop_order_status_log', 'ol'))
                ->leftJoin(
                    $db->qn('#__redshop_order_status', 'o')
                    . ' ON ' . $db->qn('ol.order_status') . ' = ' . $db->qn('o.order_status_code')
                )
                ->where($db->qn('ol.order_id') . ' = ' . $db->q($orderId));

            $orderStatus = \Redshop\DB\Tool::safeSelect($db, $query);

            if (isset($orderStatus->order_id)) {
                if ($app->isClient('administrator')) {
                    /* \RedshopHelperOrder::changeOrderStatusMail(
                        $orderId,
                        $orderStatus->order_status,
                        $orderStatus->customer_note
                    );*/

                    $emailBody = \RedshopLayoutHelper::render(
                        'email.order.payment_method_changed',
                        array(
                            'order' => $orderStatus,
	                        'encrKey' => \RedshopEntityOrder::getInstance($orderId)->getItem()->encr_key
                        )
                    );

                    $mailFrom     = $app->get('mailfrom');
                    $fromName     = $app->get('fromname');
                    $userDetail   = \RedshopHelperOrder::getOrderBillingUserInfo($orderId);

                    $isSend = \Redshop\Mail\Helper::sendEmail(
                        $mailFrom,
                        $fromName,
                        $userDetail->user_email,
                        \JText::_('COM_REDSHOP_PAYMENT_METHOD_CHANGED_EMAIL_SUBJECT'),
                        $emailBody
                    );

                    if ($isSend) {
                        \JFactory::getApplication()->enqueueMessage(\JText::_('COM_REDSHOP_SEND_ORDER_MAIL'));
                    }
                }

            } else {
                \JFactory::getApplication()->enqueueMessage(\JText::_('COM_REDSHOP_ERROR_SENDING_ORDER_MAIL'), 'error');
            }
        }

        return $result;
    }
}
