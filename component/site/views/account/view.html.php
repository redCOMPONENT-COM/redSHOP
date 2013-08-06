<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

class accountViewaccount extends JView
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();

		$prodhelperobj = new producthelper;
		$prodhelperobj->generateBreadcrumb();

		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$layout = JRequest::getVar('layout');
		$params = $app->getParams($option);

		$document = JFactory::getDocument();

		$model = $this->getModel();
		$user  = JFactory::getUser();

		$userdata = $model->getuseraccountinfo($user->id);

		if (!count($userdata) && $layout != 'mywishlist')
		{
			$msg = JText::_('COM_REDSHOP_LOGIN_USER_IS_NOT_REDSHOP_USER');
			$app->Redirect("index.php?option=" . $option . "&view=account_billto&Itemid=" . $Itemid, $msg);
		}

		$layout = JRequest::getVar('layout', 'default');
		$mail   = JRequest::getVar('mail');

		// Preform security checks
		if (($user->id == 0 && $layout != 'mywishlist') || ($user->id == 0 && $layout == 'mywishlist' && !isset($mail))) // Give permission to send wishlist while not logged in )
		{
			$app->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getVar('Itemid'));

			return;
		}

		if ($layout == 'mytags')
		{
			JLoader::import('joomla.html.pagination');
			$this->setLayout('mytags');

			$remove = JRequest::getVar('remove', 0);

			if ($remove == 1)
			{
				$model->removeTag();
			}

			$maxcategory = $params->get('maxcategory', 5);
			$limit       = $app->getUserStateFromRequest($context . 'limit', 'limit', $maxcategory, 5);
			$limitstart  = JRequest::getVar('limitstart', 0, '', 'int');
			$total       = $this->get('total');
			$pagination  = new redPagination($total, $limitstart, $limit);
			$this->pagination = $pagination;
		}

		if ($layout == 'mywishlist')
		{
			$wishlist_id = $app->input->getInt('wishlist_id', 0);

			// If wishlist Id is not set then redirect to it's main page
			if ($wishlist_id == 0)
			{
				$app->Redirect("index.php?option=com_redshop&view=wishlist&layout=viewwishlist&Itemid=" . $Itemid);
			}

			JLoader::import('joomla.html.pagination');
			JHTML::Stylesheet('colorbox.css', 'components/com_redshop/assets/css/');

			JHTML::Script('jquery.js', 'components/com_redshop/assets/js/', false);
			JHTML::Script('jquery.colorbox-min.js', 'components/com_redshop/assets/js/', false);
			JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);
			JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
			JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
			$this->setLayout('mywishlist');

			$remove = JRequest::getVar('remove', 0);

			if ($remove == 1)
			{
				$model->removeWishlistProduct();
			}

			$maxcategory = $params->get('maxcategory', 5);
			$limit       = $app->getUserStateFromRequest($context . 'limit', 'limit', $maxcategory, 5);
			$limitstart  = JRequest::getVar('limitstart', 0, '', 'int');
			$total       = $this->get('total');
			$pagination  = new redPagination($total, $limitstart, $limit);
			$this->pagination = $pagination;
		}

		if ($layout == 'compare')
		{
			$remove = JRequest::getVar('remove', 0);

			if ($remove == 1)
			{
				$model->removeCompare();
			}

			JLoader::import('joomla.html.pagination');
			$this->setLayout('compare');
		}

		$this->user = $user;
		$this->userdata = $userdata;
		$this->params = $params;

		// RedCRM Template

		// Helper object
		$helper = new redhelper;

		if ($layout == "default" && $helper->isredCRM())
		{
			$tmplPath = JPATH_BASE . '/components/com_redcrm/views/account/tmpl';

			$this->addTemplatePath($tmplPath);

			parent::display('storemanagement');
		}

		// RedCRM Template END

		parent::display($tpl);
	}
}
