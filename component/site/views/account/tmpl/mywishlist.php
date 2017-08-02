<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$url   = JURI::base();
$input = JFactory::getApplication()->input;

JHTML::_('behavior.tooltip');
JHtml::_('behavior.modal');

// Get product helper
$producthelper = productHelper::getInstance();
$configobj     = Redconfiguration::getInstance();
$redTemplate   = Redtemplate::getInstance();
$extraField    = extraField::getInstance();

$session       = JFactory::getSession();
$Itemid        = $input->getInt('Itemid');
$wishlist_id   = $input->getInt('wishlist_id');
$mail          = $input->getInt('mail', 0);
$window        = $input->getInt('window');

$model         = $this->getModel('account');
$user          = JFactory::getUser();

$pagetitle     = JText::_('COM_REDSHOP_MY_WISHLIST');
$isIndividualAddToCart = (boolean) Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE');

if ($window == 1)
{
	?>
	<script type="text/javascript">window.parent.location.reload();</script>
	<?php
	JFactory::getApplication()->close();
}

if ($this->params->get('show_page_heading', 1))
{
	?>
	<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $pagetitle; ?>
	</h1>
	<div>&nbsp;</div>
<?php
}

if ($mail == 0)
{
	$MyWishlist = $model->getMyDetail();
	$template   = RedshopHelperTemplate::getTemplate("wishlist_template");

	if (count($template) > 0 && $template[0]->template_desc != "")
	{
		$data = $template[0]->template_desc;
	}
	else
	{
		$data = "<div style=\"float: right;\">{mail_link}</div>{product_loop_start}<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\"><tbody><tr valign=\"top\"><td width=\"40%\"><div style=\"float: left; width: 195px; height: 230px; text-align: center;\">{product_thumb_image}<div>{product_name}</div><div>{product_price}</div><div>{form_addtocart:templet1}</div><div> </div><div>{remove_product_link}</div></div>		</td></tr></tbody></table><div> </div><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">	<tbody><tr> <td> <div></div> </td><td align=\"center\" valign=\"top\"><br><br></td> </tr></tbody></table>{product_loop_end}<div style=\"float: right;\">{back_link}</div>";
	}

	$template_d1 = explode("{product_loop_start}", $data);
	$template_d2 = explode("{product_loop_end}", $template_d1[1]);

	$mlink = JURI::root() . "index.php?option=com_redshop&view=account&layout=mywishlist&mail=1&tmpl=component&wishlist_id=" . $wishlist_id;

	$mail_link = '<a class="redcolorproductimg" href="' . $mlink . '"  ><img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'mailcenter16.png" ></a>';

	if (count($MyWishlist) > 0)
	{
		$template_d1[0] = str_replace('{mail_link}', $mail_link, $template_d1[0]);
	}
	else
	{
		$template_d1[0] = str_replace('{mail_link}', "", $template_d1[0]);
	}

	$wishlist_desc = $template_d2[0];

	if (strstr($data, '{product_thumb_image_2}'))
	{
		$tag     = '{product_thumb_image_2}';
		$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT_2');
		$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH_2');
	}
	elseif (strstr($data, '{product_thumb_image_3}'))
	{
		$tag     = '{product_thumb_image_3}';
		$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT_3');
		$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH_3');
	}
	elseif (strstr($data, '{product_thumb_image_1}'))
	{
		$tag     = '{product_thumb_image_1}';
		$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT');
		$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH');
	}
	else
	{
		$tag     = '{product_thumb_image}';
		$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT');
		$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH');
	}

	$temp_template  = '';
	$extraFieldName = $extraField->getSectionFieldNameArray(1, 1, 1);

	if (count($MyWishlist) > 0)
	{
		$mainid                 = null;
		$totattid               = null;
		$totcount_no_user_field = null;
		$newWishList            = array();

		// Process for clone product follow attribute data.
		foreach ($MyWishlist as $wishList)
		{
			$wishList->wishlistData = RedshopHelperWishlist::getWishlist($wishList->wishlist_id);
			$dataForCheck = null;

			if (!empty($wishList->wishlistData->products) && !empty($wishList->wishlistData->products[$wishList->product_id]))
			{
				$dataForCheck = $wishList->wishlistData->products[$wishList->product_id];
			}

			if (!$dataForCheck)
			{
				$wishList->wishlistData = null;
				$newWishList[] = $wishList;

				continue;
			}

			foreach ($dataForCheck as $productData)
			{
				$tmpWishList = clone $wishList;
				$tmpWishList->wishlistData = $productData;
				$newWishList[] = $tmpWishList;
			}
		}

		$MyWishlist = $newWishList;

		foreach ($MyWishlist as $row)
		{
			$wishlistuserfielddata  = $producthelper->getwishlistuserfieldata($row->wishlist_id, $row->product_id);
			$link                   = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . $Itemid);
			$link_remove            = 'index.php?option=com_redshop&view=account&layout=mywishlist&wishlist_id=' . $wishlist_id
				. '&pid=' . $row->product_id . '&remove=1';

			if ($isIndividualAddToCart)
			{
				$link_remove .= '&wishlist_product_id=' . $row->wishlistData->wishlist_product_id;
			}

			$link_remove = JRoute::_($link_remove . '&Itemid=' . $Itemid, false);

			$thum_image             = $producthelper->getProductImage($row->product_id, $link, $w_thumb, $h_thumb);
			$product_price          = $producthelper->getProductPrice($row->product_id);
			$product_price_discount = $producthelper->getProductNetPrice($row->product_id);

			$pname         = "<a href='" . $link . "' >" . $row->product_name . "</a>";
			$wishlist_data = str_replace($tag, $thum_image, $wishlist_desc);
			$wishlist_data = str_replace('{product_number}', $row->product_number, $wishlist_data);
			$wishlist_data = str_replace('{product_name}', $pname, $wishlist_data);
			/*if($product_price > $product_price_discount)
			{
			$wishlist_data = str_replace('{product_price}', $producthelper->getProductFormattedPrice($product_price_discount) , $wishlist_data);
			}else{
			$wishlist_data = str_replace('{product_price}', $producthelper->getProductFormattedPrice($product_price) , $wishlist_data);
			}*/
			$wishlist_data = str_replace('{product_s_desc}', $row->product_s_desc, $wishlist_data);

			// Checking for child products start
			if (strstr($wishlist_data, "{child_products}"))
			{
				$parentproductid = $row->product_id;

				if ($this->data->product_parent_id != 0)
				{
					$parentproductid = $producthelper->getMainParentProduct($row->product_id);
				}

				$frmChild = "";

				if ($parentproductid != 0)
				{
					$productInfo = $producthelper->getProductById($parentproductid);

					// Get child products
					$childproducts = $model->getAllChildProductArrayList(0, $parentproductid);

					if (count($childproducts) > 0)
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

							$cld_name = @array_merge($cld_name, $childproducts);
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

				$wishlist_data = str_replace("{child_products}", $frmChild, $wishlist_data);
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
						$attributes_set = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1);
					}

					$attributes = $producthelper->getProductAttribute($row->product_id);

					$attributes = array_merge($attributes, $wishlist_data);
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
					$attributes_set = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1);
				}

				$attributes = $producthelper->getProductAttribute($row->product_id);
				$attributes = array_merge($attributes, $attributes_set);
			}

			$attribute_template = $producthelper->getAttributeTemplate($wishlist_data);

			$wishlistData = $row->wishlistData;

			if ($wishlistData)
			{
				// Get necessary data for attributes, properties and sub-attributes.
				foreach ($attributes as $key => $attribute)
				{
					if (empty($attribute->properties))
					{
						continue;
					}

					if (!isset($wishlistData->product_items[$attribute->attribute_id]))
					{
						if ($isIndividualAddToCart)
						{
							unset($attributes[$key]);
						}

						continue;
					}

					$wishlistProductItem = $wishlistData->product_items[$attribute->attribute_id];

					foreach ($attribute->properties as $property)
					{
						$property->setdefault_selected = 0;

						if ($property->property_id != $wishlistProductItem->property_id)
						{
							continue;
						}

						$property->setdefault_selected = 1;

						if (empty($wishlistProductItem->subattribute_id))
						{
							continue;
						}

						if (empty($property->sub_properties))
						{
							$property->sub_properties = $producthelper->getAttibuteSubProperty(0, $property->value);
						}

						foreach ($property->sub_properties as $subProperty)
						{
							$subProperty->setdefault_selected = 0;

							if ($subProperty->subattribute_color_id == $wishlistProductItem->subattribute_id
								&& $subProperty->subattribute_id == $wishlistProductItem->attribute_id)
							{
								$subProperty->setdefault_selected = 1;
							}
						}
					}
				}

				$attributes = array_values($attributes);
			}

			// Check product for not for sale
			$wishlist_data = $producthelper->getProductNotForSaleComment($row, $wishlist_data, $attributes);

			$wishlist_data = $producthelper->replaceProductInStock($row->product_id, $wishlist_data, $attributes, $attribute_template);

			// Product attribute  Start
			$totalatt      = count($attributes);
			$wishlist_data = RedshopHelperAttribute::replaceAttributeData(
				$row->product_id, 0, 0, $attributes, $wishlist_data, $attribute_template, $isChilds, array(), 1, true
			);

			// Product attribute  End
			// Checking for child products end

			// Product accessory Start
			$accessory      = $producthelper->getProductAccessory(0, $row->product_id);
			$totalAccessory = count($accessory);

			$wishlist_data = $producthelper->replaceAccessoryData($row->product_id, 0, $accessory, $wishlist_data, $isChilds);

			// Product accessory End

			// Product User Field Start
			$count_no_user_field = 0;
			$returnArr           = $producthelper->getProductUserfieldFromTemplate($wishlist_data);
			$template_userfield  = $returnArr[0];

			$userfieldArr = $returnArr[1];

			if (strstr($wishlist_data, "{if product_userfield}") && strstr($wishlist_data, "{product_userfield end if}") && $template_userfield != "")
			{
				$ufield = "";
				$cart   = $session->get('cart');

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

				for ($ui = 0; $ui < count($userfieldArr); $ui++)
				{
					if (!$idx)
					{
						$cart_id = "";
					}

					$productUserFieldsFinal = $wishlistuserfielddata[$ui]->userfielddata;

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

					$wishlist_data = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $wishlist_data);
					$wishlist_data = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $wishlist_data);
				}

				$productUserFieldsForm = "<form method='post' action='' id='user_fields_form' name='user_fields_form'>";

				if ($ufield != "")
				{
					$wishlist_data = str_replace("{if product_userfield}", $productUserFieldsForm, $wishlist_data);
					$wishlist_data = str_replace("{product_userfield end if}", "</form>", $wishlist_data);
				}
				else
				{
					$wishlist_data = str_replace("{if product_userfield}", "", $wishlist_data);
					$wishlist_data = str_replace("{product_userfield end if}", "", $wishlist_data);
				}
			}

			// Product User Field End

			$rmore         = "<a href='" . $link . "' title='" . $row->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
			$wishlist_data = str_replace("{read_more}", $rmore, $wishlist_data);
			$wishlist_data = str_replace("{read_more_link}", $link, $wishlist_data);
			$remove        = '<a href="' . $link_remove . '" title="" style="text-decoration:none;">' . JText::_("COM_REDSHOP_REMOVE_PRODUCT_FROM_WISHLIST") . '</a>';
			$wishlist_data = str_replace('{remove_product_link}', $remove, $wishlist_data);

			// Extra field display
			$wishlist_data = $producthelper->getExtraSectionTag($extraFieldName, $row->product_id, "1", $wishlist_data, 1);

			$wishlist_data = str_replace("{if product_on_sale}", "", $wishlist_data);
			$wishlist_data = str_replace("{product_on_sale end if}", "", $wishlist_data);

			if (isset($row->category_id) === false)
			{
				$row->category_id = 0;
			}

			if ($isIndividualAddToCart)
			{
				$wishlist_data = $producthelper->replaceCartTemplate(
					$row->product_id, $row->category_id, 0, 0, $wishlist_data, $isChilds,
					$userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $row->wishlistData->wishlist_product_id
				);
			}
			else
			{
				$wishlist_data = $producthelper->replaceCartTemplate(
					$row->product_id, $row->category_id, 0, 0, $wishlist_data, $isChilds,
					$userfieldArr, $totalatt, $totalAccessory, $count_no_user_field
				);
			}

			$mainid .= $row->product_id . ",";
			$totattid .= $totalatt . ",";
			$totcount_no_user_field .= $count_no_user_field . ",";

			$temp_template .= $wishlist_data;
		}

		$my = "<form name='frm' method='POST' action=''>";

		$my .= "<input type='hidden' name='product_id' id='product_id' value='" . $mainid . "' >

			<input type='hidden' name='totacc_id' id='totacc_id' value='" . $totattid . "' >
			<input type='hidden' name='totcount_no_user_field' id='totcount_no_user_field' value='" . $totcount_no_user_field . "' >
			<input type='button' name='submit' onclick='return productalladdprice();' value='" . JText::_('COM_REDSHOP_ADD_TO_CART') . "'>
			</form>";
	}
	else
	{
		echo "<div>" . JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') . "</div>";
		$my = '';
	}

	$data = $template_d1[0] . $temp_template . $template_d2[1];

	$back_link = '<a href="' . JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $Itemid) . '" title="' . JText::_('COM_REDSHOP_BACK_TO_MYACCOUNT') . '">' . JText::_('COM_REDSHOP_BACK_TO_MYACCOUNT') . '</a>';
	$data      = str_replace('{back_link}', $back_link, $data);
	$mail_link = '';

	if (count($MyWishlist) > 0)
	{
		$mlink     = JURI::root() . "index.php?option=com_redshop&view=account&layout=mywishlist&mail=1&tmpl=component&wishlist_id=" . $wishlist_id;
		$mail_link = '<a class="redcolorproductimg" href="' . $mlink . '"  ><img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'mailcenter16.png" ></a>';
	}

	$data = str_replace('{mail_link}', $mail_link, $data);
	$data = str_replace('{all_cart}', $my, $data);
	$data = $redTemplate->parseredSHOPplugin($data);
	echo eval("?>" . $data . "<?php ");
}
else
{
	$mailtemplate = $redTemplate->getTemplate("wishlist_mail_template");

	if (count($mailtemplate) > 0 && $mailtemplate[0]->template_desc != "")
	{
		$wishlist_maildata = $mailtemplate[0]->template_desc;
	}
	else
	{
		$wishlist_maildata = "<table cellpadding=\"10\" cellspacing=\"10\"><tr><th colspan=\"2\">{email_to_friend}</th></tr><tr><td>{emailto_lbl}</td><td>{emailto}</td></tr><tr><td>{sender_lbl}</td><td>{sender}</td></tr><tr><td>{mail_lbl}</td><td>{mail}</td></tr><tr><td>{subject_lbl}</td><td>{subject}</td></tr><tr><td>	{cancel_button}</td><td>	{send_button}</td></tr></table>";
	}

	$data = '<form name="wishlishtsend" method="post" action="index.php">';
	$data .= $wishlist_maildata;
	$data           = str_replace("{email_to_friend}", JText::_("COM_REDSHOP_EMAIL_TO_FRIEND"), $data);
	$data           = str_replace("{emailto_lbl}", JText::_("COM_REDSHOP_EMAIL_TO"), $data);
	$email_to_field = '<input type="text" name="emailto" value="" />';
	$data           = str_replace("{emailto}", $email_to_field, $data);
	$data           = str_replace("{sender_lbl}", JText::_('COM_REDSHOP_SENDER'), $data);
	$sender_field   = '<input type="text" name="sender" value="' . $user->name . '" />';
	$data           = str_replace("{sender}", $sender_field, $data);
	$data           = str_replace("{mail_lbl}", JText::_('COM_REDSHOP_YOUR_EMAIL'), $data);
	$email_field    = '<input type="text" name="email" value="' . $user->email . '" />';
	$data           = str_replace("{mail}", $email_field, $data);
	$data           = str_replace("{subject_lbl}", JText::_('COM_REDSHOP_SUBJECT'), $data);
	$subject        = '<input type="text" name="subject" value="" >';
	$data           = str_replace("{subject}", $subject, $data);

	$cancel_btn = '<input type="button" name="cancel" class="button btn" onclick="parent.location.reload();" value="' . JText::_('COM_REDSHOP_CANCEL') . '" />';
	$data       = str_replace("{cancel_button}", $cancel_btn, $data);

	$send_btn = '<input type="submit" name="send" class="button btn btn-primary" value="' . JText::_('COM_REDSHOP_SEND') . '" />';
	$data     = str_replace("{send_button}", $send_btn, $data);

	$data .= '<input type="hidden" name="option" value="com_redshop" />'
		. '<input type="hidden" name="window" value="1" />'
		. '<input type="hidden" name="task" value="sendWishlist" />'
		. '<input type="hidden" name="Itemid" value="' . $Itemid . '" />'
		. '<input type="hidden" name="wishlist_id" value="' . $wishlist_id . '" />'
		. '<input type="hidden" name="view" value="account" />';
	$data .= '</form>';
	echo eval("?>" . $data . "<?php ");

	$data = $redTemplate->parseredSHOPplugin($data);
}
