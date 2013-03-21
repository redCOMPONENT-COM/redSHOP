<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
//$mainframe =& JFactory::getApplication();
//$mainframe->registerEvent( 'onPrePayment', 'plgRedshoprs_payment_bbs' );
require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
class plgRedshop_paymentrs_payment_beanstream extends JPlugin
{
	var $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgRedshop_paymentrs_payment_beanstream(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_beanstream');
		$this->_params = new JRegistry($this->_plugin->params);

	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment_rs_payment_beanstream($element, $data)
	{
		if ($element != 'rs_payment_beanstream')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		$db = JFactory::getDBO();
		$user = JFActory::getUser();
		$session =& JFactory::getSession();
		$ccdata = $session->get('ccdata');
		$cart = $session->get('cart');
		$config = new Redconfiguration();
		// for total amount 
		$cal_no = 2;

		if (defined('PRICE_DECIMAL'))
		{
			$cal_no = PRICE_DECIMAL;
		}

		$order_total = round($data['order_total'], $cal_no);
		$order_payment_expire_year = substr($ccdata['order_payment_expire_year'], -2);
		$order_payment_name = substr($ccdata['order_payment_name'], 0, 50);
		$CountryCode = $config->getCountryCode2($data['billinginfo']->country_code);
		// get params from plugin

		$merchant_id = $this->_params->get("merchant_id");
		$api_username = $this->_params->get("api_username");
		$api_password = $this->_params->get("api_password");
		$view_table_format = $this->_params->get("view_table_format");

		//Authnet vars to send

		$formdata = array(
			'requestType'     => 'BACKEND',
			'merchant_id'     => $merchant_id,
			'username'        => $api_username,
			'password'        => $api_password,
			'trnCardOwner'    => $order_payment_name,
			'trnCardNumber'   => $ccdata['order_payment_number'],
			'trnExpMonth'     => $ccdata['order_payment_expire_month'],
			'trnExpYear'      => $order_payment_expire_year,
			'trnOrderNumber'  => $data['order_number'],
			'trnAmount'       => $order_total,
			'ordEmailAddress' => $data['billinginfo']->user_email,
			'ordName'         => $data['billinginfo']->firstname . " " . $data['billinginfo']->lastname,
			'ordPhoneNumber'  => $data['billinginfo']->phone,
			'ordAddress1'     => $data['billinginfo']->address,
			'ordAddress2'     => "",
			'ordCity'         => $data['billinginfo']->city,
			'ordProvince'     => $data['billinginfo']->state_code,
			'ordPostalCode'   => $data['billinginfo']->zipcode,
			'ordCountry'      => $CountryCode,
		);


		//build the post string
		$poststring = '';

		foreach ($formdata AS $key => $val)
		{
			$poststring .= urlencode($key) . "=" . $val . "&";
		}

		// strip off trailing ampersand
		$poststring = substr($poststring, 0, -1);


		// Initialize curl
		$ch = curl_init();
		// Get curl to POST
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// Instruct curl to suppress the output from Beanstream, and to directly
		// return the transfer instead. (Output will be stored in $txResult.)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// This is the location of the Beanstream payment gateway
		curl_setopt($ch, CURLOPT_URL, "https://www.beanstream.com/scripts/process_transaction.asp");
		// These are the transaction parameters that we will POST
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		// Now POST the transaction. $txResult will contain Beanstream's response
		$txResult = curl_exec($ch);


		curl_close($ch);

		$arrResult = $this->explode_assoc("=", "&", $txResult); //built array

		if ($arrResult['trnApproved'] == '1')
		{
			$values->responsestatus = 'Success';
			$message = $arrResult['messageText'];
		}
		else
		{
			// Catch Transaction ID
			$message = $arrResult['messageText'];
			$values->responsestatus = 'Fail';
		}

		$values->transaction_id = $arrResult['trnId'];
		$values->message = $message;

		return $values;


	}


	function explode_assoc($glue1, $glue2, $array)
	{
		$array2 = explode($glue2, $array);

		foreach ($array2 as $val)
		{
			$pos = strpos($val, $glue1);
			$key = substr($val, 0, $pos);
			$array3[$key] = substr($val, $pos + 1, strlen($val));
		}

		return $array3;
	}


}
