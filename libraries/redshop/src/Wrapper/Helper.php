<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Wrapper;

defined('_JEXEC') or die;

/**
 * Wrapper Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param   array  $cart
     *
     * @return array
     * @since __DEPLOY_VERSION__
     */
    public function getWrapperPrice($cart = array())
    {
        $wrapper     = RedshopHelperProduct::getWrapper($cart['product_id'], $cart['wrapper_id']);
        $wrapperVat = 0;
        $wrappers  = array();

        if (count($wrapper) > 0)
        {
            if ($wrapper[0]->wrapper_price > 0)
            {
                $wrapperVat = RedshopHelperProduct::getProductTax($cart['product_id'], $wrapper[0]->wrapper_price);
            }

            $wrapperPrice = $wrapper[0]->wrapper_price;

            $wrappers['wrapper_vat']   = $wrapperVat;
            $wrappers['wrapper_price'] = $wrapperPrice;
        }

        return $wrappers;
    }
}