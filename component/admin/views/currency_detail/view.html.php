<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class currency_detailVIEWcurrency_detail extends JView
{
	function __construct( $config = array())
	{
		parent::__construct( $config );
		 
	}
	function display($tpl = null)
	{	
		$db = jFactory::getDBO();
		$uri =& JFactory::getURI();
		$lists = array();
		$detail	=& $this->get('data');
		$isNew = ($detail->currency_id < 1);
		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		
		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::title( JText::_( 'CURRENCY_MANAGEMENT' ).': <small><small>[ '.$text.' ]</small></small>' , 'redshop_currencies_48');
		
		//Start Code for country list
		$query =  " SELECT distinct(c.country_id),c.*  FROM #__redshop_country c ";
		$db->setQuery($query);
		$result= $db->loadObjectlist();		
		foreach($result as $key=>$value) 
		{
			$options[] = JHTML::_('select.option',$value->country_id,JText::_($value->country_name));
		}
		if (strstr($detail->dynamic_country_id,",")){
			$dynamic_country_id = explode(",",$detail->dynamic_country_id);
		}else {
			$dynamic_country_id = $detail->dynamic_country_id;
		}		
		$lists['dynamic_country_id'] = JHTML::_('select.genericlist', $options,'dynamic_country_id[]', 'class="inputbox"  multiple="multiple"','value','text', $dynamic_country_id);
		//End Code for country list
		
		$this->assignRef('detail',		$detail);
		$this->assignRef('lists',		$lists);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>