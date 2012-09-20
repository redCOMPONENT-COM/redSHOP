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


jimport( 'joomla.application.component.controller' );
 
class categoryController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}	
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}
	function display() {
		
		parent::display();
	}
	
	/*
	 * assign template to multiple categories
	 * 
	 */
	function assignTemplate(){
		
		$post = JRequest::get('post');
		
		$model = $this->getModel('category');
		
		if($model->assignTemplate($post)){
			$msg = JText::_('COM_REDSHOP_TEMPLATE_ASSIGN_SUCESS');
		}else {
			$msg = JText::_('COM_REDSHOP_ERROR_ASSIGNING_TEMPLATE');
		}
		$this->setRedirect( 'index.php?option=com_redshop&view=category',$msg );
	}
	function saveorder()
	{
		$option = JRequest::getVar('option');

		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('category');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );

	}
	
	function autofillcityname()
	{
		$db = JFactory::getDBO();
		ob_clean();
		$mainzipcode = JRequest::getString( 'q', '');
		$sel_zipcode="select city_name from #__redshop_zipcode where zipcode='".$mainzipcode."'";
		$db->setQuery($sel_zipcode);
	    echo $db->loadResult();
		exit;
	}
}	

