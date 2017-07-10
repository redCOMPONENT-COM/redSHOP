<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Wishlist View
 *
 * @package     RedShop.Component
 * @subpackage  View
 *
 * @since       1.0
 */
class RedshopViewWishlist extends RedshopView
{
	/**
	 * @param   string  $tpl  Template layout
	 *
	 *
	 * @since   1.0.0
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$task   = $app->input->getCmd('task', 'com_redshop');
		$layout =  $app->input->getCmd('layout');

		$params = $app->getParams('com_redshop');
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
		}
		elseif ($task == 'viewloginwishlist')
		{
			$this->setlayout('viewloginwishlist');
			$this->wishlists = $wishlist;
			$this->params    = $params;
		}
		else
		{
			$this->wish_session = $session_wishlists;
			$this->wishlist     = $wishlist;
			$this->params       = $params;
		}

		parent::display($tpl);
	}
}
