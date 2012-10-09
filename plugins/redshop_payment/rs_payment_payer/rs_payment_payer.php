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

class plgRedshop_paymentrs_payment_payer extends JPlugin
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
   	function plgRedshop_paymentrs_payment_payer(&$subject)
    	{
            // load plugin parameters
            parent::__construct( $subject );
            $this->_table_prefix = '#__redshop_';
            $this->_plugin = JPluginHelper::getPlugin( 'redshop_payment', 'rs_payment_payer' );
            $this->_params = new JRegistry( $this->_plugin->params );


   	}

   /**
    * Plugin method with the same name as the event will be called automatically.
    */
 	function onPrePayment($element, $data)
    	{
	    	if($element!='rs_payment_payer'){
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


	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
        function onNotifyPaymentrs_payment_payer($element, $request)
	{

		ob_clean();
		if($element!='rs_payment_payer'){
    		break;
    		}

		
		$order_id = $request['orderid'];
		$verify_status = $this->_params->get('verify_status','');
		$invalid_status = $this->_params->get('invalid_status','');
		$values = new stdClass();

		include(JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$element.DS.$element.DS.'payread_post_api.php'); //Loads Payers API.
		$postAPI = new payread_post_api(); //Creates an object from Payers API.

		$postAPI->setAgent($this->_params->get("agent_id"));
		$postAPI->setKeys($this->_params->get("payer_key1"), $this->_params->get("payer_key2"));
	    	

		if($postAPI->is_valid_ip()){//Checks if the IP address comes from Payer else return false!
		    if($postAPI->is_valid_callback()){//Check if the keys match (the hash) else return false!
			
			$values->order_status_code=$verify_status;
		  	$values->order_payment_status_code='Paid';
		 	$values->log=JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg=JText::_('COM_REDSHOP_ORDER_PLACED');
			
		    }
		} else {
			$values->order_status_code=$invalid_status;
		  	$values->order_payment_status_code='Unpaid';
		 	$values->log=JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
 		 	$values->msg=JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		
		}
		$values->order_id=$order_id;
		return $values;
		
    	}
    	
}
