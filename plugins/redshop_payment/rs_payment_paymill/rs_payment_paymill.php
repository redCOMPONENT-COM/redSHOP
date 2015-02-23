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
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperAdminConfiguration');

class plgRedshop_paymentrs_payment_paymill extends JPlugin
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

		// Or JPATH_ADMINISTRATOR if the template language file is only
		$lang->load('plg_redshop_payment_rs_payment_paymill', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$document = JFactory::getDocument();
		$document->addScript(JURI::base() . 'plugins/redshop_payment/rs_payment_paymill/rs_payment_paymill/js/paymill.js');
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/creditcardform.php';

		include $paymentpath;
	}

	function getCredicardForm($element, $data)
	{
		$jInput = JFactory::getApplication()->input;
		$Itemid = $jInput->getInt('Itemid', 0);
		$paymill_public_key = $this->params->get('paymill_public_key', '0');
		$document = JFactory::getDocument();

		if (version_compare(JVERSION, '3.0', '<'))
		{
			JHtml::_('redshopjquery.framework');
			$document->addScript('https://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js');
			$document->addStyleSheet('https://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap.no-responsive.no-icons.min.css');
		}
		else
		{
			JHtml::_('bootstrap.framework');
			JHtml::_('bootstrap.loadCss');
		}

		$document->addScriptDeclaration('var PAYMILL_PUBLIC_KEY = "' . $paymill_public_key . '";');
		$document->addScript('https://bridge.paymill.com/');
		JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_NUMBER');
		JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_EXPIRATION_DATE');
		?>
		<div class="containerPaymil">
			<div class="controls">
				<div class="payment-errors text-error"></div>
			</div>
			<div class="clearfix"></div>
			<div class="well">
				<form id="payment-form" method="POST" action="index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&oid=<?php
				echo (int) $data['order_id']; ?>&Itemid=<?php
				echo $Itemid; ?>">
					<div class="clearfix"></div>
					<div id="payment-form-cc">
						<input class="card-amount" type="hidden" value="<?php echo $data['order']->order_total; ?>"/>
						<input class="card-currency" type="hidden" value="<?php echo CURRENCY_CODE; ?>"/>

						<div class="controls controls-row">
							<div class="span3"><label><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CARD_NUMBER'); ?></label>
								<input class="card-number span12" type="text" size="20" value=""/>
							</div>
							<div class="span1"><label><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CVC'); ?></label>
								<input class="card-cvc span12" type="text" size="4" value=""/>
							</div>
						</div>

						<div class="controls">
							<div class="span4">
								<label><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CARD_HOLDER'); ?></label>
								<input class="card-holdername span12" type="text" size="20" value=""/>
							</div>
							<div class="span3">
								<label><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_VALID_UNTIL'); ?></label>
								<input class="card-expiry-month span5" type="text" size="2" value=""/>
								<span> / </span>
								<input class="card-expiry-year span6" type="text" size="4" value=""/>
							</div>
						</div>
					</div>
					<div class="controls">
						<div class="span12">
							<button class="submit-button btn btn-primary" type="submit"><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_BUY_NOW'); ?></button>
						</div>
					</div>
					<input type="hidden" name="option" value="com_redshop" />
					<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
					<input type="hidden" name="ccinfo" value="1" />
					<input type="hidden" name="payment_method_id" value="<?php echo $jInput->get('payment_method_id', ''); ?>" />
					<input type="hidden" name="order_id" value="<?php echo $data['order_id']; ?>" />
					<div class="clearfix"></div>
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

		$order_functions = new order_functions;
		$orderDetails = $order_functions->getOrderDetails($data['order_id']);
		$order_amount = number_format($orderDetails->order_total, 2, '.', '') * 100;

		$session = JFactory::getSession();
		$Itemid = JRequest::getVar('Itemid');
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/lib/Services/Paymill/Transactions.php';
		include $paymentpath;

		if ($token = $data['paymillToken'])
		{
			// Require "Services/Paymill/Transactions.php";
			$transactionsObject = new Services_Paymill_Transactions($paymill_private_key, "https://api.paymill.com/v2/");
			$params = array(
				'amount'      => $order_amount, // E.g. "15" for 0.15 EUR!
				'currency'    => CURRENCY_CODE, // ISO 4217
				'token'       => $token,
				'description' => 'Order: ' . $data['order_id']
			);

			$transaction = $transactionsObject->create($params);

			$session->set('paymillresult', $transaction);
		}

		$redirect_url = JRoute::_("index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paymill&Itemid=" . $Itemid . "&orderid=" . $data['order_id'], false);
		$app->redirect($redirect_url);
	}

	function onNotifyPaymentrs_payment_paymill($element, $request)
	{
		if ($element != 'rs_payment_paymill')
		{
			return;
		}

		$request = JRequest::get('request');
		$order_id = $request['orderid'];

		$session = JFactory::getSession();
		$paymillresult = $session->get('paymillresult');
		$tid = $paymillresult['id'];
		$verify_status = $this->params->get('verify_status');
		$invalid_status = $this->params->get('invalid_status');
		$values = new stdClass;

		if ($paymillresult['error'] != '')
		{
			$error_message = $paymillresult['error'];
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = $error_message;
			$values->msg = $error_message;
		}
		else
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JTEXT::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JTEXT::_('COM_REDSHOP_ORDER_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	function onCapture_Paymentrs_payment_paymill($element, $data)
	{
		return;
	}
}
