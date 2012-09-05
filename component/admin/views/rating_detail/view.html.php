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

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.view' );

class rating_detailVIEWrating_detail extends JView
{
	function display($tpl = null)
	{
		$option = JRequest::getVar('option');
		$userslist = JRequest::getVar ( 'userslist' );
		
		JToolBarHelper::title(   JText::_( 'RATING_MANAGEMENT_DETAIL' ), 'redshop_rating48' );
		
		$document = & JFactory::getDocument();
				
		$document->addStyleSheet ( 'components/'.$option.'/assets/css/search.css' );
		
		$document->addScript ('components/'.$option.'/assets/js/search.js');
		
		$uri 		=& JFactory::getURI();
		
		$this->setLayout('default');

		$lists = array();

		$detail	=& $this->get('data');

		$isNew		= ($detail->rating_id < 1);

		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		
		JToolBarHelper::title(   JText::_( 'RATING' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_rating48' );
		
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		
		$model=  $this->getModel('rating_detail');		
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );
		$lists['favoured'] = JHTML::_('select.booleanlist',  'favoured', 'class="inputbox"', $detail->favoured );

		$lists['userslist'] = JHTML::_('select.genericlist',$userslist,'userid','class="inputbox" size="1" ','value','text',$detail->userid);
		
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
	
}

?>
