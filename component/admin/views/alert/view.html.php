<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewAlert extends RedshopViewAdmin
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
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_ALERT'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_ALERT_MANAGEMENT'), 'redshop_mailcenter48');
		JToolBarHelper::deleteList();
		JToolBarHelper::custom('publish', 'publish.png', 'publish.png', JText::_('COM_REDSHOP_ALERT_READ'), true);
		JToolBarHelper::custom('unpublish', 'unpublish.png', 'unpublish.png', JText::_('COM_REDSHOP_ALERT_UNREAD'), true);

		$uri = JFactory::getURI();
		$this->state = $this->get('State');

		$optiontype = array();
		$optiontype[] = JHTML::_('select.option', 'select', JText::_('COM_REDSHOP_SELECT'));
		$optiontype[] = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_ALERT_READ'));
		$optiontype[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_ALERT_UNREAD'));

		$lists['read_filter'] = JHTML::_('select.genericlist', $optiontype, 'read_filter',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $this->state->get('read_filter')
		);

		$lists['order'] = $this->state->get('list.ordering');
		$lists['order_Dir'] = $this->state->get('list.direction');
		$alerts = $this->get('Data');
		$pagination = $this->get('Pagination');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->alerts = $alerts;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
