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

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$sub_id = JRequest::getInt('sid','','request');
		if($sub_id){
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
		$query = "SELECT count(*) FROM ".$this->_table_prefix."newsletter";
		$this->_db->setQuery($query);
		$newsletter = $this->_db->loadResult();
		$option = JRequest::getVar('option','','request');
		$url =& JURI::root();
		$link = $url.'index.php?option='.$option.'&view=newsletter';
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
        	$mainframe->redirect($link,JText::_('NEWSLETTER_NOT_AVAILABLE'));
        }
	}

	function confirmsubscribe($sub_id){
		$db = JFactory::getDBO();
		Global $mainframe;
		$query = "UPDATE `".$this->_table_prefix."newsletter_subscription` SET `published` = '1' WHERE subscription_id = '".$sub_id."' ";
		$db->setQuery($query);
		$db->query();
		$url =& JURI::root();
		$option = JRequest::getVar('option','','request');
		$link = $url.'index.php?option='.$option.'&view=newsletter';

		$mainframe->redirect($link,JText::_('MESSAGE_CONFIRMED_SUBSCRIBE'));


	}
}