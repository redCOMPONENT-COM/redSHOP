<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewOrders extends RedshopView
{
	public function display($tpl = null)
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		// Preform security checks
		if ($user->id == 0)
		{
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid')));
			$app->close();
		}

		$layout = $app->input->getCmd('layout', 'default');
		$this->setLayout($layout);

		$params        = $app->getParams('com_redshop');
		RedshopHelperBreadcrumb::generate();

		// Request variables
		$limit      = $app->getUserStateFromRequest('com_redshop' . 'limit', 'limit', 10, 'int');
		$limitstart = $app->input->getInt('limitstart', 0, '', 'int');

		$detail           = $this->get('data');
		$this->pagination = $this->get('Pagination');

		$this->detail = $detail;
		$this->params = $params;
		parent::display($tpl);
	}
}
