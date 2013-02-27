<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );

class countryViewcountry extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
		 
	}
    
	function display($tpl = null)
	{	
		//$document = & JFactory::getDocument();
		$document = & JFactory::getDocument ();
		$document->setTitle ( JText::_('COM_REDSHOP_COUNTRY' ) );
		
		JToolBarHelper::title ( JText::_('COM_REDSHOP_COUNTRY_MANAGEMENT' ), 'redshop_country_48' );
		jimport('joomla.html.pagination');
		global $mainframe, $context;
		$context = 'country_id';
		JToolbarHelper::addNewX();
		JToolbarHelper::EditListX();
		JToolbarHelper::deleteList();
		$uri =& JFactory::getURI();
		
		
	$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'country_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		$lists['order'] = $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir;
		
		$fields			= & $this->get( 'Data');
		$total = & $this->get( 'Total');	
		$pagination = & $this->get('Pagination');
	
		$this->assignRef('user',		JFactory::getUser());	
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('fields',		$fields); 		
		$this->assignRef('lists',		$lists);  
  		$this->assignRef('request_url',	$uri->toString());    	
    	parent::display($tpl);
		
  }
 
}
?>