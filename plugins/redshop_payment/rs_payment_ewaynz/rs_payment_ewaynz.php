<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_ewaynz extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_ewaynz')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_ewaynz($element, $request)
	{
		if ($element != 'rs_payment_ewaynz')
		{
			return;
		}

	}

	public function onCapture_Paymentrs_payment_ewaynz($element, $data)
	{
		return;
	}

	public function fetch_data($string, $start_tag, $end_tag)
	{
		$position = stripos($string, $start_tag);

		$str = substr($string, $position);

		$str_second = substr($str, strlen($start_tag));

		$second_positon = stripos($str_second, $end_tag);

		$str_third = substr($str_second, 0, $second_positon);

		$fetch_data = trim($str_third);

		return $fetch_data;
	}
}
