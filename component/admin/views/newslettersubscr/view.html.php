<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewNewslettersubscr extends RedshopViewAdmin
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

		$document->setTitle(JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_MANAGEMENT'), 'envelope redshop_newsletter48');
		$task = JFactory::getApplication()->input->getCmd('task', '');

		if ($task != 'import_data')
		{
			JToolBarHelper::custom('import_data', 'upload.png', 'upload_f2.png', 'COM_REDSHOP_IMPORT_DATA', false);
			JToolBarHelper::custom('export_data', 'save.png', 'save_f2.png', 'COM_REDSHOP_EXPORT_DATA', false);
			JToolBarHelper::custom('export_acy_data', 'save.png', 'save_f2.png', 'COM_REDSHOP_EXPORT_ACY_MAILING_DATA', false);
			JToolbarHelper::addNew();
			JToolbarHelper::EditList();
			JToolBarHelper::deleteList();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
		}

		if ($task == 'import_data')
		{
			JToolBarHelper::custom('importdata', 'save.png', 'save_f2.png', 'COM_REDSHOP_IMPORT', false);

			JToolBarHelper::custom('back', 'back.png', 'back_f2.png', 'COM_REDSHOP_BACK', false);

			$this->setLayout('newsletterimport');

			$model = $this->getModel('newslettersubscr');

			$newsletters = $model->getnewsletters();

			$lists['newsletters'] = JHTML::_('select.genericlist', $newsletters, 'newsletter_id', 'class="inputbox" size="1" ', 'value', 'text', '');
		}

		$this->state = $this->get('State');
		$lists['order']     = $this->state->get('list.ordering', 'subscription_id');
		$lists['order_Dir'] = $this->state->get('list.direction');

		$newslettersubscrs = $this->get('Data');
		$pagination        = $this->get('Pagination');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->newslettersubscrs = $newslettersubscrs;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
