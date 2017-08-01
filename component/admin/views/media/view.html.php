<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewMedia extends RedshopViewAdmin
{
	public $images;

	public $documents;

	public $folders;

	public $state;

	/**
	 * The pagination object.
	 *
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/medialist-thumbs.css');

		JToolBarHelper::title(JText::_('COM_REDSHOP_MEDIA_MANAGEMENT'), 'camera redshop_media48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$this->state = $this->get('State');

		$media_type       = $this->state->get('media_type', 0);
		$filter_media_section    = $this->state->get('filter_media_section', 0);

		$optiontype = array();
		$optiontype[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optiontype[] = JHTML::_('select.option', 'images', JText::_('COM_REDSHOP_IMAGE'));
		$optiontype[] = JHTML::_('select.option', 'video', JText::_('COM_REDSHOP_VIDEO'));
		$optiontype[] = JHTML::_('select.option', 'document', JText::_('COM_REDSHOP_DOCUMENT'));
		$optiontype[] = JHTML::_('select.option', 'download', JText::_('COM_REDSHOP_DOWNLOAD'));
		$optiontype[] = JHTML::_('select.option', 'youtube', JText::_('COM_REDSHOP_YOUTUBE'));

		$optionsection = array();
		$optionsection[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optionsection[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT'));
		$optionsection[] = JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_CATEGORY'));
		$optionsection[] = JHTML::_('select.option', 'catalog', JText::_('COM_REDSHOP_CATALOG'));
		$optionsection[] = JHTML::_('select.option', 'media', JText::_('COM_REDSHOP_MEDIA'));
		$optionsection[] = JHTML::_('select.option', 'property', JText::_('COM_REDSHOP_PROPERTY'));
		$optionsection[] = JHTML::_('select.option', 'subproperty', JText::_('COM_REDSHOP_SUBPROPERTY'));
		$optionsection[] = JHTML::_('select.option', 'manufacturer', JText::_('COM_REDSHOP_MANUFACTURER'));

		$lists ['order'] = $this->state->get('list.ordering', 'media_id');
		$lists ['order_Dir'] = $this->state->get('list.direction', '');

		$lists['type'] = JHTML::_('select.genericlist', $optiontype, 'media_type',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $media_type
		);

		$lists['filter_media_section'] = JHTML::_('select.genericlist', $optionsection, 'filter_media_section',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $filter_media_section
		);

		$media = $this->get('Data');
		$pagination = $this->get('Pagination');

		$this->lists = $lists;
		$this->media = $media;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		$this->assign('baseURL', JURI::root());

		if (JFactory::getApplication()->input->get('layout') == 'thumbs')
		{
			$this->images = $this->get('images');
			$this->documents = $this->get('documents');
			$this->folders = $this->get('folders');
		}

		parent::display($tpl);
	}

	public function setFolder($index = 0)
	{
		if (isset($this->folders[$index]))
		{
			$this->_tmp_folder = $this->folders[$index];
		}
		else
		{
			$this->_tmp_folder = new JObject;
		}
	}

	public function setImage($index = 0)
	{
		if (isset($this->images[$index]))
		{
			$this->_tmp_img = $this->images[$index];
		}
		else
		{
			$this->_tmp_img = new JObject;
		}
	}

	public function setDoc($index = 0)
	{
		if (isset($this->documents[$index]))
		{
			$this->_tmp_doc = $this->documents[$index];
		}
		else
		{
			$this->_tmp_doc = new JObject;
		}
	}
}
