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
class plgRedshop_paymentrs_payment_moneybooker extends JPlugin
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
   	function plgRedshop_paymentrs_payment_moneybooker(&$subject)
    {
            // load plugin parameters
            parent::__construct( $subject );
            $this->_table_prefix = '#__redshop_';
            $this->_plugin = JPluginHelper::getPlugin( 'redshop_payment', 'rs_payment_moneybooker' );
            $this->_params = new JParameter( $this->_plugin->params );


    }

   /**
    * Plugin method with the same name as the event will be called automatically.
    */
 	function onPrePayment($element, $data)
    {

		if($element!='rs_payment_moneybooker'){
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

    function onNotifyPaymentrs_payment_moneybooker($element, $request){

    	if($element!='rs_payment_moneybooker'){
    		return;
    	}

		$db = jFactory::getDBO();
		$request=JRequest::get('request');
		JPlugin::loadLanguage( 'com_redshop' );
		$moneybooker_parameters=$this->getparameters('rs_payment_moneybooker');
		$paymentinfo = $moneybooker_parameters[0];
		$paymentparams = new JParameter( $paymentinfo->params );

	 	$verify_status = $paymentparams->get('verify_status','');
		$invalid_status = $paymentparams->get('invalid_status','');

		if($request['status']=="2" )
	    {
		 	$values->order_status_code	=	$verify_status;
		 	$values->order_payment_status_code='Paid';
		 	$values->log=JTEXT::_('ORDER_PLACED');
			$values->msg=JTEXT::_('ORDER_PLACED');
 		}
		else
		{
		 	$values->order_status_code	=	$invalid_status;
		 	$values->order_payment_status_code='Unpaid';
		 	$values->log=JTEXT::_('ORDER_NOT_PLACED');
			$values->msg=JTEXT::_('ORDER_NOT_PLACED');
 		}

		$values->transaction_id=$request['mb_transaction_id'];
		$values->order_id=$request['transaction_id'];
		$values->order_id_temp=$request['orderid'];//orderid
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

	function onCapture_Paymentrs_payment_moneybooker($element, $data){
		return;
    }

}