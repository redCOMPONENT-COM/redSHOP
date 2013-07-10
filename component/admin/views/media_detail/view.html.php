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

class media_detailVIEWmedia_detail extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$option = JRequest::getVar('option', '', 'request', 'string');

		JToolBarHelper::title(JText::_('COM_REDSHOP_MEDIAS_MANAGEMENT_DETAIL'), 'redshop_media48');

		$document = JFactory::getDocument();

		$document->addScript('components/' . $option . '/assets/js/media.js');
		$document->addStyleSheet('components/' . $option . '/assets/css/search.css');
		$document->addScript('components/' . $option . '/assets/js/search.js');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');
		$model = $this->getModel('media_detail');

		$isNew = ($detail->media_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_MEDIAS') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_media48');

		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$media_section = JRequest::getVar('media_section');
		$showbuttons = JRequest::getVar('showbuttons');

		$optiontype = array();
		$optiontype[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optiontype[] = JHTML::_('select.option', 'images', JText::_('COM_REDSHOP_Image'));
		$optiontype[] = JHTML::_('select.option', 'video', JText::_('COM_REDSHOP_Video'));
		$optiontype[] = JHTML::_('select.option', 'document', JText::_('COM_REDSHOP_Document'));

		if ($media_section == 'product' && $showbuttons == 1)
		{
			$optiontype[] = JHTML::_('select.option', 'download', JText::_('COM_REDSHOP_Download'));
		}

		$optionsection = array();
		$optionsection[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optionsection[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_Product'));
		$optionsection[] = JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_Category'));
		$optionsection[] = JHTML::_('select.option', 'catalog', JText::_('COM_REDSHOP_Catalog'));
		$optionsection[] = JHTML::_('select.option', 'media', JText::_('COM_REDSHOP_Media'));

		$optionbulk = array();
		$optionbulk[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optionbulk[] = JHTML::_('select.option', 'yes', JText::_('COM_REDSHOP_YES_ZIP_UPLOAD'));
		$optionbulk[] = JHTML::_('select.option', 'no', JText::_('COM_REDSHOP_NO_ZIP_UPLOAD'));

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$section_id = JRequest::getVar('section_id');
		$section_name = JRequest::getVar('section_name');
		$media_section = JRequest::getVar('media_section');

		if ($media_section == 'catalog')
		{
			$detail->media_type = 'document';
			$detail->media_section = $media_section;
			$detail->section_name = $section_name;
			$detail->section_id = $section_id;
		}

		$lists['type'] = JHTML::_('select.genericlist', $optiontype, 'media_type', 'class="inputbox" size="1" ', 'value', 'text', $detail->media_type, '0');

		if ($detail->media_id == 0)
		{
			$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'media_section',
				'class="inputbox" size="1" style="width:100px;" onchange="select_type(this)" title="' . $option . '"',
				'value', 'text', $detail->media_section, '0'
			);
		}
		else
		{
			$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'media_section',
				'class="inputbox" size="1" style="width:100px;" disabled="disabled" onchange="select_type(this)" title="' . $option . '"',
				'value', 'text', $detail->media_section, '0'
			);
		}

		$lists['bulk'] = JHTML::_('select.genericlist', $optionbulk, 'bulk',
			'class="inputbox" size="1" onchange="media_bulk(this)" title="' . $option . '" ',
			'value', 'text', 'no'
		);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
