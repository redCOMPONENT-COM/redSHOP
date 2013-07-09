<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';

class account_shiptoViewaccount_shipto extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$order_functions = new order_functions;

		// Extra_field;
		$extra_field     = new extraField;

		$task = JRequest::getVar('task');
		$user = JFactory::getUser();
		$uri  = JFactory::getURI();

		// Preform security checks
		$session = JFactory::getSession();
		$auth    = $session->get('auth');
		$params  = $app->getParams('com_redshop');

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
			$app->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getVar('Itemid'));
			exit;
		}

		if ($task == 'addshipping')
		{
			JHTML::Script('jquery-1.4.2.min.js', 'components/com_redshop/assets/js/', false);
			JHTML::Script('jquery.validate.js', 'components/com_redshop/assets/js/', false);
			JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
			JHTML::Script('registration.js', 'components/com_redshop/assets/js/', false);
			JHTML::Stylesheet('validation.css', 'components/com_redshop/assets/css/');

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
		$this->params = $params;

		parent::display($tpl);
	}
}
