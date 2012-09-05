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
 * Product Mini Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class product_miniController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}	
	/**
	 * cancel function
	 *
	 * @access public
	 * @return void
	 */
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}
	/**
	 * logic for display
	 *
	 * @access public
	 * @return void
	 */
	function display() {
		parent::display();
	}
}