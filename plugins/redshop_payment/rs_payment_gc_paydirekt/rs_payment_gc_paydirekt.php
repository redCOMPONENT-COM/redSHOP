<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_gc_paydirekt extends JPlugin
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
	 * [__construct description]
	 *
	 * @param  [type]  &$subject  [description]
	 * @param  array   $config    [description]
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plgRedshop_paymentrs_payment_gc_paydirekt');
		parent::__construct($subject, $config);

		/**
		 * Configuration of the merchants identifier, project and password
		 * This information can be found in the GiroCockpit's project settings
		 */
		$this->merchantID      = $this->params->get("merchant_id");
		$this->projectID       = $this->params->get("project_id");
		$this->projectPassword = $this->params->get("secret_password");

		// Include GiroCheckout SDK
		include_once JPATH_LIBRARIES . '/girocheckout/girocheckout.php';
	}

	/**
	 * [onPrePayment description]
	 *
	 * @param   [type]  $element  [description]
	 * @param   [type]  $data     [description]
	 *
	 * @return  [type]            [description]
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_gc_paydirekt')
		{
			return false;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app    = JFactory::getApplication();
		$jinput = $app->input;
		$itemId = $jinput->getInt('Itemid');

		$amount = number_format($data['carttotal'], 2, '.', '') * 100;

		$shippingInfo = $data['shippinginfo'];

		$request = new GiroCheckout_SDK_Request('paydirektTransaction');
		$request->setSecret($this->projectPassword)
			->addParam('merchantId', $this->merchantID)
			->addParam('projectId', $this->projectID)
			->addParam('merchantTxId', $data['order_id'])
			->addParam('amount', $amount)
			->addParam('currency', RedshopHelperCurrency::getISOCode(Redshop::getConfig()->get('CURRENCY_CODE')))
			->addParam('purpose', 'Beispieltransaktion')
			->addParam('shippingAddresseFirstName', $shippingInfo->firstname)
			->addParam('shippingAddresseLastName', $shippingInfo->lastname)
			->addParam('shippingEmail', $shippingInfo->email)
			->addParam('shippingZipCode', $shippingInfo->zipcode)
			->addParam('shippingCity', $shippingInfo->city)
			->addParam('shippingCountry', $shippingInfo->country_2_code)
			->addParam('orderId', $data['order_id'])
			->addParam('urlRedirect', JURI::base() . "index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_gc_paydirekt&orderid=" . $data['order_id'] . "&Itemid=" . $itemId)
			->addParam('urlNotify', JURI::base() . "index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_gc_paydirekt&orderid=" . $data['order_id'] . "&Itemid=" . $itemId)
			->submit();

		// If transaction succeeded update your local system an redirect the customer
		if ($request->requestHasSucceeded())
		{
			$request->getResponseParam('reference');
			$request->getResponseParam('redirect');
			$request->redirectCustomerToPaymentProvider();
		}
		else
		{
			$request->getResponseParam('rc');
			$request->getResponseParam('msg');
			$request->getResponseMessage($request->getResponseParam('rc'), 'DE');
			$app->enqueueMessage($request->getResponseRaw());
		}
	}

	/**
	 * [onNotifyPaymentrs_payment_gc_paydirekt description]
	 *
	 * @param   [type]  $element  [description]
	 * @param   [type]  $request  [description]
	 *
	 * @return  [type]            [description]
	 */
	public function onNotifyPaymentrs_payment_gc_paydirekt($element, $request)
	{
		if ($element != 'rs_payment_gc_paydirekt')
		{
			return false;
		}

		$verifyStatus   = $this->params->get('verify_status', '');
		$invalidStatus  = $this->params->get('invalid_status', '');
		$values          = new stdClass;

		$jinput = JFactory::getApplication()->input;
		$inputValues = $jinput->getArray(
			array(
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
			)
		);


		$notify = new GiroCheckout_SDK_Notify('paydirektTransaction');
		$notify->setSecret($this->projectPassword);
		$notify->parseNotification($inputValues);

		// Check response and update transaction
		if ($notify->paymentSuccessful())
		{
			$values->order_status_code = $verifyStatus;
			$values->order_payment_status_code = 'Paid';
		}
		else
		{
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
		}

		$values->log = $values->msg = $notify->getResponseMessage($notify->getResponseParam('gcResultPayment'), 'DE');
		$values->transaction_id = $notify->getResponseParam('gcReference');
		$values->order_id       = $inputValues['orderid'];

		return $values;
	}
}
