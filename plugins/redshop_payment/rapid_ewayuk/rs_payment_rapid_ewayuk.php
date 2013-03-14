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
//$mainframe =& JFactory::getApplication();
//$mainframe->registerEvent( 'onPrePayment', 'plgRedshoprs_payment_bbs' );
class plgRedshop_paymentrs_payment_rapid_ewayuk extends JPlugin
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
   	function plgRedshop_paymentrs_payment_rapid_ewayuk(&$subject)
    {
            // load plugin parameters
            parent::__construct( $subject );
            $this->_table_prefix = '#__redshop_';
            $this->_plugin = JPluginHelper::getPlugin( 'redshop_payment', 'rs_payment_rapid_ewayuk' );
            $this->_params = new JRegistry( $this->_plugin->params );


    }
    

   /**
    * Plugin method with the same name as the event will be called automatically.
    */
 	function onPrePayment($element, $data)
    {

		
		if($element!='rs_payment_rapid_ewayuk'){
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

    function onNotifyPaymentrs_payment_rapid_ewayuk($element, $request){

	    	if($element!='rs_payment_rapid_ewayuk'){
	    		return;
	    	}
    	

		$db = jFactory::getDBO();
		$AccessCode = $request["AccessCode"];
		

		JPlugin::loadLanguage( 'com_redshop' );
		$netcash_parameters	=	$this->getparameters('rs_payment_rapid_ewayuk');
		$paymentinfo = $netcash_parameters[0];
		$paymentparams = new JRegistry( $paymentinfo->params );

	 	$verify_status = $paymentparams->get('verify_status','');
		$invalid_status = $paymentparams->get('invalid_status','');
		$auth_type = $paymentparams->get('auth_type','');

		$eWAYcustomer_id = $paymentparams->get("customer_id");
   		$eWAYusername = $paymentparams->get("username");
		$eWAYpassword = $this->_params->get("password");

	 	$order_id=$request['orderid'];
	 	

		// for transaction status

		
		$request = array(
		    'Authentication' => array(
			'Username' => $eWAYusername,
			'Password' => $eWAYpassword,
			'CustomerID' => $eWAYcustomer_id,
		    ),
		    'AccessCode' => $AccessCode
		);

		try {
		    $client = new SoapClient("https://uk.ewaypayments.com/hotpotato/soap.asmx?WSDL", array(
			'trace'      => false,
			'exceptions' => true,
		     ) );
		    $result = $client->GetAccessCodeResult( array('request' => $request) );
		} catch (Exception $e) {
		    $lblError = $e->getMessage();
		}
		// End
		$response = $result->GetAccessCodeResultResult;
		

	 	$values = new stdClass();
		if($response->ResponseCode == 00)
		{

		   $tid = $response->TransactionID;

			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid)) {
				$transaction_id=$tid;
			 	$values->order_status_code	=	$verify_status;
			 	$values->order_payment_status_code='Paid';
			 	$values->log=JText::_('COM_REDSHOP_ORDER_PLACED');
	 		 	$values->msg=JText::_('COM_REDSHOP_ORDER_PLACED');
	 		}
			}else{
				$values->order_status_code=$invalid_status;
				$values->order_payment_status_code='Unpaid';
				if($lblError!="")
				{
					$values->log=$lblError;
	 		 		$values->msg=$lblError;
				} else {
			 		$values->log=JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
	 		 		$values->msg=JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				}
			}

		$values->transaction_id=$tid;
		$values->order_id=$order_id;
		return $values;
    }

	function getparameters($payment){
		$db = JFactory::getDBO();
		$sql="SELECT * FROM #__extensions WHERE `element`='".$payment."'";
		$db->setQuery($sql);
		$params=$db->loadObjectList();
		return $params;
	}


	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid){

		$db = JFactory::getDBO();
		$res = false;
		 $query = "SELECT COUNT(*) FROM ".$this->_table_prefix."order_payment WHERE `order_id` = '" . $db->getEscaped($order_id ) . "' and order_payment_trans_id = '" .$db->getEscaped( $tid) . "'";
		$db->SetQuery($query);
	 	$order_payment = $db->loadResult();
		if ($order_payment == 0){
			$res = true;
		}
		return $res;
	}

	function onCapture_Paymentrs_payment_ewayuk($element, $data){
		return;
    }


   


}
