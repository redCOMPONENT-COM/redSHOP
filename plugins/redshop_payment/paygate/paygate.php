<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_PaymentPaygate extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * This method will be triggered on before placing order with paygate.
	 *
	 * @param   string  $element  Name of the payment plugin
	 * @param   array   $data     Cart Information
	 *
	 * @return  void
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'paygate')
		{
			return;
		}

		$app = JFactory::getApplication();

		$paymentId       = $this->params->get('paygateId');
		$reference       = $data['order_id'];
		$amount          = round(RedshopHelperCurrency::convert($data['order']->order_total, '', 'ZAR'), 2) * 100;
		$currency        = 'ZAR';
		$returnUrl       = JURI::base() . "index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=paygate&Itemid=" . $app->input->getInt('Itemid') . "&orderid=" . $data['order_id'];
		$transactionDate = date('Y-m-d H:i');
		$email           = $data['billinginfo']->user_email;

		$checksumSource = array(
			'PAYGATE_ID'       => $paymentId,
			'REFERENCE'        => $reference,
			'AMOUNT'           => $amount,
			'CURRENCY'         => $currency,
			'RETURN_URL'       => $returnUrl,
			'TRANSACTION_DATE' => $transactionDate
		);

		if ($email)
		{
			$checksumSource['EMAIL'] = $email;
		}

		$checksumSource['CHECKSUM'] = $this->params->get('encryptionKey');

		$checkSum = md5(implode("|", $checksumSource));

		// Update checksum value to send.
		$checksumSource['CHECKSUM'] = $checkSum;

		echo '<form action="https://www.paygate.co.za/paywebv2/process.trans" method="post" id="paygateform">';

		foreach ($checksumSource as $name => $value)
		{
			echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
		}

		echo '</form>';

		JFactory::getDocument()->addScriptDeclaration('
			jQuery(document).ready(function($) {
				jQuery("#paygateform").submit();
			});
		');
	}

	/**
	 * Method will check the return params from gateway
	 * 	and update the payment status based on that.
	 *
	 * @param   string  $element  Plugin Name
	 * @param   array   $request  Request data
	 *
	 * @return  object  Payment succss or fail info and message.
	 */
	public function onNotifyPaymentPaygate($element, $request)
	{
		if ($element != 'paygate')
		{
			return;
		}

		$app           = JFactory::getApplication();
		$input         = $app->input;

		$status        = $input->getInt('TRANSACTION_STATUS');
		$tid           = $input->getInt('TRANSACTION_ID');
		$resultCode    = $input->getInt('RESULT_CODE');
		$resultDesc    = $input->getString('RESULT_DESC');

		$checksumSource = array(
			'PAYGATE_ID'         => $this->params->get('paygateId'),
			'REFERENCE'          => $input->getInt('REFERENCE'),
			'TRANSACTION_STATUS' => $status,
			'RESULT_CODE'        => $resultCode,
			'AUTH_CODE'          => $input->getString('AUTH_CODE'),
			'AMOUNT'             => $input->getFloat('AMOUNT'),
			'RESULT_DESC'        => $resultDesc,
			'TRANSACTION_ID'     => $tid
		);

		if ($riskIndicator = $input->getString('RISK_INDICATOR'))
		{
			$checksumSource['RISK_INDICATOR'] = $riskIndicator;
		}

		// Local secret key
		$checksumSource['CHECKSUM'] = $this->params->get('encryptionKey');

		$testChecksum = md5(implode("|", $checksumSource));

		$values = new stdClass;

		// Invalid trasaction
		if ($testChecksum != $input->getString('CHECKSUM'))
		{
			$values->order_status_code         = $this->params->get('invalid_status', '');
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('PLG_REDSHOP_PAYMENT_PAYGATE_PAYMENT_INVALID_LOG');
			$values->msg = '';

			$app->enqueueMessage($values->log, 'Error');
		}
		// Transaction is valid and success
		else if ($status == 1 && $resultCode == 990017)
		{
			$values->order_status_code = $this->params->get('verify_status', '');
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::sprintf('PLG_REDSHOP_PAYMENT_PAYGATE_PAYMENT_SUCCESS_LOG', $tid);
			$values->msg = JText::_('PLG_REDSHOP_PAYMENT_PAYGATE_PAYMENT_SUCCESS');
			$values->type = 'Success';
		}
		// Transaction is valid but payment fail
		else
		{
			$values->order_status_code         = $this->params->get('invalid_status', '');
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::sprintf('PLG_REDSHOP_PAYMENT_PAYGATE_PAYMENT_FAIL_LOG', $resultDesc);
			$values->msg = '';

			$app->enqueueMessage($values->log, 'Warning');
		}

		$values->transaction_id = $tid;
		$values->order_id       = $input->getInt('orderid');

		return $values;
	}
}
