<?php
/**
 * @package     Redshop.Library
 * @subpackage  Payment
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Common architecture for payment class.
 *
 * @package     Redshop.Library
 * @subpackage  Payment
 * @since       1.5
 */
abstract class RedshopPayment extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin base path
	 *
	 * @var  null
	 */
	protected $path = null;

	/**
	 * Constructor
	 *
	 * @param   object  $subject   The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);

		$this->setPluginPath();

		JLoader::import('redshop.library');
	}

	/**
	 * Pre Payment method to setup payment redirection form
	 *
	 * @param   string  $element    Payment Element Name
	 * @param   array   $orderInfo  Order Information array
	 *
	 * @return  void
	 */
	abstract public function onPrePayment($element, $orderInfo);

	/**
	 * Prepare Payment Input
	 *
	 * @param   array  $orderInfo  Order Information
	 *
	 * @return  array              Payment Gateway for parameters
	 */
	abstract protected function preparePaymentInput($orderInfo);

	/**
	 * Set Plugin Base path.
	 *
	 * @return  void
	 */
	protected function setPluginPath()
	{
		$this->path = JPATH_SITE . '/plugins/' . $this->_type . '/' . $this->_name;
	}

	/**
	 * Get notify url for payment status update.
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  string             Notify url
	 */
	protected function getNotifyUrl($orderId)
	{
		return JUri::base()
				. 'index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=' . $this->_name
				. '&orderid=' . $orderId
				. '&Itemid=' . JFactory::getApplication()->input->getInt('Itemid');
	}

	/**
	 * Get return url of for the payment.
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  string   Return Url
	 */
	protected function getReturnUrl($orderId)
	{
		return JUri::base()
				. 'index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $orderId
				. '&Itemid=' . JFactory::getApplication()->input->getInt('Itemid');
	}

	/**
	 * Get 2 digit code for Payment Language
	 *
	 * @return  string  Language Code
	 */
	protected function getLang()
	{
		return substr(JFactory::getLanguage()->getTag(), 0, 2);
	}

	/**
	 * Render payment form to redirect on payment gateway page.
	 *
	 * @param   array  $orderInfo  Order Information Array
	 *
	 * @return  string             Rendered Layout HTML
	 */
	protected function renderPaymentForm($orderInfo)
	{
		$formInput = $this->preparePaymentInput($orderInfo);

		return RedshopLayoutHelper::render(
			'form',
			array(
				'orderInfo' => $orderInfo,
				'formInput' => $formInput,
				'params'    => $this->params
			),
			$this->path . '/layouts'
		);
	}

	/**
	 * Set order payment status object
	 *
	 * @param   integer  $orderId        Order Id
	 * @param   string   $transactionId  Transaction
	 * @param   string   $status         Order Status Code
	 * @param   string   $paymentStatus  Order Payment Status Code
	 * @param   string   $message        Order Message
	 * @param   string   $log            Order Payment Log
	 *
	 * @return  object                   Values
	 */
	protected function setStatus($orderId, $transactionId, $status, $paymentStatus, $message, $log)
	{
		$values = new stdClass;

		$values->transaction_id            = $transactionId;
		$values->order_id                  = $orderId;
		$values->order_status_code         = $status;
		$values->order_payment_status_code = $paymentStatus;
		$values->log                       = $log;
		$values->msg                       = $message;

		return $values;
	}
}
