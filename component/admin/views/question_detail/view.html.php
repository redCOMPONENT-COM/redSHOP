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

class question_detailVIEWquestion_detail extends JView
{
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_QUESTION'));

		$uri        = JFactory::getURI();
		$lists      = array();
		$model      = $this->getModel();

		$detail     = $this->get('data');
		$answers    = $this->get('answers');
		$pagination = $this->get('Pagination');
		$isNew      = ($detail->question_id < 1);

		$text       = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_QUESTION_DETAIL') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_question48');
		JToolBarHelper::save();
		JToolBarHelper::custom('send', 'send.png', 'send.png', JText::_('COM_REDSHOP_SEND'), false);

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$option                         = $model->getProduct();
		$optionsection                  = array();
		$optionsection[0]               = new stdClass;
		$optionsection[0]->product_id   = 0;
		$optionsection[0]->product_name = JText::_('COM_REDSHOP_SELECT');

		if (count($option) > 0)
		{
			$optionsection = @array_merge($optionsection, $option);
		}

		$lists['published']  = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);
		$lists['product_id'] = JHTML::_('select.genericlist', $optionsection, 'product_id',
		'class               ="inputbox" size="1" ', 'product_id', 'product_name', $detail->product_id
		);

		$this->lists         = $lists;
		$this->detail        = $detail;
		$this->answers       = $answers;
		$this->pagination    = $pagination;
		$this->request_url   = $uri->toString();

		parent::display($tpl);
	}
}
