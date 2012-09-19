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
 * login Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class loginController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}

	/*
	 *  setlogin function
	 */
	function setlogin()
	{
		$username=JRequest::getVar('username', '', 'method', 'username');
		$password=JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$returnitemid = JRequest::getVar('returnitemid');
		$menu =& JSite::getMenu();
		$mywishlist=JRequest::getVar('mywishlist');
		$item = $menu->getItem($returnitemid);

		include_once (JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');
		$redhelper = new redhelper();


		$model = &$this->getModel('login');


		$shoppergroupid = JRequest::getInt('protalid', '', 'post', 0);

		$msg = "";

		if ($shoppergroupid!=0 ){
			$check = $model->CheckShopperGroup($username,$shoppergroupid);
			$link = "index.php?option=".$option."&view=login&layout=portal&protalid=".$shoppergroupid;
			if ($check > 0) {
				$model->setlogin($username,$password);
				$return = JRequest::getVar('return');
			}else {
				$msg = JText::_("COM_REDSHOP_SHOPPERGROUP_NOT_MATCH");
				$return = "";
			}
		}else{
			$model->setlogin($username,$password);
			$return = JRequest::getVar('return');
		}

		if($mywishlist==1)
		{
			$wishreturn = JRoute::_ ( 'index.php?loginwishlist=1&option=com_redshop&view=wishlist&Itemid='.$Itemid, false );
			$this->setRedirect($wishreturn);


		}else{
			if($item) {
			$link = $item->link.'&Itemid='.$returnitemid;
			} else {
				$link = 'index.php?option='.$option.'&Itemid='.$returnitemid;
			}

			if(!empty($return))
			{
				$s_Itemid = $redhelper->getCheckoutItemid ();
				$Itemid = $s_Itemid ? $s_Itemid : $Itemid;
				$return = JRoute::_ ( 'index.php?option=com_redshop&view=checkout&Itemid='.$Itemid, false );

				$this->setRedirect($return);
			}else
			{
				$this->setRedirect($link,$msg);
			}
		}


	}
	/*
	 *  logout function
	 */
	function logout()
	{

		$mainframe = JFactory::getApplication();
		$params = &$mainframe->getParams('com_redshop');
		$logout_itemid = JRequest::getVar('logout');
		/*$menu	=& $mainframe->getMenu();
		$item	=& $menu->getActive();
		$redconfig = $item->query;
		$item = $menu->getItem($redconfig['logout']);*/
		$menu =& JSite::getMenu();
		$item = $menu->getItem($logout_itemid);
		if($item) {
			$link = JRoute::_($item->link.'&Itemid='.$logout_itemid);
		} else {
			$link = JRoute::_('index.php?option=com_redshop');
		}
		$mainframe->logout();
		$this->setRedirect($link);
	}
}