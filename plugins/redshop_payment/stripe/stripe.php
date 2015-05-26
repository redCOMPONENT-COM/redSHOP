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

// Load stripe library
require_once dirname(__DIR__) . '/stripe/library/init.php';

class plgRedshop_PaymentStripe extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * This method will be triggered on before placing order to authorize or charge credit card
	 *
	 * Example of return parameters:
	 * $return->responsestatus = 'Success' or 'Fail';
	 * $return->message        = 'Success or Fail messafe';
	 * $return->transaction_id = 'Transaction Id from gateway';
	 *
	 * @param   string  $element  Name of the payment plugin
	 * @param   array   $data     Cart Information
	 *
	 * @return  object  Authorize or Charge success or failed message and transaction id
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'stripe')
		{
			return;
		}

		$app = JFactory::getApplication();

		echo RedshopLayoutHelper::render(
			'form',
			array(
				'action' => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=stripe&orderid=" . $data['order_id'],
				'data'   => $data,
				'params' => $this->params
			),
			dirname(__DIR__) . '/stripe/layouts'
		);
	}
}
