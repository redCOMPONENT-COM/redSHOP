<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

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
        $app = Factory::getApplication();

        // Request variables
        $task   = $app->input->getCmd('task', 'com_redshop');
        $layout = $app->input->getCmd('layout');

        $model = $this->getModel("wishlist");

        $this->params    = $app->getParams('com_redshop');
        $this->wishlists = $model->getUserWishlist();

        if ($task == 'viewwishlist' || $layout == 'viewwishlist') {
            $this->setlayout('viewwishlist');
            $this->wish_products = $model->getWishlistProduct();
            $this->wish_session  = $model->getWishlistProductFromSession();
        } elseif ($task == 'viewloginwishlist') {
            $this->setlayout('viewloginwishlist');
        } else {
            $this->wish_session = $model->getWishlistProductFromSession();
        }

        // Modal button for Add to wishlist
        /*
        echo RedshopLayoutHelper::render(
            'modal.iframe',
            [
                'modalButton'     => '.modalAddToWishlistButton',
                'selector'        => 'modalAddToWishlist',
                'params'          => [
                            'title'      => Text::_('COM_REDSHOP_ADD_TOO_WISHLIST'),
                            'footer'     => '<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">' . Text::_('COM_REDSHOP_CLOSE') . '</button>',
                            'modalWidth' => '50',
                            'bodyHeight' => '40',
                            'modalCss'   => '',
                ]
            ]
        );
        */

        parent::display($tpl);
    }
}