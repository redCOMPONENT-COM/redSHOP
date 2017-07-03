<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * PlgRedshop_PaymentRs_Payment_MoneyBooker class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_MoneyBooker extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * [onPrePayment]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_moneybooker')
		{
			return;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * [onNotifyPaymentrs_payment_moneybooker]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $request  [request params]
	 *
	 * @return  [object]  $values
	 */
	public function onNotifyPaymentrs_payment_moneybooker($element, $request)
	{
		if ($element != 'rs_payment_moneybooker')
		{
			return;
		}

		JPlugin::loadLanguage('com_redshop');

		$db             = JFactory::getDbo();
		$request        = JFactory::getApplication()->input;
		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');

		if ($request->get('status', 0) == "2")
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $request->get('mb_transaction_id', 0);
		$values->order_id       = $request->get('transaction_id', 0);
		$values->order_id_temp  = $request->get('orderid', 0);

		return $values;
	}

	/**
	 * [onStatus_Paymentrs_payment_moneybooker description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function onStatus_Paymentrs_payment_moneybooker($element, $data)
	{
		ob_clean();

		jimport('joomla.http');

		// Prepare for refund
		$urlQuery = [
			'action' => 'prepare',
			'email' => $this->params->get('pay_to_email', ''),
			'password' => strtolower(md5($this->params->get('pay_to_password'))),
			'mb_transaction_id' => $data['order_transactionid']
		];

		$http = new JHttp(new JRegistry);

		$response = $http->get('https://www.moneybookers.com/app/refund.pl?' . http_build_query($urlQuery));

		unset($urlQuery);

		$return = new stdClass;

		if (200 == $response->code)
		{
			// Get sid from skrill
			$sid = simplexml_load_string($response->body)->sid;

			// Execute refund.
			$urlQuery = [
				'action' => 'refund',
				'sid' => (string) $sid
			];

			$http = new JHttp(new JRegistry);

			$refundStatus = $http->get('https://www.moneybookers.com/app/refund.pl?' . http_build_query($urlQuery));

			if ($refundStatus->code == 200)
			{
				$responseData = simplexml_load_string($refundStatus->body);

				$return->responsestatus = 'Fail';
				$return->message        = JText::sprintf('PLG_REDSHOP_PAYMENT_MONEYBOOKER_PAYMENT_REFUND_FAIL', (string) $responseData->error);
				$return->type           = 'error';

				if ((int) $responseData->status == 2)
				{
					// Update transaction string
					$query = $db->getQuery(true)
							->update($db->qn('#__redshop_order_payment'))
							->set($db->qn('order_payment_trans_id') . ' = ' . $db->q((string) $responseData->mb_transaction_id))
							->where($db->qn('order_id') . ' = ' . $db->q($data['order_id']));

					// Set the query and execute the update.
					$db->setQuery($query)->execute();

					$return->responsestatus = 'Success';
					$return->type           = 'message';
					$return->message        = JText::_('PLG_REDSHOP_PAYMENT_MONEYBOOKER_PAYMENT_REFUND_SUCCESS');
				}

				JFactory::getApplication()->enqueueMessage($return->message, $return->type);

				return $return;
			}
			else
			{
				JError::raiseError(403, $refundStatus->body);
			}
		}
		else
		{
			JError::raiseError(403, $response->body);
		}
	}
}
