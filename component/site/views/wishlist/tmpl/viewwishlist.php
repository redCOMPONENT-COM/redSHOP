<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$app           = JFactory::getApplication();
$producthelper = productHelper::getInstance();

$url       = JURI::base();
$user      = JFactory::getUser();
$itemId    = $app->input->getInt('Itemid');
$productId = $app->input->getInt('product_id');
$wishlists = $this->wishlists;

$pageTitle = JText::_('COM_REDSHOP_MY_WISHLIST');

$redTemplate       = Redtemplate::getInstance();
$extraField        = extraField::getInstance();
$template          = RedshopHelperTemplate::getTemplate("wishlist_template");
$wishlistData1     = $template[0]->template_desc;
$returnArr         = $producthelper->getProductUserfieldFromTemplate($wishlistData1);
$templateUserField = $returnArr[0];
$userFieldArray    = $returnArr[1];

if ($this->params->get('show_page_heading', 1))
{
	?>
	<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $pageTitle; ?></h1>
	<div>&nbsp;</div>
	<?php
}

if (!$user->id)
{
	$rows = $this->wish_session;

	echo "<div class='mod_redshop_wishlist'>";

	if (count($rows) > 0)
	{
		// Send mail link
		$mlink = JURI::root() . "index.php?option=com_redshop&view=account&layout=mywishlist&mail=1&tmpl=component";

		displayProducts($rows);
		$reglink          = JRoute::_("index.php?wishlist=1&option=com_redshop&view=login&Itemid=" . $itemId);
		$myproductid      = '';
		$countNoUserField = 0;

		for ($p = 0, $pn = count($rows); $p < $pn; $p++)
		{
			for ($ui = 0; $ui < count($userFieldArray); $ui++)
			{
				$productUserFields = $extraField->list_all_user_fields($userFieldArray[$ui], 12, '', 0, 0, $rows[$p]->product_id);

				$ufield .= $productUserFields[1];

				if ($productUserFields[1] != "")
				{
					$countNoUserField++;
				}
			}

			$myproductid .= $rows[$p]->product_id . ",";
		}
		?>
		<script language="javascript">
			function clickMe() {
				window.location = "<?php echo $reglink ?>";
			}
		</script>

		<?php

		if ($countNoUserField > 0)
		{
			echo "<br /><div id='saveid' style='clear:both;' style='display:block'><form method='post' ><input type='hidden' name='product_id' value='" . $myproductid . "' ><input type='button' onClick='return productalladdprice(1)'  value='" . JText::_('SAVE_WISHLIST') . "' ></form></div>";
		}
		else
		{
			echo "<br /><div id='saveid' style='clear:both;'><input type='button' onClick='clickMe()'  value='" . JText::_('COM_REDSHOP_SAVE_WISHLIST') . "' ></div>";
		}
	}
	else
	{
		echo "<div>" . JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') . "</div>";
	}

	echo "</div>";
}
else
{
	// If user logged in than display this code.
	echo "<div class='mod_redshop_wishlist'>";

	if (!empty($this->wish_session))
	{
		$mlink            = JURI::root() . "index.php?option=com_redshop&view=account&layout=mywishlist&mail=1&tmpl=component";
		$rows             = $this->wish_session;
		$myproductid      = '';
		$countNoUserField = 0;
		displayProducts($rows);

		for ($p = 0, $pn = count($rows); $p < $pn; $p++)
		{
			for ($ui = 0; $ui < count($userFieldArray); $ui++)
			{
				$productUserFields = $extraField->list_all_user_fields($userFieldArray[$ui], 12, '', 0, 0, $rows[$p]->product_id);

				$ufield .= $productUserFields[1];

				if ($productUserFields[1] != "")
				{
					$countNoUserField++;
				}
			}

			$myproductid .= $rows[$p]->product_id . ",";
		}

		echo "<br />";
		$myWishlistLink = "index.php?tmpl=component&option=com_redshop&view=wishlist&task=addtowishlist&tmpl=component";

		if ($countNoUserField > 0)
		{
			echo "<br /><div style='clear:both;' ><a class=\"redcolorproductimg\" href=\"" . $myWishlistLink . "\"  ><form method='post' ><input type='hidden' name='product_id' value='" . $myproductid . "' ><input type='button' onClick='return productalladdprice(2)'  value='" . JText::_('COM_REDSHOP_SAVE_WISHLIST') . "' ></form></a></div>";
		}
		else
		{
			echo "<div style=\"clear:both;\" ><a class=\"redcolorproductimg\" href=\"" . $myWishlistLink . "\"  ><input type='button'  value='" . JText::_('COM_REDSHOP_SAVE_WISHLIST') . "'></a></div><br /><br />";
		}
	}

	if (count($wishlists) > 0)
	{
		$wishProducts = $this->wish_products;

		// Send mail link
		echo "<table>";

		foreach ($wishlists as $wishlist)
		{
			$wishlist_link = JRoute::_("index.php?view=account&layout=mywishlist&wishlist_id=" . $wishlist->wishlist_id . "&option=com_redshop&Itemid=" . $itemId);
			$del_wishlist  = JRoute::_("index.php?view=wishlist&task=delwishlist&wishlist_id=" . $wishlist->wishlist_id . "&option=com_redshop&Itemid=" . $itemId);
			echo "<tr><td><a href=\"" . $wishlist_link . "\">" . $wishlist->wishlist_name . "</a></td>"
				. "<td><a href=\"" . $del_wishlist . "\">" . JText::_('COM_REDSHOP_DELETE') . "</a></td></tr>";
		}

		echo "</table>";
	}
	elseif (count($this->wish_session) <= 0 && count($wishlists) <= 0)
	{
		echo "<div>" . JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') . "</div>";
	}

	echo "</div>";
}

