<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_googlecheckout extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_googlecheckout')
		{
			return;
		}

		if (empty($this->plugin))
		{
			$this->plugin = $element;
		}

		$app = JFactory::getApplication();

		include_once JPATH_SITE . '/plugins/redshop_payment/rs_payment_googlecheckout/rs_payment_googlecheckout/extra_info.php';
	}
}
