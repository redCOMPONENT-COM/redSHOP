<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewLogin extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		$params = $app->getParams('com_redshop');

		$model = $this->getModel();

		$shoppergroupid = $app->input->getInt('protalid', 0);

		$ShopperGroupDetail = $model->ShopperGroupDetail($shoppergroupid);

		$layout = $app->input->getCmd('layout', '');

		$check = $model->CheckShopperGroup($user->username, $shoppergroupid);

		if ($layout == 'portal' || Redshop::getConfig()->get('PORTAL_SHOP') == 1)
		{
			isset($ShopperGroupDetail[0]->shopper_group_portal) ? $portal = $ShopperGroupDetail[0]->shopper_group_portal : $portal = 0;

			if ($portal == 1 || Redshop::getConfig()->get('PORTAL_SHOP') == 1)
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
				$app->enqueuemessage(JText::_('COM_REDSHOP_SHOPPER_GROUP_PORTAL_IS_DISABLE'));
				$app->redirect(JRoute::_('index.php?option=com_redshop'));
			}
		}
		else
		{
			if ($user->id != "")
			{
				$this->setLayout('logout');
			}
		}

		$this->ShopperGroupDetail = $ShopperGroupDetail;
		$this->check = $check;
		parent::display($tpl);
	}
}
