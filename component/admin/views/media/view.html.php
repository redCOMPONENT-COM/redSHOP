<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

class mediaViewmedia extends JView
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
		$context = 'media';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_MEDIA'));
		$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/medialist-thumbs.css');

		JToolBarHelper::title(JText::_('COM_REDSHOP_MEDIA_MANAGEMENT'), 'redshop_media48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'media_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$media_type       = $app->getUserStateFromRequest($context . 'media_type', 'media_type', 0);
		$media_section    = $app->getUserStateFromRequest($context . 'media_section', 'media_section', 0);

		$optiontype = array();
		$optiontype[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optiontype[] = JHTML::_('select.option', 'images', JText::_('COM_REDSHOP_IMAGE'));
		$optiontype[] = JHTML::_('select.option', 'video', JText::_('COM_REDSHOP_VIDEO'));
		$optiontype[] = JHTML::_('select.option', 'document', JText::_('COM_REDSHOP_DOCUMENT'));
		$optiontype[] = JHTML::_('select.option', 'download', JText::_('COM_REDSHOP_DOWNLOAD'));

		$optionsection = array();
		$optionsection[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optionsection[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT'));
		$optionsection[] = JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_CATEGORY'));
		$optionsection[] = JHTML::_('select.option', 'catalog', JText::_('COM_REDSHOP_CATALOG'));
		$optionsection[] = JHTML::_('select.option', 'media', JText::_('COM_REDSHOP_MEDIA'));
		$optionsection[] = JHTML::_('select.option', 'property', JText::_('COM_REDSHOP_PROPERTY'));
		$optionsection[] = JHTML::_('select.option', 'subproperty', JText::_('COM_REDSHOP_SUBPROPERTY'));
		$optionsection[] = JHTML::_('select.option', 'manufacturer', JText::_('COM_REDSHOP_MANUFACTURER'));

		$lists ['order'] = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;

		$lists['type'] = JHTML::_('select.genericlist', $optiontype, 'media_type',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $media_type
		);

		$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'media_section',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $media_section
		);

		$media = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		$this->lists = $lists;
		$this->media = $media;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		$this->assign('baseURL', JURI::root());
		$this->images = $this->get('images');
		$this->documents = $this->get('documents');
		$this->folders = $this->get('folders');
		$this->state = $this->get('state');

		parent::display($tpl);
	}

	public function setFolder($index = 0)
	{
		if (isset($this->folders[$index]))
		{
			$this->_tmp_folder = & $this->folders[$index];
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
			$this->_tmp_img = & $this->images[$index];
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
			$this->_tmp_doc = & $this->documents[$index];
		}
		else
		{
			$this->_tmp_doc = new JObject;
		}
	}
}
