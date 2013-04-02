<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/category.php';
require_once JPATH_ROOT . '/components/com_redshop/helpers/product.php';
require_once JPATH_ROOT . '/components/com_redshop/helpers/helper.php';

$config        = new Redconfiguration;
$producthelper = new producthelper;
$redhelper     = new redhelper;

$url        = JURI::base();
$option     = JRequest::getVar('option');
$wishlists  = $this->wishlists;
$product_id = JRequest::getInt('product_id');
$user       = JFactory::getUser();

if (!$user->id)
{
	$rows = $this->wish_products;
	echo "<div class='mod_redshop_wishlist'>";

	if (count($rows) > 0)
	{
		// Send mail link
		$mlink = JURI::root() . "index.php?option=com_redshop&view=account&layout=mywishlist&mail=1&tmpl=component";
		echo $mail_link = '<div class="mod_wishlist_mail_icon"><a class="modal" href="' . $mlink . '" rel="{handler:\'iframe\',size:{x:450,y:400}}" ><img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'mailcenter16.png" ></a></div>';
		display_products($rows);
		$reglink = JURI::root() . "index.php?option=com_redshop&view=registration";
		echo "<br /><div><a href=" . $reglink . "><input type='button' value='" . JText::_('COM_REDSHOP_SAVE_WISHLIST') . "></a></div>";
	}
	else
	{
		echo "<div>" . JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') . "</div>";
	}

	echo "</div>";
}
else // If user logged in than display this code.
{
	echo "<div class='mod_redshop_wishlist'>";

	if (count($this->wish_session) > 0)
	{
		$mlink = JURI::root() . "index.php?option=com_redshop&view=account&layout=mywishlist&mail=1&tmpl=component";
		echo $mail_link = '<div class="mod_wishlist_mail_icon"><a class="modal" href="' . $mlink . '" rel="{handler:\'iframe\',size:{x:450,y:400}}" ><img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'mailcenter16.png" ></a></div>';
		display_products($this->wish_session);
		echo "<br />";
		$mywishlist_link = "index.php?option=com_redshop&view=wishlist&task=addtowishlist&tmpl=component";
		echo "<a class=\"modal\" href=\"" . $mywishlist_link . "\" rel=\"{handler:'iframe',size:{x:450,y:350}}\" ><input type='submit' value='" . JText::_('COM_REDSHOP_SAVE_WISHLIST') . "'></a>";
		echo "<br /><br />";
	}

	if (count($wishlists) > 0)
	{
		$wish_products = $this->wish_products;

		// Send mail link
		for ($j = 0; $j < count($wishlists); $j++)
		{
			$rows  = $wish_products[$wishlists[$j]->wishlist_id];
			$mlink = JURI::root() . "index.php?option=com_redshop&view=account&layout=mywishlist&mail=1&tmpl=component&wishlist_id=" . $wishlists[$j]->wishlist_id;
			echo $mail_link = '<div class="mod_wishlist_mail_icon"><a class="modal" href="' . $mlink . '" rel="{handler:\'iframe\',size:{x:450,y:400}}" ><img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'mailcenter16.png" >' . $wishlists[$j]->wishlist_name . '</a></div>';

			display_products($rows);
			echo "<br />";
		}
	}
	else
	{
		echo "<div>" . JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') . "</div>";
	}

	echo "</div>";
}

function display_products($rows)
{
	$url        = JURI::base();
	$option     = JRequest::getVar('option');
	$extra_data = new producthelper;

	$producthelper = new producthelper;
	$redhelper     = new redhelper;

	for ($i = 0; $i < count($rows); $i++)
	{
		$row           = $rows[$i];
		$Itemid        = $redhelper->getItemid($row->product_id);
		$link          = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $row->product_id . '&Itemid=' . $Itemid);
		$product_price = $producthelper->getProductPrice($row->product_id);

		$productArr             = $producthelper->getProductNetPrice($row->product_id);
		$product_price_discount = $productArr['productPrice'] + $productArr['productVat'];

		if ($row->product_full_image)
		{
			echo $thum_image = "<div class='mod_wishlist_product_image' >" .
				$thum_image = $producthelper->getProductImage($row->product_id, $link, "100", "100") . "
			</div>";
		}

		echo "<a href='" . $link . "'>" . $row->product_name . "</a><br>";

		if ($row->product_on_sale && $product_price_discount > 0)
		{
			if ($product_price > $product_price_discount)
			{
				$s_price = $product_price - $product_price_discount;

				if ($this->show_discountpricelayout)
				{
					echo "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>" . $producthelper->getProductFormattedPrice($product_price) . "</span></div>";
					$product_price = $product_price_discount;
					echo "<div id='mod_redmainprice' class='mod_redmainprice'>" . $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
					echo "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) . "</div>";
				}
				else
				{
					$product_price = $product_price_discount;
					echo "<div class='mod_redproducts_price'>" . $producthelper->getProductFormattedPrice($product_price) . "</div>";
				}
			}
			else
			{
				echo "<div class='mod_redproducts_price'>" . $producthelper->getProductFormattedPrice($product_price) . "</div>";
			}
		}
		else
		{
			echo "<div class='mod_redproducts_price'>" . $producthelper->getProductFormattedPrice($product_price) . "</div>";
		}

		echo "<br><a href='" . $link . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>&nbsp;";

		echo $addtocartdata = $producthelper->replaceCartTemplate($row->product_id);

		echo "<div>" . $addtocartdata . "</div>";
	}
}
