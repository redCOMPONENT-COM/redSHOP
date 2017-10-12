<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Payoo payment class
 *
 * @package  Redshop.Plugin
 *
 * @since    1.0.0
 */
class PlgRedshop_Paymentrs_Payment_Payoo extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   An optional associative array of configuration settings.
	 *                            Recognized key values include 'name', 'group', 'params', 'language'
	 *                            (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_redshop_payment_rs_payment_payoo');
		parent::__construct($subject, $config);
	}

	/**
	 * This method will be triggered on before placing order to authorize or charge credit card
	 *
	 * @param   string  $element  Name of the payment plugin
	 * @param   array   $data     Cart Information
	 *
	 * @return  mixed
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_payoo')
		{
			return;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * Notify payment
	 *
	 * @param   string  $element  Name of plugin
	 * @param   array   $request  HTTP request data
	 *
	 * @return  object  Contains the information of order success of falier in object
	 */
	public function onNotifyPaymentrs_Payment_Payoo($element, $request)
	{
		if ($element != 'rs_payment_payoo')
		{
			return;
		}

		$app   = JFactory::getApplication();
		$input = $app->input;

		$orderId  = $input->getInt('order_no', 0);
		$session  = $input->getString('session', '');
		$status   = $input->getInt('status', 0);
		$checksum = $input->getString('checksum', '');

		$checksumKey = hash('sha512', $this->params->get('checksumkey') . $session . '.' . $orderId . '.' . $status);

		$values           = new stdClass;
		$values->order_id = (int) $orderId;

		if ($status == '1' && $checksumKey == $checksum)
		{
			$values->order_status_code         = $this->params->get('verify_status', '');
			$values->order_payment_status_code = 'Paid';
			$values->log                       = JText::_('PLG_RS_PAYMENT_PAYOO_ORDER_PLACED');
			$values->msg                       = JText::_('PLG_RS_PAYMENT_PAYOO_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code         = $this->params->get('invalid_status', '');
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('PLG_RS_PAYMENT_PAYOO_NOT_PLACED');
			$values->msg                       = JText::_('PLG_RS_PAYMENT_PAYOO_NOT_PLACED');
		}

		return $values;
	}
}
