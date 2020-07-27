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

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function deleteCartItem() {
        \Redshop\Helper\Ajax::validateAjaxRequest();

        $app         = JFactory::getApplication();
        $input       = $app->input;
        $cartElement = $input->post->getInt('idx');

        $input->set('ajax_cart_box', 1);
        \Redshop\Cart\Helper::removeItemCart($cartElement);

        \RedshopHelperCart::addCartToDatabase();
        \RedshopHelperCart::ajaxRenderModuleCartHtml();

        $cartObject = \RedshopHelperCart::renderModuleCartHtml(\Redshop\Cart\Helper::getCart());

        echo $cartObject->cartHtml? $cartObject->cartHtml: '' ;

        $app->close();
    }

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function getShippingRate()
    {
        echo \Redshop\Shipping\Rate::calculate();

        \JFactory::getApplication()->close();
    }

    /**
     * Change Attribute
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public static function changeAttribute()
    {
        $post = \JFactory::getApplication()->input->post->getArray();
        $cart = \Redshop\Cart\Cart::modify(
            \Redshop\Cart\Helper::changeAttribute($post),
            \JFactory::getUser()->id
        );

        \Redshop\Cart\Helper::setCart($cart);
        \Redshop\Cart\Ajax::renderModuleCartHtml();

        ?>
        <script type="text/javascript">
            window.parent.location.reload();
        </script>
        <?php
    }

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function cancel() {
        $link = \JRoute::_(
            'index.php?option=com_redshop&view=cart&Itemid=' . \JFactory::getApplication()->input->getInt('Itemid'),
            false
        ); ?>
        <script language="javascript">
            window.parent.location.href = "<?php echo $link ?>";
        </script>
        <?php
        \JFactory::getApplication()->close();
    }
}
