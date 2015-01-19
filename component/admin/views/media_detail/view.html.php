<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewMedia_detail extends RedshopView
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

		$document = JFactory::getDocument();

		$document->addScript('components/com_redshop/assets/js/media.js');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');
		$model = $this->getModel('media_detail');

		$isNew = ($detail->media_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_MEDIAS') . ': <small><small>[ ' . $text . ' ]</small></small>', 'camera redshop_media48');

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
		$optiontype[] = JHTML::_('select.option', 'images', JText::_('COM_REDSHOP_IMAGE'));
		$optiontype[] = JHTML::_('select.option', 'video', JText::_('COM_REDSHOP_VIDEO'));
		$optiontype[] = JHTML::_('select.option', 'document', JText::_('COM_REDSHOP_DOCUMENT'));

		if ($media_section == 'product' && $showbuttons == 1)
		{
			$optiontype[] = JHTML::_('select.option', 'download', JText::_('COM_REDSHOP_Download'));
		}

		$optionsection = array();
		$optionsection[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT'));
		$optionsection[] = JHTML::_('select.option', 'property', JText::_('COM_REDSHOP_PROPERTY'));
		$optionsection[] = JHTML::_('select.option', 'subproperty', JText::_('COM_REDSHOP_SUBPROPERTY'));
		$optionsection[] = JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_CATEGORY'));
		$optionsection[] = JHTML::_('select.option', 'catalog', JText::_('COM_REDSHOP_CATALOG'));

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

		if ($detail->media_id == 0 && !$showbuttons)
		{
			$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'media_section',
				'', 'value', 'text'
			);
		}
		else
		{
			$defaultMedia = ($showbuttons) ? $media_section : $detail->media_section;
			$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'disable_media_section',
				' disabled="disabled" ',
				'value', 'text', $defaultMedia
			);
		}

		$lists['bulk'] = JHTML::_('select.genericlist', $optionbulk, 'bulk',
			'class="inputbox" size="1" onchange="media_bulk(this)" title="com_redshop" ',
			'value', 'text', 'no'
		);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
