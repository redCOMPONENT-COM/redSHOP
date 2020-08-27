<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * redSHOP google tag manager System Plugin
 *
 * @since  2.0
 */
class PlgSystemRedGoogleTagmanager extends JPlugin
{
    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    /**
     * This event is triggered after the framework has loaded and initialised and the router has route the client request.
     * Routing is the process of examining the request environment to determine which component should receive the request.
     * The component optional parameters are then set in the request object to be processed when the application is being dispatched.
     * When this event triggers the router has parsed the route and pushed the request parameters into JRequest for retrieval by the application.
     *
     * @return  void
     * @throws  Exception
     */
    public function onAfterRoute()
    {
        $app = JFactory::getApplication();

        if (!$app->isClient('site')) {
            return;
        }

        // Place GA code for page is not receipt page.
        if ('receipt' == $app->input->getWord('layout', '')) {
            return;
        }

        require_once __DIR__ . '/helper/google_tagmanager.php';

        $googleTagmanagerHelper = new RedSHOPGoogle_TagmanagerHelper();
        $googleTagmanagerHelper->placeTransGTM();
    }

    /**
     * Event trigger when display order
     *
     * @param   object  $orderDetail  Order detail data
     *
     * @return  void
     * @throws  Exception
     *
     * @since  2.0
     */
    public function onDisplayOrderReceipt(&$orderDetail)
    {
        $app = JFactory::getApplication();

        if (!$app->isClient('site')) {
            return;
        }

        // Just work if current layout is receipt and tracker key exist.
        if ($app->input->getWord('layout', '') != 'receipt') {
            return;
        }

        require_once __DIR__ . '/helper/google_tagmanager.php';

        $googleTagmanagerHelper = new RedSHOPGoogle_TagmanagerHelper();

        /** @var RedshopModelOrder_detail $orderDetailModel */
        $orderDetailModel = JModelLegacy::getInstance('Order_Detail', 'RedshopModel');

        $billingAddress = $orderDetailModel->billingaddresses();
        $orderItems     = RedshopHelperOrder::getOrderItemDetail($orderDetail->order_id);

        // Start place transaction for new receipt.
        $orderTransaction = array(
            'order_id'       => $orderDetail->order_id,
            'shopname'       => Redshop::getConfig()->get('SHOP_NAME'),
            'order_total'    => $orderDetail->order_total,
            'order_tax'      => $orderDetail->order_tax,
            'order_shipping' => $orderDetail->order_shipping,
            'city'           => $billingAddress->city,
            'currency'       => \Redshop::getConfig()->get('CURRENCY_CODE')
        );

        if (isset($billingAddress->country_code)) {
            $orderTransaction['country'] = RedshopHelperOrder::getCountryName($billingAddress->country_code);

            if (isset($billingAddress->state_code)) {
                $orderTransaction['state'] = RedshopHelperOrder::getStateName(
                    $billingAddress->state_code,
                    $billingAddress->country_code
                );
            }
        }

        // Collect data for google tag manager. Init variable
        $tagmanagerData = array(
            // Collect data to add transaction = order
            'addtrans' => $orderTransaction,
            // Start array to collect data to addItems
            'addItem'  => array()
        );

        $items = array();

        foreach ($orderItems as $orderItem) {
            $categoryName = $orderDetailModel->getCategoryNameByProductId($orderItem->product_id);

            $key = $orderDetail->order_id . '_' . $orderItem->order_item_sku . $orderItem->order_item_name . '_' . $categoryName
                . '_' . $orderItem->product_item_price;

            if (array_key_exists($key, $items)) {
                $items[$key]['product_quantity'] += $orderItem->product_quantity;

                continue;
            }

            $items[$key] = array(
                'order_id'         => $orderDetail->order_id,
                'product_number'   => $orderItem->order_item_sku,
                'product_name'     => $orderItem->order_item_name,
                'product_category' => $categoryName,
                'product_price'    => $orderItem->product_item_price,
                'product_quantity' => $orderItem->product_quantity,
                'currency'         => \Redshop::getConfig()->get('CURRENCY_CODE')
            );
        }

        $tagmanagerData['addItem'] = array_values($items);

        $googleTagmanagerHelper->placeTransGTM($tagmanagerData);
    }
}
