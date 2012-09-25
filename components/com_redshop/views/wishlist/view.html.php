<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

class wishlistViewwishlist extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $mainframe;

        $task = JRequest::getVar('task', 'com_redshop');

        $params = $mainframe->getParams('com_redshop');
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
        if ($task == 'viewwishlist')
        {
            $this->setlayout('viewwishlist');
            $this->assignRef('wishlists', $wishlist);
            $this->assignRef('wish_products', $wish_products);
            $this->assignRef('wish_session', $session_wishlists);
            $this->assignRef('params', $params);
            parent::display($tpl);
        }
        else if ($task == 'viewloginwishlist')
        {
            $this->setlayout('viewloginwishlist');
            $this->assignRef('wishlists', $wishlist);
            $this->assignRef('params', $params);
            parent::display($tpl);
        }
        else
        {
            $this->assignRef('wish_session', $session_wishlists);
            $this->assignRef('wishlist', $wishlist);
            $this->assignRef('params', $params);
            parent::display($tpl);
        }
    }
}

