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
/*$mainframe =& JFactory::getApplication();
$mainframe->registerEvent( 'onPrePayment', 'plgRedshoppayment_authorize' );*/
require_once ( JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
class plgRedshop_paymentrs_payment_imglobal extends JPlugin
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
   	function plgRedshop_paymentrs_payment_imglobal(&$subject)
    {
            // load plugin parameters
            parent::__construct( $subject );
            $this->_table_prefix = '#__redshop_';
            $this->_plugin = JPluginHelper::getPlugin( 'redshop_payment', 'rs_payment_imglobal' );
            $this->_params = new JParameter( $this->_plugin->params );

    }

    /**
    * Plugin method with the same name as the event will be called automatically.
    */
 	function onPrePayment_rs_payment_imglobal($element, $data)
    {
    	if($element!='rs_payment_imglobal'){
    		return;
    	}
    	if (empty($plugin))
        {
         	$plugin = $element;
        }

 		$mainframe 		=& JFactory::getApplication();
 		$session 		=& JFactory::getSession();
		$ccdata 		= $session->get('ccdata');
	    $url 			= "https://secure.imglobalpayments.com/api/transact.php";
	 	$urlParts 		= parse_url( $url );

		if( !isset( $urlParts['scheme'] )) $urlParts['scheme'] = 'http';

	  	$formdata = array (
						'type' => 'sale',
				   		'username' => $this->_params->get("username"),
				   		'password' => $this->_params->get("password"),
				   		'orderid' => $data['order_number'],
				   		'amount' => $data['order_total'],
						'ccnumber' => $ccdata['order_payment_number'],
						'cvv' => $ccdata['credit_card_code'],
						'ccexp' => ($ccdata['order_payment_expire_month']) . ($ccdata['order_payment_expire_year'])
				     );
		$poststring = '';
		foreach($formdata AS $key => $val){
			$poststring .= urlencode($key) . "=" . urlencode($val) . "&";
		}

		$poststring = substr($poststring, 0, -1);
		$CR 		= curl_init();
		curl_setopt($CR, CURLOPT_URL, $url);
		curl_setopt($CR, CURLOPT_TIMEOUT, 30 );
		curl_setopt($CR, CURLOPT_FAILONERROR, true);
		if( $poststring ) {
			curl_setopt($CR, CURLOPT_POSTFIELDS, $poststring );
			curl_setopt($CR, CURLOPT_POST, 1);
		}
	    curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
		if( $urlParts['scheme'] == 'https') {
			curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);
		}
		$result = curl_exec( $CR );
		$error = curl_error( $CR );
     	curl_close( $CR );

		parse_str( $result,$output );
		$verify_status = $this->_params->get("verify_status");
		$invalid_status =$this->_params->get("invalid_status");
		if(!empty($output['response'])){
			if($output['response'] == '1')
			{
			 	$message = JTEXT::_('ORDER_PLACED');
				$values->responsestatus		= 'Success';
  			}else
			{
 			 	$message = JTEXT::_('ORDER_NOT_PLACED');
				$values->responsestatus		= 'Fail';
 			}
			$values->transaction_id=$output['transactionid'];
			$values->order_id=$data['order_id'];
 		}else{
 			$message = JTEXT::_('ORDER_NOT_PLACED');
			$values->responsestatus		= 'Fail';
			$values->transaction_id=0;
 		}
		$values->message 			= $message;

		return $values;
    }

  function onCapture_Paymentrs_payment_imglobal($element, $data){
  		return;
    }

}