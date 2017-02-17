<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopViewAccount_shipto extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$order_functions = order_functions::getInstance();

		// Extra_field;
		$extra_field     = extraField::getInstance();

		$task = JRequest::getCmd('task');
		$user = JFactory::getUser();
		$uri  = JFactory::getURI();

		// Preform security checks
		$session = JFactory::getSession();
		$auth    = $session->get('auth');
		$params  = $app->getParams('com_redshop');
		$lists   = array();

		if ($user->id)
		{
			$billingaddresses = $order_functions->getBillingAddress($user->id);
		}
		elseif (isset($auth['users_info_id']) && $auth['users_info_id'])
		{
			$model            = $this->getModel('account_shipto');
			$billingaddresses = $model->_loadData($auth['users_info_id']);
		}
		else
		{
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid')));
			exit;
		}

		if ($task == 'addshipping')
		{
			JHtml::_('redshopjquery.framework');
			JHtml::script('com_redshop/jquery.validate.js', false, true);
			JHtml::script('com_redshop/common.js', false, true);
			JHtml::script('com_redshop/registration.js', false, true);
			JHtml::stylesheet('com_redshop/validation.css', array(), true);

			$shippingaddresses = $this->get('Data');

			if ($shippingaddresses->users_info_id > 0 && $shippingaddresses->user_id != $billingaddresses->user_id)
			{
				echo JText::_('COM_REDSHOP_ALERTNOTAUTH');

				return;
			}

			$lists['shipping_customer_field'] = $extra_field->list_all_field(14, $shippingaddresses->users_info_id);
			$lists['shipping_company_field']  = $extra_field->list_all_field(15, $shippingaddresses->users_info_id);

			$this->setLayout('form');
		}
		else
		{
			$shippingaddresses = $order_functions->getShippingAddress($user->id);
		}

		$this->lists = $lists;
		$this->shippingaddresses = $shippingaddresses;
		$this->billingaddresses = $billingaddresses;
		$this->request_url = $uri->toString();
		JFilterOutput::cleanText($this->request_url);
		$this->params = $params;

		parent::display($tpl);
	}
}
