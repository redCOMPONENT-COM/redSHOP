<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopViewAccount extends RedshopView
{
	/**
	 * @param   string  $tpl  Layout
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();
		$input = $app->input;
		$params = $app->getParams('com_redshop');

		RedshopHelperBreadcrumb::generate();

		$itemId = $input->getInt('Itemid');
		$layout = $input->getCmd('layout');

		$model = $this->getModel();
		$user  = JFactory::getUser();

		$userdata = $model->getuseraccountinfo($user->id);

		if (!count($userdata) && $layout != 'mywishlist')
		{
			$app->redirect(
				JRoute::_("index.php?option=com_redshop&view=account_billto&Itemid=" . $itemId),
				JText::_('COM_REDSHOP_LOGIN_USER_IS_NOT_REDSHOP_USER')
			);
		}

		$layout = $input->getCmd('layout', 'default');
		$mail   = $input->getInt('mail');

		// Preform security checks. Give permission to send wishlist while not logged in
		if (($user->id == 0 && $layout != 'mywishlist') || ($user->id == 0 && $layout == 'mywishlist' && !isset($mail)))
		{
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . $itemId));

			return;
		}

		if ($layout == 'mytags')
		{
			JLoader::import('joomla.html.pagination');
			$this->setLayout('mytags');

			$remove = $input->getInt('remove', 0);

			if ($remove == 1)
			{
				$model->removeTag();
			}

			$maxcategory = $params->get('maxcategory', 5);
			$limit       = $app->getUserStateFromRequest($context . 'limit', 'limit', $maxcategory, 5);
			$limitstart  = $input->getInt('limitstart', 0, '', 'int');
			$total       = $this->get('total');
			$pagination  = new JPagination($total, $limitstart, $limit);
			$this->pagination = $pagination;
		}

		if ($layout == 'mywishlist')
		{
			$wishlistId = $input->getInt('wishlist_id', 0);

			if ($wishlistId == 0)
			{
				if (!Redshop::getConfig()->get('WISHLIST_LIST'))
				{
					$usersWishlist = RedshopHelperWishlist::getUserWishlist();
					$usersWishlist = reset($usersWishlist);

					$app->redirect(JRoute::_("index.php?option=com_redshop&view=account&layout=mywishlist&wishlist_id=" . $usersWishlist->wishlist_id . "&Itemid=" . $itemId));
				}
			}

			// If wishlist Id is not set then redirect to it's main page
			if ($wishlistId == 0)
			{
				$app->redirect(JRoute::_("index.php?option=com_redshop&view=wishlist&layout=viewwishlist&Itemid=" . $itemId));
			}

			JLoader::import('joomla.html.pagination');

			$this->setLayout('mywishlist');

			$remove = $input->getInt('remove', 0);

			if ($remove == 1)
			{
				$model->removeWishlistProduct();
			}

			$maxcategory = $params->get('maxcategory', 5);
			$limit       = $app->getUserStateFromRequest($context . 'limit', 'limit', $maxcategory, 5);
			$limitstart  = $input->getInt('limitstart', 0, '', 'int');
			$total       = $this->get('total');
			$pagination  = new JPagination($total, $limitstart, $limit);
			$this->pagination = $pagination;
		}

		if ($layout == 'compare')
		{
			$remove = $input->getInt('remove', 0);

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
