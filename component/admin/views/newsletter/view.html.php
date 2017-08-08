<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewNewsletter extends RedshopViewAdmin
{
	/**
	 * The current user.
	 *
	 * @var  JUser
	 */
	public $user;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public $state;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_NEWSLETTER'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_NEWSLETTER_MANAGEMENT'), 'envelope redshop_newsletter48');
		$layout = JFactory::getApplication()->input->getCmd('layout', '');

		if ($layout == 'previewlog')
		{
			$this->setLayout($layout);
		}
		else
		{
			JToolBarHelper::custom('send_newsletter_preview', 'send.png', 'send.png', JText::_('COM_REDSHOP_SEND_NEWSLETTER'), true, false);
			JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
			JToolbarHelper::addNew();
			JToolbarHelper::EditList();
			JToolBarHelper::deleteList();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
		}

		$this->state = $this->get('State');
		$lists['order']     = $this->state->get('list.ordering', 'newsletter_id');
		$lists['order_Dir'] = $this->state->get('list.direction');

		$newsletters = $this->get('Data');
		$pagination  = $this->get('Pagination');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->newsletters = $newsletters;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
