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
 * send friend Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class send_friendController extends JController  
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	/**
	 * sendmail function
	 *
	 * @access public
	 * @return void
	 */
	function sendmail()
	{
		$post = JRequest::get('post');
		$your_name = $post['your_name'];
		$name = $post['friends_name'];
		$pid  = $post['pid'];
		$email= $post['friends_email'];
		 
		$model = $this->getModel('send_friend');
		
		$model->sendProductMailToFriend($your_name,$name,$pid,$email);
	}	
}
?>