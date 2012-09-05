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

class newslettersubscr_detailVIEWnewslettersubscr_detail extends JView
{
	function display($tpl = null)
	{
		$option = JRequest::getVar('option');
		
		$userlist = JRequest::getVar ('userlist');
		
		JToolBarHelper::title(   JText::_( 'NEWSLETTER_SUBSCR__MANAGEMENT_DETAIL' ), 'redshop_newsletter48' );
		
		$document = & JFactory::getDocument();
		
		$document->addScript ('components/'.$option.'/assets/js/select_sort.js');
		
		$document->addStyleSheet ( 'components/'.$option.'/assets/css/search.css' );
		
		$document->addScript ('components/'.$option.'/assets/js/search.js');
		
		$uri =& JFactory::getURI();
		
		$this->setLayout('default');

		$lists = array();

		$detail	=& $this->get('data');

		$isNew = ($detail->subscription_id < 1);

		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		
		JToolBarHelper::title(   JText::_( 'NEWSLETTER_SUBSCR' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_newsletter48');
		JToolBarHelper::apply();
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		
		$model=  $this->getModel('newslettersubscr_detail');		
		$newsletters=$model->getnewsletters();
		
		$lists['newsletters'] = JHTML::_('select.genericlist',$newsletters,'newsletter_id','class="inputbox" size="1" ','value','text',$detail->newsletter_id);
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );
	
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>