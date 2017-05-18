<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Google Analytics
 *
 * @since  2.0
 *
 * @deprecated  2.0.6  Use plugin system - google analytics for redshop
 */
class RedshopHelperGoogleanalytics
{
	/**
	 * The analytics.js JavaScript snippet is a new way to measure how users interact with your website.
	 * It is similar to the previous tracking code, ga.js,
	 * but offers more flexibility for developers to customize their implementations.
	 *
	 * @return  string               PageView tracking code
	 *
	 * @deprecated  2.0.6
	 */
	public function pageTrackerView()
	{
		return '';
	}

	/**
	 * Once the plugin has been loaded, it creates a transparent shopping cart object.
	 * You can add transaction and item data to the shopping cart, and once fully configured,
	 * you send all the data at once.
	 *
	 * @param   array $data Order Information in associative array
	 *
	 * @return  string        Add GA Ecommerce Transaction code
	 *
	 * @deprecated  2.0.6
	 */
	public function addTrans($data)
	{
		return '';
	}

	/**
	 * Add items to the shopping cart
	 *
	 * @param   array $itemData Order Item information Associative Array
	 *
	 * @return  string            Transaction Item information.
	 *
	 * @deprecated  2.0.6
	 */
	public function addItem($itemData)
	{
		return '';
	}

	/**
	 * Finally, once we have configured all ecommerce data in the shopping cart, we will send it to GA.
	 *
	 * @return  string  Sending Information of ecommerce tracking.
	 *
	 * @deprecated  2.0.6
	 */
	public function trackTrans()
	{
		return '';
	}

	/**
	 * Code settings for Google Analytics
	 *
	 * @param   array $analyticsData Analytics data in associative array which needs to be send on GA.
	 *
	 * @return  void
	 *
	 * @see     https://developers.google.com/analytics/devguides/collection/analyticsjs/
	 *
	 * @deprecated  2.0.6
	 */
	public function placeTrans($analyticsData = array())
	{
		return;
	}
}