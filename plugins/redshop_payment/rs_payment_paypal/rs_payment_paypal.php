<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_paypal extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_redshop_payment_rs_payment_paypal');
		parent::__construct($subject, $config);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_paypal')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_paypal($element, $request)
	{
		if ($element != 'rs_payment_paypal')
		{
			return;
		}

		$db             = JFactory::getDbo();
		$request        = JRequest::get('request');
		$Itemid         = $request["Itemid"];

		$is_test        = $this->params->get('sandbox', '');
		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');
		$cancel_status  = $this->params->get('cancel_status', '');

		$user           = JFactory::getUser();

		$order_id       = $request["orderid"];

		$status         = $request['payment_status'];
		$tid            = $request['txn_id'];
		$uri            = JURI::getInstance();
		$url            = JURI::base();
		$uid            = $user->id;

		$values         = new stdClass;

		if ($status == 'Completed')
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}
}
