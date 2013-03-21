<?php

/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
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

/* This class is used to create items to be added to the shopping cart
  * Invoke a separate instance of this class for each item to be
  * added to the cart.
  * Required fields are the item name, description, quantity and price
  * The private-data and tax-selector for each item can be set in the
  * constructor call or using individual Set functions
  */
class GoogleItem
{
	var $item_name;
	var $item_description;
	var $unit_price;
	var $quantity;
	var $merchant_private_item_data;
	var $merchant_item_id;
	var $tax_table_selector;
	var $email_delivery;
	var $digital_content = false;
	var $digital_description;
	var $digital_key;
	var $digital_url;

	function GoogleItem($name, $desc, $qty, $price)
	{
		$this->item_name = $name;
		$this->item_description = $desc;
		$this->unit_price = $price;
		$this->quantity = $qty;
	}

	function SetMerchantPrivateItemData($private_data)
	{
		$this->merchant_private_item_data = $private_data;
	}

	function SetMerchantItemId($item_id)
	{
		$this->merchant_item_id = $item_id;
	}

	function SetTaxTableSelector($tax_selector)
	{
		$this->tax_table_selector = $tax_selector;
	}

	function SetEmailDigitalDelivery($email_delivery = 'false')
	{
		$this->digital_url = '';
		$this->digital_key = '';
		$this->digital_description = '';
		$this->email_delivery = $email_delivery;
		$this->digital_content = true;
	}

	function SetURLDigitalContent($digital_url, $digital_key, $digital_description)
	{
		$this->digital_url = $digital_url;
		$this->digital_key = $digital_key;
		$this->digital_description = $digital_description;
		$this->email_delivery = 'false';
		$this->digital_content = true;
	}
}
?>
