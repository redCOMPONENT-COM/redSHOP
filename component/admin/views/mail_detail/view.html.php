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
jimport('joomla.html.pane');

class mail_detailVIEWmail_detail extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_MAIL_MANAGEMENT_DETAIL'), 'redshop_mailcenter48');

		$option = JRequest::getVar('option', '', 'request', 'string');

		$document = JFactory::getDocument();

		$document->addScript('components/' . $option . '/assets/js/json.js');
		$document->addScript('components/' . $option . '/assets/js/validation.js');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->mail_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_MAIL') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_mailcenter48');

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

		$model = $this->getModel('mail_detail');

		if ($detail->mail_section == 'order_status' && $detail->mail_section != '0')
		{
			$order_status = $model->mail_section();
			$select = array();
			$select[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_Select'));
			$merge = array_merge($select, $order_status);

			$lists['order_status'] = JHTML::_('select.genericlist', $merge, 'mail_order_status',
				'class="inputbox" size="1" title="" ', 'value', 'text', $detail->mail_order_status
			);
		}

		$redtemplate = new Redtemplate;
		$optiontype = $redtemplate->getMailSections();
		$lists['type'] = JHTML::_('select.genericlist', $optiontype, 'mail_section',
			'class="inputbox" size="1" onchange="mail_select(this)" ', 'value', 'text', $detail->mail_section
		);

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$pane = JPane::getInstance('sliders');

		$this->pane = $pane;
		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
