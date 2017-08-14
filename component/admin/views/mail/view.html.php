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
 * View Mail
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewMail extends RedshopViewForm
{
	/**
	 * Split fieldset in form into column
	 *
	 * @var   integer
	 * @since __DEPLOY_VERSION__
	 */
	public $formFieldsetsColumn = 1;

	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_MAIL_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
	}
}



/*class RedshopViewMail extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_MAIL'));
		jimport('joomla.html.pagination');

		JToolBarHelper::title(JText::_('COM_REDSHOP_MAIL_MANAGEMENT'), 'envelope redshop_mailcenter48');

		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$state = $this->get('State');
		$lists['order'] = $state->get('list.ordering', 'm.mail_id');
		$lists['order_Dir'] = $state->get('list.direction');
		$lists['filter'] = $state->get('filter');

		$redtemplate = Redtemplate::getInstance();
		$optionsection = $redtemplate->getMailSections();
		$lists['mailsection'] = JHTML::_('select.genericlist', $optionsection, 'filter_section',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"',
			'value', 'text', $state->get('filter_section')
		);

		$media = $this->get('Data');

		$pagination = $this->get('Pagination');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->media = $media;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}*/
