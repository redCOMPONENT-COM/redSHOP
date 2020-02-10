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
    public function getWrapperPriceList($cart = array())
    {
        $wrapper     = $this->_producthelper->getWrapper($cart['product_id'], $cart['wrapper_id']);
        $wrapper_vat = 0;
        $wrapperArr  = array();

        if (count($wrapper) > 0)
        {
            if ($wrapper[0]->wrapper_price > 0)
            {
                $wrapper_vat = RedshopHelperProduct::getProductTax($cart['product_id'], $wrapper[0]->wrapper_price);
            }

            $wrapper_price = $wrapper[0]->wrapper_price;
        }

        $wrapperArr['wrapper_vat']   = $wrapper_vat;
        $wrapperArr['wrapper_price'] = $wrapper_price;

        return $wrapperArr;
    }
}