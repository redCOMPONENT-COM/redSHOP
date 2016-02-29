<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JLoader::load('RedshopHelperAdminExtra_field');
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperHelper');

class RedshopViewOrder_detail extends RedshopView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_ORDER'));
		$order_functions = new order_functions;
		$redhelper = new redhelper;

		$uri = JFactory::getURI();

		// Load payment plugin language file
		$payment_lang_list = $redhelper->getPlugins("redshop_payment");

		$language = JFactory::getLanguage();
		$base_dir = JPATH_ADMINISTRATOR;
		$language_tag = $language->getTag();

		for ($l = 0, $ln = count($payment_lang_list); $l < $ln; $l++)
		{
			$extension = 'plg_redshop_payment_' . $payment_lang_list[$l]->element;
			$language->load($extension, $base_dir, $language_tag, true);
		}

		// Load Shipping plugin language files
		$shippingPlugins = JPluginHelper::getPlugin("redshop_shipping");

		for ($l = 0, $ln = count($shippingPlugins); $l < $ln; $l++)
		{
			$extension = 'plg_redshop_shipping_' . strtolower($shippingPlugins[$l]->name);
			$language->load($extension, $base_dir);
		}

		$layout = JRequest::getVar('layout');
		$document->addScript('components/com_redshop/assets/js/order.js');
		$document->addScript('components/com_redshop/assets/js/common.js');
		$document->addScript('components/com_redshop/assets/js/validation.js');
		$document->addScript('components/com_redshop/assets/js/json.js');

		$lists = array();

		$model = $this->getModel();

		$detail = $this->get('data');

		$billing = $order_functions->getOrderBillingUserInfo($detail->order_id);
		$shipping = RedshopHelperOrder::getOrderShippingUserInfo($detail->order_id);

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

			$isCompany = array();
			$isCompany[0] = new stdClass;
			$isCompany[0]->value = 0;
			$isCompany[0]->text = JText::_('COM_REDSHOP_USER_CUSTOMER');
			$isCompany[1] = new stdClass;
			$isCompany[1]->value = 1;
			$isCompany[1]->text = JText::_('COM_REDSHOP_USER_COMPANY');
			$lists['is_company'] = JHTML::_(
				'select.genericlist',
				$isCompany,
				'is_company',
				'class="inputbox" onchange="showOfflineCompanyOrCustomer(this.value);" ',
				'value',
				'text',
				$billing->is_company
			);

			$lists['tax_exempt'] = JHTML::_('select.booleanlist', 'tax_exempt', 'class="inputbox"', $billing->tax_exempt);
			$lists['tax_exempt_approved']     = JHTML::_('select.booleanlist', 'tax_exempt_approved', 'class="inputbox"', $billing->tax_exempt_approved);
			$lists['requesting_tax_exempt']   = JHTML::_('select.booleanlist', 'requesting_tax_exempt', 'class="inputbox"', $billing->requesting_tax_exempt);

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
		JToolBarHelper::title(JText::_('COM_REDSHOP_ORDER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'pencil-2 redshop_order48');
		JToolBarHelper::cancel('cancel', JText::_('COM_REDSHOP_ORDERLIST'));

		$order_id = $detail->order_id;
		RedshopToolbarHelper::link(
			'index.php?option=com_redshop&view=order_detail&task=createpdfstocknote&cid[]=' . $order_id,
			'redshop_export_export32',
			'COM_REDSHOP_CREATE_STOCKNOTE',
			'_blank'
		);

		RedshopToolbarHelper::link(
			'index.php?option=com_redshop&view=order_detail&task=createpdf&cid[]=' . $order_id,
			'redshop_export_export32',
			'COM_REDSHOP_CREATE_SHIPPING_LABEL',
			'_blank'
		);

		$tmpl = JFactory::getApplication()->input->get('tmpl', '');
		$appendTmpl = ($tmpl) ? '&tmpl=component' : '';

		RedshopToolbarHelper::link(
			'index.php?option=com_redshop&view=order_detail&task=send_downloadmail&cid[]=' . $order_id . $appendTmpl,
			'send',
			'COM_REDSHOP_SEND_DOWNLOEADMAIL'
		);

		RedshopToolbarHelper::link(
			'index.php?option=com_redshop&view=order_detail&task=resendOrderMail&orderid=' . $order_id . $appendTmpl,
			'send',
			'COM_REDSHOP_RESEND_ORDER_MAIL'
		);

		RedshopToolbarHelper::link(
			'index.php?option=com_redshop&view=order_detail&task=send_invoicemail&cid[]=' . $order_id . $appendTmpl,
			'send',
			'COM_REDSHOP_SEND_INVOICEMAIL'
		);

		if ($tmpl)
		{
			RedshopToolbarHelper::link(
				'index.php?option=com_redshop&view=order&tmpl=component',
				'back',
				'COM_REDSHOP_BACK'
			);
		}

		RedshopToolbarHelper::link(
			'index.php?tmpl=component&option=com_redshop&view=order_detail&layout=print_order&cid[]=' . $order_id,
			'print',
			'COM_REDSHOP_PRINT',
			'_blank'
		);

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
