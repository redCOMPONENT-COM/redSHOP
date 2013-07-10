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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/quotation.php';

class quotation_detailVIEWquotation_detail extends JView
{
	public function display($tpl = null)
	{
		$quotationHelper = new quotationHelper;
		$option = JRequest::getVar('option');
		$layout = JRequest::getVar('layout', 'default');

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_QUOTATION'));

		$document->addScript(JURI::base() . 'components/' . $option . '/assets/js/order.js');
		$document->addScript(JURI::base() . 'components/' . $option . '/assets/js/common.js');
		$document->addStyleSheet(JURI::base() . 'components/' . $option . '/assets/css/search.css');
		$document->addScript(JURI::base() . 'components/' . $option . '/assets/js/search.js');
		$document->addScript(JURI::base() . 'components/' . $option . '/assets/js/json.js');

		$uri = JFactory::getURI();
		$lists = array();
		$model = $this->getModel();

		if ($layout != 'default')
		{
			$this->setLayout($layout);
		}
		$detail = $this->get('data');
		$redconfig = new Redconfiguration;

		$isNew = ($detail->quotation_id < 1);
		$userarr = $this->get('userdata');

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_QUOTATION_DETAIL') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_quotation48');
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
