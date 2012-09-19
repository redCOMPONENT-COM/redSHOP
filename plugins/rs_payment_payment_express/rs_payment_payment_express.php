<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
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
require_once ( JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
require_once ( JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . 'rs_payment_payment_express'. DS . 'rs_payment_payment_express' . DS .'PxPay_Curl.inc.php');
class plgRedshop_paymentrs_payment_payment_express extends JPlugin
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
   	function plgRedshop_paymentrs_payment_payment_express(&$subject)
    {
            // load plugin parameters
            parent::__construct( $subject );
            $this->_table_prefix = '#__redshop_';
            $this->_plugin = JPluginHelper::getPlugin( 'redshop_payment', 'rs_payment_payment_express' );
            $this->_params = new JRegistry( $this->_plugin->params );

    }

	function onPrePayment($element, $data)
    {
   		if($element!='rs_payment_payment_express'){
    		return;
    	}
    	if (empty($plugin))
        {
         	$plugin = $element;
        }

 		$mainframe =& JFactory::getApplication();
		$paymentpath=JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$plugin.DS.$plugin.DS.'extra_info.php';
		include($paymentpath);
    }

    /**
    * Plugin method with the same name as the event will be called automatically.
    */
 	function onPrePayment_rs_payment_payment_express($element, $data)
    {
    	if($element!='rs_payment_payment_express'){
    		return;
    	}
    	if (empty($plugin))
        {
         	$plugin = $element;
        }
        if($this->_params->get("px_post_txnmethod")=='PxPost')
        {

		// pxpost
	 		$cmdDoTxnTransaction = "";
	        // Get user billing information
		    $session =& JFactory::getSession();
			$ccdata = $session->get('ccdata');

	 	    #Calculate AmountInput

			$amount = $data['order_total'] ;
	        $amount = sprintf("%9.2f",$amount); //convert .8 to .80

		     $currency = CURRENCY_CODE;
		     $merchRef = substr($data['billinginfo']->firstname, 0, 50)." ". substr($data['billinginfo']->lastname, 0, 50);
			 $cmdDoTxnTransaction .= "<Txn>";
			 $cmdDoTxnTransaction .= "<PostUsername>".$this->_params->get("px_post_username")."</PostUsername>"; #Insert your DPS Username here
			 $cmdDoTxnTransaction .= "<PostPassword>".$this->_params->get("px_post_password")."</PostPassword>"; #Insert your DPS Password here
			 $cmdDoTxnTransaction .= "<Amount>".$amount."</Amount>";
			 $cmdDoTxnTransaction .= "<InputCurrency>$currency</InputCurrency>";
			 $cmdDoTxnTransaction .= "<CardHolderName>".$ccdata['order_payment_name']."</CardHolderName>";
			 $cmdDoTxnTransaction .= "<CardNumber>".$ccdata['order_payment_number']."</CardNumber>";
			 $cmdDoTxnTransaction .= "<DateExpiry>".($ccdata['order_payment_expire_month']) . substr($ccdata['order_payment_expire_year'], 2, 2)."</DateExpiry>";
			 $cmdDoTxnTransaction .= "<Cvc2>".$ccdata['credit_card_code']."</Cvc2>";
			 $cmdDoTxnTransaction .= "<TxnType>".$this->_params->get("px_post_txntype")."</TxnType>";
			 $cmdDoTxnTransaction .= "<TxnData1>".JText::_('COM_REDSHOP_ORDER_ID')." : ".$order_number."</TxnData1>";
			 $cmdDoTxnTransaction .= "<MerchantReference>$merchRef</MerchantReference>";
			 $cmdDoTxnTransaction .= "</Txn>";

			 $URL = "sec2.paymentexpress.com/pxpost.aspx";
				//echo "\n\n\n\nSENT:\n$cmdDoTxnTransaction\n\n\n\n\n$";

			 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL,"https://".$URL);
			 curl_setopt($ch, CURLOPT_POST, 1);
			 curl_setopt($ch, CURLOPT_POSTFIELDS,$cmdDoTxnTransaction);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			 //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //Needs to be included if no *.crt is available to verify SSL certificates
			 curl_setopt($ch,CURLOPT_SSLVERSION,3);
			 $result = curl_exec ($ch);


			 curl_close ($ch);

			 $params =$this->parse_xml($result);

			if( !$params ) {

				return false;
			}

			$response = $params['TXN']['SUCCESS'];
			$authorized =  $params['TXN'][$response]['AUTHORIZED'];
			// Approved - Success!
			if ($authorized == '1') {

				$values->responsestatus		= 'Success';
			}
			else{
				$values->responsestatus		= 'Fail';
			}
			$values->transaction_id	= $params['TXN'][$response]['DPSTXNREF'];
			$values->message= $params['TXN'][$response]['CARDHOLDERHELPTEXT'];
			return $values;
        }else{
			$mainframe =& JFactory::getApplication();
	 		$paymentpath=JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$plugin.DS.$plugin.DS.'extra_info.php';
			include($paymentpath);
        }


    }
	function parse_xml($data)
	{
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $data, $vals, $index);
		xml_parser_free($xml_parser);

		$params = array();
		$level = array();
		foreach ($vals as $xml_elem) {

			if ($xml_elem['type'] == 'open') {
				if (array_key_exists('attributes',$xml_elem)) {
					list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
				}
				else {
					$level[$xml_elem['level']] = $xml_elem['tag'];
				}
			}
			if ($xml_elem['type'] == 'complete') {
				$start_level = 1;
				$php_stmt = '$params';

				while($start_level < $xml_elem['level']) {
				$php_stmt .= '[$level['.$start_level.']]';
				$start_level++;
				}
				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				@eval($php_stmt);
			}
		}
		return $params;
	}

   function onCapture_Paymentrs_payment_payment_express($element, $data){
   		if($element!='rs_payment_payment_express'){
    		return;
    	}

 		$objOrder = new order_functions();
		$db = JFactory::getDBO();
		JPlugin::loadLanguage( 'com_redshop' );
		$order_id	=	$data['order_id'];
		$Itemid = $_REQUEST['Itemid'];
		if($this->_params->get("px_post_txntype")=='Auth'){

			$orderDetail = $objOrder->getOrderPaymentDetail($data['order_id']);
	 	  	$cmdDoTxnTransaction = "";
	        $db = JFactory::getDBO();
			// Get user billing information

		    #Calculate AmountInput
			 $amount = $order_total;

			 $order_payment_amount=$orderDetail[0]->order_payment_amount;
			 $order_payment_trans_id=$orderDetail[0]->order_payment_trans_id;

		     $currency = CURRENCY_CODE;
			 $cmdDoTxnTransaction .= "<Txn>";
			 $cmdDoTxnTransaction .= "<PostUsername>".$this->_params->get("px_post_username")."</PostUsername>"; #Insert your DPS Username here
			 $cmdDoTxnTransaction .= "<PostPassword>".$this->_params->get("px_post_password")."</PostPassword>"; #Insert your DPS Password here
			 $cmdDoTxnTransaction .= "<Amount>$order_payment_amount</Amount>";
			 $cmdDoTxnTransaction .= "<InputCurrency>$currency</InputCurrency>";
			 $cmdDoTxnTransaction .= "<DpsTxnRef>$order_payment_trans_id</DpsTxnRef>";
			 $cmdDoTxnTransaction .= "<TxnType>Complete</TxnType>";
			 $cmdDoTxnTransaction .= "</Txn>";



			 $URL = "sec2.paymentexpress.com/pxpost.aspx";
				//echo "\n\n\n\nSENT:\n$cmdDoTxnTransaction\n\n\n\n\n$";

			 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL,"https://".$URL);
			 curl_setopt($ch, CURLOPT_POST, 1);
			 curl_setopt($ch, CURLOPT_POSTFIELDS,$cmdDoTxnTransaction);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			 //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //Needs to be included if no *.crt is available to verify SSL certificates
			 curl_setopt($ch,CURLOPT_SSLVERSION,3);
			 $result = curl_exec ($ch);

 			 curl_close ($ch);

			 $params = $this->parse_xml($result);

			if( !$params ) {

				return false;
			}

			$PX_msg = $params['TXN']['RESPONSETEXT'];
			$authorized =  $params['TXN']['SUCCESS'];
			$message=$params['TXN'][$response]['CARDHOLDERHELPTEXT'];
			// Approved - Success!

			if($authorized=='1' || $authorized==1)
	        {
				$values->responsestatus 	= 'Success';
	        }
	        else
	        {
	            $values->responsestatus 	= 'Fail';
	        }
	        $values->message 	= $message;
	        return $values;
 		}
     }
}