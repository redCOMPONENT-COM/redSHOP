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
 * Account Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class accountController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	/**
	 * Method to edit created Tag
	 *
	 */
	function editTag()
	{
		global $mainframe;
		$Itemid = JRequest::getVar('Itemid');
		$option = JRequest::getVar ('option');

		$post = JRequest::get('post');

		$model = $this->getModel('account');

		if ($model->editTag($post))
		{
			$mainframe->enqueueMessage(JText::_('TAG_EDITED_SUCCESSFULLY'));
		}else {
			$mainframe->enqueueMessage(JText::_('ERROR_EDITING_TAG'));
		}

		$this->setRedirect ( 'index.php?option=' . $option . '&view=account&layout=mytags&Itemid='.$Itemid);
	}

	/**
	 * Method to send created wishlist
	 *
	 */
	function sendWishlist(){

		$post = JRequest::get('post');

		$emailto = $post['emailto'];
		$sender = $post['sender'];
		$email = $post['email'];
		$subject = $post['subject'];
		$Itemid = $post['Itemid'];
		$wishlis_id = $post['wishlist_id'];
		$model = $this->getModel('account');

		if ($emailto == ""){
			$msg = JText::_('PLEASE_ENTER_EMAIL_TO');
		}else if ($sender == ""){
			$msg = JText::_('PLEASE_ENTER_SENDER_NAME');
		}else if ($email == ""){
			$msg = JText::_('PLEASE_ENTER_SENDER_EMAIL');
		}else if ($subject == ""){
			$msg = JText::_('PLEASE_ENTER_SUBJECT');
		}else if ($model->sendWishlist($post)){
			$msg = JText::_('SEND_SUCCESSFULLY');
		}else {
			$msg = JText::_('ERROR_SENDING');
		}

		$this->setRedirect ( 'index.php?option=com_redshop&view=account&layout=mywishlist&mail=0&window=1&tmpl=component&wishlist_id='.$wishlis_id.'&Itemid'.$Itemid,$msg);
	}
	
	/*
	 *  Method to subscribe newsletter
	 */
	function newsletterSubscribe()
	{
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');

	 	$userhelper = new rsUserhelper();
		$userhelper->newsletterSubscribe(0,array(),1);
		
		$msg=JText::_('SUBSCRIBE_SUCCESS');
		$this->setRedirect("index.php?option=".$option."&view=account&Itemid=".$Itemid,$msg);
	}
	/*
	 *  Method to unsubscribe newsletter
	 */
	function newsletterUnsubscribe()
	{
		$user =& JFactory::getUser();
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');

		$userhelper = new rsUserhelper();
		$userhelper->newsletterUnsubscribe($user->email);
		$msg=JText::_('CANCLE_SUBSCRIPTION');
		
		$this->setRedirect("index.php?option=".$option."&view=account&Itemid=".$Itemid,$msg);
	}
}?>