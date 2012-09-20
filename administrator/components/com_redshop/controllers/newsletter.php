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

class newsletterController extends JController
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
	function send_newsletter_preview()
	{
		$view = & $this->getView('newsletter', 'preview');

		parent::display();
	}

	function send_newsletter()
	{
		$session =& JFactory::getSession();
		$option = JRequest::getVar('option');

		$cid 	= JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$userid = JRequest::getVar ( 'userid', array (0 ), 'post', 'array' );
		$username = JRequest::getVar ( 'username', array (0 ), 'post', 'array' );

		$newsletter_id = JRequest::getVar('newsletter_id');

		$tmpcid = array_chunk($cid,NEWSLETTER_MAIL_CHUNK);//NEWSLETTER_MAIL_CHUNK
		$tmpuserid = array_chunk($userid,NEWSLETTER_MAIL_CHUNK);
		$tmpusername = array_chunk($username,NEWSLETTER_MAIL_CHUNK);

		$session->set( 'subscribers',$tmpcid );
		$session->set( 'subscribersuid',$tmpuserid );
		$session->set( 'subscribersuname',$tmpusername );
		$session->set( 'incNo',1 );

		$this->setRedirect ( 'index.php?option='.$option.'&view=newsletter&layout=previewlog&newsletter_id='.$newsletter_id);
		return;
	}

	function sendRecursiveNewsletter()
	{
		$session =& JFactory::getSession();
		$newsletter_id = JRequest::getVar('newsletter_id');
		$option = JRequest::getVar('option');

		$model = $this->getModel ('newsletter');

		$subscribers = $session->get( 'subscribers');
		$subscribersuid = $session->get( 'subscribersuid');
		$subscribersuname = $session->get( 'subscribersuname');
		$incNo = $session->get( 'incNo');

		$cid = array();
		$user_id = array();
		$username = array();
		if(count($subscribers)>0)
		{
			$cid = $subscribers[0];
			unset($subscribers[0]);
			$subscribers = array_merge(array(),$subscribers);
		}
		if(count($subscribersuid)>0)
		{
			$user_id = $subscribersuid[0];
			unset($subscribersuid[0]);
			$subscribersuid = array_merge(array(),$subscribersuid);
		}
		if(count($subscribersuname)>0)
		{
			$username = $subscribersuname[0];
			unset($subscribersuname[0]);
			$subscribersuname = array_merge(array(),$subscribersuname);
		}

		$retuser = $model->newsletterEntry($cid,$user_id,$username);

		$responcemsg = "";
		for($i=0;$i<count($cid);$i++)
		{
			$subscriber = $model->getNewsletterSubscriber($newsletter_id,$cid[$i]);
			$responcemsg .= "<div>".$incNo.": ".$subscriber->name."( ".$subscriber->email." ) -> ";
			if($retuser[$i])
			{
				$responcemsg .= "<span style='color: #00ff00'>".JText::_('COM_REDSHOP_NEWSLETTER_SENT_SUCCESSFULLY' )."</span>";
			} else {
				$responcemsg .= "<span style='color: #ff0000'>".JText::_('COM_REDSHOP_NEWSLETTER_MAIL_NOT_SENT' )."</span>";
			}
			$responcemsg .= "</div>";
			$incNo++;
		}
		$session->set( 'subscribers',$subscribers );
		$session->set( 'subscribersuid',$subscribersuid );
		$session->set( 'subscribersuname',$subscribersuname );
		$session->set( 'incNo',$incNo );

		if(count($cid)==0)
		{
			$session->clear( 'subscribers');
			$session->clear( 'subscribersuid');
			$session->clear( 'subscribersuname');
			$session->clear( 'incNo');
		}
		$responcemsg = "<div id='sentresponse'>".$responcemsg."</div>";
		echo $responcemsg;
		exit;
	}
}?>