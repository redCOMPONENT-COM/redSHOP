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
class newsletterModelnewsletter extends JModel
{
	var $_table_prefix = null;
	var $_db = null;

	function __construct()
	{
		parent::__construct();

		$this->_db = & JFactory :: getDBO();
		$this->_table_prefix = '#__redshop_';
		$sub_id = JRequest::getInt('sid','','request');
		if($sub_id)
		{
			$this->confirmsubscribe($sub_id);
		}
	}

	function checksubscriptionbymail($email)
	{
		Global $mainframe;
		$user =& JFactory::getUser();
		$and = "";
		if($user->id)
		{
			$and .= "AND `user_id`='".$user->id."' ";
			$email = $user->email;
		}
		$query = "SELECT COUNT(*) FROM ".$this->_table_prefix."newsletter";
		$this->_db->setQuery($query);
		$newsletter = $this->_db->loadResult();
		$url =& JURI::root();
		$link = $url.'index.php?option=com_redshop&view=newsletter';
        if($newsletter!=0)
        {
			$query = "SELECT subscription_id FROM  ".$this->_table_prefix."newsletter_subscription "
					."WHERE email='".$email."' "
					."AND newsletter_id='".DEFAULT_NEWSLETTER."' "
					.$and;
			$this->_db->setQuery($query);
			$alreadysub = $this->_db->loadResult();

			if($alreadysub)
				return true;
			else
				return false;
        }
        else
        {
        	$mainframe->redirect($link,JText::_('COM_REDSHOP_NEWSLETTER_NOT_AVAILABLE'));
        }
	}

	function confirmsubscribe($sub_id)
	{		
		Global $mainframe;
		$query = "UPDATE `".$this->_table_prefix."newsletter_subscription` SET `published` = '1' WHERE subscription_id = '".$sub_id."' ";
		$this->_db->setQuery($query);
		$this->_db->query();
		$url =& JURI::root();
		$link = $url.'index.php?option=com_redshop&view=newsletter';
		$mainframe->redirect($link,JText::_('COM_REDSHOP_MESSAGE_CONFIRMED_SUBSCRIBE'));
	}
}?>