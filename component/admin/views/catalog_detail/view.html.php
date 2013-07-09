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

class catalog_detailVIEWcatalog_detail extends JView
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

		JToolBarHelper::title(JText::_('COM_REDSHOP_CATALOG_MANAGEMENT_DETAIL'), 'redshop_catalogmanagement48');

		$document = JFactory::getDocument();

		$document->addStyleSheet('components/' . $option . '/assets/css/colorpicker.css');
		$document->addStyleSheet('components/' . $option . '/assets/css/layout.css');
		$document->addScript('components/' . $option . '/assets/js/validation.js');

		$document->addScript('components/' . $option . '/assets/js/jquery.js');
		$document->addScript('components/' . $option . '/assets/js/colorpicker.js');

		$document->addScript('components/' . $option . '/assets/js/eye.js');
		$document->addScript('components/' . $option . '/assets/js/utils.js');
		$document->addScript('components/' . $option . '/assets/js/layout.js?ver=1.0.2');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$layout = JRequest::getVar('layout', 'default');

		$this->setLayout($layout);

		$isNew = ($detail->catalog_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_CATALOG') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_catalogmanagement48');

		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
