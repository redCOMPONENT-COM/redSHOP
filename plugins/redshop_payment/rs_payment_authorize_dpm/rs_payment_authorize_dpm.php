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
require_once ( JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'configuration.php');
require_once ( JPATH_SITE . DS .'plugins'.DS.'redshop_payment'.DS.'rs_payment_authorize_dpm'.DS.'rs_payment_authorize_dpm'.DS.'authorize_lib'.DS.'AuthorizeNet.php');
class plgRedshop_paymentrs_payment_authorize_dpm extends JPlugin
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
   	function plgRedshop_paymentrs_payment_authorize_dpm(&$subject)
    {
            // load plugin parameters
            parent::__construct( $subject );
            $this->_table_prefix = '#__redshop_';
            $this->_plugin = JPluginHelper::getPlugin( 'redshop_payment', 'rs_payment_authorize_dpm' );
            $this->_params = new JRegistry( $this->_plugin->params );

    }

    
    function onPrePayment($element, $data)
    {
		if($element!='rs_payment_authorize_dpm'){
	    		return;
	    	}
	    	if (empty($plugin))
		{
		 	$plugin = $element;
		}
		$request=JRequest::get('request');
		if($request['stap']==2){
			
			$this->authorizeData($element , $data);
		}
	
		$mainframe =& JFactory::getApplication();
		$paymentpath=JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$plugin.DS.$plugin.DS.'extra_info.php';
		include($paymentpath);
   }
    
    	function authorizeData($element , $data)
    	{
    	
	    	if($element!='rs_payment_authorize_dpm'){
	    		return;
	    	}
	    	if (empty($plugin))
	        {
	         	$plugin = $element;
	        }
	        
        	$mainframe =& JFactory::getApplication();
		$Itemid = JRequest::getVar('Itemid');
	
		$trans_id = $this->_params->get("transaction_id");
		$is_test = $this->_params->get("is_test");
		
		$redirect_url = JURI::base()."index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_authorize_dpm&Itemid=$Itemid&orderid=".$data['order_id']; // Where the user will end up.
		$api_login_id = $this->_params->get("access_id");
		$md5_setting = ""; // Your MD5 Setting
		$response = new AuthorizeNetSIM($api_login_id, $md5_setting);
		
		if ($response->isAuthorizeNet())
		{
			if ($response->approved)
			{
			// Do your processing here.
				$redirect_url .= '&response_code=1&transaction_id='.$response->transaction_id;
			}
			else
			{
				$redirect_url .= '&response_code='.$response->response_code.'&response_reason_text='.$response->response_reason_text;
			}
			
			
		}
		else
		{
			$redirect_url .= '&response_code='.$response->response_code.'&response_reason_text='.$response->response_reason_text;
		}

		
		echo AuthorizeNetDPM::getRelayResponseSnippet($redirect_url);
			
			
    	}
    
	
    
    function onNotifyPaymentrs_payment_authorize_dpm($element, $request){

	    	if($element!='rs_payment_authorize_dpm'){
	    		return;
	    	}
	    	
	    	if (empty($plugin))
			{
		 	$plugin = $element;
			}


    	$db = JFactory::getDBO();
		$request=JRequest::get('request');
		$Itemid = $request["Itemid"];
		$user = JFActory::getUser();
       	$user_id = $user->id;
		// Result Response 
		
	 	$tid = $request['transaction_id'];
		$response_code = htmlentities($request['response_code']);
		$response_reason = htmlentities($request['response_reason_text']);
		$order_id = $request["orderid"];
		
		JPlugin::loadLanguage( 'com_redshop' );
		$authorize_dpm_parameters=$this->getparameters('rs_payment_authorize_dpm');
		$paymentinfo = $authorize_dpm_parameters[0];
		
		
		
		$paymentparams = new JRegistry( $paymentinfo->params );
	 	$verify_status = $paymentparams->get('verify_status','');
	 	$invalid_status = $paymentparams->get('invalid_status','');
		$cancel_status = $paymentparams->get('cancel_status','');
		
		
		if (isset($tid) && $response_code==1) 
		{
		 	$values->order_status_code=$verify_status;
		 	$values->order_payment_status_code='Paid';
		 	$values->log=JTEXT::_('COM_REDSHOP_ORDER_PLACED');
 		 	$values->msg=JTEXT::_('COM_REDSHOP_ORDER_PLACED');

		}else{
			$values->order_status_code=$invalid_status;
			$values->order_payment_status_code='Unpaid';
		 	$values->log= $response_reason;
 		 	$values->msg= $response_reason;
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
    

    
	
    		
}

