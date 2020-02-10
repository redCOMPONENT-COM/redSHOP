<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Accessory;

defined('_JEXEC') or die;

/**
 * Accessory Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Helper
{
    public static function generateAccessoryFromCart($cartItemId = 0, $product_id = 0, $quantity = 1)
    {
        $generateAccessoryCart = array();
        $cartItemData = self::getCartItemAccessoryDetail($cartItemId);
        $in = count($cartItemData);

        for ($i = 0; $i < $in; $i++)
        {
            $accessory          = RedshopHelperAccessory::getProductAccessories($cartItemData[$i]->product_id);
            $accessoryPriceList = \Redshop\Product\Accessory::getPrice($product_id, $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1);
            $accessoryPrice     = $accessoryPriceList[0];

            $generateAccessoryCart[$i]['accessory_id']     = $cartItemData[$i]->product_id;
            $generateAccessoryCart[$i]['accessory_name']   = $accessory[0]->product_name;
            $generateAccessoryCart[$i]['accessory_oprand'] = $accessory[0]->oprand;
            $generateAccessoryCart[$i]['accessory_price']  = $accessoryPrice;
            $generateAccessoryCart[$i]['accessory_childs'] = RedshopHelperCart::generateAttributeFromCart($cartItemId, 1, $cartItemData[$i]->product_id, $quantity);
        }

        return $generateAccessoryCart;
    }

    /**
     * @param   int  $cartItemId
     *
     * @return null
     * @since __DEPLOY_VERSION__
     */
    public static function getCartItemAccessoryDetail($cartItemId = 0)
    {
        $list  = null;
        $db    = \JFactory::getDbo();
        $query = $db->getQuery(true);

        if ($cartItemId != 0)
        {
            $query->select('*')
                ->from($db->qn('#__redshop_usercart_accessory_item'))
                ->where($db->qn('cart_item_id') . '=' . $db->q((int) $cartItemId));

            $db->setQuery($query);
            $list = $db->loadObjectlist();
        }

        return $list;
    }
}