/**
 * @param   array  $rows  Array of products object
 *
 *
 * @since  __DEPLOY_VERSION__
 *
 * @throws Exception
 */
function displayProducts($rows)
{
	$extraField    = extraField::getInstance();
	$session       = JFactory::getSession();
	$producthelper = productHelper::getInstance();
	$template      = RedshopHelperTemplate::getTemplate("wishlist_template");

	if (!empty($template))
	{
		foreach ($rows as $row)
		{
			$Itemid = RedshopHelperRouter::getItemId($row->product_id);
			$link   = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . $Itemid);

			$product_price          = $producthelper->getProductPrice($row->product_id);
			$product_price_discount = RedshopHelperProductPrice::getNetPrice($row->product_id);

			echo "<div id='wishlist_box'>";

			if ($row->product_full_image)
			{
				echo $thum_image = "<div class='wishlist_left'><div class='mod_wishlist_product_image wishlist_image'>" .
					$thum_image = $producthelper->getProductImage($row->product_id, $link, "85", "63") . "</div></div>";
			}
			else
			{
				$maindefaultpath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
				echo $thum_image = "<div class='wishlist_left'><div class='mod_wishlist_product_image wishlist_image'><a href='" . $link . "'><img src='" . $maindefaultpath . "' height='85' width='63' /></a></div></div>";
			}

			echo "<div class='wishlist_center'><div class='wishlist_title'><a href='" . $link . "'>" . $row->product_name . "</a></div><br>";

			if (!$row->not_for_sale)
			{
				if ($row->product_on_sale && $product_price_discount > 0)
				{
					if ($product_price > $product_price_discount)
					{
						$s_price = $product_price - $product_price_discount;

						if ($this->show_discountpricelayout)
						{
							echo "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>" . RedshopHelperProductPrice::formattedPrice($product_price) . "</span></div>";
							$product_price = $product_price_discount;
							echo "<div id='mod_redmainprice' class='mod_redmainprice wishlist_price'>" . RedshopHelperProductPrice::formattedPrice($product_price_discount) . "</div>";
							echo "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . RedshopHelperProductPrice::formattedPrice($s_price) . "</div>";
						}
						else
						{
							$product_price = $product_price_discount;
							echo "<div class='mod_redproducts_price wishlist_price'>" . RedshopHelperProductPrice::formattedPrice($product_price) . "</div>";
						}
					}
					else
					{
						echo "<div class='mod_redproducts_price wishlist_price'>" . RedshopHelperProductPrice::formattedPrice($product_price) . "</div>";
					}
				}
				else
				{
					echo "<div class='mod_redproducts_price wishlist_price'>" . RedshopHelperProductPrice::formattedPrice($product_price) . "</div>";
				}
			}

			echo "<br><div class='wishlist_readmore'><a href='" . $link . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a></div>&nbsp;</div> ";

			$addtocartdata = $producthelper->replaceCartTemplate($row->product_id, 0, 0, $row->product_id);

			echo "<div class='wishlist_right'>" . $addtocartdata . "</div><br class='clear' /></div><br class='clear' />";
		}
	}
	else
	{
		$ph_thumb       = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
		$pw_thumb       = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
		$wishlist_data1 = $template[0]->template_desc;

		$mlink          = JURI::root() . "index.php?option=com_redshop&view=account&layout=mywishlist&mail=1&tmpl=component";
		$mail_link      = '<a class="redcolorproductimg" href="' . $mlink . '"  ><img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'mailcenter16.png" ></a>';
		$wishlist_data1 = str_replace('{mail_link}', $mail_link, $wishlist_data1);
		$template_d1    = explode("{product_loop_start}", $wishlist_data1);
		$template_d2    = explode("{product_loop_end}", $template_d1[1]);
		$temp_template  = '';
		$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);
		$mainid = '';
		$totattid = '';
		$totcount_no_user_field = '';

		foreach ($rows as $row)
		{
			$wishlistData = $template_d2[0];

			$Itemid = RedshopHelperRouter::getItemId($row->product_id);
			$link   = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . $Itemid);

			$product_price          = $producthelper->getProductPrice($row->product_id);
			$product_price_discount = RedshopHelperProductPrice::getNetPrice($row->product_id);

			if ($row->product_full_image)
			{
				$thum_image   = $producthelper->getProductImage($row->product_id, $link, $pw_thumb, $ph_thumb);
				$wishlistData = str_replace('{product_thumb_image}', $thum_image, $wishlistData);
			}
			else
			{
				$maindefaultpath = RedshopHelperMedia::getImagePath(
					Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE'),
					'',
					'thumb',
					'product',
					$pw_thumb,
					$ph_thumb,
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
				$thum_image      = "<a href='" . $link . "'><img src='" . $maindefaultpath . "'  /></a>";
				$wishlistData    = str_replace('{product_thumb_image}', $thum_image, $wishlistData);
			}

			$pname   = "<a href='" . $link . "'>" . $row->product_name . "</a>";
			$pnumber = $row->product_number;
			$pdesc   = $row->product_s_desc;

			// Checking for child products start
			if (strstr($wishlistData, "{child_products}"))
			{
				$parentproductid = $row->product_id;

				if ($this->data->product_parent_id != 0)
				{
					$parentproductid = $producthelper->getMainParentProduct($row->product_id);
				}

				$frmChild = "";

				if ($parentproductid != 0)
				{
					$productInfo = RedshopHelperProduct::getProductById($parentproductid);

					// Get child products
					$childproducts = $model->getAllChildProductArrayList(0, $parentproductid);

					if (!empty($childproducts))
					{
						$childproducts = array_merge(array($productInfo), $childproducts);

						$cld_name = array();

						if (count($childproducts) > 0)
						{
							$parentid = 0;

							for ($c = 0, $cn = count($childproducts); $c < $cn; $c++)
							{
								if ($childproducts[$c]->product_parent_id == 0)
								{
									$level = "";
								}
								else
								{
									if ($parentid != $childproducts[$c]->product_parent_id)
									{
										$level = $level;
									}
								}

								$parentid = $childproducts[$c]->product_parent_id;

								$childproducts[$c]->product_name = $level . $childproducts[$c]->product_name;
							}

							$cld_name = array_merge($cld_name, $childproducts);
						}

						$selected                  = array($row->product_id);
						$lists['product_child_id'] = JHTML::_('select.genericlist', $cld_name, 'pid', 'class="inputbox" size="1"  onchange="document.frmChild.submit();"', 'product_id', 'product_name', $selected);

						$frmChild .= "<form name='frmChild' method='get'>";
						$frmChild .= JText::_('COM_REDSHOP_CHILD_PRODUCTS') . $lists ['product_child_id'];
						$frmChild .= "<input type='hidden' name='Itemid' value='" . $Itemid . "'>";
						$frmChild .= "<input type='hidden' name='cid' value='" . $row->category_id . "'>";
						$frmChild .= "<input type='hidden' name='view' value='product'>";
						$frmChild .= "<input type='hidden' name='option' value='com_redshop'>";
						$frmChild .= "</form>";

					}
				}

				$wishlistData = str_replace("{child_products}", $frmChild, $wishlistData);
			}

			$childproduct = $producthelper->getChildProduct($row->product_id);

			if (count($childproduct) > 0)
			{
				if (Redshop::getConfig()->get('PURCHASE_PARENT_WITH_CHILD') == 1)
				{
					$isChilds       = false;
					$attributes_set = array();

					if ($row->attribute_set_id > 0)
					{
						$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $row->attribute_set_id, 0, 1);
					}

					$attributes = RedshopHelperProduct_Attribute::getProductAttribute($row->product_id);
					$attributes = array_merge($attributes, $wishlistData);
				}
				else
				{
					$isChilds   = true;
					$attributes = array();
				}
			}
			else
			{
				$isChilds       = false;
				$attributes_set = array();

				if ($row->attribute_set_id > 0)
				{
					$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $row->attribute_set_id, 0, 1);
				}

				$attributes = RedshopHelperProduct_Attribute::getProductAttribute($row->product_id);
				$attributes = array_merge($attributes, $attributes_set);
			}

			if (empty($row->product_items))
			{
				$attributes = null;
			}
			else
			{
				foreach ($attributes as $key => $attribute)
				{
					if (empty($attribute->properties))
					{
						continue;
					}

					if (!isset($attribute->properties[$row->product_items->property_id]))
					{
						unset($attributes[$key]);
						continue;
					}

					$attribute->properties[$row->product_items->property_id]->setdefault_selected = 1;
				}

				$attributes = array_values($attributes);
			}

			$attribute_template = $producthelper->getAttributeTemplate($wishlistData);

			// Check product for not for sale
			$wishlistData = $producthelper->getProductNotForSaleComment($row, $wishlistData, $attributes);

			$wishlistData = $producthelper->replaceProductInStock($row->product_id, $wishlistData, $attributes, $attribute_template);

			/* Product attribute  Start */
			$totalatt     = count($attributes);
			$wishlistData = RedshopHelperAttribute::replaceAttributeData(
				$row->product_id, 0, 0, $attributes, $wishlistData, $attribute_template, $isChilds, array(), 1, true
			);

			/* Product attribute  End. Checking for child products end */

			if (!$row->not_for_sale)
			{
				if ($row->product_on_sale && $product_price_discount > 0)
				{
					if ($product_price > $product_price_discount)
					{
						$s_price = $product_price - $product_price_discount;

						if ($this->show_discountpricelayout)
						{
							$mainproduct_price = RedshopHelperProductPrice::formattedPrice($product_price);
							$product_price     = $product_price_discount;
							$mainproduct_price = RedshopHelperProductPrice::formattedPrice($product_price_discount);

						}
						else
						{
							$product_price     = $product_price_discount;
							$mainproduct_price = RedshopHelperProductPrice::formattedPrice($product_price);
						}
					}
					else
					{
						$mainproduct_price = RedshopHelperProductPrice::formattedPrice($product_price);

					}
				}
				else
				{
					$mainproduct_price = RedshopHelperProductPrice::formattedPrice($product_price);

				}

				$wishlistData = str_replace('{product_price}', $mainproduct_price, $wishlistData);
			}

			// Product User Field Start
			$count_no_user_field = 0;
			$returnArr           = $producthelper->getProductUserfieldFromTemplate($wishlistData);
			$template_userfield  = $returnArr[0];

			$userfieldArr = $returnArr[1];

			if (strstr($wishlistData, "{if product_userfield}") && strstr($wishlistData, "{product_userfield end if}") && $template_userfield != "")
			{
				$ufield = "";
				$cart   = $session->get('cart');

				/**
				 * @TODO Consider about this logic because $idx will be overwritten right after condition
				 */
				if (isset($cart['idx']))
				{
					$idx = (int) ($cart['idx']);
				}

				$idx     = 0;
				$cart_id = '';

				for ($j = 0; $j < $idx; $j++)
				{
					if ($cart[$j]['product_id'] == $row->product_id)
					{
						$cart_id = $j;
					}
				}

				for ($ui = 0, $countUserfield = count($userfieldArr); $ui < $countUserfield; $ui++)
				{
					if (!$idx)
					{
						$cart_id = "";
					}

					$mysesspro = "productuserfield_" . $ui;

					for ($check_i = 1; $check_i <= $_SESSION ["no_of_prod"]; $check_i++)
					{
						if ($_SESSION ['wish_' . $check_i]->product_id == $row->product_id)
						{
							$productUserFieldsFinal = $_SESSION['wish_' . $check_i]->$mysesspro;
						}
					}

					if ($productUserFieldsFinal != '')
					{
						$productUserFields = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id, $productUserFieldsFinal, 1);
					}
					else
					{
						$productUserFields = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', $cart_id, 0, $row->product_id);
					}

					$ufield .= $productUserFields[1];

					//
					if ($productUserFields[1] != "")
					{
						$count_no_user_field++;
					}

					if ($productUserFieldsFinal != '')
					{
						$wishlistData = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $wishlistData);
						$wishlistData = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $wishlistData);
					}
					else
					{
						$wishlistData = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $wishlistData);
						$wishlistData = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $wishlistData);
					}
				}

				$productUserFieldsForm = "<form method='post' action='' id='user_fields_form' name='user_fields_form'>";

				if ($ufield != "")
				{
					$wishlistData = str_replace("{if product_userfield}", $productUserFieldsForm, $wishlistData);
					$wishlistData = str_replace("{product_userfield end if}", "</form>", $wishlistData);
				}
				else
				{
					$wishlistData = str_replace("{if product_userfield}", "", $wishlistData);
					$wishlistData = str_replace("{product_userfield end if}", "", $wishlistData);
				}
			}

			// Product User Field End

			/////////////////////////////////// Product accessory Start /////////////////////////////////
			$accessory      = RedshopHelperAccessory::getProductAccessories(0, $row->product_id);
			$totalAccessory = count($accessory);

			$wishlistData = RedshopHelperProductAccessory::replaceAccessoryData($row->product_id, 0, $accessory, $wishlistData, $isChilds);

			/////////////////////////////////// Product accessory End /////////////////////////////////

			$wishlistData = str_replace('{product_name}', $pname, $wishlistData);
			$wishlistData = str_replace('{product_number}', $pnumber, $wishlistData);
			$wishlistData = str_replace('{product_s_desc}', $pdesc, $wishlistData);

			$wishlistData = RedshopHelperProductTag::getExtraSectionTag($extraFieldName, $row->product_id, "1", $wishlistData, 1);
			$wishlistData = $producthelper->replaceCartTemplate($row->product_id, $row->category_id, 0, 0, $wishlistData, $isChilds, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field);

			$rmore        = "<a href='" . $link . "' title='" . $row->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
			$wishlistData = str_replace("{read_more}", $rmore, $wishlistData);
			$wishlistData = str_replace("{read_more_link}", $link, $wishlistData);
			$wishlistData = str_replace("{product_loop_start}", '', $wishlistData);
			$wishlistData = str_replace("{product_loop_end}", '', $wishlistData);
			$wishlistData = str_replace("{back_link}", '', $wishlistData);
			$wishlistData = str_replace("{back_link}", '', $wishlistData);
			$wishlistData = str_replace("{mail_link}", '', $wishlistData);
			$wishlistData = str_replace("{if product_on_sale}", '', $wishlistData);
			$wishlistData = str_replace("{product_on_sale end if}", '', $wishlistData);
			$wishlistData = str_replace("<table></table>", '', $wishlistData);
			$wishlistData = str_replace("{all_cart}", '', $wishlistData);
			$wishlistData = str_replace("{if product_on_sale}", "", $wishlistData);
			$wishlistData = str_replace("{product_on_sale end if}", "", $wishlistData);

			$regdellink = "index.php?mydel=1&view=wishlist&wishlist_id=" . $row->product_id . "&task=mysessdelwishlist";

			if (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
			{
				$regdellink .= isset($row->product_items->attribute_id) ? '&attribute_id=' . $row->product_items->attribute_id : '';
				$regdellink .= isset($row->product_items->property_id) ? '&property_id=' . $row->product_items->property_id : '';
				$regdellink .= isset($row->product_items->subattribute_id) ? '&subattribute_id=' . $row->product_items->subattribute_id : '';
			}

			$regdellink = JRoute::_($regdellink, false);

			$mainregdellink = "<div><a href=\"" . $regdellink . "\">" . JText::_('COM_REDSHOP_REMOVE_PRODUCT_FROM_WISHLIST') . "</a></div>";

			$wishlistData = str_replace('{remove_product_link}', $mainregdellink, $wishlistData);

			$mainid                 .= $row->product_id . ",";
			$totattid               .= $totalatt . ",";
			$totcount_no_user_field .= $count_no_user_field . ",";

			$temp_template .= $wishlistData;
		}

		$my = "<form name='frm' method='POST' action=''>";

		$my   .= "<input type='hidden' name='product_id' id='product_id' value='" . $mainid . "' >

			<input type='hidden' name='totacc_id' id='totacc_id' value='" . $totattid . "' >
			<input type='hidden' name='totcount_no_user_field' id='totcount_no_user_field' value='" . $totcount_no_user_field . "' >
			<input type='button' name='submit' onclick='return productalladdprice();' value='" . JText::_('COM_REDSHOP_ADD_TO_CART') . "'>
			</form>";
		$data = $template_d1[0] . $temp_template . $template_d2[1];
		$data = str_replace('{back_link}', '', $data);
		$data = str_replace('{all_cart}', $my, $data);
		$data = RedshopHelperTemplate::parseRedshopPlugin($data);
		echo eval("?>" . $data . "<?php ");
	}
}
