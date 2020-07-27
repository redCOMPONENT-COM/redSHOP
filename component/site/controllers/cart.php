<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * Cart Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerCart extends RedshopController
{
    /**
     * Constructor
     *
     * @param   array  $default  config array
     * @since   2.0.6
     */
    public function __construct($default = array())
    {
        parent::__construct($default);
    }

    /**
     * Method to add product in cart
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public function add()
    {
        \Redshop\Workflow\Cart::add();
    }

    /**
     * Method to add coupon code in cart for discount
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public function coupon()
    {
        \Redshop\Workflow\Promotion::applyCoupon();
    }

    /**
     * Method for modify calculate cart
     *
     * @param   array  $cart  Cart data.
     * @return  mixed
     * @throws  Exception
     */
    public function modifyCalculation()
    {
        return \RedshopHelperCart::modifyCalculation();
    }

    /**
     * Method to add voucher code in cart for discount
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public function voucher()
    {
        \Redshop\Workflow\Promotion::applyVoucher();
    }

    /**
     * Method to update product info in cart
     * @return void
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function update()
    {
        \Redshop\Workflow\Cart::update();
    }

    /**
     * Method to update all product info in cart
     *
     * @return void
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function update_all()
    {
        \Redshop\Workflow\Cart::updateAll();
    }

    /**
     * Method to make cart empty
     * @return void
     * @since  __DEPLOY_VERSION__
     */
    public function empty_cart()
    {
        \Redshop\Workflow\Cart::emptyCart();
    }

    /**
     * Method to delete cart entry from session
     *
     * @return void
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function delete()
    {
        \Redshop\Workflow\Cart::delete();
    }

    /**
     * Method to delete cart entry from session by ajax
     *
     * @return void
     *
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function ajaxDeleteCartItem()
    {
        \Redshop\Cart\Ajax::deleteCartItem();
    }

    /**
     * discount calculator Ajax Function
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public function discountCalculator()
    {
        ob_clean();
        $get = \JFactory::getApplication()->input->get->getArray();
        \Redshop\Promotion\Discount::discountCalculator($get);

        \JFactory::getApplication()->close();
    }

    /**
     * Method to add multiple products by its product number using mod_redmasscart module.
     *
     * @return void
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function redmasscart()
    {
        \Redshop\Workflow\Cart::redMassCart();
    }

    /**
     * Get Shipping rate function
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public function getShippingRate()
    {
        \Redshop\Cart\Ajax::getShippingRate();
    }

    /**
     * Change Attribute
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public function changeAttribute()
    {
        $post = \JFactory::getApplication()->input->post->getArray();
        $cart = \Redshop\Cart\Cart::modify(\Redshop\Cart\Helper::changeAttribute($post), JFactory::getUser()->id);
        \Redshop\Cart\Helper::setCart($cart);
        \RedshopHelperCart::ajaxRenderModuleCartHtml();

        ?>
        <script type="text/javascript">
            window.parent.location.reload();
        </script>
        <?php
    }

    /**
     * Method called when user pressed cancel button
     *
     * @return void
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function cancel()
    {
        $link = \JRoute::_(
            'index.php?option=com_redshop&view=cart&Itemid=' . \JFactory::getApplication()->input->getInt('Itemid'),
            false
        ); ?>
        <script language="javascript">
            window.parent.location.href = "<?php echo $link ?>";
        </script>
        <?php
        JFactory::getApplication()->close();
    }

    /**
     * Get product tax for ajax request
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     * @deprecated
     * @see  \Redshop\Cart\Ajax::getProductTax();
     */
    public function ajaxGetProductTax()
    {
        \Redshop\Cart\Ajax::getProductTax();
    }
}
