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
 * Order Detail Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class passwordController extends JController  
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	/*
	 *  Metod to reset Password
	 */
	function reset()
	{
		$email=JRequest::getVar('email','request');
		$model = &$this->getModel('password');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		//Request a reset
		if ($model->resetpassword($email) === false)
		{
			$message = JText::sprintf('RESET_PASSWORD_MAIL_ERROR', $model->getError());
			$this->setRedirect('index.php?option='.$option.'&view=password', $message);
			return false;
		}
		else
		{
			$this->setRedirect('index.php?option='.$option.'&view=password&layout=complete');	
		}
	}
	/*
	 *  Method to changepassword
	 */
	function changepassword()
	{
		$model = &$this->getModel('password');
		$token=JRequest::getVar('token');
		$option = JRequest::getVar('option');
		if($model->changepassword($token) === false)
		{
			$message = JText::sprintf('RESET_PASSWORD_TOKEN_ERROR', $model->getError());
			$this->setRedirect('index.php?option='.$option.'&view=password&layout=complete', $message);
			return false;
		}
		else
		{
			parent::display();
		}
	}
	/*
	 *  Method to setpassword
	 */
	function setpassword()
	{
		$password=JRequest::getVar('password');
		$uid=JRequest::getVar('uid');
		$option = JRequest::getVar('option');		
		$model = &$this->getModel('password');
		$model->setpassword($password,$uid);
		$message=JText::_('RESET_PASSWORD_DONE');
		$this->setRedirect('index.php?option='.$option.'&view=login',$message);		
	}
}