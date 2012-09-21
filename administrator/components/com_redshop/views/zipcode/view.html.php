<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );

class zipcodeViewzipcode extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $context;
		$context = 'zipcode_id';
		$document = JFactory::getDocument ();
		$document->setTitle ( JText::_('COM_REDSHOP_ZIPCODE' ) );

		JToolBarHelper::title ( JText::_('COM_REDSHOP_ZIPCODE_MANAGEMENT' ), 'redshop_region_48' );

		jimport('joomla.html.pagination');
		JToolbarHelper::addNewX();
		JToolbarHelper::EditListX();
		JToolbarHelper::deleteList();

		$uri = JFactory::getURI();

		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'zipcode_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$fields			= $this->get( 'Data');
		$pagination = $this->get('Pagination');
		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('fields',		$fields);
		$this->assignRef('lists',		$lists);
  		$this->assignRef('request_url',	$uri->toString());

    	parent::display($tpl);
    }

}

