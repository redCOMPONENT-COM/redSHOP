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

require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'shipping.php');
class plgRedshop_shippinglabelrs_shippinglabel_consignor extends JPlugin
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
	function plgRedshop_shippinglabelrs_shippinglabel_consignor(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_shippinglabel', 'redshop_shippinglabel_consignor');
		$this->_params = new JRegistry($this->_plugin->params);


	}

	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	function onChangeStatusToShipped($order_id, $order_status, $paymentstatus)
	{
		if ($order_status == "S" && $paymentstatus == "Paid")
		{
			$generate_consignor_label = $this->generateConsignorParcel($order_id);
		}
	}

	// Generate Consignor Label
	function generateConsignorParcel($order_id)
	{
		$order_functions = new order_functions();
		$shippinghelper = new shipping();
		$config = new Redconfiguration();
		$db = JFactory::getDBO();
		$order_details = $order_functions->getOrderDetails($order_id);
		$consignor_parameters = $this->getparameters('rs_shippinglabel_consignor');
		$labelinfo = $consignor_parameters[0];
		$labelparams = new JRegistry($labelinfo->params);


		$ftp_host = $labelparams->get('ftp_host', '');
		$ftp_username = $labelparams->get('ftp_username', '');
		$ftp_password = $labelparams->get('ftp_password', '');
		$path_for_sharing_folder = $labelparams->get('path_for_sharing_folder', '');
		$ref_code = 1;

		if ($order_details->ship_method_id != "")
		{
			$details = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $order_details->ship_method_id)));

			if ("plgredshop_shippingdefault_shipping_GLS" == $details[0])
			{
				$ref_code = 0;
				$Gls_zipcode = $order_details->shop_id;
				$Gls_zipcode = explode("|", $Gls_zipcode);
				$Gls_zipcode = $Gls_zipcode[0];

				$Gls_phone = explode("###", $order_details->shop_id);
				$Gls_phone = $Gls_phone[1];

			}
			else
			{
				$Gls_zipcode = "";
			}

			if ($details[4] != "")
			{
				$consignor_carrier_code = $this->getCondignorCarrierCode($details[4]);
			}
			//$consignor_carrier_code ="";

			if ($consignor_carrier_code == "")
			{


				$shippingmethod_id = $labelparams->get('shippingmethod', '');
				$shipping_number = $labelparams->get('consignornumber', '');

				for ($s = 0; $s < count($shippingmethod_id); $s++)
				{
					if ("plgredshop_shipping" . $shippingmethod_id[$s] == $details[0])
					{
						$consignor_carrier_code = $shipping_number[$s];
					}
				}
			}
		}


		if ($order_details->track_no != '')
			return;
		$orderproducts = $order_functions->getOrderItemDetail($order_id);
		$billingInfo = $order_functions->getOrderBillingUserInfo($order_id);

		$shippingInfo = $order_functions->getOrderShippingUserInfo($order_id);

		if ($billingInfo->is_company == 1)
		{
			$name = $billingInfo->company_name;
		}
		else
		{
			$name = $billingInfo->firstname . " " . $billingInfo->lastname;
		}

		if ($Gls_phone == "")
		{
			$Gls_phone = $billingInfo->phone;
		}

		$country_2_code = $config->getCountryCode2($billingInfo->country_code);

		// for product conetent
		$totalWeight = 0;
		$content_products = array();
		for ($c = 0; $c < count($orderproducts); $c++)
		{
			$product_id[] = $orderproducts [$c]->product_id;
			$qty += $orderproducts [$c]->product_quantity;
			$content_products[] = $orderproducts[$c]->order_item_name;

			$sql = "SELECT weight FROM #__redshop_product WHERE product_id ='" . $orderproducts [$c]->product_id . "'";
			$db->setQuery($sql);
			$weight = $db->loadResult();
			$totalWeight += ($weight * $orderproducts [$c]->product_quantity);

		}

		$content_products = array_unique($content_products);
		$content_products = implode(",", $content_products);

		// total quantity
		$total_qty = $qty;

		// produts
		$product_id = implode("-", $product_id);


		$myFile = $order_details->order_number . ".txt";

		// for total amount
		$cal_no = 2;

		if (defined('PRICE_DECIMAL'))
		{
			$cal_no = PRICE_DECIMAL;
		}
		$order_total_label = round($order_total, $cal_no);
		$fh = fopen(REDSHOP_FRONT_DOCUMENT_RELPATH . "consignor_label/" . $myFile, 'w') or die("can't open file");
		$label_file = REDSHOP_FRONT_DOCUMENT_RELPATH . "consignor_label/" . $myFile;

		$stringData = '"' . $order_details->order_id . '","' . $order_details->order_number . '","' . $name . '","","' . $billingInfo->address . '","Pakkeshop: ' . trim($Gls_zipcode) . '","' . $billingInfo->zipcode . '","' . $billingInfo->city . '","' . $country_2_code . '","' . $billingInfo->firstname . '","' . $billingInfo->user_email . '","0","","' . $ref_code . '","' . $consignor_carrier_code . '","","' . $Gls_phone . '";';
		fwrite($fh, $stringData);
		fclose($fh);

		//Connect to the FTP server
		$ftpstream = @ftp_connect($ftp_host);
		$str = $path_for_sharing_folder;
		$last = $str[strlen($str) - 1];

		if ($last == "/")
		{
			$slash = "";
		}
		else
		{
			$slash = "/";
		}
		//Login to the FTP server
		$login = @ftp_login($ftpstream, $ftp_username, $ftp_password);

		if ($login)
		{


			$upload = ftp_put($ftpstream, $path_for_sharing_folder . $slash . $myFile, $label_file, FTP_ASCII);

		}

		//Close FTP connection
		ftp_close($ftpstream);

		return "test";
	}

	function getparameters($payment)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	function getCondignorCarrierCode($shipping_rate_id)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT consignor_carrier_code FROM #__redshop_shipping_rate  WHERE `shipping_rate_id`='" . $shipping_rate_id . "'";
		$db->setQuery($sql);
		$consignor_carrier_code = $db->loadResult();

		return $consignor_carrier_code;
	}


}