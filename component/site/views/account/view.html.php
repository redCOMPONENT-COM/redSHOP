<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewAccount extends RedshopView
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();

		$prodhelperobj = productHelper::getInstance();
		$prodhelperobj->generateBreadcrumb();

		$Itemid = JRequest::getInt('Itemid');
		$layout = JRequest::getCmd('layout');
		$params = $app->getParams('com_redshop');

		$document = JFactory::getDocument();

		$model = $this->getModel();
		$user  = JFactory::getUser();

		$userdata = $model->getuseraccountinfo($user->id);

		if (!count($userdata) && $layout != 'mywishlist')
		{
			$msg = JText::_('COM_REDSHOP_LOGIN_USER_IS_NOT_REDSHOP_USER');
			$app->redirect(JRoute::_("index.php?option=com_redshop&view=account_billto&Itemid=" . $Itemid), $msg);
		}

		$layout = JRequest::getCmd('layout', 'default');
		$mail   = JRequest::getInt('mail');

		// Preform security checks. Give permission to send wishlist while not logged in
		if (($user->id == 0 && $layout != 'mywishlist') || ($user->id == 0 && $layout == 'mywishlist' && !isset($mail)))
		{
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid')));

			return;
		}

		if ($layout == 'mytags')
		{
			JLoader::import('joomla.html.pagination');
			$this->setLayout('mytags');

			$remove = JRequest::getInt('remove', 0);

			if ($remove == 1)
			{
				$model->removeTag();
			}

			$maxcategory = $params->get('maxcategory', 5);
			$limit       = $app->getUserStateFromRequest($context . 'limit', 'limit', $maxcategory, 5);
			$limitstart  = JRequest::getInt('limitstart', 0, '', 'int');
			$total       = $this->get('total');
			$pagination  = new JPagination($total, $limitstart, $limit);
			$this->pagination = $pagination;
		}

		if ($layout == 'mywishlist')
		{
			$wishlist_id = $app->input->getInt('wishlist_id', 0);

			// If wishlist Id is not set then redirect to it's main page
			if ($wishlist_id == 0)
			{
				$app->redirect(JRoute::_("index.php?option=com_redshop&view=wishlist&layout=viewwishlist&Itemid=" . $Itemid));
			}

			JLoader::import('joomla.html.pagination');

			$this->setLayout('mywishlist');

			$remove = JRequest::getInt('remove', 0);

			if ($remove == 1)
			{
				$model->removeWishlistProduct();
			}

			$maxcategory = $params->get('maxcategory', 5);
			$limit       = $app->getUserStateFromRequest($context . 'limit', 'limit', $maxcategory, 5);
			$limitstart  = JRequest::getInt('limitstart', 0, '', 'int');
			$total       = $this->get('total');
			$pagination  = new JPagination($total, $limitstart, $limit);
			$this->pagination = $pagination;
		}

		if ($layout == 'compare')
		{
			$remove = JRequest::getInt('remove', 0);

			if ($remove == 1)
			{
				$model->removeCompare();
			}

			JLoader::import('joomla.html.pagination');
			$this->setLayout('compare');
		}

		$this->user     = $user;
		$this->userdata = $userdata;
		$this->params   = $params;

		parent::display($tpl);
	}
}
