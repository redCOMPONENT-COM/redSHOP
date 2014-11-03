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

class RedshopViewAccount extends RedshopView
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();

		$prodhelperobj = new producthelper;
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
			$app->Redirect("index.php?option=com_redshop&view=account_billto&Itemid=" . $Itemid, $msg);
		}

		$layout = JRequest::getCmd('layout', 'default');
		$mail   = JRequest::getInt('mail');

		// Preform security checks
		if (($user->id == 0 && $layout != 'mywishlist') || ($user->id == 0 && $layout == 'mywishlist' && !isset($mail))) // Give permission to send wishlist while not logged in )
		{
			$app->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid'));

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
			JHtml::script('com_redshop/colorbox.js', false, true);

			if (version_compare(JVERSION, '3.0', '<'))
			{
				JHtml::script('com_redshop/jquery.js', false, true);
			}

			JHTML::Script('jquery.colorbox-min.js', 'components/com_redshop/assets/js/', false);
			JHtml::script('com_redshop/redbox.js', false, true);
			JHtml::script('com_redshop/attribute.js', false, true);
			JHtml::script('com_redshop/common.js', false, true);
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
			$pagination  = new redPagination($total, $limitstart, $limit);
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
