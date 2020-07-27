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
 * @since  2.1.0
 */
class Render
{
    /**
     * Method for render cart, replace tag in template
     *
     * @param   integer  $productId         Product Id
     * @param   integer  $categoryId        Category Id
     * @param   integer  $accessoryId       Accessory Id
     * @param   integer  $relatedProductId  Related product Id
     * @param   string   $content           Template content
     * @param   boolean  $isChild           Is child product?
     * @param   array    $userFields        User fields
     * @param   integer  $totalAttr         Total attributes
     * @param   integer  $totalAccessory    Total accessories
     * @param   integer  $countNoUserField  Total user fields
     * @param   integer  $moduleId          Module Id
     * @param   integer  $giftCardId        Giftcard Id
     *
     * @return  mixed|string
     * @throws  \Exception
     *
     * @since   2.1.0
     */
    public static function replace(
        $productId = 0,
        $categoryId = 0,
        $accessoryId = 0,
        $relatedProductId = 0,
        $content = "",
        $isChild = false,
        $userFields = array(),
        $totalAttr = 0,
        $totalAccessory = 0,
        $countNoUserField = 0,
        $moduleId = 0,
        $giftCardId = 0
    ) {
        \JPluginHelper::importPlugin('redshop_product');

        $input           = \JFactory::getApplication()->input;
        $productPreOrder = '';
        $userId          = \JFactory::getUser()->id;
        $fieldSection    = \RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD;

        if ($relatedProductId != 0) {
            $productId = $relatedProductId;
        } elseif ($giftCardId != 0) {
            $productId = $giftCardId;
        }

        if ($giftCardId != 0) {
            $product      = \RedshopEntityGiftcard::getInstance($giftCardId)->getItem();
            $fieldSection = \RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD;
        } else {
            $product = \Redshop\Product\Product::getProductById($productId);

            if (isset($product->preorder)) {
                $productPreOrder = $product->preorder;
            }
        }

        $taxExemptAddToCart = \RedshopHelperCart::taxExemptAddToCart($userId, true);
        $cartTemplate       = \Redshop\Template\Helper::getAddToCart($content);

        if (null === $cartTemplate) {
            if (!empty($content)) {
                $cartTemplate                = new \stdClass;
                $cartTemplate->name          = "";
                $cartTemplate->template_desc = "";
            } else {
                $cartTemplate                = new \stdClass;
                $cartTemplate->name          = "notemplate";
                $cartTemplate->template_desc = "<div>{addtocart_link}</div>";
                $content                     = "{form_addtocart:$cartTemplate->name}";
            }
        }

        // IF PRODUCT CHILD IS EXISTS THEN DONT SHOW PRODUCT ATTRIBUTES
        if ($isChild) {
            return str_replace("{form_addtocart:$cartTemplate->name}", "", $content);
        } elseif (\RedshopHelperProduct::isProductDateRange($userFields, $productId)) {
            // New type custom field - Selection based on selected conditions
            return str_replace(
                "{form_addtocart:$cartTemplate->name}",
                \JText::_('COM_REDSHOP_PRODUCT_DATE_FIELD_EXPIRED'),
                $content
            );
        } elseif ($product->not_for_sale) {
            return str_replace("{form_addtocart:$cartTemplate->name}", '', $content);
        } elseif (!$taxExemptAddToCart) {
            $content = str_replace("{form_addtocart:$cartTemplate->name}", '', $content);

            return $content;
        } elseif (!\Redshop::getConfig()->get('SHOW_PRICE')) {
            return str_replace("{form_addtocart:$cartTemplate->name}", '', $content);
        } elseif ($product->expired == 1) {
            return str_replace(
                "{form_addtocart:$cartTemplate->name}",
                \Redshop::getConfig()->get('PRODUCT_EXPIRE_TEXT'),
                $content
            );
        }

        $cartForm = $cartTemplate->template_desc ?? '';

        $cartTemplateWapper = \RedshopTagsReplacer::_(
            'addtocart',
            $cartForm,
            array(
                'productId'          => $productId,
                'product'            => $product,
                'totalAttr'          => $totalAttr,
                'accessoryId'        => $accessoryId,
                'relatedProductId'   => $relatedProductId,
                'productPreOrder'    => $productPreOrder,
                'product'            => $product,
                'userId'             => $userId,
                'giftcardId'         => $giftCardId,
                'totalAccessory'     => $totalAccessory,
                'countNoUserField'   => $countNoUserField,
                'cartTemplate'       => $cartTemplate,
                'categoryId'         => $categoryId,
                'content'            => $content,
                'isChild'            => $isChild,
                'taxExemptAddToCart' => $taxExemptAddToCart,
                'userFields'         => $userFields,
                'fieldSection'       => $fieldSection,
                'cartForm'           => $cartForm
            )
        );

        if (isset($cartTemplate->name)) {
            $content = str_replace("{form_addtocart:$cartTemplate->name}", $cartTemplateWapper, $content);
        }

        return $content;
    }


    /**
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public static function getTemplateCart() {
        $template = '';

        if (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) {
            $template = \RedshopHelperTemplate::getTemplate("quotation_cart");
        } else {
            if (!\Redshop::getConfig()->get('USE_AS_CATALOG')) {
                $template = \RedshopHelperTemplate::getTemplate("cart");
            } else {
                $template = \RedshopHelperTemplate::getTemplate("catalogue_cart");
            }
        }

        return $template;
    }

    /**
     * @param array $cart
     * @return stdClass
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function moduleCart($cart = array())
    {
        $cart             = empty($cart) ? \Redshop\Cart\Helper::getCart() : $cart;
        $return           = new stdClass();
        $totalQuantity    = 0;
        $idx              = $cart['idx'];
        $cartParams       = \Redshop\Cart\Module::getParams();
        $html             = (string)$cartParams->get('cart_output', 'simple');
        $showShippingLine = (int)$cartParams->get('show_shipping_line', 0);
        $showWithVAT      = (int)$cartParams->get('show_with_vat', 0);
        $ajax             = \JFactory::getApplication()->input->getInt('ajax_cart_box');

        for ($i = 0; $i < $idx; $i++) {
            $totalQuantity += $cart[$i]['quantity'];
        }

        // Load cart module language
        $lang = \JFactory::getLanguage();
        $lang->load('mod_redshop_cart', JPATH_SITE);

        $return->cartHtml = \RedshopLayoutHelper::render(
            'cart.cart',
            array(
                'cartOutput'       => $html,
                'totalQuantity'    => $totalQuantity,
                'cart'             => $cart,
                'showWithVat'      => $showWithVAT,
                'showShippingLine' => $showShippingLine
            ),
            '',
            array('option' => 'com_redshop')
        );

        $return->totalQuantity = $totalQuantity;

        $shippingRateHtml = \Redshop\Shipping\Rate::getFreeShippingRate();

        if ($ajax === 1 && \Redshop::getConfig()->getBool('AJAX_CART_BOX')) {
            echo '`' . $return->cartHtml . '`' . $shippingRateHtml;
            \JFactory::getApplication()->close();
        }

        return $return;
    }
}
