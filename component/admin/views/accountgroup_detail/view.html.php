<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class accountgroup_detailVIEWaccountgroup_detail extends JView
{
	function __construct( $config = array())
	{
		parent::__construct( $config );
	}

	function display($tpl = null)
	{	
		$uri =& JFactory::getURI();
		
		JToolBarHelper::save();
		JToolBarHelper::apply();
		
		$lists = array();
		$detail	=& $this->get('data');
		$isNew = ($detail->accountgroup_id < 1);
		
		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );
		if ($isNew)  
		{
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::title( JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP' ).': <small><small>[ '.$text.' ]</small></small>' , 'redshop_accountgroup48');
		
		$lists['published'] = JHTML::_('select.booleanlist','published', 'class="inputbox"', $detail->published );	
		
		$this->assignRef('detail',		$detail);
		$this->assignRef('lists',		$lists);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>