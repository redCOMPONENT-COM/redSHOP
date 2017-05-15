<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * login Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerLogin extends RedshopController
{
	/**
	 *  Setlogin function
	 *
	 * @return  void
	 */
	public function setlogin()
	{
		$username     = JRequest::getVar('username', '', 'method', 'username');
		$password     = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
		$Itemid       = JRequest::getVar('Itemid');
		$returnitemid = JRequest::getVar('returnitemid');
		$mywishlist   = JRequest::getVar('mywishlist');
		$menu         = JFactory::getApplication()->getMenu();
		$item         = $menu->getItem($returnitemid);


		$redhelper = redhelper::getInstance();

		$model = $this->getModel('login');

		$shoppergroupid = JRequest::getInt('protalid', '', 'post', 0);

		$msg = "";

		if ($shoppergroupid != 0)
		{
			$check = $model->CheckShopperGroup($username, $shoppergroupid);
			$link  = "index.php?option=com_redshop&view=login&layout=portal&protalid=" . $shoppergroupid;

			if ($check > 0)
			{
				$model->setlogin($username, $password);
				$return = JRequest::getVar('return');
			}
			else
			{
				$msg    = JText::_("COM_REDSHOP_SHOPPERGROUP_NOT_MATCH");
				$return = "";
			}
		}
		else
		{
			$model->setlogin($username, $password);
			$return = JRequest::getVar('return');
		}

		if ($mywishlist == 1)
		{
			$wishreturn = JRoute::_('index.php?loginwishlist=1&option=com_redshop&view=wishlist&Itemid=' . $Itemid, false);
			$this->setRedirect($wishreturn);

		}
		else
		{
			if ($item)
			{
				$link = $item->link . '&Itemid=' . $returnitemid;
			}
			else
			{
				$link = 'index.php?option=com_redshop&Itemid=' . $returnitemid;
			}

			if (!empty($return))
			{
				$s_Itemid = RedshopHelperUtility::getCheckoutItemId();
				$Itemid   = $s_Itemid ? $s_Itemid : $Itemid;
				$return   = JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid, false);

				$this->setRedirect($return);
			}
			else
			{
				$this->setRedirect($link, $msg);
			}
		}

	}

	/**
	 *  logout function
	 *
	 * @return void
	 */
	public function logout()
	{
		$app           = JFactory::getApplication();
		$params        = $app->getParams('com_redshop');
		$logout_itemid = JRequest::getVar('logout');
		$menu          = JFactory::getApplication()->getMenu();
		$item          = $menu->getItem($logout_itemid);

		if ($item)
		{
			$link = JRoute::_($item->link . '&Itemid=' . $logout_itemid, false);
		}
		else
		{
			$link = JRoute::_('index.php?option=com_redshop', false);
		}

		$app->logout();
		$this->setRedirect($link);
	}
}
