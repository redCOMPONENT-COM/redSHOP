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

class PlgRedshop_Paymentrs_Payment_Paymill extends JPlugin
{
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
		$lang          = JFactory::getLanguage();
		$lang->load('plg_redshop_payment_rs_payment_paymill', JPATH_ADMINISTRATOR);

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

		$document->addScript(JURI::base() . 'plugins/redshop_payment/rs_payment_paymill/rs_payment_paymill/js/BrandDetection.js');
		$document->addScript(JURI::base() . 'plugins/redshop_payment/rs_payment_paymill/rs_payment_paymill/js/paymill.js');
		$document->addStyleSheet(JURI::base() . 'plugins/redshop_payment/rs_payment_paymill/rs_payment_paymill/css/paymill_styles.css');

		$request = JRequest::get('request');

		if (isset($request['ccinfo']) && $request['ccinfo'] == 1)
		{
			$post = JRequest::get('post');
			$Itemid = JRequest::getInt('Itemid');
			$post['Itemid'] = $Itemid;

			$this->getOrderAndCcdata("rs_payment_paymill", $post);
		}
		else
		{
			$this->getCredicardForm("rs_payment_paymill", $data);
		}
	}

	function getCredicardForm($element, $data)
	{
		$jInput = JFactory::getApplication()->input;
		$Itemid = $jInput->getInt('Itemid', 0);
		$paymill_public_key = $this->params->get('paymill_public_key', '0');
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('var PAYMILL_PUBLIC_KEY = "' . $paymill_public_key . '"; var VALIDATE_CVC = true;');
		$document->addScript('https://bridge.paymill.com/');
		JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_NUMBER');
		JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_EXPIRATION_DATE');
		JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_HOLDERNAME');
		JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_CVC');
		?>
		<div class="containerPaymil">
			<div class="well">
				<div class="payment_errors text-error">&nbsp;</div>
				<form id="payment-form" method="POST" action="<?php
				echo JURI::base(); ?>index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&oid=<?php
				echo (int) $data['order_id']; ?>&Itemid=<?php
				echo $Itemid; ?>">
					<div class="clearfix"></div>
					<div id="payment-form-cc">
						<input class="card-amount-int" type="hidden" value="<?php echo $data['order']->order_total; ?>" name="amount"/>
						<input class="card-currency" type="hidden" value="<?php echo Redshop::getConfig()->get('CURRENCY_CODE'); ?>" name="currency"/>
						<div class="row-fluid">
							<div class="span4"><label for="card-number"><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CARD_NUMBER'); ?></label>
								<input class="card-number span12" id ="card-number" type="text" size="19" value=""
									   placeholder="<?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CARD_NUMBER_PLACEHOLDER'); ?>" maxlength="19"/>
							</div>
							<div class="span2">
								<label><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_VALID_UNTIL'); ?></label>
								<input id="card-expiry" class="card-expiry span12" type="text"
									   placeholder="<?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CARD_EXPIRY_PLACEHOLDER'); ?>" maxlength="7">
							</div>
						</div>
						<div class="row-fluid">
							<div class="span4">
								<label><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CARD_HOLDER'); ?></label>
								<input class="card-holdername span12" type="text" size="20" value=""/>
							</div>

							<div class="span2">
								<label>
									<?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CVC'); ?>
									<?php echo JHTML::tooltip(JText::_('PLG_RS_PAYMENT_PAYMILL_CVC_TIP')); ?>
								</label>
								<input class="card-cvc span12" type="text" size="4" maxlength="4" value=""/>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<button id="paymill-submit-button" class="submit-button btn btn-primary" type="submit">
							<?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_BUY_NOW'); ?>
						</button>
					</div>
					<input type="hidden" name="option" value="com_redshop" />
					<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
					<input type="hidden" name="ccinfo" value="1" />
					<input type="hidden" name="payment_method_id" value="<?php echo $jInput->get('payment_method_id', ''); ?>" />
					<input type="hidden" name="order_id" value="<?php echo $data['order_id']; ?>" />
				</form>
			</div>
		</div>
	<?php
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function getOrderAndCcdata($element, $data)
	{
		$app = JFactory::getApplication();

		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		$paymill_private_key = $this->params->get('paymill_private_key', '0');
		$environment = $this->params->get('environment', 'sandbox');
		$order_functions = order_functions::getInstance();
		$orderDetails = $order_functions->getOrderDetails($data['order_id']);
		$order_amount = number_format($orderDetails->order_total, 2, '.', '') * 100;
		$Itemid = $app->input->getInt('Itemid', 0);
		$redirect_url = "index.php?option=com_redshop&view=order_detail&layout=receipt&Itemid=" . $Itemid . "&oid=" . $data['order_id'];

		if ($paymillToken = $app->input->get('paymillToken', ''))
		{
			$request = new Paymill\Request($paymill_private_key);
			$payment = new Paymill\Models\Request\Payment;
			$transaction = new Paymill\Models\Request\Transaction;

			try
			{
				$payment->setToken($paymillToken);
				$response = $request->create($payment);
				$transaction->setPayment($response->getId());
				$transaction->setAmount($order_amount);
				$transaction->setCurrency(Redshop::getConfig()->get('CURRENCY_CODE'));
				$transaction->setDescription('Order: ' . $data['order_id']);
				$response = $request->create($transaction);
				$transactionId = $response->getId();
				$key = md5(implode('/', array($data['order_id'], $paymill_private_key, $transactionId, $environment)));
				$redirect_url = "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paymill&Itemid="
					. $app->input->getInt('Itemid', 0) . "&orderid=" . $data['order_id'] . '&transactionId=' . $transactionId . '&key=' . $key;
			}
			catch (\Paymill\Services\PaymillException $e)
			{
				$app->enqueueMessage($e->getResponseCode() . ' : ' . $e->getErrorMessage(), 'error');
			}
		}

		$app->redirect(JRoute::_($redirect_url, false));
	}

	function onNotifyPaymentrs_payment_paymill($element, $request)
	{
		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		$order_id = $request['orderid'];
		$transactionId = $request['transactionId'];
		$paymill_private_key = $this->params->get('paymill_private_key', '0');
		$verify_status = $this->params->get('verify_status');
		$invalid_status = $this->params->get('invalid_status');
		$environment = $this->params->get('environment', 'sandbox');
		$values = new stdClass;
		$key = md5(implode('/', array($order_id, $paymill_private_key, $transactionId, $environment)));

		if ($key == $request['key'])
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JTEXT::_('PLG_RS_PAYMENT_PAYMILL_ORDER_PLACED');
			$values->msg = JTEXT::_('PLG_RS_PAYMENT_PAYMILL_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('PLG_RS_PAYMENT_PAYMILL_ORDER_NOT_PLACED');
			$values->msg = JText::_('PLG_RS_PAYMENT_PAYMILL_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $transactionId;
		$values->order_id = $order_id;

		return $values;
	}

	function onCapture_Paymentrs_payment_paymill($element, $data)
	{
		return;
	}
}
