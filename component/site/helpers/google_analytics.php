<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * We are in redSHOP Using the Traditional Snippet
 * This document describes how to set up Analytics tracking for your website using the traditional ga.js tracking code snippet.
 * If you are setting up tracking for the first time, we recommend that you use the default tracking code snippet, described in Tracking Sites.
 */
class googleanalytics
{
	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		$this->_table_prefix = '#__' . TABLE_PREFIX;
	}

	/**
	 * When you first begin implementing tracking in Google Analytics website, you need to install the tracking code on your website pages.
	 * The generic tracking code snippet consists of two parts: a script tag that references the ga.js tracking code,
	 * and another script that executes the tracking code.
	 */
	public function pageTrackerView()
	{
		// The first line of the tracking script should always initialize the page tracker object.
		$pagecode = "
			var _gaq = _gaq || [];
		 	 _gaq.push(['_setAccount', '" . GOOGLE_ANA_TRACKER_KEY . "']);
		  	_gaq.push(['_trackPageview']);
		";

		return $pagecode;
	}

	/**
	 * Creates a transaction object with the given values.
	 * As with _addItem(), this method handles only transaction tracking and provides no additional ecommerce functionality.
	 * Therefore, if the transaction is a duplicate of an existing transaction for that session, the old transaction values are over-written with the new transaction values.
	 * Arguments for this method are matched by position, so be sure to supply all parameters, even if some of them have an empty value.
	 */
	public function addTrans($data)
	{
		$packegecode = "
			_gaq.push(['_addTrans',
			    '" . $data['order_id'] . "',        // order ID - required
			    '" . $data['shopname'] . "',  		// affiliation or store name
			    '" . $data['order_total'] . "',     // total - required
			    '" . $data['order_tax'] . "',		// tax
			    '" . $data['order_shipping'] . "',  // shipping
		    	'" . $data['city'] . "',       						// city
			    '" . $data['state'] . "',     		// state or province
			    '" . $data['country'] . "'    		// country
			  ]);
	    ";

		return $packegecode;

	}

	/**
	 * Use this method to track items purchased by visitors to your ecommerce site.
	 * This method tracks individual items by their SKU.
	 * This means that the sku parameter is required.
	 * This method then associates the item to the parent transaction object via the orderId argument.
	 */
	public function addItem($itemdata)
	{
		$itemdata['product_name'] = str_replace("\n", " ", $itemdata['product_name']);
		$itemdata['product_name'] = str_replace("\r", " ", $itemdata['product_name']);

		$packegecode = "
			// add item might be called for every item in the shopping cart
		   // where your ecommerce engine loops through each item in the cart and
		   // prints out _addItem for each
		  _gaq.push(['_addItem',
		    '" . $itemdata['order_id'] . "',           		  // order ID - required
		    '" . $itemdata['product_number'] . "',           // SKU/code - required
		    '" . $itemdata['product_name'] . "',        	// product name
		    '" . $itemdata['product_category'] . "',   		// category or variation
		    '" . $itemdata['product_price'] . "',          // unit price - required
		    '" . $itemdata['product_quantity'] . "'        // quantity - required
		  ]);
	    ";

		return $packegecode;
	}

	/**
	 * Sends both the transaction and item data to the Google Analytics server.
	 * This method should be called after _trackPageview(), and used in conjunction with the _addItem() and addTrans() methods.
	 * It should be called after items and transaction elements have been set up.
	 */
	public function trackTrans()
	{
		// Submits transaction to the Analytics servers
		$packegecode = "
			_gaq.push(['_trackTrans']); 	//submits transaction to the Analytics servers
		";

		return $packegecode;
	}

	/**
	 * Code setting for google analytics
	 *
	 * As per ecoomerce tracking API
	 * @source: http://code.google.com/apis/analytics/docs/tracking/gaTrackingEcommerce.html
	 */
	public function placeTrans($analyticsData = array())
	{
		$pageCode = '';
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

		$pageCode .= "

		(function() {
		    	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  	})();

		";
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($pageCode);
	}
}
