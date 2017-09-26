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
	 * @since   1.0.0
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$task   = $app->input->getCmd('task', 'com_redshop');
		$layout = $app->input->getCmd('layout');

		$model  = $this->getModel("wishlist");

		$this->params    = $app->getParams('com_redshop');
		$this->wishlists = $model->getUserWishlist();

		if ($task == 'viewwishlist' || $layout == 'viewwishlist')
		{
			$this->setlayout('viewwishlist');
			$this->wish_products = $model->getWishlistProduct();
			$this->wish_session  = $model->getWishlistProductFromSession();
		}
		elseif ($task == 'viewloginwishlist')
		{
			$this->setlayout('viewloginwishlist');
		}
		else
		{
			$this->wish_session = $model->getWishlistProductFromSession();
		}

		parent::display($tpl);
	}
}
