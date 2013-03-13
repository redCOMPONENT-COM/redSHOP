<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');

class loginViewlogin extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$user =& JFactory::getUser();

		$params = & $mainframe->getParams('com_redshop');

		$model = $this->getModel();

		$shoppergroupid = JRequest::getInt('protalid', 0);

		$ShopperGroupDetail = $model->ShopperGroupDetail($shoppergroupid);

		$layout = JRequest::getVar('layout', '');

		$user =& JFactory::getUser();

		$check = $model->CheckShopperGroup($user->username, $shoppergroupid);

		if ($layout == 'portal' || PORTAL_SHOP == 1)
		{

			isset($ShopperGroupDetail[0]->shopper_group_portal) ? $portal = $ShopperGroupDetail[0]->shopper_group_portal : $portal = 0;

			if ($portal == 1 || PORTAL_SHOP == 1)
			{

				if ($user->id != "")
				{
					$this->setLayout('portals');
				}
				else
				{
					$this->setLayout('portal');
				}
			}
			else
			{
				$mainframe->enqueuemessage(JText::_('COM_REDSHOP_SHOPPER_GROUP_PORTAL_IS_DISABLE'));
				$mainframe->Redirect('index.php?option=com_redshop');
			}

		}
		else
		{

			if ($user->id != "")
			{
				$this->setLayout('logout');
			}
		}

		$this->assignRef('ShopperGroupDetail', $ShopperGroupDetail);
		$this->assignRef('check', $check);
		parent::display($tpl);
	}
}