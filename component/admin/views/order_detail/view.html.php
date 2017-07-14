<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Order detail view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewOrder_Detail extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$input    = JFactory::getApplication()->input;
		$document->setTitle(JText::_('COM_REDSHOP_ORDER'));

		$uri = JUri::getInstance();

		// Load payment languages
		RedshopHelperPayment::loadLanguages();

		// Load Shipping plugin language files
		RedshopHelperShipping::loadLanguages();

		$layout = $input->getCmd('layout', '');
		$document->addScript('components/com_redshop/assets/js/order.js');
		$document->addScript('components/com_redshop/assets/js/common.js');
		$document->addScript('components/com_redshop/assets/js/validation.js');
		$document->addScript('components/com_redshop/assets/js/json.js');

		$lists = array();

		$model = $this->getModel();

		$detail = $this->get('data');

		$billing  = RedshopHelperOrder::getOrderBillingUserInfo($detail->order_id);
		$shipping = RedshopHelperOrder::getOrderShippingUserInfo($detail->order_id);

		$task = $input->getCmd('task', '');

		if ($task == 'ccdetail')
		{
			$ccdetail = $model->getccdetail($detail->order_id);
			$this->ccdetail = $ccdetail;
			$this->setLayout('ccdetail');

			parent::display($tpl);
			JFactory::getApplication()->close();
		}

		if ($layout == 'shipping' || $layout == 'billing')
		{
			if (!$shipping || $layout == 'billing')
			{
				$shipping = $billing;
			}

			$this->setLayout($layout);

			$countryarray           = RedshopHelperWorld::getCountryList((array) $shipping);
			$shipping->country_code = $countryarray['country_code'];
			$lists['country_code']  = $countryarray['country_dropdown'];

			$statearray             = RedshopHelperWorld::getStateList((array) $shipping);
			$lists['state_code']    = $statearray['state_dropdown'];

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

		$payment_detail = RedshopHelperOrder::getPaymentInfo($detail->order_id);

		if (is_array($payment_detail) && count($payment_detail))
		{
			$payment_detail = $payment_detail[0];
		}

		$isNew = ($detail->order_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');
		JToolBarHelper::title(JText::_('COM_REDSHOP_ORDER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'pencil-2 redshop_order48');

		JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));

		$order_id = $detail->order_id;

		if (RedshopHelperPdf::isAvailablePdfPlugins())
		{
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
		}

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

		if (isset($payment_detail->plugin->params) && $payment_detail->plugin->params->get('enableVault')
			&& ('P' == $detail->order_status || 'Unpaid' == $detail->order_payment_status))
		{
			RedshopToolbarHelper::link(
				'index.php?option=com_redshop&view=order_detail&task=pay&orderId=' . $order_id . $appendTmpl,
				'credit',
				'COM_REDSHOP_ORDER_PAY'
			);
		}

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

		$lists['order_extra_fields'] = RedshopHelperExtrafields::listAllField(RedshopHelperExtrafields::SECTION_ORDER, $order_id, "", "", "", $billing->user_email);

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
