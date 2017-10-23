<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewNewsletter extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$context = 'newsletter_preview';

		$cid = $app->input->post->get('cid', array(0), 'array');

		$selected_product = $app->input->get('product', '');
		$n                = $cid[0];
		$model            = $this->getModel('newsletter');
		$subscribers      = $model->listallsubscribers($n);
		$product_category = new product_category;

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_NEWSLETTER'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_NEWSLETTER_MANAGEMENT'), 'redshop_newsletter48');

		JToolBarHelper::custom('send_newsletter', 'send.png', 'send.png', 'Send Newsletter');
		JToolBarHelper::cancel('close', JText::_('JTOOLBAR_CLOSE'));

		$uri = JFactory::getURI();

		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'newsletter_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$newsletters = $this->get('Data');
		$pagination = $this->get('Pagination');

		$oprand = $app->input->getCmd('oprand', 'select');

		$optionoprand = array();
		$optionoprand[] = JHTML::_('select.option', 'select', JText::_('COM_REDSHOP_SELECT'));
		$optionoprand[] = JHTML::_('select.option', 'more', JText::_('COM_REDSHOP_GTOREQUEL'));
		$optionoprand[] = JHTML::_('select.option', 'less', JText::_('COM_REDSHOP_LTOREQUEL'));
		$optionoprand[] = JHTML::_('select.option', 'equally', JText::_('COM_REDSHOP_EQUAL_SIGN'));
		$lists['oprand'] = JHTML::_('select.genericlist', $optionoprand, 'oprand', 'class="inputbox" size="1" ', 'value', 'text', $oprand);

		$country_option = array();
		$country_option[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT_COUNTRY'));

		$country = $model->getContry();

		$country_data = array_merge($country_option, $country);

		$country_value = $app->input->get('country', '');

		$lists['country'] = JHTML::_('select.genericlist', $country_data, 'country[]',
			'class="inputbox" multiple="multiple" size="4" ', 'value', 'text', $country_value
		);

		$categories = array();

		$categories = $product_category->list_all("product_category[]", 0, '', 10, true, true);
		$lists['categories'] = $categories;

		$product_data = array();
		$product_data = $model->getProduct();

		$lists['product'] = JHTML::_('select.genericlist', $product_data, 'product[]',
			'class="inputbox" multiple="multiple" size="8" ', 'value', 'text', $selected_product
		);

		$shopper_option   = array();
		$shopper_option[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT'));
		$shoppergroup     = $app->input->get('shoppergroups', '');
		$ShopperGrup      = $model->getShopperGroup();
		$ShopperGroups    = array_merge($shopper_option, $ShopperGrup);

		$lists['shoppergroups'] = JHTML::_('select.genericlist', $ShopperGroups, 'shoppergroups[]',
			'class="inputbox" multiple="multiple" size="8" ', 'value', 'text', $shoppergroup
		);

		$this->subscribers = $subscribers;
		$this->lists = $lists;
		$this->newsletters = $newsletters;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		$this->setLayout('preview');

		parent::display($tpl);
	}
}
