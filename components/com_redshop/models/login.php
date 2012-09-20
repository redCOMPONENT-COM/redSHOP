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

class loginModellogin extends JModel
{
	function __construct()
	{
		parent::__construct();
	}

	function setlogin($username,$password)
	{
		$mainframe = &JFactory::getApplication ();

		$credentials = array();
		$credentials['username'] = $username;
		$credentials['password'] = $password;

		//preform the login action
		$error = $mainframe->login($credentials);

		if (isset($error->message)) {

			$Itemid = JRequest::getVar('Itemid');
			$forgotpwd_link='index.php?option=com_redshop&view=password&Itemid='.$Itemid;
			$msg = "<a href='".JRoute::_($forgotpwd_link)."'>".JText::_('COM_REDSHOP_FORGOT_PWD_LINK')."</a>";
			$mainframe->enqueuemessage($msg);
		}
	}
	function ShopperGroupDetail($sid=0){
		$user =& JFactory::getUser();
		if($sid ==0)
			$query = "SELECT sg.* FROM #__".TABLE_PREFIX."_shopper_group as sg LEFT JOIN #__".TABLE_PREFIX."_users_info as ui on sg.`shopper_group_id`= ui.shopper_group_id WHERE ui.user_id = ".$user->id;
		else
			$query = "SELECT sg.* FROM #__".TABLE_PREFIX."_shopper_group as sg WHERE sg.`shopper_group_id`= ".$sid;
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	function CheckShopperGroup($username,$shoppergroupid){

		$query = "SELECT sg.`shopper_group_id` FROM (`#__".TABLE_PREFIX."_shopper_group` as sg LEFT JOIN #__".TABLE_PREFIX."_users_info as ui on sg.`shopper_group_id`= ui.shopper_group_id) LEFT JOIN #__users as u on ui.user_id = u.id WHERE u.username = '".$username."' AND ui.shopper_group_id =".$shoppergroupid." AND sg.shopper_group_portal = 1";
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}
}