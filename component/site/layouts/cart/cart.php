<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperCart');
$productHelper = new producthelper;
$cartHelper = new rsCarthelper;
$cart = $displayData['cart'];
$total = 0;

if (isset($cart) && isset($cart['idx']) && $cart['idx'] > 0)
{
	$total = $cart['mod_cart_total'];
}

if ($displayData['cartOutput'] == 'simple'): ?>
	<div class="mod_cart_extend_total_pro_value" id="mod_cart_total_txt_product" >
	<?php if ($displayData['totalQuantity']): ?>
		<?php echo JText::_('MOD_REDSHOP_CART_TOTAL_PRODUCT') . ': ' . $displayData['totalQuantity'] . ' ' . JText::_('MOD_REDSHOP_CART_PRODUCTS_IN_CART'); ?>
	<?php endif; ?>
	</div>
<?php else: ?>
	<div class="mod_cart_products" id="mod_cart_products">
	<?php if ($displayData['totalQuantity']):
		$total = $cart['mod_cart_total'];
		?>
		<?php for($i = 0; $i < $cart['idx']; $i++):

			if ($cartHelper->rs_multi_array_key_exists('giftcard_id', $cart[$i]) && $cart[$i]['giftcard_id'])
			{
				$giftCardData = $productHelper->getGiftcardData($cart[$i]['giftcard_id']);
				$name         = $giftCardData->giftcard_name;
			}
			else
			{
				$productDetail = RedshopHelperProduct::getProductById($cart[$i]['product_id']);
				$name           = $productDetail->product_name;
			}
			?>
			<div class="mod_cart_product">
				<div class="mod_cart_product_name">
					<?php echo $name . ' x ' . $cart[$i]['quantity']; ?>
				</div>
				<?php if (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)):
					if ($displayData['showWithVat'])
					{
						$price = $cart[$i]['product_price'];
					}
					else
					{
						$price = $cart[$i]['product_price_excl_vat'];
					}
					?>
				<div class="mod_cart_product_price">
					<?php echo JText::_('MOD_REDSHOP_CART_PRICE') . " " . $productHelper->getProductFormattedPrice($price, true); ?>
				</div>
				<?php endif; ?>
			</div>
		<?php endfor; ?>
	<?php endif; ?>
	</div>
<?php endif; ?>
<?php if ((!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)) && $displayData['totalQuantity']): ?>
<div class="mod_cart_totalprice">
	<div class="mod_cart_total_txt cartItemAlign" id="mod_cart_total_txt_ajax" >
		<?php echo JText::_('MOD_REDSHOP_CART_TOTAL'); ?> :
	</div>
	<div class="mod_cart_total_value cartItemAlign" id="mod_cart_total_value_ajax">
		<?php echo $productHelper->getProductFormattedPrice($total); ?>
	</div>
	<div class="clr"></div>
	<?php if ($displayData['showShippingLine']):
		$shippingValue = $cart['shipping'];

		if (!$displayData['showWithVat'])
		{
			if (!isset($cart['shipping_tax']))
			{
				$cart['shipping_tax'] = 0;
			}

			$shippingValue = $cart['shipping'] - $cart['shipping_tax'];
		}
		?>
		<div class="mod_cart_shipping_txt cartItemAlign" id="mod_cart_shipping_txt_ajax" >
			<?php echo JText::_('MOD_REDSHOP_CART_SHIPPING_LBL'); ?> :
		</div>
		<div class="mod_cart_shipping_value cartItemAlign" id="mod_cart_shipping_value_ajax">
			<?php echo $productHelper->getProductFormattedPrice($shippingValue); ?>
		</div>
		<div class="clr"></div>
	<?php endif; ?>
</div>
<?php else: ?>
	<?php echo JText::_('MOD_REDSHOP_CART_EMPTY_CART'); ?>
<?php endif;
