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

include_once (JPATH_COMPONENT.DS.'helpers'.DS.'user.php');
/**
 * newsletter Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class newsletterController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	/*
	 *  Method to subscribe newsletter
	 */
	function subscribe()
	{
		$post = JRequest::get('post');
		$model = $this->getModel('newsletter');

		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$newsletteritemid = JRequest::getVar('newsletteritemid');
		$menu =& JSite::getMenu();
		$item = $menu->getItem($newsletteritemid);
		if($item) {
			$return = $item->link.'&Itemid='.$newsletteritemid;
		} else {
			$return = "index.php?option=com_redshop&view=newsletter&layout=thankyou&Itemid=".$Itemid;
		}

	 	/*
	 	 *  check if user has alreday subscribe.
	 	 */
		$alreadysubscriberbymail = $model->checksubscriptionbymail($post['email1']);

		if($alreadysubscriberbymail)
		{
			$msg=JText::_('ALREADY_NEWSLETTER_SUBSCRIBER');
		}
		else
		{
			$userhelper = new rsUserhelper();
			if($userhelper->newsletterSubscribe(0,$post,1))
			{
				if(NEWSLETTER_CONFIRMATION)
					$msg=JText::_('SUBSCRIBE_SUCCESS');
				else
					$msg=JText::_('NEWSLEETER_SUBSCRIBE_SUCCESS');
			}
			else
			{
				$msg=JText::_('NEWSLEETER_SUBSCRIBE_FAIL');
			}
		}
		$this->setRedirect($return,$msg);
	}
	/*
	 *  Method to unsubscribe newsletter
	 */
	function unsubscribe()
	{
		$post = JRequest::get('get');
		$model = $this->getModel('newsletter');

		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$email = JRequest::getVar('email1');
		$newsletteritemid = JRequest::getVar('newsletteritemid');
		$menu =& JSite::getMenu();
		$item = $menu->getItem($newsletteritemid);
		if($item) {
			$return = $item->link.'&Itemid='.$newsletteritemid;
		} else {
			$return = "index.php?option=com_redshop&view=newsletter&layout=thankyou&Itemid=".$Itemid;
		}

		/*
 	 	 *  check if user has subscribe or not.
 	 	 */
		$alreadysubscriberbymail = $model->checksubscriptionbymail($email);
		if($alreadysubscriberbymail)
		{
			$userhelper = new rsUserhelper();
			if($userhelper->newsletterUnsubscribe($email))
			{
				$msg=JText::_('CANCLE_SUBSCRIPTION');
			}
			else
			{
				$msg=JText::_('CANCLE_SUBSCRIPTION_FAIL');
			}
		}else{
			$msg=JText::_('ALREADY_CANCLE_SUBSCRIPTION');
		}
		$this->setRedirect($return,$msg);
	}
}?>