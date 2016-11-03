<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Google Analytics
 *
 * @since  2.5
 */
class RedshopHelperGoogleanalytics
{
	/**
	 * The analytics.js JavaScript snippet is a new way to measure how users interact with your website.
	 * It is similar to the previous tracking code, ga.js,
	 * but offers more flexibility for developers to customize their implementations.
	 *
	 * @return  string  PageView tracking code
	 */
	public static function pageTrackerView()
	{
		// The first line of the tracking script should always initialize the page tracker object.
		$pagecode = "
		  	ga('create', '" . Redshop::getConfig()->get('GOOGLE_ANA_TRACKER_KEY') . "', 'auto');
			ga('send', 'pageview');

		";

		return $pagecode;
	}

	/**
	 * Once the plugin has been loaded, it creates a transparent shopping cart object.
	 * You can add transaction and item data to the shopping cart, and once fully configured,
	 * you send all the data at once.
	 *
	 * @param   array  $data  Order Information in associative array
	 *
	 * @return  string  Add GA Ecommerce Transaction code
	 */
	public function addTrans($data)
	{
		$packegecode = "
			ga('require', 'ecommerce', 'ecommerce.js');

			ga('ecommerce:addTransaction', {
				'id': '" . $data['order_id'] . "',             // Transaction ID. Required.
				'affiliation': '" . $data['shopname'] . "',    // Affiliation or store name.
				'revenue': '" . $data['order_total'] . "',     // Grand Total.
				'shipping': '" . $data['order_shipping'] . "', // Shipping.
				'tax': '" . $data['order_tax'] . "'            // Tax.
			});

		";

		return $packegecode;
	}

	/**
	 * Add items to the shopping cart
	 *
	 * @param   array  $itemdata  Order Item information Associative Array
	 *
	 * @return string Transaction Item information.
	 */
	public function addItem($itemdata)
	{
		$itemdata['product_name'] = str_replace("\n", " ", $itemdata['product_name']);
		$itemdata['product_name'] = str_replace("\r", " ", $itemdata['product_name']);

		$packegecode = "

			ga('ecommerce:addItem', {
				'id': '" . $itemdata['order_id'] . "',                  // Transaction ID. Required.
				'name': '" . $itemdata['product_name'] . "',    		// Product name. Required.
				'sku': '" . $itemdata['product_number'] . "',           // SKU/code.
				'category': '" . $itemdata['product_category'] . "',    // Category or variation.
				'price': '" . $itemdata['product_price'] . "',          // Unit price.
				'quantity': '" . $itemdata['product_quantity'] . "'     // Quantity.
			});
		";

		return $packegecode;
	}

	/**
	 * Finally, once we have configured all ecommerce data in the shopping cart, we will send it to GA.
	 *
	 * @return  string  Sending Information of ecommerce tracking.
	 */
	public function trackTrans()
	{
		// Submits transaction to the Analytics servers
		$packegecode = "
			ga('ecommerce:send');
		";

		return $packegecode;
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
			$addtrans = $analyticsData['addtrans'];

			// Add Transaction/Order to google Analytic
			$pageCode .= $this->addTrans($addtrans);

			if (isset($analyticsData['addItem']))
			{
				$addItem = $analyticsData['addItem'];

				$tItem = count($addItem);

				for ($i = 0; $i < $tItem; $i++)
				{
					$item = $addItem[$i];

					// Add Order Items to google Analytic
					$pageCode .= $this->addItem($item);
				}
			}

			// Track added order to google analytics
			$pageCode .= $this->trackTrans();
		}

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($pageCode);
	}
}
