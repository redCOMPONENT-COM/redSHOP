<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_cart
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHtml::stylesheet('mod_redshop_cart/cart.css', false, true);
?>

<?php JFactory::getDocument()->addStyleDeclaration('.mod_cart_checkout{background-color:' . Redshop::getConfig()->get('ADDTOCART_BACKGROUND') . ';}'); ?>

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
					'cartOutput'       => $outputView,
					'totalQuantity'    => $count,
					'cart'             => $cart,
					'showWithVat'      => $showWithVat,
					'showShippingLine' => $showShippingLine,
					'showWithDiscount' => $showWithDiscount
				),
				'',
				array(
					'component' => 'com_redshop'
				)
			);
		?>
	</div>
    <div class="mod_cart_checkout" id="mod_cart_checkout_ajax">
		<?php if ($count || $showEmptyBtn): ?>
        <a href="<?php echo JRoute::_("index.php?option=com_redshop&view=cart&Itemid=" . $itemId); ?>">
            <?php echo $buttonText? $buttonText: JText::_('MOD_REDSHOP_CART_CHECKOUT');?>
		</a>
		<?php endif; ?>
    </div>
</div>
