<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

defined('_JEXEC') or die;

/**
 * Render class
 *
 * @since  __DEPLOY_VERSION__
 */
class Ajax
{
    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function getProductTax() {
        \Redshop\Helper\Ajax::validateAjaxRequest('get');

        $app = \JFactory::getApplication();

        $productId    = $app->input->getInt('id', 0);
        $productPrice = $app->input->getFloat('price', 0);
        $userId       = $app->input->getInt('userId', 0);
        $taxExempt    = $app->input->getBool('taxExempt', false);

        $product = new \Registry;
        $product->set(
            'tax',
            \RedshopHelperProduct::getProductTax(
                $productId,
                $productPrice,
                $userId,
                $taxExempt
            )
        );

        ob_clean();
        print $product;

        $app->close();
    }

    /**
     * @param bool $isModify
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function renderModuleCartHtml($isModify = true)
    {
        $cart = \Redshop\Cart\Helper::getCart();

        if ($isModify === true) {
            $cart = \RedshopHelperDiscount::modifyDiscount($cart);
        }

        \Redshop\Cart\Render::moduleCart($cart);
    }
}
