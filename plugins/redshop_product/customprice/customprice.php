<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgredshop_productcustomprice extends JPlugin
{
	/**
	 * Example prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param    object        The Product Template Data
	 * @param    object        The product params
	 * @param    object        The product object
	 */
	public function onPrepareProduct(&$template, &$params, $product)
	{
		$document = JFactory::getDocument();
		$document->addScriptDeclaration("
			function getExtraParams(frm){
				return '&product_custom_price='+$('#plg_product_custom_price').val();
			}
		");

		$product_custom_price_html = "<input class='inputbox' id='plg_product_custom_price' type='text' name='product_custom_price' value='' />";
		$template = str_replace("{product_custom_price}", $product_custom_price_html, $template);
	}

	/**
	 * gallary update cart session variables
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param    object         The Product Template Data
	 * @param    object         The product params
	 * @param    int            The product object     *
	 */
	public function onBeforeSetCartSession(& $cart, $data)
	{
		if (!isset($data['product_custom_price']))
		{
			return;
		}

		$idx = $cart['idx'];

		$cart[$idx]['product_price'] = $data['product_custom_price'];
		$cart[$idx]['product_old_price'] = $data['product_custom_price'];
		$cart[$idx]['product_old_price_excl_vat'] = $data['product_custom_price'];
		$cart[$idx]['product_price_excl_vat'] = $data['product_custom_price'];

		// set product custom price
		$cart['product_custom_price'][$cart[$idx]['product_id']] = $data['product_custom_price'];

		return;
	}

	/**
	 * update cart session variables
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param    object         The Product Template Data
	 * @param    object         The product params
	 * @param    int            The product object     *
	 */
	public function onSameCartProduct(& $cart, $data, $i)
	{
		if (!isset($data['product_custom_price']))
		{
			return;
		}

		$cart[$i]['product_price'] = $data['product_custom_price'];
		$cart[$i]['product_old_price'] = $data['product_custom_price'];
		$cart[$i]['product_old_price_excl_vat'] = $data['product_custom_price'];
		$cart[$i]['product_price_excl_vat'] = $data['product_custom_price'];

		// set product custom price
		$cart['product_custom_price'][$cart[$i]['product_id']] = $data['product_custom_price'];
	}

	/**
	 * update cart session variables
	 *
	 * Method is called by the redSHOP product frontend helper from getProductNetPrice function
	 *
	 * @param    int             The product id
	 *
	 * @return  int/boolean  return product price if success else return false
	 */
	public function setProductCustomPrice($product_id)
	{
		$session = JFactory::getSession();
		$cart = $session->get('cart');

		$prices = $cart['product_custom_price'];

		if (isset($prices[$product_id]))
		{
			return $prices[$product_id];
		}
		else
		{
			return false;
		}
	}

}
