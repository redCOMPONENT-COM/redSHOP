<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );

class currencyViewcurrency extends JView
{
    function display($tpl = null)
	{
		//$document = & JFactory::getDocument();
		global $mainframe, $context;
		$context = 'currency_id';
		$document = JFactory::getDocument ();
		$document->setTitle ( JText::_('COM_REDSHOP_CURRENCY' ) );

		JToolBarHelper::title ( JText::_('COM_REDSHOP_CURRENCY_MANAGEMENT' ), 'redshop_currencies_48' );
		jimport('joomla.html.pagination');
		JToolbarHelper::addNewX();
		JToolbarHelper::EditListX();
		JToolbarHelper::deleteList();
		$uri = JFactory::getURI();


	$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'currency_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$fields			= $this->get( 'Data');
		$pagination = $this->get('Pagination');

		//$this->assignRef('user',		JFactory::getUser());
        $this->user = JFactory::getUser();
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('fields',		$fields);
		$this->assignRef('lists',		$lists);
  		//$this->assignRef('request_url',	$uri->toString());
        $this->request_url = $uri->toString();
    	parent::display($tpl);

  }

}
