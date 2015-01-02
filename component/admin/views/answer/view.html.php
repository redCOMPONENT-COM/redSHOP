<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewAnswer extends RedshopView
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_answer'));
		$model = $this->getModel('answer');

		$array = JRequest::getVar('parent_id', 0, '', 'array');
		$parent_id = (int) $array[0];

		JToolBarHelper::title(JText::_('COM_REDSHOP_ANSWER_MANAGEMENT'), 'redshop_question48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri = JFactory::getURI();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'question_date');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');
		$product_id       = $app->getUserStateFromRequest($context . 'product_id', 'product_id', 0);

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$question = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		$option = $model->getProduct();
		$optionsection = array();
		$optionsection[0]->product_id = 0;
		$optionsection[0]->product_name = JText::_('COM_REDSHOP_SELECT');

		if (count($option) > 0)
		{
			$optionsection = @array_merge($optionsection, $option);
		}

		$lists['product_id'] = JHTML::_('select.genericlist', $optionsection, 'product_id',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"',
			'product_id',
			'product_name',
			$product_id
		);

		$this->lists = $lists;
		$this->parent_id = $parent_id;
		$this->question = $question;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
