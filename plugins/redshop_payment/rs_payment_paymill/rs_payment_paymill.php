<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * PlgRedshop_PaymentRs_Payment_PayMill class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */

class PlgRedshop_PaymentRs_Payment_PayMill extends JPlugin
{
	/**
	 * [$autoloadLanguage]
	 *
	 * @var  boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config = array())
	{
		spl_autoload_register(array('PlgRedshop_Paymentrs_Payment_Paymill', 'autoload'));

		parent::__construct($subject, $config);
	}

	/**
	 * Function for autoload paymill classes
	 *
	 * @param   string  $className  Class name
	 *
	 * @return  void
	 */
	public static function autoload($className)
	{
		$prefix = 'Paymill\\';
		$len = strlen($prefix);

		if (strncmp($prefix, $className, $len) !== 0)
		{
			return;
		}

		$className = ltrim($className, "\\");
		$fileName = '';

		if ($lastNsPos = strripos($className, "\\"))
		{
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName = str_replace("\\", DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}

		$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		require 'rs_payment_paymill/lib/' . $fileName;
	}

	/**
	 * [onPrePayment description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_paymill')
		{
			return;
		}

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
		JHtml::stylesheet('rs_payment_paymill/paymill_styles.css', false, true);

		$request = JFactory::getApplication()->input;

		if ($request->get('ccinfo') == 1)
		{
			$input 			= JFactory::getApplication()->input;
			$post  			= $input->getArray($_POST);
			$itemId 		= $input->get('Itemid', 0, 'INT');
			$post['Itemid'] = $itemId;

			$this->getOrderAndCcdata("rs_payment_paymill", $post);
		}
		else
		{
			$this->getCredicardForm("rs_payment_paymill", $data);
		}
	}

	/**
	 * [getCredicardForm description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	function getCredicardForm($element, $data)
	{
		$input = JFactory::getApplication()->input;
		$itemId = $input->getInt('Itemid', 0);
		$paymillPublicKey = $this->params->get('paymill_public_key', '0');

		$document = JFactory::getDocument();
		$document->addScriptDeclaration('var PAYMILL_PUBLIC_KEY = "' . $paymillPublicKey . '"; var VALIDATE_CVC = true;');
		$document->addScript('https://bridge.paymill.com/');

		JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_NUMBER');
		JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_EXPIRATION_DATE');
		JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_HOLDERNAME');
		JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_CVC');

		include_once JPluginHelper::getLayoutPath('redshop_payment', 'rs_payment_paymill');
	}

	/**
	 * [getOrderAndCcdata description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function getOrderAndCcdata($element, $data)
	{
		$app = JFactory::getApplication();

		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		$paymillPrivateKey = $this->params->get('paymill_private_key', '0');
		$environment = $this->params->get('environment', 'sandbox');
		$orderFunctions = order_functions::getInstance();
		$orderDetails = $orderFunctions->getOrderDetails($data['order_id']);
		$orderAmount = number_format($orderDetails->order_total, 2, '.', '') * 100;
		$itemId = $app->input->getInt('Itemid', 0);
		$redirect_url = "index.php?option=com_redshop&view=order_detail&layout=receipt&Itemid=" . $itemId . "&oid=" . $data['order_id'];

		if ($paymillToken = $app->input->get('paymillToken', ''))
		{
			$request = new Paymill\Request($paymillPrivateKey);
			$payment = new Paymill\Models\Request\Payment;
			$transaction = new Paymill\Models\Request\Transaction;

			try
			{
				$payment->setToken($paymillToken);
				$response = $request->create($payment);
				$transaction->setPayment($response->getId());
				$transaction->setAmount($orderAmount);
				$transaction->setCurrency(Redshop::getConfig()->get('CURRENCY_CODE'));
				$transaction->setDescription('Order: ' . $data['order_id']);
				$response = $request->create($transaction);
				$transactionId = $response->getId();
				$key = md5(implode('/', array($data['order_id'], $paymillPrivateKey, $transactionId, $environment)));
				$redirectUrl = JRoute::_("index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paymill&Itemid=" . $app->input->getInt('Itemid', 0) . "&orderid=" . $data['order_id'] . '&transactionId=' . $transactionId . '&key=' . $key);
			}
			catch (\Paymill\Services\PaymillException $e)
			{
				$app->enqueueMessage($e->getResponseCode() . ' : ' . $e->getErrorMessage(), 'error');
			}
		}

		$app->redirect(JRoute::_($redirectUrl, false));
	}

	/**
	 * [onNotifyPaymentrs_payment_paymill description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [string]  $request  [request string]
	 *
	 * @return  [object]  $values
	 */
	function onNotifyPaymentrs_payment_paymill($element, $request)
	{
		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		$orderId = $request['orderid'];
		$transactionId = $request['transactionId'];
		$paymillPrivateKey = $this->params->get('paymill_private_key', '0');
		$verifyStatus = $this->params->get('verify_status');
		$invalidStatus = $this->params->get('invalid_status');
		$environment = $this->params->get('environment', 'sandbox');
		$values = new stdClass;
		$key = md5(implode('/', array($orderId, $paymillPrivateKey, $transactionId, $environment)));

		if ($key == $request['key'])
		{
			$values->order_status_code = $verifyStatus;
			$values->order_payment_status_code = 'Paid';
			$values->log = JTEXT::_('PLG_RS_PAYMENT_PAYMILL_ORDER_PLACED');
			$values->msg = JTEXT::_('PLG_RS_PAYMENT_PAYMILL_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('PLG_RS_PAYMENT_PAYMILL_ORDER_NOT_PLACED');
			$values->msg = JText::_('PLG_RS_PAYMENT_PAYMILL_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $transactionId;
		$values->order_id = $order_id;

		return $values;
	}

	/**
	 * [onCapture_Paymentrs_payment_paymill description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]]
	 *
	 * @return  [void]
	 */
	function onCapture_Paymentrs_payment_paymill($element, $data)
	{
		return;
	}
}
