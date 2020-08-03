<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * RedSHOPGoogle_TagmanagerHelper
 *
 * @since  __DEPLOY_VERSION__
 */
class RedSHOPGoogle_TagmanagerHelper
{
    /**
     * Code settings for Google tag manager
     *
     * @param   array  $tagmanagerData  Tag manager data in associative array which needs to be send on GA.
     *
     * @return  void
     */
    public function placeTransGTM($tagmanagerData = array())
    {
        $dataLayer = '';

        if (isset($tagmanagerData['addtrans']))
        {
            // Add Transaction/Order to google tag manager
            $dataLayer = $this->addTransGTM($tagmanagerData['addtrans']);

            $dataLayer .= "'transactionProducts': [";

            // Add order items detail
            if (isset($tagmanagerData['addItem']))
            {
                foreach ($tagmanagerData['addItem'] as $transactionItem)
                {
                    // Add Order Items to google tag manager
                    $dataLayer .= $this->addItemGTM($transactionItem);
                }
            }

            $dataLayer .= "]";

        }

        $pageCode = "
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
                $dataLayer
            });
        ";

        JFactory::getDocument()->addScriptDeclaration($pageCode);
    }

    /**
     * The tagmanager.js JavaScript snippet is a new way to measure how users interact with your website.
     * It is similar to the previous tracking code, ga.js,
     * but offers more flexibility for developers to customize their implementations.
     *
     * @return  string               PageView tracking code
     */
    public function pageTrackerViewGTM()
    {
        // The first line of the tracking script should always initialize the page tracker object.
        $pageCode = "
			gtag('config', '" . $this->trackerKey . "');
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
    public function addTransGTM($data)
    {
        $packageCode = "
				'transactionId': '" . $data['order_id'] . "',             // Transaction ID. Required.
				'transactionAffiliation': '" . $data['shopname'] . "',    // Affiliation or store name.
				'transactionTotal': " . $data['order_total'] . ",     // Grand Total.
				'transactionShipping': " . $data['order_shipping'] . ", // Shipping.
				'transactionTax': " . $data['order_tax'] . ",            // Tax.
		";

        return $packageCode;
    }

    /**
     * Add items to the shopping cart
     *
     * @param   array  $itemData  Order Item information Associative Array
     *
     * @return  string            Transaction Item information.
     */
    public function addItemGTM($itemData)
    {
        $itemData['product_name'] = str_replace("\n", " ", $itemData['product_name']);
        $itemData['product_name'] = str_replace("\r", " ", $itemData['product_name']);

        $packageCode = "{
				'name': '" . $itemData['product_name'] . "',            // Product name. Required.
				'sku': '" . $itemData['product_number'] . "',           // SKU/code.
				'category': '" . $itemData['product_category'] . "',    // Category or variation.
				'price': " . $itemData['product_price'] . ",          // Unit price.
				'quantity': '" . $itemData['product_quantity'] . "'     // Quantity.
				},
		";

        return $packageCode;
    }
}
