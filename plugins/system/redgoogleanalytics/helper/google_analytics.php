<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * redSHOPGoogle_AnalyticsHelper
 *
 * @since  2.0
 */
class RedSHOPGoogle_AnalyticsHelper
{
    /**
     * @var  string
     */
    public $trackerKey;

    /**
     * RedSHOPGoogle_AnalyticsHelper constructor.
     *
     * @param   string  $trackerKey  Tracker Key
     *
     */
    public function __construct($trackerKey = '')
    {
        $this->trackerKey = $trackerKey;
    }

    /**
     * Code settings for Google Analytics
     *
     * @param   array  $analyticsData  Analytics data in associative array which needs to be send on GA.
     *
     * @return  void
     *
     * @see     https://developers.google.com/analytics/devguides/collection/analyticsjs/
     */
    /*
        public function placeTrans($analyticsData = array())
        {
            Factory::getDocument()->addScript("https://www.googletagmanager.com/gtag/js?id=$this->trackerKey", [], ['async' => 'async']);

            $pageCode = $this->pageTrackerView();

            if (isset($analyticsData['addtrans'])) {
                // Add Transaction/Order to google Analytic
                $pageCode .= $this->addTrans($analyticsData['addtrans']);

                // Add order items detail
                if (isset($analyticsData['addItem'])) {
                    foreach ($analyticsData['addItem'] as $transactionItem) {
                        // Add Order Items to google Analytic
                        $pageCode .= $this->addItem($transactionItem);
                    }
                }
            }

            Factory::getDocument()->addScriptDeclaration($pageCode);
        }
    */
    /**
     * Code settings for Google Analytics
     *
     * @param   array  $analyticsData  Analytics data in associative array which needs to be send on GA.
     *
     * @return  void
     *
     * @see     https://developers.google.com/analytics/devguides/collection/analyticsjs/
     */
    public function placeTrans($analyticsData = array())
    {
        Factory::getDocument()->addScript("https://www.googletagmanager.com/gtag/js?id=$this->trackerKey", [], ['async' => 'async']);

        // $pageCode = $this->pageTrackerView();

        $pageTitle = JFactory::getApplication()->JComponentTitle;

        // The first line of the tracking script should always initialize the page tracker object.
        $pageTrans = "
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
    
            gtag('config', '" . $this->trackerKey . "', {
                'page_title': '" . $pageTitle . "'
            });
        ";
        /*
                if (isset($analyticsData['addtrans'])) {
                    $pagePurchase = "gtag('event', 'purchase', {
                    'transaction_id': '" . $analyticsData['addtrans']['order_id'] . "',                      // Transaction ID. Required.
                    'value': '" . number_format($analyticsData['addtrans']['order_total'], 2, '.', '') . "', // Grand Total.
                    'tax': '" . $analyticsData['addtrans']['order_tax'] . "',                                // Tax.
                    'shipping': '" . $analyticsData['addtrans']['order_shipping'] . "',                      // Shipping.
                    'currency': '" . $analyticsData['addtrans']['currency'] . "'                             // local currency code.
                    'items': [
                        if (isset(" . $analyticsData['addItem'] . ")) {
                            foreach (" . $analyticsData['addItem'] . "as" . $transactionItem . ") {
                                " . $transactionItem['product_name'] . " = str_replace('\n', ' ', " . $transactionItem['product_name'] . ");
                                " . $transactionItem['product_name'] . " = str_replace('\r', ' ', " . $transactionItem['product_name'] . ");
            
                                    {
                                        'item_id': '" . $transactionItem['product_number'] . "',                         // SKU/code.
                                        'item_name': '" . $transactionItem['product_name'] . "',                         // Product name. Required.
                                        'affiliation': '" . $transactionItem['shopname'] . "',                           // Affiliation or store name.
                                        'item_category': '" . $transactionItem['product_category'] . "',                 // Category or variation.
                                        'price': '" . number_format($transactionItem['product_price'], 2, '.', '') . "', // Unit price.
                                        'quantity': '" . $transactionItem['product_quantity'] . "',                      // Quantity.
                                        'currency': '" . $transactionItem['currency'] . "'                               // local currency code.
                                    }
                            }
                        }
                    ]
                })";
                }
        */
        Factory::getDocument()->addScriptDeclaration($pageTrans);
    }


    /**
     * The analytics.js JavaScript snippet is a new way to measure how users interact with your website.
     * It is similar to the previous tracking code, ga.js,
     * but offers more flexibility for developers to customize their implementations.
     *
     * @return  string               PageView tracking code
     */
    public function pageTrackerView()
    {
        $pageTitle = Factory::getApplication()->getMenu()->getActive()->title;

        // The first line of the tracking script should always initialize the page tracker object.
        $pageCode = "
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
    
            gtag('config', '" . $this->trackerKey . "', {
                'page_title': '" . $pageTitle . "'
            });
        ";

        return $pageCode;
    }

    /**
     * Once the plugin has been loaded, it creates a transparent shopping cart object.
     * You can add transaction and item data to the shopping cart, and once fully configured,
     * you send all the data at once.
     *
     * @param   array  $data  Order Information in associative array
     *
     * @return  string        Add GA Ecommerce Transaction code
     */
    /*
        public function addTrans($data)
        {
            $packageCode = "
                gtag('event', 'purchase', {
                    'transaction_id': '" . $data['order_id'] . "',                      // Transaction ID. Required.
                    'value': '" . number_format($data['order_total'], 2, '.', '') . "', // Grand Total.
                    'tax': '" . $data['order_tax'] . "',                                // Tax.
                    'shipping': '" . $data['order_shipping'] . "',                      // Shipping.
                    'currency': '" . $data['currency'] . "'                             // local currency code.
                });

            ";

            return $packageCode;
        }
    */
    /**
     * Add items to the shopping cart
     *
     * @param   array  $itemData  Order Item information Associative Array
     *
     * @return  string            Transaction Item information.
     */
    /*
        public function addItem($itemData)
        {
            $itemData['product_name'] = str_replace("\n", " ", $itemData['product_name']);
            $itemData['product_name'] = str_replace("\r", " ", $itemData['product_name']);

            $packageCode = "
                ga('ecommerce:addItem', {
                    'item_id': '" . $itemData['product_number'] . "',                         // SKU/code.
                    'item_name': '" . $itemData['product_name'] . "',                         // Product name. Required.
                    'affiliation': '" . $itemData['shopname'] . "',                           // Affiliation or store name.
                    'item_category': '" . $itemData['product_category'] . "',                 // Category or variation.
                    'price': '" . number_format($itemData['product_price'], 2, '.', '') . "', // Unit price.
                    'quantity': '" . $itemData['product_quantity'] . "',                      // Quantity.
                    'currency': '" . $itemData['currency'] . "'                               // local currency code.
                });
            ";

            return $packageCode;
        }
    */
}