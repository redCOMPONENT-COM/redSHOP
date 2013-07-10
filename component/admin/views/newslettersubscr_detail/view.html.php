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

class newslettersubscr_detailVIEWnewslettersubscr_detail extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$option = JRequest::getVar('option');

		$userlist = JRequest::getVar('userlist');

		JToolBarHelper::title(JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR__MANAGEMENT_DETAIL'), 'redshop_newsletter48');

		$document = JFactory::getDocument();

		$document->addScript('components/' . $option . '/assets/js/select_sort.js');
		$document->addStyleSheet('components/' . $option . '/assets/css/search.css');
		$document->addScript('components/' . $option . '/assets/js/search.js');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->subscription_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_newsletter48');
		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$model = $this->getModel('newslettersubscr_detail');
		$newsletters = $model->getnewsletters();

		$lists['newsletters'] = JHTML::_('select.genericlist', $newsletters, 'newsletter_id',
			'class="inputbox" size="1" ', 'value', 'text', $detail->newsletter_id
		);
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
