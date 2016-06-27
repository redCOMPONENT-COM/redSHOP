<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewQuestion extends RedshopViewAdmin
{
	public $state;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_question'));
		$model = $this->getModel('question');

		JToolBarHelper::title(JText::_('COM_REDSHOP_QUESTION_MANAGEMENT'), 'redshop_question48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$this->state = $this->get('State');
		$lists['order']     = $this->state->get('list.ordering', 'question_date');
		$lists['order_Dir'] = $this->state->get('list.direction', 'desc');

		$question   = $this->get('Data');
		$pagination = $this->get('Pagination');

		$option                         = $model->getProduct();
		$optionsection                  = array();
		$optionsection[0]               = new stdClass;
		$optionsection[0]->product_id   = 0;
		$optionsection[0]->product_name = JText::_('COM_REDSHOP_SELECT');

		if (count($option) > 0)
		{
			$optionsection = @array_merge($optionsection, $option);
		}

		$lists['product_id'] = JHTML::_('select.genericlist', $optionsection, 'product_id',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'product_id', 'product_name', $this->state->get('product_id')
		);

		$this->lists       = $lists;
		$this->question    = $question;
		$this->pagination  = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
