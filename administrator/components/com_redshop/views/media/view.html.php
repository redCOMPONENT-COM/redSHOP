<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
//ccc
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.view' );

class mediaViewmedia extends JView
{
	function display($tpl = null)
    {
		global $mainframe, $context;

		$document = JFactory::getDocument();
		$document->setTitle ( JText::_('COM_REDSHOP_MEDIA' ) );
		//$document->addStyleSheet('assets/medialist-thumbs.css');
		$document->addStyleSheet( JURI::root().'administrator/components/com_redshop/assets/css/medialist-thumbs.css' );

		JToolBarHelper::title ( JText::_('COM_REDSHOP_MEDIA_MANAGEMENT' ), 'redshop_media48' );


		JToolBarHelper::addNewX ();
		JToolBarHelper::editListX ();
		JToolBarHelper::deleteList ();
		JToolBarHelper::publishList ();
		JToolBarHelper::unpublishList ();

		$uri = JFactory::getURI ();
		$context = 'media';
		$filter_order = $mainframe->getUserStateFromRequest ( $context . 'filter_order', 'filter_order', 'media_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( $context . 'filter_order_Dir', 'filter_order_Dir', '' );

		$media_type = $mainframe->getUserStateFromRequest( $context.'media_type','media_type',0 );
		$media_section = $mainframe->getUserStateFromRequest( $context.'media_section','media_section',0 );


		$optiontype = array();
		$optiontype[]   = JHTML::_('select.option', '0',JText::_('COM_REDSHOP_SELECT'));
		$optiontype[]   = JHTML::_('select.option', 'images', JText::_('COM_REDSHOP_IMAGE'));
		$optiontype[]   = JHTML::_('select.option', 'video', JText::_('COM_REDSHOP_VIDEO'));
		$optiontype[]   = JHTML::_('select.option', 'document', JText::_('COM_REDSHOP_DOCUMENT'));
		$optiontype[]   = JHTML::_('select.option', 'download', JText::_('COM_REDSHOP_DOWNLOAD'));

		$optionsection = array();
		$optionsection[]   = JHTML::_('select.option', '0',JText::_('COM_REDSHOP_SELECT'));
		$optionsection[]   = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT'));
		$optionsection[]   = JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_CATEGORY'));
		$optionsection[]   = JHTML::_('select.option', 'catalog', JText::_('COM_REDSHOP_CATALOG'));
		$optionsection[]   = JHTML::_('select.option', 'media', JText::_('COM_REDSHOP_MEDIA'));
		$optionsection[]   = JHTML::_('select.option', 'property', JText::_('COM_REDSHOP_PROPERTY'));
		$optionsection[]   = JHTML::_('select.option', 'subproperty', JText::_('COM_REDSHOP_SUBPROPERTY'));
		$optionsection[]   = JHTML::_('select.option', 'manufacturer', JText::_('COM_REDSHOP_MANUFACTURER'));



		$lists ['order'] = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;

		$lists['type'] 		= JHTML::_('select.genericlist',$optiontype,  'media_type', 'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text',  $media_type );

		$lists['section'] 	= JHTML::_('select.genericlist',$optionsection,  'media_section', 'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text',  $media_section );

		$media = $this->get ( 'Data' );
		//$total = $this->get ( 'Total' );
		$pagination = $this->get ( 'Pagination' );

		$this->assignRef ( 'lists', $lists );
		$this->assignRef ( 'media', $media );
		$this->assignRef ( 'pagination', $pagination);

		//$this->assignRef ( 'request_url', $uri->toString() );
        $this->request_url = $uri->toString();

		//$this->assign('baseURL', JURI::root());
        $this->baseURL = JURI::root();

		//$this->assignRef('images', $this->get('images'));
        $this->images = $this->get('images');

		//$this->assignRef('documents', $this->get('documents'));
        $this->documents = $this->get('documents');

		//$this->assignRef('folders', $this->get('folders'));
        $this->folders = $this->get('folders');

		//$this->assignRef('state', $this->get('state'));
        $this->state = $this->get('state');

		parent::display ( $tpl );
	}
	function setFolder($index = 0)
	{
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = $this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}

	function setImage($index = 0)
	{
		if (isset($this->images[$index])) {
			$this->_tmp_img = $this->images[$index];
		} else {
			$this->_tmp_img = new JObject;
		}
	}

	function setDoc($index = 0)
	{
		if (isset($this->documents[$index])) {
			$this->_tmp_doc = $this->documents[$index];
		} else {
			$this->_tmp_doc = new JObject;
		}
	}
}
