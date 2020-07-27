<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class RedshopModelCart.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelCart extends RedshopModel
{
    public $_id = null;

    public $_data = null;

    /**
     *  Product data
     *
     * @var  [type]
     */
    public $_product = null;

    public $_table_prefix = null;

    public $_template = null;

    public $_r_voucher = 0;

    public $_c_remain = 0;

    public $_globalvoucher = 0;

    public $_producthelper = null;

    public $_carthelper = null;

    public $_userhelper = null;

    public $_objshipping = null;

    public function __construct()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';
        $this->_objshipping  = shipping::getInstance();
        $user                = JFactory::getUser();

        // Remove expired products from cart
        $this->emptyExpiredCartProducts();

        $cart = \Redshop\Cart\Helper::getCart();

        if (!empty($cart)) {
            $cart = \Redshop\Cart\Helper::getCart();

            $userId         = $user->id;
            $userSession    = \JFactory::getSession()->get('rs_user');
            $shopperGroupId = \RedshopHelperUser::getShopperGroup($userId);

            if (array_key_exists('user_shopper_group_id', $cart)) {
                $userArr = \RedshopHelperUser::getVatUserInformation($userId);

                // Removed due to discount issue $userSession['vatCountry']
                if ($cart['user_shopper_group_id'] != $shopperGroupId
                    || (!isset($userSession['vatCountry']) || !isset($userSession['vatState']) || $userSession['vatCountry'] != $userArr->country_code || $userSession['vatState'] != $userArr->state_code)
                ) {
                    $cart                          = \Redshop\Cart\Cart::modify($cart, $userId);
                    $cart['user_shopper_group_id'] = $shopperGroupId;

                    $task = JFactory::getApplication()->input->getCmd('task');

                    if ($task != 'coupon' && $task != 'voucher') {
                        $cart = RedshopHelperDiscount::modifyDiscount($cart);
                    }
                }
            }

            \Redshop\Cart\Helper::setCart($cart);
        }
    }

    /**
     * @return  void
     *
     * @since   1.0
     * @deprecated
     * @see \Redshop\Cart\Helper::emptyExpiredCartProducts();
     */
    public function emptyExpiredCartProducts()
    {
        \Redshop\Cart\Helper::emptyExpiredCartProducts();
    }

    /**
     * @param $cartElement
     * @since 2.1.6
     */
    public function delete($cartElement)
    {
        \Redshop\Cart\Helper::removeItemCart($cartElement);
    }

    /**
     * Empty cart
     *
     * @return  boolean
     *
     * @since   2.0.6
     */
    public function emptyCart()
    {
        return \RedshopHelperCart::emptyCart();
    }

    /**
     *
     * @return  array|null
     *
     * @since   2.0.6
     */
    public function getData()
    {
        if (empty($this->_data)) {
            $this->_data = \Redshop\Cart\Render::getTemplateCart();
        }

        return $this->_data;
    }

    /**
     * Update cart.
     * @param   array  $data  data in cart
     * @since   __DEPLOY_VERSION__
     */
    public function update($data)
    {
        return \Redshop\Cart\Helper::updateCart($data);
    }

    /**
     * @param $data
     * @since __DEPLOY_VERSION__
     */
    public function update_all($data)
    {
        \Redshop\Cart\Helper::updateAll($data);
    }

    /**
     * @return array|bool
     * @throws Exception
     * @deprecated
     * @since __DEPLOY_VERSION__
     */
    public function coupon()
    {
        return \RedshopHelperCartDiscount::applyCoupon();
    }

    /**
     * @return array|bool
     * @throws Exception
     * @deprecated
     * @since __DEPLOY_VERSION__
     */
    public function voucher()
    {
        return \RedshopHelperCartDiscount::applyVoucher();
    }

    /**
     * @param $post
     * @throws Exception
     * @since __DEPLOY_VERSION__
     */
    public function redmasscart($post)
    {
        \Redshop\Cart\Helper::redMassCart($post);
    }
    
    /**
     * @param   array  $data  Data
     *
     * @return   array
     * @deprecated
     * @since    2.0.6
     */
    public function changeAttribute($data)
    {
        return \Redshop\Cart\Helper::changeAttribute($data);
    }
}
