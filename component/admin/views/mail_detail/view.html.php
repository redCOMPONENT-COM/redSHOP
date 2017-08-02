<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopViewMail_detail extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		$document = JFactory::getDocument();

		$document->addScript('components/com_redshop/assets/js/json.js');
		$document->addScript('components/com_redshop/assets/js/validation.js');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->mail_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_MAIL') . ': <small><small>[ ' . $text . ' ]</small></small>', 'envelope-opened redshop_mailcenter48');

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

		$redtemplate = Redtemplate::getInstance();
		$optiontype = $redtemplate->getMailSections();
		$lists['type'] = JHTML::_('select.genericlist', $optiontype, 'mail_section',
			'class="inputbox" size="1" onchange="mail_select(this)" ', 'value', 'text', $detail->mail_section
		);

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
