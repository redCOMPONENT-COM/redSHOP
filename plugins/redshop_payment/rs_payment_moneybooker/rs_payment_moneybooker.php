<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_moneybooker extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
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
	 * @since   11.1
	 */
	public function __construct(&$subject, $config = array())
	{
		JFactory::getLanguage()->load('plg_redshop_payment_rs_payment_moneybooker', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_moneybooker')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_moneybooker($element, $request)
	{
		if ($element != 'rs_payment_moneybooker')
		{
			return;
		}

		JPlugin::loadLanguage('com_redshop');

		$db             = JFactory::getDbo();
		$request        = JRequest::get('request');
		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');

		if ($request['status'] == "2")
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

		$values->transaction_id = $request['mb_transaction_id'];
		$values->order_id       = $request['transaction_id'];
		$values->order_id_temp  = $request['orderid'];

		return $values;
	}

	public function onStatus_Paymentrs_payment_moneybooker($element, $data)
	{
		ob_clean();

		jimport('joomla.http');

		// Prepare for refund
		$urlQuery = array(
			'action' => 'prepare',
			'email' => $this->params->get('pay_to_email', ''),
			'password' => strtolower(md5($this->params->get('pay_to_password'))),
			//'transaction_id' => $data['order_id'],
			'mb_transaction_id' => $data['order_transactionid']
		);

		$http = new JHttp(new JRegistry);

		$response = $http->get('https://www.moneybookers.com/app/refund.pl?' . http_build_query($urlQuery));

		unset($urlQuery);

		$return = new stdClass;

		if (200 == $response->code)
		{
			// Get sid from skrill
			$sid = simplexml_load_string($response->body)->sid;

			// Execute refund.
			$urlQuery = array(
				'action' => 'refund',
				'sid' => (string) $sid
			);

			$http = new JHttp(new JRegistry);

			$refundStatus = $http->get('https://www.moneybookers.com/app/refund.pl?' . http_build_query($urlQuery));

			if (200 == $refundStatus->code)
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
