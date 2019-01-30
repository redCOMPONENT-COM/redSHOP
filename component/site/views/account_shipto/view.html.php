<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @return  mixed         A string if successful, otherwise an Error object.
	 * @throws  Exception
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   12.2
	 */
	public function display($tpl = null)
	{
		/** @var JApplicationSite $app */
		$app  = JFactory::getApplication();
		$task = $app->input->getCmd('task');
		$user = JFactory::getUser();

		// Preform security checks
		$session        = JFactory::getSession();
		$auth           = $session->get('auth');
		$lists          = array();
		$billingAddress = new stdClass;

		if ($user->id)
		{
			$billingAddress = RedshopHelperOrder::getBillingAddress($user->id);
		}
		elseif (isset($auth['users_info_id']) && $auth['users_info_id'])
		{
			$model          = $this->getModel('account_shipto');
			$billingAddress = $model->_loadData($auth['users_info_id']);
		}
		else
		{
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . $app->input->getInt('Itemid', 0)));
			$app->close();
		}

		if ($task == 'addshipping')
		{
			JHtml::_('redshopjquery.framework');
			/** @scrutinizer ignore-deprecated */
			JHtml::script('com_redshop/jquery.validate.min.js', false, true);
			/** @scrutinizer ignore-deprecated */
			JHtml::script('com_redshop/redshop.common.min.js', false, true);
			/** @scrutinizer ignore-deprecated */
			JHtml::script('com_redshop/redshop.registration.min.js', false, true);
			/** @scrutinizer ignore-deprecated */
			JHtml::stylesheet('com_redshop/redshop.validation.min.css', array(), true);

			$shippingAddresses = $this->get('Data');

			if ($shippingAddresses->users_info_id > 0 && $shippingAddresses->user_id != $billingAddress->user_id)
			{
				echo JText::_('COM_REDSHOP_ALERTNOTAUTH');

				return;
			}

			$lists['shipping_customer_field'] = Redshop\Fields\SiteHelper::renderFields(
				RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS, $shippingAddresses->users_info_id
			);
			$lists['shipping_company_field']  = Redshop\Fields\SiteHelper::renderFields(
				RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS, $shippingAddresses->users_info_id
			);

			$this->setLayout('form');
		}
		else
		{
			if ($user->id)
			{
				$shippingAddresses = RedshopHelperOrder::getShippingAddress($user->id);
			}
			else
			{
				$shippingAddresses = RedshopHelperOrder::getShippingAddress(-$auth['users_info_id']);
			}
		}

		$this->lists             = $lists;
		$this->shippingAddresses = $shippingAddresses;
		$this->billingAddresses  = $billingAddress;
		$this->request_url       = JUri::getInstance()->toString();

		JFilterOutput::cleanText($this->request_url);
		$this->params = $app->getParams();

		parent::display($tpl);
	}
}
