<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Account  view
 *
 * @package     RedSHOP.Frontend
 * @subpackage  View
 * @since       1.6.0
 */
class RedshopViewAccount extends RedshopView
{
	/**
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * @var  JUser
	 */
	public $user;

	/**
	 * @var  mixed
	 */
	public $userdata;

	/**
	 * @var  Joomla\Registry\Registry
	 */
	public $params;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed         A string if successful, otherwise a JError object.
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		/** @var JApplicationSite $app */
		$app    = JFactory::getApplication();
		$input  = $app->input;
		$params = $app->getParams('com_redshop');

		RedshopHelperBreadcrumb::generate();

		$itemId = $input->getInt('Itemid');
		$layout = $input->getCmd('layout');

		/** @var RedshopModelAccount $model */
		$model = $this->getModel();
		$user  = JFactory::getUser();

		$userData = $model->getUserAccountInfo($user->id);

		if (!count($userData) && $layout != 'mywishlist')
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
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . $itemId, false));
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

			$maxcategory      = $params->get('maxcategory', 5);
			$limit            = $app->getUserStateFromRequest($model->context . 'limit', 'limit', $maxcategory, 5);
			$limitstart       = $input->getInt('limitstart', 0, '', 'int');
			$total            = $this->get('total');
			$pagination       = new JPagination($total, $limitstart, $limit);
			$this->pagination = $pagination;
		}

		if ($layout == 'mywishlist')
		{
			$wishlistId = $input->getInt('wishlist_id', 0);

			if ($wishlistId == 0 && !Redshop::getConfig()->get('WISHLIST_LIST'))
			{
				$usersWishlist = RedshopHelperWishlist::getUserWishlist();
				$usersWishlist = reset($usersWishlist);

				$app->redirect(
					JRoute::_(
						"index.php?option=com_redshop&view=account&layout=mywishlist&wishlist_id="
						. $usersWishlist->wishlist_id . "&Itemid=" . $itemId,
						false
					)
				);
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

			$maxcategory      = $params->get('maxcategory', 5);
			$limit            = $app->getUserStateFromRequest($model->context . 'limit', 'limit', $maxcategory, 5);
			$limitstart       = $input->getInt('limitstart', 0, '', 'int');
			$total            = $this->get('total');
			$pagination       = new JPagination($total, $limitstart, $limit);
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
		$this->userdata = $userData;
		$this->params   = $params;

		parent::display($tpl);
	}
}
