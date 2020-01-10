<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_cart
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$token     = JSession::getFormToken();
$redhelper = redhelper::getInstance();
$app       = JFactory::getApplication();
$itemId    = (int) RedshopHelperRouter::getCartItemId();

$getNewItemId = true;

if ($itemId != 0)
{
	$menu = $app->getMenu();
	$item = $menu->getItem($itemId);

	$getNewItemId = false;

	if (isset($item->id) === false)
	{
		$getNewItemId = true;
	}
}

if ($getNewItemId)
{
	$itemId = (int) RedshopHelperRouter::getCategoryItemid();
}

$displayButton = JText::_('MOD_REDSHOP_CART_CHECKOUT');

if ($button_text != "")
{
	$displayButton = $button_text;
}

JFactory::getDocument()->addStyleDeclaration(
	'.mod_cart_checkout{background-color:' . Redshop::getConfig()->get('ADDTOCART_BACKGROUND') . ';}'
);
?>
<div class="mod_cart_main">
	<div class="mod_cart_top">
		<div class="mod_cart_image"></div>
		<div class="mod_cart_title"><?php echo JText::_('MOD_REDSHOP_CART_CART_TEXT');?></div>
	</div>
	<div class="mod_cart_total" id="mod_cart_total">
		<?php
		echo RedshopLayoutHelper::render(
				'cart.cart',
				array(
					'cartOutput'       => $output_view,
					'totalQuantity'    => $count,
					'cart'             => $cart,
					'showWithVat'      => $show_with_vat,
					'showShippingLine' => $show_shipping_line,
					'showWithDiscount' => $show_with_discount
				),
				'',
				array(
					'component' => 'com_redshop'
				)
			);
		?>
	</div>
    <div class="mod_cart_checkout" id="mod_cart_checkout_ajax">
		<?php if ($count || $show_empty_btn): ?>
        <a href="<?php echo JRoute::_("index.php?option=com_redshop&view=cart&Itemid=" . $itemId); ?>">
            <?php echo $displayButton;?>
		</a>
		<?php endif; ?>
    </div>
</div>

<script type="text/javascript">
    function deleteCartItem(idx)
    {
        jQuery.ajax({
            type: "POST",
            data: {
                "idx": idx,
                "<?php echo $token ?>": "1"
            },
            url: "<?php echo JUri::root() . 'index.php?option=com_redshop&task=cart.ajaxDeleteCartItem'; ?>",
            success: function(data) {
                responce = data.split("`");

                if (jQuery('#mod_cart_total') && responce[1]) {
                    jQuery('#mod_cart_total').html(responce[1]);
                }
		    
                if (jQuery('#rs_promote_free_shipping_div') && responce[2]) {
                    jQuery('#rs_promote_free_shipping_div').html(responce[2]);
                }
		    
                if (jQuery('#mod_cart_checkout_ajax')) {
                    jQuery('#mod_cart_checkout_ajax').css("display", "inline-block");
                }
            }
        });
    }
</script>
