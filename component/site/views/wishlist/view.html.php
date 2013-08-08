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

class wishlistViewwishlist extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables

		$params = $app->getParams('com_redshop');
		$task   = JRequest::getVar('task', 'com_redshop');

		$option = JRequest::getVar('option', 'com_redshop');
		$Itemid = JRequest::getVar('Itemid');
		$pid    = JRequest::getInt('product_id');
		$layout = JRequest::getVar('layout');

		$config = new Redconfiguration;

		$pageheadingtag = '';

		$params   = $app->getParams('com_redshop');
		$document = JFactory::getDocument();
		JHTML::Stylesheet('colorbox.css', 'components/com_redshop/assets/css/');

		JHTML::Script('jquery.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('jquery.colorbox-min.js', 'components/com_redshop/assets/js/', false);

		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);
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
