<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_gc_creditcard extends JPlugin
{
	/**
	 * Merchant ID
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	private $merchantID;

	/**
	 * Project ID
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	private $projectID;

	/**
	 * Project Password
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	private $projectPassword;

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
		JPlugin::loadLanguage('plg_redshop_payment_rs_payment_gc_creditcard');
		parent::__construct($subject, $config);

		/**
		 * Configuration of the merchants identifier, project and password
		 * This information can be found in the GiroCockpit's project settings
		 */
		$this->merchantID      = $this->params->get("merchant_id");
		$this->projectID       = $this->params->get("project_id");
		$this->projectPassword = $this->params->get("secret_password");

		// include GiroCheckout SDK
		include_once JPATH_LIBRARIES . '/girocheckout/girocheckout.php';
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_gc_creditcard')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$jinput = JFactory::getApplication()->input;

		$itemId  = $jinput->getInt('Itemid');

		$amount = $data['carttotal'];

		$request = new GiroCheckout_SDK_Request('creditCardTransaction');
		$request->setSecret($this->projectPassword);
		$request->addParam('merchantId', $this->merchantID)
				->addParam('projectId', $this->projectID)
				->addParam('merchantTxId', $data['order_id'])
				->addParam('amount', number_format($amount, 2, '.', '') * 100)
				->addParam('currency','EUR')
				->addParam('purpose','Beispieltransaktion')
				->addParam('urlRedirect', JURI::base() . "index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_gc_creditcard&orderid=" . $data['order_id'] . "&Itemid=" . $itemId)
				->addParam('urlNotify', JURI::base() . "index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_gc_creditcard&orderid=" . $data['order_id'] . "&Itemid=" . $itemId)
				->submit();

	 	/* If transaction succeeded update your local system an redirect the customer */
		if($request->requestHasSucceeded())
		{
			$request->getResponseParam('reference');
			$request->getResponseParam('redirect');
			$request->redirectCustomerToPaymentProvider();
		}
	}

	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	public function onNotifyPaymentrs_payment_gc_creditcard($element, $request)
	{
		if ($element != 'rs_payment_gc_creditcard')
		{
			return false;
		}

		$verify_status   = $this->params->get('verify_status', '');
		$invalid_status  = $this->params->get('invalid_status', '');
		$values          = new stdClass;

		$jinput = JFactory::getApplication()->input;
		$inputValues = $jinput->getArray(array(
			'option' => 'STRING',
			'view' => 'STRING',
			'tmpl' => 'STRING',
			'controller' => 'STRING',
			'task' => 'STRING',
			'payment_plugin' => 'STRING',
			'orderid' => 'int',
			'gcReference' => 'CMD',
			'gcMerchantTxId' => 'RAW',
			'gcBackendTxId' => 'RAW',
			'gcAmount' => 'int',
			'gcCurrency' => 'ALNUM',
			'gcResultPayment' => 'int',
			'gcHash' => 'ALNUM'
		));

		$notify = new GiroCheckout_SDK_Notify('giropayTransaction');
		$notify->setSecret($this->projectPassword);
		$notify->parseNotification($inputValues);

		//check response and update transaction
		if ($notify->paymentSuccessful())
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
		}

		$values->log = $values->msg = $notify->getResponseMessage($notify->getResponseParam('gcResultPayment'), 'DE');
		$values->transaction_id = $notify->getResponseParam('gcReference');
		$values->order_id       = $inputValues['orderid'];

		return $values;
	}
}
