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

class mailViewmail extends JView
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

	public function display($tpl = null)
	{
		$context = 'mail_id';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_MAIL'));
		jimport('joomla.html.pagination');

		JToolBarHelper::title(JText::_('COM_REDSHOP_MAIL_MANAGEMENT'), 'redshop_mailcenter48');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'm.mail_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$filter_section   = $app->getUserStateFromRequest($context . 'filter_section', 'filter_section', 0);

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$redtemplate = new Redtemplate;
		$optionsection = $redtemplate->getMailSections();
		$lists['mailsection'] = JHTML::_('select.genericlist', $optionsection, 'filter_section',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"',
			'value', 'text', $filter_section
		);

		$total = $this->get('Total');
		$media = $this->get('Data');

		$pagination = $this->get('Pagination');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->media = $media;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
