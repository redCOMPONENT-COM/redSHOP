<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');

class plgRedshop_paymentrs_payment_cyberplus extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_cyberplus')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_cyberplus($element, $request)
	{
		if ($element != 'rs_payment_cyberplus')
		{
			return;
		}

		$db                 = JFactory::getDbo();
		$request            = JRequest::get('request');
		$order_id           = $request['orderid'];
		$vads_trans_id      = $request['vads_trans_id'];

		// Get params from plugin parameters
		$verify_status      = $this->params->get("verify_status");
		$invalid_status     = $this->params->get("invalid_status");
		$site_id            = $this->params->get("site_id");
		$certificate_number = $this->params->get("certificate_number");
		$key                = $certificate_number;
		$contenu_signature  = "";
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

		// The certificate
		$signature_calculee = sha1($contenu_signature);

		if (isset($request['signature']) && $signature_calculee == $request['signature'])
		{
			/*
			 * Authenticated request
			 * Beware however to properly control the transmitted parameters
			 * In particular vads_site_id and vads_ctx_mode
			 */
			if ($request['vads_result'] == "00")
			{
				// Payment ok
				$values->order_status_code         = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->transaction_id            = $vads_trans_id;
				$values->order_id                  = $order_id;
			}
			else
			{
				// Payment refused or referral
				$values->order_status_code         = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->order_id                  = $order_id;
			}
		}
		else
		{
			// Invalid signature – do not take this entry form into account
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->order_id                  = $order_id;
		}

		return $values;
	}
}
