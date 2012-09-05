<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$producthelper = new producthelper ( );
$redhelper = new redhelper ( );
$uri = JURI::getInstance ();
$url = $uri->root (true);
$Itemid = JRequest::getVar ( 'Itemid' );
$Itemid = $redhelper->getCartItemid ( $Itemid );

if ($Itemid == "" || $Itemid == 0) {

	$cItemid = $redhelper->getItemid();
	$tmpItemid = $cItemid;

} else {
	$tmpItemid = $Itemid;

}
$display_button = JTEXT::_ ( 'CHECKOUT' );
if($button_text!="")
{
	$display_button = $button_text;
}
$link = JRoute::_ ( "index.php?option=com_redshop&view=cart&Itemid=" . $tmpItemid );

$cartTotalProduct = $count;
$cartTotallbl = "";
$cartTotalValue = "";
$shippingvalue		= "";
$shippinglbl = "";
if (! DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
{
	$cartTotallbl = JTEXT::_ ( 'TOTAL' ).':';
	$shippinglbl	= JTEXT::_ ( 'SHIPPING_LBL' ).':';
	$cartTotalValue = $producthelper->getProductFormattedPrice ( $total );
	$shippingvalue	= $producthelper->getProductFormattedPrice ( $shipping );
}

?>
<div class="mod_cart_main">
<div class="mod_cart_top">
	<div class="mod_cart_image"></div>
	<div class="mod_cart_title"><?php echo JTEXT::_ ( 'CART_TEXT' );?></div>
</div>
<div class="mod_cart_total" id="mod_cart_total">
<?php
switch ($output_view)
{
	case 'simple':
		if ($count)
		{
			$output  ='<div class="mod_cart_extend_total_pro_value" id="mod_cart_total_txt_product" >';
			$output .= JTEXT::_ ( 'TOTAL_PRODUCT' ). ':' . ' '. $cartTotalProduct. ' ' . JTEXT::_ ( 'PRODUCTS_IN_CART' );
			$output .='</div>';
		}
		break;

	case 'extended':

		$output = '<div class="mod_cart_products" id="mod_cart_products">';
 		if ($count)
		{
			$cartTotalProduct = $idx;

			for($i = 0; $i < $idx; $i ++)
			{
				$carthelper 		= new rsCarthelper();
				if($carthelper->rs_multi_array_key_exists('giftcard_id',$cart [$i]) && $cart [$i] ['giftcard_id'])
				{
					$giftcardData = $producthelper->getGiftcardData($cart [$i] ['giftcard_id']);
					$name = $giftcardData->giftcard_name;
				}
				else
				{
					$product_detail = $producthelper->getProductById ( $cart [$i] ['product_id'] );
					$name = $product_detail->product_name;
				}
				$output .= $cart [$i] ['quantity'] . " x " . $name . "<br />";
				if (! DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
				{
					if ($show_with_vat)
					{
						$output .= JText::_ ( 'PRICE' ) . " " . $producthelper->getProductFormattedPrice ( $cart [$i] ['product_price'], true ) . "<br />";
					}
					else
					{
						$output .= JText::_ ( 'PRICE' ) . " " . $producthelper->getProductFormattedPrice ( $cart [$i] ['product_price_excl_vat'], true ) . "<br />";
					}
				}
			}
		}

		$output .='</div>';
 	break;
}

if(!empty($count))
{
	$output .= '<div class="mod_cart_total_txt" id="mod_cart_total_txt_ajax" >'.$cartTotallbl.'</div>';
	$output .= '<div class="mod_cart_total_value" id="mod_cart_total_value_ajax">'.$cartTotalValue.'</div>';
	if($show_shipping_line)
	{
		$output .= '<div class="mod_cart_shipping_txt" id="mod_cart_shipping_txt_ajax" >'.$shippinglbl.'</div>';
		$output .= '<div class="mod_cart_shipping_value" id="mod_cart_shipping_value_ajax">'.$shippingvalue.'</div>';
	}
}else{
	$output = JTEXT::_ ( 'EMPTY_CART' );
}
echo $output;
?>
</div>

<?php	if($count || $show_empty_btn)
		{
			$styledis="display:block;";
		} else {
			$styledis="display:none;";
		}


if(is_file(JPATH_SITE."/components/com_redshop/assets/images/".ADDTOCART_BACKGROUND))
{ ?>
	<div class="mod_cart_checkout" id="mod_cart_checkout_ajax" style="cursor:pointer;background:url('<?php echo JURI::Base();?>/components/com_redshop/assets/images/<?php echo ADDTOCART_BACKGROUND;?>');background-position:bottom;background-repeat:no-repeat; <?php echo $styledis;?>">
		<a href="<?php echo $link; ?>"><?php echo $display_button;?></a>
	</div>
<?php
}
else
{?>
	<div style="cursor:pointer; <?php echo $styledis;?>">
		<a href="<?php echo $link; ?>"><?php echo JText::_('CHECKOUT' );?></a>
	</div>

<?php }
?>
</div>
