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

  class GoogleResult {
    var $shipping_name;
    var $address_id;
    var $shippable;
    var $ship_price;

    var $tax_amount;

    var $coupon_arr = array();
    var $giftcert_arr = array();

    function GoogleResult($address_id) {
      $this->address_id = $address_id;
    }

    function SetShippingDetails($name, $price, $shippable = "true") {
      $this->shipping_name = $name;
      $this->ship_price = $price;
      $this->shippable = $shippable;
    }

    function SetTaxDetails($amount) {
      $this->tax_amount = $amount;
    }

    function AddCoupons($coupon) {
      $this->coupon_arr[] = $coupon;
    }

    function AddGiftCertificates($gift) {
      $this->giftcert_arr[] = $gift;
    }
  }

 /* This is a class used to return the results of coupons
  * that the buyer entered code for on the place order page
  */
  class GoogleCoupons {
    var $coupon_valid;
    var $coupon_code;
    var $coupon_amount;
    var $coupon_message;

    function googlecoupons($valid, $code, $amount, $message) {
      $this->coupon_valid = $valid;
      $this->coupon_code = $code;
      $this->coupon_amount = $amount;
      $this->coupon_message = $message;
    }
  }

 /* This is a class used to return the results of gift certificates
  * that the buyer entered code for on the place order page
  */
  class GoogleGiftcerts {
    var $gift_valid;
    var $gift_code;
    var $gift_amount;
    var $gift_message;

    function googlegiftcerts($valid, $code, $amount, $message) {
      $this->gift_valid = $valid;
      $this->gift_code = $code;
      $this->gift_amount = $amount;
      $this->gift_message = $message;
    }
  }
?>
