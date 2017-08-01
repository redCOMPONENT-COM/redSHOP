<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * Account Shipping To view
 *
 * @package     RedSHOP.Frontend
 * @subpackage  View
 * @since       1.6.0
 */
class RedshopViewAccount_Shipto extends RedshopView
{
	/**
	 * @var  array
	 */
	public $shippingAddresses;

	/**
	 * @var  array
	 */
	public $lists;

	/**
	 * @var  object
	 */
	public $billingAddresses;

	/**
	 * @var  Registry
	 */
	public $params;

	/**
	 * @var  string
	 */
	public $request_url;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   12.2
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$orderFunctions = order_functions::getInstance();

		// Extra_field;
		$extraField = extraField::getInstance();

		$task = $app->input->getCmd('task');
		$user = JFactory::getUser();

		// Preform security checks
		$session        = JFactory::getSession();
		$auth           = $session->get('auth');
		$lists          = array();
		$billingAddress = new stdClass;

		if ($user->id)
		{
			$billingAddress = $orderFunctions->getBillingAddress($user->id);
		}
		elseif (isset($auth['users_info_id']) && $auth['users_info_id'])
		{
			$model          = $this->getModel('account_shipto');
			$billingAddress = $model->_loadData($auth['users_info_id']);
		}
		else
		{
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid')));
			$app->close();
		}

		if ($task == 'addshipping')
		{
			JHtml::_('redshopjquery.framework');
			JHtml::script('com_redshop/jquery.validate.js', false, true);
			JHtml::script('com_redshop/common.js', false, true);
			JHtml::script('com_redshop/registration.js', false, true);
			JHtml::stylesheet('com_redshop/validation.css', array(), true);

			$shippingAddresses = $this->get('Data');

			if ($shippingAddresses->users_info_id > 0 && $shippingAddresses->user_id != $billingAddress->user_id)
			{
				echo JText::_('COM_REDSHOP_ALERTNOTAUTH');

				return;
			}

			$lists['shipping_customer_field'] = $extraField->list_all_field(14, $shippingAddresses->users_info_id);
			$lists['shipping_company_field']  = $extraField->list_all_field(15, $shippingAddresses->users_info_id);

			$this->setLayout('form');
		}
		else
		{
			$shippingAddresses = RedshopHelperOrder::getShippingAddress($user->id);
		}

		$this->lists             = $lists;
		$this->shippingAddresses = $shippingAddresses;
		$this->billingAddresses  = $billingAddress;
		$this->request_url       = JUri::getInstance()->toString();
		JFilterOutput::cleanText($this->request_url);
		$this->params = JFactory::getApplication()->getParams();

		parent::display($tpl);
	}
}
