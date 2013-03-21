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
/*$mainframe =& JFactory::getApplication();
$mainframe->registerEvent( 'onPrePayment', 'plgRedshoppayment_authorize' );*/
require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
class plgRedshop_paymentrs_payment_cyberplus extends JPlugin
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
	function plgRedshop_paymentrs_payment_cyberplus(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_cyberplus');
		$this->_params = new JRegistry($this->_plugin->params);

	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_cyberplus')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $plugin . DS . 'extra_info.php';
		include($paymentpath);
	}

	function onNotifyPaymentrs_payment_cyberplus($element, $request)
	{

		if ($element != 'rs_payment_cyberplus')
		{
			return;
		}

		$db = jFactory::getDBO();
		$request = JRequest::get('request');
		$order_id = $request['orderid'];
		$vads_trans_id = $request['vads_trans_id'];

		// get params from plugin parameters
		$verify_status = $this->_params->get("verify_status");
		$invalid_status = $this->_params->get("invalid_status");
		$site_id = $this->_params->get("site_id");
		$certificate_number = $this->_params->get("certificate_number");
		$key = $certificate_number;
		$contenu_signature = "";
		//	$params = $_POST;
		ksort($request);


		foreach ($request as $nom => $valeur)
		{
			if ($nom != "view" && $nom != "Itemid" && $nom != "controller" && $nom != "option" && $nom != "orderid" && $nom != "payment_plugin" && $nom != "task")
			{
				if (substr($nom, 0, 5) == 'vads_')
				{
					// It is a field used to calculate the signature
					$contenu_signature .= $valeur . "+";
				}
			}
		}

		$contenu_signature .= $key;
		// The certifica
		$signature_calculee = sha1($contenu_signature);


		if (isset($request['signature']) && $signature_calculee == $request['signature'])
		{
			// Authenticated request
			// Beware however to properly control the transmitted parameters
			// In particular vads_site_id and vads_ctx_mode
			if ($request['vads_result'] == "00")
			{
				// Payment ok
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->transaction_id = $vads_trans_id;
				$values->order_id = $order_id;
			}
			else
			{
				// Payment refused or referral
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->order_id = $order_id;
			}
		}
		else
		{
			// Invalid signature – do not take this entry form into account
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->order_id = $order_id;
		}

		return $values;
	}


}