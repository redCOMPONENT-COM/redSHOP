<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class state_detailVIEWstate_detail extends JView
{
	function __construct( $config = array())
	{
		parent::__construct( $config );

	}
	function display($tpl = null)
	{
		$document = & JFactory::getDocument();
		JToolBarHelper::title(   JText::_( 'STATE_DETAIL' ), 'redshop_region_48' );
		$uri =& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model = $this->getModel('state_detail');

		JToolBarHelper::save();
		JToolBarHelper::apply();
		$lists = array();
		$detail	=& $this->get('data');
		$isNew = ($detail->state_id < 1);

		// 	fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'THE DETAIL' ), $detail->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		$db = jFactory::getDBO();
		JToolBarHelper::title(   JText::_( 'STATE' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_region_48' );
		require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'helper.php' );
  		$redhelper = new redhelper();
		$q = "SELECT  country_id as value,country_name as text,country_jtext from #__".TABLE_PREFIX."_country ORDER BY country_name ASC";
  		$db->setQuery($q);
 	 	$countries = $db->loadObjectList( );
  		$countries = $redhelper->convertLanguageString($countries);

		$temps[0]->value="0";
		$temps[0]->text=JText::_('SELECT');
		$countries=@array_merge($temps,$countries);
		$country_list = explode(',',COUNTRY_LIST);

		$tmp = new stdClass;
		$tmp = @array_merge($tmp,$country_list);



		$lists['country_id'] 	= JHTML::_('select.genericlist',   $countries, 'country_id', 'class="inputbox" size="1" ', 'value', 'text', $detail->country_id );

		$state_data= $redhelper->getStateAbbrivationByList();

		$lists['show_state'] 	= JHTML::_('select.genericlist',   $state_data, 'show_state', 'class="inputbox" size="1" ', 'value', 'text', $detail->show_state );



		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {

		 	//EDIT - check out the item
			$model->checkout( $user->get('id') );

			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::title(   JText::_( 'state' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_region_48' );


		$this->assignRef('detail',		$detail);
		$this->assignRef('lists',		$lists);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>