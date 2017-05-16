<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

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
	 * @param   string $trackerKey Tracker Key
	 *
	 */
	public function __construct($trackerKey = '')
	{
		$this->trackerKey = $trackerKey;
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
		// The first line of the tracking script should always initialize the page tracker object.
		$pageCode = "
			ga('create', '" . $this->trackerKey . "', 'auto');
			ga('send', 'pageview');

		";

		return $pageCode;
	}

	/**
	 * Once the plugin has been loaded, it creates a transparent shopping cart object.
	 * You can add transaction and item data to the shopping cart, and once fully configured,
	 * you send all the data at once.
	 *
	 * @param   array $data Order Information in associative array
	 *
	 * @return  string        Add GA Ecommerce Transaction code
	 */
	public function addTrans($data)
	{
		$packageCode = "
			ga('require', 'ecommerce', 'ecommerce.js');

			ga('ecommerce:addTransaction', {
				'id': '" . $data['order_id'] . "',             // Transaction ID. Required.
				'affiliation': '" . $data['shopname'] . "',    // Affiliation or store name.
				'revenue': '" . $data['order_total'] . "',     // Grand Total.
				'shipping': '" . $data['order_shipping'] . "', // Shipping.
				'tax': '" . $data['order_tax'] . "'            // Tax.
			});

		";

		return $packageCode;
	}

	/**
	 * Add items to the shopping cart
	 *
	 * @param   array $itemData Order Item information Associative Array
	 *
	 * @return  string            Transaction Item information.
	 */
	public function addItem($itemData)
	{
		$itemData['product_name'] = str_replace("\n", " ", $itemData['product_name']);
		$itemData['product_name'] = str_replace("\r", " ", $itemData['product_name']);

		$packageCode = "
			ga('ecommerce:addItem', {
				'id': '" . $itemData['order_id'] . "',                  // Transaction ID. Required.
				'name': '" . $itemData['product_name'] . "',            // Product name. Required.
				'sku': '" . $itemData['product_number'] . "',           // SKU/code.
				'category': '" . $itemData['product_category'] . "',    // Category or variation.
				'price': '" . $itemData['product_price'] . "',          // Unit price.
				'quantity': '" . $itemData['product_quantity'] . "'     // Quantity.
			});
		";

		return $packageCode;
	}

	/**
	 * Finally, once we have configured all ecommerce data in the shopping cart, we will send it to GA.
	 *
	 * @return  string  Sending Information of ecommerce tracking.
	 */
	public function trackTrans()
	{
		// Submits transaction to the Analytics servers
		$packageCode = "
			ga('ecommerce:send');
		";

		return $packageCode;
	}

	/**
	 * Code settings for Google Analytics
	 *
	 * @param   array $analyticsData Analytics data in associative array which needs to be send on GA.
	 *
	 * @return  void
	 *
	 * @see     https://developers.google.com/analytics/devguides/collection/analyticsjs/
	 */
	public function placeTrans($analyticsData = array())
	{
		$pageCode = "

		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		";

		$pageCode .= $this->pageTrackerView();

		if (isset($analyticsData['addtrans']))
		{
			// Add Transaction/Order to google Analytic
			$pageCode .= $this->addTrans($analyticsData['addtrans']);

			// Add order items detail
			if (isset($analyticsData['addItem']))
			{
				foreach ($analyticsData['addItem'] as $transactionItem)
				{
					// Add Order Items to google Analytic
					$pageCode .= $this->addItem($transactionItem);
				}
			}

			// Track added order to google analytics
			$pageCode .= $this->trackTrans();
		}

		JFactory::getDocument()->addScriptDeclaration($pageCode);
	}
}
