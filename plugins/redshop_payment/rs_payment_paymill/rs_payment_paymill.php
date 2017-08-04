<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Paymill\Models\Request\Payment;
use Paymill\Models\Request\Transaction;
use Paymill\Request;

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Paymill payment class
 *
 * @package  Redshop.Plugin
 *
 * @since    2.0.0
 */
class PlgRedshop_Paymentrs_Payment_Paymill extends JPlugin
{
	/**
	 * @var   boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * Constructor
	 *
	 * @param   object $subject    The object to observe
	 * @param   array  $config     An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_payment_rs_payment_paymill', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * This method will be triggered on before placing order to authorize or charge credit card
	 *
	 * @param   string $element Name of the payment plugin
	 * @param   array  $data    Cart Information
	 *
	 * @return  object            Authorize or Charge success or failed message and transaction id
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		$this->loadFramework();

		$document = JFactory::getDocument();

		if (version_compare(JVERSION, '3.0', '<'))
		{
			JHtml::_('redshopjquery.framework');
			$document->addScript('https://maxcdn.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js');
			$document->addStyleSheet('https://maxcdn.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css');
		}
		else
		{
			JHtml::_('bootstrap.framework');
			JHtml::_('bootstrap.loadCss');
		}

		JHtml::script('rs_payment_paymill/BrandDetection.js', false, true);
		JHtml::script('rs_payment_paymill/paymill.js', false, true);
		JHtml::stylesheet('rs_payment_paymill/paymill_styles.css', array(), true);

		$app = JFactory::getApplication();

		if ($app->input->getInt('ccinfo', 0) == 1)
		{
			$post           = $app->input->post->getArray();
			$itemId         = $app->input->getInt('Itemid', 0);
			$post['Itemid'] = $itemId;

			$this->getOrderAndCcdata('rs_payment_paymill', $post);
		}
		else
		{
			$this->getCredicardForm('rs_payment_paymill', $data);
		}
	}

	/**
	 * Method for get credit card form
	 *
	 * @param   string $element Name of payment plugin
	 * @param   array  $data    Data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function getCredicardForm($element, $data)
	{
		$this->loadFramework();

		echo RedshopLayoutHelper::render(
			'form',
			array(
				'element' => $element,
				'data'    => $data,
				'params'  => $this->params
			),
			__DIR__ . '/layouts'
		);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   string $element Name of payment plugin
	 * @param   array  $data    Data
	 *
	 * @return  void
	 */
	public function getOrderAndCcdata($element, $data)
	{
		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		$this->loadFramework();

		$app = JFactory::getApplication();

		$paymillPrivateKey = $this->params->get('paymill_private_key', '0');
		$environment       = $this->params->get('environment', 'sandbox');
		$orderDetails      = RedshopHelperOrder::getOrderDetail($data['order_id']);
		$orderAmount       = number_format($orderDetails->order_total, 2, '.', '') * 100;
		$itemId            = $app->input->getInt('Itemid', 0);
		$redirectUrl       = "index.php?option=com_redshop&view=order_detail&layout=receipt&Itemid=" . $itemId . "&oid=" . $data['order_id'];

		$paymillToken = $app->input->get('paymillToken', '');

		if (empty($paymillToken))
		{
			$app->redirect(JRoute::_($redirectUrl, false));
		}

		$request     = new Request($paymillPrivateKey);
		$payment     = new Payment;
		$transaction = new Transaction;

		try
		{
			$payment->setToken($paymillToken);

			$response = $request->create($payment);

			$transaction->setPayment($response->getId());
			$transaction->setAmount($orderAmount);
			$transaction->setCurrency(Redshop::getConfig()->get('CURRENCY_CODE'));
			$transaction->setDescription('Order: ' . $data['order_id']);

			$response      = $request->create($transaction);
			$transactionId = $response->getId();
			$key           = md5(implode('/', array($data['order_id'], $paymillPrivateKey, $transactionId, $environment)));
			$redirectUrl   = 'index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment'
				. '&payment_plugin=rs_payment_paymill&Itemid=' . $itemId . "&orderid=" . $data['order_id'] . '&transactionId='
				. $transactionId . '&key=' . $key;
		}
		catch (Exception $e)
		{
			$app->enqueueMessage($e->getCode() . ' : ' . $e->getMessage(), 'error');
		}

		$app->redirect(JRoute::_($redirectUrl, false));
	}

	/**
	 * Method on notify payment
	 *
	 * @param   string $element Name of payment plugin
	 * @param   array  $data    Data
	 *
	 * @return  object
	 *
	 * @since   1.0.0
	 */
	public function onNotifyPaymentrs_Payment_Paymill($element, $data)
	{
		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		$this->loadFramework();

		$orderId           = $data['orderid'];
		$transactionId     = $data['transactionId'];
		$paymillPrivateKey = $this->params->get('paymill_private_key', '0');
		$verifyStatus      = $this->params->get('verify_status');
		$invalidStatus     = $this->params->get('invalid_status');
		$environment       = $this->params->get('environment', 'sandbox');
		$values            = new stdClass;
		$key               = md5(implode('/', array($orderId, $paymillPrivateKey, $transactionId, $environment)));

		if ($key == $data['key'])
		{
			$values->order_status_code         = $verifyStatus;
			$values->order_payment_status_code = 'Paid';
			$values->log                       = JTEXT::_('PLG_RS_PAYMENT_PAYMILL_ORDER_PLACED');
			$values->msg                       = JTEXT::_('PLG_RS_PAYMENT_PAYMILL_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code         = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('PLG_RS_PAYMENT_PAYMILL_ORDER_NOT_PLACED');
			$values->msg                       = JText::_('PLG_RS_PAYMENT_PAYMILL_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $transactionId;
		$values->order_id       = $orderId;

		return $values;
	}

	/**
	 * Method on capture payment
	 *
	 * @param   string $element Name of payment plugin
	 * @param   array  $data    Data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onCapture_Paymentrs_payment_paymill($element, $data)
	{
		return;
	}

	/**
	 * Method for load framework.
	 *
	 * @return  void
	 *
	 * @since  2.0.0
	 */
	public function loadFramework()
	{
		require_once __DIR__ . '/library/vendor/autoload.php';
	}
}
