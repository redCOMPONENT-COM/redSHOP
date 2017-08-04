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
 * View Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.2.1
 */
class RedshopViewQuestion extends RedshopViewAdmin
{
	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 *
	 * @since   2.0.0.5
	 */

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_QUESTION_MANAGEMENT'), 'redshop_question_48');

		$this->form       = $this->get('Form');
		$this->detail     = $this->get('Item');
		$this->item       = $this->detail;
		$this->state      = $this->get('State');

		$model = $this->getModel('Question');
		$this->answers = $model->getAnswers($this->item->id);
		$this->requestUrl = JUri::getInstance()->toString();

		if (!$this->item->id)
		{
			$user = JFactory::getUser();
			$this->item->user_email = $user->email;
			$this->item->user_name 	= $user->username;
		}

		$this->addToolBar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->id < 1);

		// Prepare text for title
		$title = JText::_('COM_REDSHOP_QUESTION_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';

		JToolBarHelper::title($title, 'redshop_question_48');
		JToolBarHelper::apply('question.apply');
		JToolBarHelper::save('question.save');

		if ($isNew)
		{
			JToolBarHelper::cancel('question.cancel');
		}
		else
		{
			JToolBarHelper::cancel('question.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}
}
