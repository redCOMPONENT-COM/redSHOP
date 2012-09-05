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

class plgRedshop_paymentrs_payment_eway3dsecure extends JPlugin
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
  	function plgRedshop_paymentrs_payment_eway3dsecure(&$subject)
    {
            // load plugin parameters
            parent::__construct( $subject );
            $this->_table_prefix = '#__redshop_';
            $this->_plugin = JPluginHelper::getPlugin( 'redshop_payment', 'rs_payment_eway3dsecure' );
            $this->_params = new JParameter( $this->_plugin->params );
    }

   /**
    * Plugin method with the same name as the event will be called automatically.
    */
 	function onPrePayment($element, $data)
    {
		if($element!='rs_payment_eway3dsecure'){
    		return;
    	}
    	if (empty($plugin))
        {
         	$plugin = $element;
        }

 		$mainframe =& JFactory::getApplication();
 		$paymentpath=JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$plugin.DS.'extra_info.php';
		include($paymentpath);
    }


  function onNotifyPaymentrs_payment_eway3dsecure($element, $request){

    	if($element!='rs_payment_eway3dsecure'){
    		return;
    	}
    	$db = JFactory::getDBO();
		$request=JRequest::get('request');
		$result = $request["ewayTrxnStatus"];
		$trxnReference = $request["ewayTrxnReference"];
		//$transaction_number = $_REQUEST["ewayTrxnNumber"];
		$eWAYresponseText = $request["eWAYresponseText"];
		$eWAYReturnAmount = $request["eWAYReturnAmount"];
		$eWAYAuthCode = $request["eWAYAuthCode"];
		$order_id=$request['orderid'];
		$Itemid=$request['Itemid'];
		$quickpay_parameters=$this->getparameters('rs_payment_eway3dsecure');
		$paymentinfo = $quickpay_parameters[0];
		$paymentparams = new JParameter( $paymentinfo->params );

		$verify_status = $paymentparams->get('verify_status','');
		$invalid_status = $paymentparams->get('invalid_status','');

		if ($transaction_number = ""){
		  $transaction_number = "NOT DEFINED";
		}

		if($result=='True'){
            // UPDATE THE ORDER STATUS to 'VALID'
		 	$values->order_status_code=$verify_status;
		 	$values->order_payment_status_code='PAID';
		 	$values->log=JTEXT::_('ORDER_PLACED');
 		 	$values->msg=JTEXT::_('ORDER_PLACED');
		}else{
			$values->order_status_code=$invalid_status;
			$values->order_payment_status_code='UNPAID';
		 	$values->log=JTEXT::_('ORDER_NOT_PLACED.');
 		 	$values->msg=JTEXT::_('ORDER_NOT_PLACED');
		}
		$values->transaction_id=$trxnReference;
		$values->order_id=$order_id;
		return $values;
    }

	function getparameters($payment){
		$db = JFactory::getDBO();
		$sql="SELECT * FROM #__plugins WHERE `element`='".$payment."'";
		$db->setQuery($sql);
		$params=$db->loadObjectList();
		return $params;
	}


	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid){

		$db = JFactory::getDBO();
		$res = false;
		 $query = "SELECT COUNT(*) `qty` FROM `#__redshop_order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id ) . "' and order_payment_trans_id = '" .$db->getEscaped( $tid) . "'";
		$db->SetQuery($query);
	 	$order_payment = $db->loadResult();
		if ($order_payment == 0){
			$res = true;
		}
		return $res;
	}
  function onCapture_Paymentrs_payment_eway3dsecure($element, $data){
 		return;
    }

}