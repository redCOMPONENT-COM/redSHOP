<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_cart
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

$producthelper = new producthelper;
$redhelper     = new redhelper;
$uri           = JURI::getInstance();
$url           = $uri->root(true);
$app           = JFactory::getApplication();
$itemId        = (int) $redhelper->getCartItemid($app->input->getInt('Itemid', 101));

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
	$itemId = (int) $redhelper->getCategoryItemid();
}

$display_button = JText::_('COM_REDSHOP_CHECKOUT');

if ($button_text != "")
{
	$display_button = $button_text;
}

$link = JRoute::_("index.php?option=com_redshop&view=cart&Itemid=" . $itemId);

$cartTotalProduct = $count;
$cartTotallbl = "";
$cartTotalValue = "";
$shippingvalue = "";
$shippinglbl = "";

if (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
{
	$cartTotallbl   = JText::_('COM_REDSHOP_TOTAL') . ':';
	$shippinglbl    = JText::_('COM_REDSHOP_SHIPPING_LBL') . ':';
	$cartTotalValue = $producthelper->getProductFormattedPrice($total);
	$shippingvalue  = $producthelper->getProductFormattedPrice($shipping);
}

?>
<div class="mod_cart_main">
	<div class="mod_cart_top">
		<div class="mod_cart_image"></div>
		<div class="mod_cart_title"><?php echo JText::_('COM_REDSHOP_CART_TEXT');?></div>
	</div>
	<div class="mod_cart_total" id="mod_cart_total">
		<?php
		switch ($output_view)
		{
			case 'simple':
				if ($count)
				{
					$output = '<div class="mod_cart_extend_total_pro_value" id="mod_cart_total_txt_product" >';
					$output .= JText::_('COM_REDSHOP_TOTAL_PRODUCT') . ':' . ' ' . $cartTotalProduct . ' ' . JText::_('COM_REDSHOP_PRODUCTS_IN_CART');
					$output .= '</div>';
				}
				break;

			case 'extended':

				$output = '<div class="mod_cart_products" id="mod_cart_products">';

				if ($count)
				{
					$cartTotalProduct = $idx;

					for ($i = 0; $i < $idx; $i++)
					{
						$carthelper = new rsCarthelper;

						if ($carthelper->rs_multi_array_key_exists('giftcard_id', $cart [$i]) && $cart [$i] ['giftcard_id'])
						{
							$giftcardData = $producthelper->getGiftcardData($cart [$i] ['giftcard_id']);
							$name         = $giftcardData->giftcard_name;
						}
						else
						{
							$product_detail = $producthelper->getProductById($cart [$i] ['product_id']);
							$name           = $product_detail->product_name;
						}

                        $output .= '<div class="mod_cart_product">';
                        $output .= '<div class="mod_cart_product_name">' . $name . ' x ' . $cart [$i] ['quantity'] . '</div>';

						if (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
						{
                            $output .= '<div class="mod_cart_product_price">';
							if ($show_with_vat)
							{
								$output .= JText::_('COM_REDSHOP_PRICE') . " " . $producthelper->getProductFormattedPrice($cart [$i] ['product_price'], true);
							}
							else
							{
								$output .= JText::_('COM_REDSHOP_PRICE') . " " . $producthelper->getProductFormattedPrice($cart [$i] ['product_price_excl_vat'], true);
							}
                            $output .= '</div>';
						}
                        $output .= '</div>';
					}
				}

				$output .= '</div>';
				break;
		}

		if (!empty($count))
		{
            $output .= '<div class="mod_cart_totalprice">';
			$output .= '<div class="mod_cart_total_txt" id="mod_cart_total_txt_ajax" >' . $cartTotallbl . '</div>';
			$output .= '<div class="mod_cart_total_value" id="mod_cart_total_value_ajax">' . $cartTotalValue . '</div>';
			if ($show_shipping_line)
			{
				$output .= '<div class="mod_cart_shipping_txt" id="mod_cart_shipping_txt_ajax" >' . $shippinglbl . '</div>';
				$output .= '<div class="mod_cart_shipping_value" id="mod_cart_shipping_value_ajax">' . $shippingvalue . '</div>';
			}
            $output .= '</div>';
		}
		else
		{
			$output = JText::_('COM_REDSHOP_EMPTY_CART');
		}

		echo $output;
		?>
	</div>

	<?php
	if ($count || $show_empty_btn)
	{
		$styledis = "display:block;";
	}
	else
	{
		$styledis = "display:none;";
	}

	if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . ADDTOCART_BACKGROUND))
	{
        $styledis .= 'background:url(' . REDSHOP_FRONT_IMAGES_ABSPATH.ADDTOCART_BACKGROUND . ');background-position:bottom;background-repeat:no-repeat; ';
    }
	?>

    <div class="mod_cart_checkout" id="mod_cart_checkout_ajax" style="cursor:pointer; <?php echo $styledis; ?>">
        <a href="<?php echo $link; ?>">
            <?php echo $display_button;?></a>
    </div>
</div>