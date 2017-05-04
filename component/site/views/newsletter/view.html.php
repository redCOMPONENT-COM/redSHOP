<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewNewsletter extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$params = $app->getParams('com_redshop');

		$pathway  = $app->getPathway();
		$document = JFactory::getDocument();

		$pathway->addItem(JText::_('COM_REDSHOP_NEWSLETTER_SUBSCRIPTION'), '');

		$userdata = JRequest::getString('userdata');
		$layout   = JRequest::getCmd('layout');
		$user     = JFactory::getUser();

		$this->user = $user;
		$this->userdata = $userdata;
		$this->params = $params;

		if ($layout == 'thankyou')
		{
			$this->setLayout('thankyou');
		}

		parent::display($tpl);
	}
}
