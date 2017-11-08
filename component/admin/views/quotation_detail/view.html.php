<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopViewQuotation_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		$quotationHelper = quotationHelper::getInstance();

		$layout = JFactory::getApplication()->input->getCmd('layout', 'default');

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_QUOTATION'));

		$document->addScript(JURI::base() . 'components/com_redshop/assets/js/order.js');
		$document->addScript(JURI::base() . 'components/com_redshop/assets/js/common.js');
		$document->addScript('components/com_redshop/assets/js/json.js');

		$uri = JFactory::getURI();
		$lists = array();

		if ($layout != 'default')
		{
			$this->setLayout($layout);
		}

		$detail = $this->get('data');
		$isNew = ($detail->quotation_id < 1);
		$userarr = $this->get('userdata');

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_QUOTATION_DETAIL') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_quotation48');
		JToolBarHelper::apply();
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

		$status = $quotationHelper->getQuotationStatusList();
		$lists['quotation_status'] = JHTML::_('select.genericlist', $status, 'quotation_status',
			'class="inputbox" size="1" ', 'value', 'text', $detail->quotation_status
		);

		$this->lists = $lists;
		$this->quotation = $detail;
		$this->quotationuser = $userarr;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
