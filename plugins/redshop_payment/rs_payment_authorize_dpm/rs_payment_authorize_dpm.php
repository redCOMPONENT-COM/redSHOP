<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin.Redshop_Payment
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/plugins/redshop_payment/rs_payment_authorize_dpm/rs_payment_authorize_dpm/authorize_lib/AuthorizeNet.php';

/**
 * Class plgRedshop_paymentrs_payment_authorize_dpm
 *
 * @since  1.5
 */
class PlgRedshop_PaymentRs_Payment_Authorize_Dpm extends JPlugin
{
	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * onPrePayment
	 *
	 * @param   string  $element  Name element
	 * @param   array   $data     Request data
	 *
	 * @throws Exception
	 * @return  void
	 */
	public function onPrePayment($element, $data)
	{
		if ($this->verifyData($element))
		{
			$step = JFactory::getApplication()->input->getInt('step', 0);

			if ($step == 2)
			{
				$this->authorizeData($element, $data);

				return;
			}

			$juri = JUri::getInstance();
			$juri->setVar('step', 2);
			$juri->setVar('tmp', 'component');
			$juri->setVar('encr', $data['order']->encr_key);

			echo AuthorizeNetDPM::getCreditCardForm(
				$data['order']->order_total,
				$data['order']->order_number,
				$juri->toString(),
				$this->params->get("access_id"),
				$this->params->get("transaction_id"),
				$this->params->get("is_test"),
				false
			);
		}

		return;
	}

	/**
	 * authorizeData
	 *
	 * @param   string  $element  Name element
	 * @param   array   $data     Request data
	 *
	 * @throws Exception
	 * @return  void
	 */
	public function authorizeData($element, $data)
	{
		if ($this->verifyData($element))
		{
			$app      = JFactory::getApplication();
			$itemId   = $app->input->getInt('Itemid', 0);

			// Where the user will end up.
			$redirectUrl = JURI::base()
				. "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_authorize_dpm&Itemid=$itemId&orderid="
				. $data['order_id'];
			$apiLoginId = $this->params->get("access_id");

			// Your MD5 Setting
			$md5Settings = $this->params->get("md5_key");
			$response    = new AuthorizeNetSIM($apiLoginId, $md5Settings);

			if ($response->isAuthorizeNet())
			{
				if ($response->approved)
				{
					// Do your processing here.
					$redirectUrl .= '&response_code=1&transaction_id=' . $response->transaction_id;
				}
				else
				{
					$redirectUrl .= '&response_code=' . $response->response_code . '&response_reason_text=' . $response->response_reason_text;
				}
			}
			else
			{
				$redirectUrl .= '&response_code=' . $response->response_code . '&response_reason_text=' . $response->response_reason_text;
			}

			echo AuthorizeNetDPM::getRelayResponseSnippet($redirectUrl);
		}

		return;
	}

	/**
	 * onNotifyPaymentrs_payment_authorize_dpm
	 *
	 * @param   string  $element  Name element
	 * @param   array   $request  Request data
	 *
	 * @return  stdClass|void
	 *
	 * @throws Exception
	 */
	public function onNotifyPaymentrs_payment_authorize_dpm($element, $request)
	{
		if ($this->verifyData($element))
		{
			$input = JFactory::getApplication()->input;

			// Result Response
			$tid             = $input->get('transaction_id');
			$response_code   = htmlentities($input->getString('response_code'));
			$response_reason = htmlentities($input->getString('response_reason_text'));
			$order_id        = $input->get("orderid");

			JPlugin::loadLanguage('com_redshop');

			$verify_status  = $this->params->get('verify_status', '');
			$invalid_status = $this->params->get('invalid_status', '');
			$values = new stdClass;

			if (isset($tid) && $response_code == 1)
			{
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';

				$values->log = JTEXT::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JTEXT::_('COM_REDSHOP_ORDER_PLACED');
			}
			else
			{
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = $response_reason;
				$values->msg = $response_reason;
			}

			$values->transaction_id = $tid;
			$values->order_id       = $order_id;

			return $values;
		}

		return;
	}

	/**
	 * @param   string  $element  Element
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	protected function verifyData($element)
	{
		$this->params->def('access_id', '62qpC9xN9nN4');
		$this->params->def('transaction_id', '97sRY6pGTea3E48d');

		if ($element != $this->_name || empty($this->params->get("access_id")) || empty($this->params->get("transaction_id")))
		{
			return false;
		}

		return true;
	}
}
