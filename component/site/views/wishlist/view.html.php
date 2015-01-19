<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewWishlist extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables

		$params = $app->getParams('com_redshop');
		$task   = JRequest::getCmd('task', 'com_redshop');

		$Itemid = JRequest::getInt('Itemid');
		$pid    = JRequest::getInt('product_id');
		$layout = JRequest::getCmd('layout');

		$config = new Redconfiguration;

		$pageheadingtag = '';

		$params   = $app->getParams('com_redshop');

		JHtml::_('redshopjquery.framework');
		JHtml::script('com_redshop/attribute.js', false, true);
		JHtml::script('com_redshop/common.js', false, true);
		JHtml::script('com_redshop/redbox.js', false, true);
		$model = $this->getModel("wishlist");

		$wishlist          = $model->getUserWishlist();
		$wish_products     = $model->getWishlistProduct();
		$session_wishlists = $model->getWishlistProductFromSession();

		if ($task == 'viewwishlist' || $layout == 'viewwishlist')
		{
			$this->setlayout('viewwishlist');
			$this->wishlists     = $wishlist;
			$this->wish_products = $wish_products;
			$this->wish_session  = $session_wishlists;
			$this->params        = $params;
			parent::display($tpl);
		}
		elseif ($task == 'viewloginwishlist')
		{
			$this->setlayout('viewloginwishlist');
			$this->wishlists = $wishlist;
			$this->params = $params;
			parent::display($tpl);
		}
		else
		{
			$this->wish_session = $session_wishlists;
			$this->wishlist = $wishlist;
			$this->params = $params;
			parent::display($tpl);
		}
	}
}
