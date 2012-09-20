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
/**
 * split payment Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class split_paymentController extends JController  
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
		
		$user =& JFactory::getUser();
		$model = $this->getModel('split_payment');
	}
	/**
	 * payremaining function
	 *
	 * @access public
	 * @return void
	 */
	function payremaining()
	{  	   
		global $mainframe;
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$task = JRequest::getVar('task');
   		$model = $this->getModel('split_payment');
		 
		$orderresult =  $model->orderplace();
	 
		$view = & $this->getView('split_payment', 'result');
		parent::display();
	}
}