<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/plugins/redshop_payment/rs_payment_authorize_dpm/libraries/authorize_lib/AuthorizeNet.php';

/**
 * Class PlgRedshop_PaymentRs_Payment_Authorize_Dpm
 *
 * @since  1.7.0
 */
class PlgRedshop_PaymentRs_Payment_Authorize_Dpm extends JPlugin
{
	/**
	 * Load the language file on instantiation.
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
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_redshop_payment_rs_payment_authorize_dpm');
		parent::__construct($subject, $config);
	}

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
		if ($element != 'rs_payment_authorize_dpm')
		{
			return;
		}

		$stap = JFactory::getApplication()->input->getInt('stap', 0);

		if ($stap == 2)
		{
			$this->authorizeData($element, $data);

			return;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/libraries/extra_info.php';
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
		if ($element != 'rs_payment_authorize_dpm')
		{
			return;
		}

		$app      = JFactory::getApplication();
		$itemId   = $app->input->getInt('Itemid', 0);

		// Where the user will end up.
		$redirectUrl = JURI::base()
			. "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_authorize_dpm&Itemid=$itemId&orderid="
			. $data['order_id'];

		$accessId 	= $this->params->get("access_id");

		// Your MD5 Setting
		$md5Key 	= $this->params->get("md5_key");
		$response 	= new AuthorizeNetSIM($accessId, $md5Key);

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
		if ($element != 'rs_payment_authorize_dpm')
		{
			return;
		}

		$input = JFactory::getApplication()->input;

		// Result Response
		$tranId         = $input->get('transaction_id');
		$responseCode   = htmlentities($input->getString('response_code'));
		$responseReason = htmlentities($input->getString('response_reason_text'));
		$orderId        = $input->get("orderid");

		JPlugin::loadLanguage('com_redshop');

		$verifyStatus  = $this->params->get('verify_status', '');
		$invalidStatus = $this->params->get('invalid_status', '');
		$values = new stdClass;

		if (isset($tranId) && $responseCode == 1)
		{
			$values->order_status_code = $verifyStatus;
			$values->order_payment_status_code = 'Paid';

			$values->log = JTEXT::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JTEXT::_('COM_REDSHOP_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = $responseReason;
			$values->msg = $responseReason;
		}

		$values->transaction_id = $tranId;
		$values->order_id       = $orderId;

		return $values;
	}
}
