<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewSplit_payment extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$params = $app->getParams('com_redshop');

		$pathway  = $app->getPathway();
		$document = JFactory::getDocument();

		$pathway->addItem(JText::_('COM_REDSHOP_SPLIT_PAYMENT'), '');

		$userdata = JRequest::getString('userdata');
		$user     = JFactory::getUser();

		// Preform security checks
		if ($user->id == 0)
		{
			echo JText::_('COM_REDSHOP_ALERTNOTAUTH_ACCOUNT');

			return;
		}

		$this->user = $user;
		$this->userdata = $userdata;
		$this->params = $params;
		$payment_method_id = JRequest::getString('payment_method_id');
		$this->payment_method_id = $payment_method_id;

		parent::display($tpl);
	}
}
