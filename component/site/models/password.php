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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'mail.php' );

class passwordModelpassword extends JModel
{
	function __construct()
	{
		parent::__construct();
	}

	function resetpassword($email)
	{
		$db = &JFactory::getDBO();

		$query="SELECT id from #__users WHERE email like '$email'";

		$db->setQuery($query);
		$id = $db->loadResult();

		// Check the results
		if ($id=="" || $id==0)
		{
			$this->setError(JText::_('RESET_PASSWORD_MAIL_ERROR'));
			return false;
		}

		// Generate a new token
		$token = $this->genRandomString();

		$query	= 'UPDATE #__users'
				. ' SET activation = '.$db->Quote($token)
				. ' WHERE id = '.(int) $id
				. ' AND block = 0';

		$db->setQuery($query);

		// Save the token
		if (!$db->query())
		{
			$this->setError(JText::_('DATABASE_ERROR'));
			return false;
		}

		// Send the token to the user via e-mail
		if (!$this->sendresetpasswordmail($email, $token))
		{
			return false;
		}
	}
	function sendresetpasswordmail($email, $token)
	{
		$redshopMail = new redshopMail();
		$config		= &JFactory::getConfig();
		$uri		= &JFactory::getURI();
		$option 	= JRequest::getVar('option','request');
		$url		= JURI::base().'index.php?option='.$option.'&view=password&layout=complete';
		$sitename	= $config->getValue('sitename');

		$db = &JFactory::getDBO();

		$query = "SELECT u.* , ru.*"
		. "\n FROM #__users AS u"
		. "\n LEFT JOIN #__redshop_users_info AS ru ON u.id = ru.user_id WHERE u.email like '".$email."'"
		;

		$db->setQuery($query);
		$userinfo = $db->loadObjectList();

		$message = "";
		$subject = "";
		$mailbcc=NULL;
		$mailinfo = $redshopMail->getMailtemplate(0,"status_of_password_reset");
		if(count($mailinfo)>0)
		{
			$mailinfo = $mailinfo[0];
			$message = $mailinfo->mail_body;
			$subject = $mailinfo->mail_subject;
			if(trim($mailinfo->mail_bcc)!="")
			{
				$mailbcc= explode(",",$mailinfo->mail_bcc);
			}
		}
	    $search[] = "{username}";
		$search[] = "{reset_token}";
		$search[] = "{password_complete_url}";
		$search[] = "{firstname}";
		$search[] = "{lastname}";
		$search[] = "{fullname}";

		$replace[] = $userinfo[0]->username;
		$replace[] = $token;
		$replace[] = $url;
		$replace[] = $userinfo[0]->firstname;
		$replace[] = $userinfo[0]->lastname;
		$replace[] = $userinfo[0]->firstname." ".$userinfo[0]->lastname;

		$message = str_replace($search,$replace,$message);


		// Set the e-mail parameters
		$from		= $config->getValue('mailfrom');
		$fromname	= $config->getValue('fromname');

		$body		= $message; //JText::sprintf('PASSWORD_RESET_CONFIRMATION_MAIL_TEXT', $sitename, $token, $url);

		// Send the e-mail
		if (!JUtility::sendMail($from, $fromname, $email, $subject, $body,1,NULL,$mailbcc))
		{
			$this->setError('ERROR_SENDING_CONFIRMATION_MAIL');
			return false;
		}

		return true;
	}
	function genRandomString()
	{
		$length=0;
	    $length=35;
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	    $string = null;
	    for ($p = 0; $p < $length; $p++)
	    {
	        $string .= $characters[mt_rand(0, strlen($characters))];
	    }
	    return $string;
	}

	function changepassword($token)
	{
		$db = JFactory::getDBO();
		$query="SELECT id from #__users WHERE activation like '$token'";
		$db->setQuery($query);

		// Check the results
		if (!($id = $db->loadResult()) || trim($token)=="")
		{
			$this->setError(JText::_('RESET_PASSWORD_TOKEN_ERROR'));
			return false;
		}
		JRequest::setVar('uid',$id);

		return true;
	}
	function setpassword($password,$uid)
	{
		$db = JFactory::getDBO();

		$query	= 'UPDATE #__users'
				. ' SET password = "'.md5($password).'"'
				. ' ,activation = NULL '
				. ' WHERE id = '.(int)$uid
				. ' AND block = 0';

		$db->setQuery($query);

		// Saving new password
		if (!$db->query())
		{
			$this->setError(JText::_('DATABASE_ERROR'));
			return false;
		}
	}
}