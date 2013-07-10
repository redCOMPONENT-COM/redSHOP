<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';

class order_detailVIEWorder_detail extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$option = JRequest::getVar('option');
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_ORDER'));
		$order_functions = new order_functions;
		$redhelper = new redhelper;

		$uri = JFactory::getURI();

		// Load language file
		$payment_lang_list = $redhelper->getPlugins("redshop_payment");

		$language = JFactory::getLanguage();
		$base_dir = JPATH_ADMINISTRATOR;
		$language_tag = $language->getTag();

		for ($l = 0; $l < count($payment_lang_list); $l++)
		{
			$extension = 'plg_redshop_payment_' . $payment_lang_list[$l]->element;
			$language->load($extension, $base_dir, $language_tag, true);
		}

		$layout = JRequest::getVar('layout');
		$document->addScript('components/' . $option . '/assets/js/order.js');
		$document->addScript('components/' . $option . '/assets/js/common.js');
		$document->addScript('components/' . $option . '/assets/js/validation.js');
		$document->addScript(JURI::base() . 'components/' . $option . '/assets/js/select_sort.js');
		$document->addStyleSheet(JURI::base() . 'components/' . $option . '/assets/css/search.css');
		$document->addScript(JURI::base() . 'components/' . $option . '/assets/js/search.js');
		$document->addScript(JURI::base() . 'components/' . $option . '/assets/js/json.js');

		$lists = array();

		$model = $this->getModel();

		$detail = $this->get('data');

		$billing = $order_functions->getOrderBillingUserInfo($detail->order_id);
		$shipping = $order_functions->getOrderShippingUserInfo($detail->order_id);

		$task = JRequest::getVar('task');

		if ($task == 'ccdetail')
		{
			$ccdetail = $model->getccdetail($detail->order_id);
			$this->ccdetail = $ccdetail;
			$this->setLayout('ccdetail');

			parent::display($tpl);
			exit;
		}

		if ($layout == 'shipping' || $layout == 'billing')
		{
			if (!$shipping || $layout == 'billing')
			{
				$shipping = $billing;
			}

			$this->setLayout($layout);
			$Redconfiguration = new Redconfiguration;

			$countryarray = $Redconfiguration->getCountryList((array) $shipping);
			$shipping->country_code = $countryarray['country_code'];
			$lists['country_code'] = $countryarray['country_dropdown'];
			$statearray = $Redconfiguration->getStateList((array) $shipping);
			$lists['state_code'] = $statearray['state_dropdown'];
			$showcountry = (count($countryarray['countrylist']) == 1 && count($statearray['statelist']) == 0) ? 0 : 1;
			$showstate = ($statearray['is_states'] <= 0) ? 0 : 1;

			$this->showcountry = $showcountry;
			$this->showstate = $showstate;
		}

		elseif ($layout == "print_order" || $layout == 'productorderinfo' || $layout == 'creditcardpayment')
		{
			$this->setLayout($layout);
		}

		else
		{
			$this->setLayout('default');
		}

		$payment_detail = $order_functions->getOrderPaymentDetail($detail->order_id);

		if (count($payment_detail) > 0)
		{
			$payment_detail = $payment_detail[0];
		}

		$isNew = ($detail->order_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');
		JToolBarHelper::title(JText::_('COM_REDSHOP_ORDER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_order48');

		$redhelper = new redhelper;
		$backlink = 'index.php?option=com_redshop&view=order';
		$backlink = $redhelper->sslLink($backlink, 0);
		$new_link = 'index.php?option=com_redshop&view=order';
		JToolBarHelper::back(JText::_('COM_REDSHOP_ORDERLIST'), 'javascript:location.href=\'' . $new_link . '\';');

		// Section can be added from here
		$option = array();
		$option[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));

		$this->lists = $lists;
		$this->detail = $detail;
		$this->billing = $billing;
		$this->shipping = $shipping;
		$this->payment_detail = $payment_detail;
		$this->shipping_rate_id = $detail->ship_method_id;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
