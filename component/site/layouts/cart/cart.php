<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout data
 * ==============================
 * @var   array $displayData Display data.
 */

$cart = $displayData['cart'];
$total = 0;

if (isset($cart) && isset($cart['idx']) && $cart['idx'] > 0) {
    $total = $cart['mod_cart_total'];
}

if ($displayData['cartOutput'] == 'simple'): ?>
    <div class="mod_cart_extend_total_pro_value" id="mod_cart_total_txt_product">
        <?php if ($displayData['totalQuantity']): ?>
            <?php echo JText::_(
                    'MOD_REDSHOP_CART_TOTAL_PRODUCT'
                ) . ' ' . $displayData['totalQuantity'] . ' ' . JText::plural(
                    'MOD_REDSHOP_CART_PRODUCTS_IN_CART',
                    $displayData['totalQuantity']
                ); ?>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="mod_cart_products" id="mod_cart_products">
        <?php if ($displayData['totalQuantity']):
            $total = $cart['mod_cart_total'];
            ?>
            <?php for ($i = 0; $i < $cart['idx']; $i++):

            if (\Redshop\Helper\Utility::rsMultiArrayKeyExists('giftcard_id', $cart[$i]) && $cart[$i]['giftcard_id']) {
                $giftCardData = RedshopEntityGiftcard::getInstance($cart[$i]['giftcard_id'])->getItem();
                $name         = $giftCardData->giftcard_name;
            } else {
                $productDetail = \Redshop\Product\Product::getProductById($cart[$i]['product_id']);
                $name          = $productDetail->product_name;
            }
            ?>
            <div class="mod_cart_product">
                <div class="mod_cart_product_name">
                    <?php echo $name . ' x ' . $cart[$i]['quantity']; ?>
                </div>
                <?php if (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get(
                            'DEFAULT_QUOTATION_MODE'
                        ) && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))):
                    if ($displayData['showWithVat']) {
                        $price = $cart[$i]['product_price'];
                    } else {
                        $price = $cart[$i]['product_price_excl_vat'];
                    }
                    ?>
                    <div class="mod_cart_product_price">
                        <?php echo JText::_('MOD_REDSHOP_CART_PRICE') . " " . RedshopHelperProductPrice::formattedPrice(
                                $price,
                                true
                            ); ?>
                    </div>
                <?php endif; ?>
                <div class="mod_cart_product_delete">
                <a href="javascript:void(0);"
                       onclick="deleteCartItem(<?php echo $i; ?>, '<?php echo JSession::getFormToken() ?>', '<?php echo JUri::root() . 'index.php?option=com_redshop&task=cart.ajaxDeleteCartItem'; ?>')"><?php echo JText::_('COM_REDSHOP_DELETE'); ?></a>
                </div>
            </div>
        <?php endfor; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
<span id="mod_cart_total_quantity" class="hidden"><?php echo $displayData['totalQuantity']; ?></span>
<?php if ((!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get(
                'DEFAULT_QUOTATION_MODE'
            ) && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))) && $displayData['totalQuantity']): ?>
    <div class="mod_cart_totalprice">
        <?php if ($displayData['showShippingLine']):
            $shippingValue = $cart['shipping'];

            if (!$displayData['showWithVat']) {
                if (!isset($cart['shipping_tax'])) {
                    $cart['shipping_tax'] = 0;
                }

                $shippingValue = $cart['shipping'] - $cart['shipping_tax'];
            }
            ?>
            <div class="mod_cart_shipping_txt cartItemAlign" id="mod_cart_shipping_txt_ajax">
                <?php echo JText::_('MOD_REDSHOP_CART_SHIPPING_LBL'); ?> :
            </div>
            <div class="mod_cart_shipping_value cartItemAlign" id="mod_cart_shipping_value_ajax">
                <?php echo RedshopHelperProductPrice::formattedPrice($shippingValue); ?>
            </div>
            <div class="clr"></div>
        <?php endif; ?>

        <?php if (isset($displayData['showWithDiscount'])):
            $discountValue = $cart['discount_ex_vat'];

            if ($displayData['showWithVat']) {
                $discountValue = $cart['discount_ex_vat'] + $cart['discount_vat'];
            }

            if ($discountValue > 0) :
                ?>
                <div class="mod_cart_discount_txt cartItemAlign" id="mod_cart_discount_txt_ajax">
                    <?php echo JText::_('MOD_REDSHOP_CART_DISCOUNT_LBL'); ?> :
                </div>
                <div class="mod_cart_discount_value cartItemAlign" id="mod_cart_discount_value_ajax">
                    <?php echo RedshopHelperProductPrice::formattedPrice($discountValue); ?>
                </div>
                <div class="clr"></div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="mod_cart_total_txt cartItemAlign" id="mod_cart_total_txt_ajax">
            <?php echo JText::_('MOD_REDSHOP_CART_TOTAL'); ?>
        </div>
        <div class="mod_cart_total_value cartItemAlign" id="mod_cart_total_value_ajax">
            <?php echo RedshopHelperProductPrice::formattedPrice($total); ?>
        </div>
        <div class="clr"></div>

    </div>
    <?php
    // Tweak by Ronni START - Message when order total is below order minimum	
    $totalInclVat	  = $total * 1.25;
    $orderMinimumDiff = Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL') - $totalInclVat;
    
    if ($orderMinimumDiff > 0) { ?>
        <div class="price_box price_box_orange hasPopover" style="font-size: 12px!important" 
                title="<?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_TIP'); ?>" 
                data-content="<?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_TIP1'); ?>">
            <li class="fas fa-info-circle"></li> 
            <?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_LBL4'); ?>
            <b>
                <?php echo RedshopHelperProductPrice::formattedPrice($orderMinimumDiff); ?>
            </b>
            <?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_LBL5'); ?>
        </div> <?php
    }
    // Tweak by Ronni END - Message when order total is below order minimum ?>
<?php else: ?>
    <!-- // Tweak by Ronni - Add div class="mod_cart_empty" -->
    <div class="mod_cart_empty">
        <?php echo JText::_('MOD_REDSHOP_CART_EMPTY_CART'); ?>
    </div>
<?php endif;
?>
