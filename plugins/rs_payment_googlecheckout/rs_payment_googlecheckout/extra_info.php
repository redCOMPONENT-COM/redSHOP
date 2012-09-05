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



include_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'shipping.php');
$shippinghelper = new shipping();

include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');
$producthelper = new producthelper();
# new
$session =& JFactory::getSession();
$cart = $session->get('cart');

/*echo "<pre>";
print_r($cart);
echo "</pre>";*/

/*$rates = $producthelper->getAllTaxRates();*/

$currencyClass = new convertPrice ( );

$currency_code = "USD"; // currency accepted by google

$order = $data['order'];

$db = JFactory::getDBO ();

$url = JURI::root ();


// Include all the required files


require_once ('library'.DS.'googlecart.php');

require_once ('library'.DS.'googleitem.php');

require_once ('library'.DS.'googleshipping.php');

require_once ('library'.DS.'googletax.php');

$servertype = $this->_params->get("is_test","sandbox");

$merchantid = $this->_params->get("merchant_id","");

$merchantkey = $this->_params->get("merchant_key","");

$buttonsize = $this->_params->get("button_size","medium");

$buttonstyle = $this->_params->get("button_style","white");


if ($buttonsize == "medium") {

	$width = "168";

	$height = "44";
}

elseif ($buttonsize == "small") {

	$width = "160";

	$height = "43";
}

elseif ($buttonsize == "large") {

	$width = "180";

	$height = "46";

}

$conurl = $url . "index.php?option=com_redshop";

$editurl = $url . "index.php?option=com_redshop&view=cart";

/*

 * changing start

 */

$merchant_id = $this->_params->get("merchant_id",""); // Your Merchant ID


$merchant_key = $this->_params->get("merchant_key",""); // Your Merchant Key


$server_type = $this->_params->get("is_test","sandbox");

$currency = "USD";

$cart_google = new GoogleCart ( $merchant_id, $merchant_key, $server_type, $currency );

/*

 * add product items

 */
# get total item in cart
$totalItem = $cart['idx'];
for($p = 0; $p <$totalItem; $p ++){

	$cartItem = $cart[$p];

	$product_id = $cartItem['product_id'];

	$proudcts = JTable::getInstance('product_detail','Table');
	$proudcts->load($product_id);

	$item_price = $currencyClass->convert ( $cartItem['product_price'], '', $currency_code );

	$item = new GoogleItem ( $proudcts->product_name , // Item name


	$proudcts->product_s_desc, // Item      description


	$cartItem['quantity'], // Quantity


	$item_price ); // Unit price


	# cart_attribute
	$private_data = "productid=".$product_id.";";
	$attributes = $cartItem['cart_attribute'];
	$atdata = "";
	$totalAtt = count($attributes);
	for($a=0;$a<$totalAtt;$a++){

		$attribute = $attributes[$a];

		$attribute_id = $attribute['attribute_id'];
		//$atdata .= "attribute=".$attribute_id.";";
		$atdata .= $attribute_id.":";

		if(isset($attribute['attribute_childs'])){

			$property = $attribute['attribute_childs'];
			if(isset($property[0]['property_id'])){
				$property_id = $property[0]['property_id'];
				//$atdata .= "property=".$property_id.";";
				$atdata .= $property_id;
			}
			if(isset($property[0]['property_childs']) && isset($property[0]['property_childs'][0]['subproperty_id'])){
				$subproperty_id = $property[0]['property_childs'][0]['subproperty_id'];
				//$atdata .= "subproperty=".$subproperty_id.";";
				$atdata .= ":".$subproperty_id;
			}
		}

		if($a != $totalAtt-1) $atdata .= "-";

	}
	//if($totalAtt>0)
	$private_data .= "attribute=".$atdata.";";

	# End

	# cart_accessory
	$accessories = $cartItem['cart_accessory'];
	$accessory_id = array();
	for($a=0;$a<count($accessories);$a++){
		$accessory = $accessories[$a];
		$accessory_id[] = $accessory['accessory_id'];
	}

	if(count($accessory_id) > 0){
		$accessorids = implode(",",$accessory_id);
		$private_data .= "accessory=".$accessorids.";";
	}

	$item->SetMerchantPrivateItemData($private_data);

	$cart_google->AddItem ( $item );

}

# discount price as product
$discount_price = (0-$currencyClass->convert ( 0, '', $currency_code ));

if ($discount_price>0){

	$disoucnt_item = new GoogleItem ( JText::_('DISCOUNT'), // Item name
									"", // Item      description
									1, // Quantity
									$discount_price // Unit price
								);

	$cart_google->AddItem ( $disoucnt_item );
}

/* New enhancement
 * Add merchant calculations options
 *
 */
$responce_hangler_url = JUri::root()."plugins/redshop_payment/rs_payment_googlecheckout/responsehandler.php";

$cart_google->SetMerchantCalculations(
		$responce_hangler_url, // merchant-calculations-url
        "true", // merchant-calculated tax
        "true", // accept-merchant-coupons
        "true"); // accept-merchant-gift-certificates

/**
 * Add merchant-calculated-shipping option
 */
$default_shipping_rate = (float) $cart->shipping;
$ship = new GoogleMerchantCalculatedShipping("Shippping Rate", // Shippping method
                                                 $default_shipping_rate); // Default, fallback price
$cart_google->AddShipping($ship);
# End

# set tax information
/*$tax_rule = new GoogleDefaultTaxRule($rates['state_tax']);
$tax_rule->SetStateAreas($rates['state']);
$cart_google->AddDefaultTaxRules($tax_rule);

$taxrule = new GoogleDefaultTaxRule($rates['country_tax']);
$taxrule->AddPostalArea($rates['country']);
$cart_google->AddDefaultTaxRules($taxrule);*/

$tax_rule = new GoogleDefaultTaxRule(0.15);
#$tax_rule->SetWorldArea(true);
$cart_google->AddDefaultTaxRules($tax_rule);
# End

$cart_google->AddRoundingPolicy("UP", "TOTAL");

$cart_google->SetMerchantPrivateData (

new MerchantPrivateData ( array ("subtotal" => $cart['subtotal'] ) ) );


// Add shipping options
/*$shipping_method_name = explode ( "|", $shippinghelper->decryptShipping ( $order->ship_method_id ) );

if (isset ( $shipping_method_name [1] ) && $shipping_method_name [1] != "") {

	$shipping_price = $currencyClass->convert ( $order->order_shipping, '', $currency_code );

	$ship_1 = new GoogleFlatRateShipping ( $shipping_method_name [1], $shipping_price );

	$cart_google->AddShipping ( $ship_1 );

}*/

// Specify "Return to xyz" link


$cart_google->SetContinueShoppingUrl ( $conurl );

$cart_google->SetEditCartUrl($editurl);

// Request buyer's phone number


$cart_google->SetRequestBuyerPhone ( false );

// Display Google Checkout button


return $cart_google->CheckoutButtonCode ( strtoupper ( $buttonsize ) );

/*

 * changing end

 */
//echo "</pre>";
?>
