<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');

class plgRedshop_paymentrs_payment_localcreditcard extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_localcreditcard($element, $data)
	{
		if ($element != 'rs_payment_localcreditcard')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		return;
	}

	public function onCapture_Paymentrs_payment_localcreditcard($element, $data)
	{
		return;
	}
}
