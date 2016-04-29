<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewQuestion_detail extends RedshopView
{
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_QUESTION'));

		$uri        = JFactory::getURI();
		$lists      = array();
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

		$lists['published']  = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$this->lists         = $lists;
		$this->detail        = $detail;
		$this->answers       = $answers;
		$this->pagination    = $pagination;
		$this->request_url   = $uri->toString();

		parent::display($tpl);
	}
}
