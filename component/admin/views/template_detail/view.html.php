<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


class RedshopViewTemplate_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		JToolBarHelper::title(JText::_('COM_REDSHOP_TEMPLATES_MANAGEMET'), 'redshop_templates48');

		$uri = JFactory::getURI();

		$model = $this->getModel('template_detail');
		$user = JFactory::getUser();
		$redtemplate = Redtemplate::getInstance();

		// 	fail if checked out not by 'me'
		if ($model->isCheckedOut($user->get('id')))
		{
			$msg = JText::sprintf('DESCBEINGEDITTED', JText::_('COM_REDSHOP_THE_DETAIL'), $detail->title);
			$app->redirect('index.php?option=com_redshop&view=template', $msg);
		}

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->template_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_TEMPLATES') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_templates48');

		JToolBarHelper::apply();

		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			// EDIT - check out the item
			$model->checkout($user->get('id'));

			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		// TEMPLATE MOVE DB TO FILE
		$post = $jinput->post->getArray();
		if ($isNew && (isset($post['template_name']) && $post['template_name'] != ""))
		{
			$detail->template_name    = $post['template_name'];
			$detail->template_section = $post['template_section'];
			$template_desc            = $jinput->post->get('template_desc', '', 'raw');
			$detail->template_desc    = $template_desc;
			$detail->published        = $post['published'];
			$detail->msg              = JText::_('PLEASE_CHANGE_FILE_NAME_IT_IS_ALREADY_EXISTS');
		}
		// TEMPLATE MOVE DB TO FILE END
		// Section can be added from here
		$optionsection = $redtemplate->getTemplateSections();
		$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'template_section',
			'class="inputbox" size="1"  onchange="showclicktellbox();"', 'value', 'text', $detail->template_section
		);

		$lists['published'] = JHTML::_('redshopselect.booleanlist', 'published', 'class="inputbox" ', $detail->published);

		$order_functions = order_functions::getInstance();

		$paymentMethod = $order_functions->getPaymentMethodInfo();

		$payment_methods = explode(',', $detail->payment_methods);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $payment_methods);
		$lists['payment_methods'] = JHTML::_('select.genericlist', $paymentMethod, 'payment_methods[]',
			'class="inputbox" multiple="multiple" size="4" ', 'element', 'name', $payment_methods
		);

		$shippingMethod = $order_functions->getShippingMethodInfo();
		$shipping_methods = explode(',', $detail->shipping_methods);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $shipping_methods);

		$lists['shipping_methods'] = JHTML::_('select.genericlist', $shippingMethod, 'shipping_methods[]',
			'class="inputbox" multiple="multiple" size="4" ', 'element', 'name', $shipping_methods
		);
		$lists['order_status'] = $order_functions->getstatuslist('order_status', $detail->order_status, 'class="inputbox" multiple="multiple"');

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
