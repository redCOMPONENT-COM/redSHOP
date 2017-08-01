<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
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
class PlgRedshop_Paymentrs_Payment_Authorize_Dpm extends JPlugin
{
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

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
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
		$Itemid   = $app->input->getInt('Itemid', 0);

		// Where the user will end up.
		$redirect_url = JURI::base()
			. "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_authorize_dpm&Itemid=$Itemid&orderid="
			. $data['order_id'];
		$api_login_id = $this->params->get("access_id");

		// Your MD5 Setting
		$md5_setting = $this->params->get("md5_key");
		$response    = new AuthorizeNetSIM($api_login_id, $md5_setting);

		if ($response->isAuthorizeNet())
		{
			if ($response->approved)
			{
				// Do your processing here.
				$redirect_url .= '&response_code=1&transaction_id=' . $response->transaction_id;
			}
			else
			{
				$redirect_url .= '&response_code=' . $response->response_code . '&response_reason_text=' . $response->response_reason_text;
			}
		}
		else
		{
			$redirect_url .= '&response_code=' . $response->response_code . '&response_reason_text=' . $response->response_reason_text;
		}

		echo AuthorizeNetDPM::getRelayResponseSnippet($redirect_url);
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
}
