<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$url       = JURI::base();
$redconfig = Redconfiguration::getInstance();

$quotationHelper = quotationHelper::getInstance();
$extra_field     = extra_field::getInstance();

$extra_field_new = extraField::getInstance();

$producthelper = productHelper::getInstance();

$order_functions = order_functions::getInstance();

$redTemplate = Redtemplate::getInstance();

$Itemid = JRequest::getInt('Itemid', 1);
$quoid  = JRequest::getInt('quoid');
$encr   = JRequest::getString('encr');

$quotationDetail = $quotationHelper->getQuotationDetail($quoid);

$quotationProducts = $quotationHelper->getQuotationProduct($quoid);

$fieldArray = $extra_field_new->getSectionFieldList(17, 0, 0);

$template = $redTemplate->getTemplate("quotation_detail");

if (count($template) > 0 && $template[0]->template_desc != "")
{
	$quotation_template = $template[0]->template_desc;
}
else
{
	$quotation_template = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\">\r\n<tbody>\r\n<tr>\r\n<td colspan=\"2\">\r\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">\r\n<tbody>\r\n<tr style=\"background-color: #cccccc\">\r\n<th align=\"left\">{quotation_information_lbl}{print}</th>\r\n</tr>\r\n<tr>\r\n</tr>\r\n<tr>\r\n<td>{quotation_id_lbl} : {quotation_id}</td>\r\n</tr>\r\n<tr>\r\n<td>{quotation_number_lbl} : {quotation_number}</td>\r\n</tr>\r\n<tr>\r\n<td>{quotation_date_lbl} : {quotation_date}</td>\r\n</tr>\r\n<tr>\r\n<td>{quotation_status_lbl} : {quotation_status}</td>\r\n</tr>\r\n<tr>\r\n<td>{quotation_note_lbl} : {quotation_note}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td colspan=\"2\">\r\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">\r\n<tbody>\r\n<tr style=\"background-color: #cccccc\">\r\n<th align=\"left\">{account_information_lbl}</th>\r\n</tr>\r\n<tr>\r\n<td>{account_information}{quotation_custom_field_list}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td colspan=\"2\">\r\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">\r\n<tbody>\r\n<tr style=\"background-color: #cccccc\">\r\n<th align=\"left\">{quotation_detail_lbl}</th>\r\n</tr>\r\n<tr>\r\n</tr>\r\n<tr>\r\n<td>\r\n<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">\r\n<tbody>\r\n<tr>\r\n<td></td>\r\n<td>{product_name_lbl}</td>\r\n<td>{note_lbl}</td>\r\n<td>{price_lbl}</td>\r\n<td>{quantity_lbl}</td>\r\n<td align=\"right\">{total_price_lbl}</td>\r\n</tr>\r\n{product_loop_start}       \r\n<tr>\r\n<td>{product_thumb_image}</td>\r\n<td>{product_name}<br />({product_number_lbl} - {product_number})<br />{product_accessory}<br /> {product_attribute}<br />{product_userfields}</td>\r\n<td>{product_wrapper}</td>\r\n<td>{product_price}</td>\r\n<td>{product_quantity}</td>\r\n<td align=\"right\">{product_total_price}</td>\r\n</tr>\r\n{product_loop_end}\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td></td>\r\n</tr>\r\n<tr>\r\n<td>\r\n<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">\r\n<tbody>\r\n<tr align=\"left\">\r\n<td align=\"left\"><strong>{quotation_subtotal_lbl} : </strong></td>\r\n<td align=\"right\">{quotation_subtotal}</td>\r\n</tr>\r\n<tr align=\"left\">\r\n<td align=\"left\"><strong>{quotation_vat_lbl} : </strong></td>\r\n<td align=\"right\">{quotation_vat}</td>\r\n</tr>\r\n<tr align=\"left\">\r\n<td align=\"left\"><strong>{quotation_discount_lbl} : </strong></td>\r\n<td align=\"right\">{quotation_discount}</td>\r\n</tr>\r\n<tr align=\"left\">\r\n<td colspan=\"2\" align=\"left\">\r\n<hr />\r\n</td>\r\n</tr>\r\n<tr align=\"left\">\r\n<td align=\"left\"><strong>{total_lbl} :</strong></td>\r\n<td align=\"right\">{quotation_total}</td>\r\n</tr>\r\n<tr align=\"left\">\r\n<td colspan=\"2\" align=\"left\">\r\n<hr />\r\n<br /> \r\n<hr />\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>";
}

$print     = JRequest::getInt('print');
$p_url     = explode('?', $_SERVER['REQUEST_URI']);
$print_tag = '';

if ($print)
{
	$print_tag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' ><img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' /></a>";
}
else
{
	$print_url = $url . "index.php?tmpl=component&option=com_redshop&view=quotation_detail&quoid=" . $quoid . "&print=1";
	$print_tag = "<a href='#' onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' ><img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' /></a>";
}

$quotation_template = str_replace("{print}", $print_tag, $quotation_template);

$search []  = "{quotation_id}";
$replace [] = $quoid;

$search []  = "{quotation_number}";
$replace [] = $quotationDetail->quotation_number;

$search []  = "{quotation_date}";
$replace [] = $redconfig->convertDateFormat($quotationDetail->quotation_cdate);

$search [] = "{quotation_customer_note_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_CUSTOMER_NOTE');

$search [] = "{quotation_customer_note}";
$replace[] = $quotationDetail->quotation_customer_note;

$statustext = $quotationHelper->getQuotationStatusName($quotationDetail->quotation_status);

if ($quotationDetail->quotation_status == '2')
{
	$frm = "<form method='post'>
	<input type='radio' name='quotation_status' checked value='3'>" . JText::_('COM_REDSHOP_ACCEPT') . "
	<input type='radio' name='quotation_status' value='4'>" . JText::_('COM_REDSHOP_REJECT') . "
	<input type='hidden' name='quotation_id' value='$quoid'>
	<input type='hidden' name='option' value='com_redshop'>
	<input type='hidden' name='Itemid' value='$Itemid'>
	<input type='hidden' name='encr' value='$encr'>
	<input type='hidden' name='view' value='quotation_detail'>
	<input type='hidden' name='task' value='updatestatus'>
	<input type='submit' name='submit' value='" . JText::_("COM_REDSHOP_SUBMIT") . "' onclick='return confirm(\"" . JText::_('COM_REDSHOP_CONFIRM_SEND_QUOTATION') . "\")' />
	<div>
		<textarea name='quotation_customer_note' >" . $quotationDetail->quotation_customer_note . "</textarea>"
	. "</div>
	</form>";

	$quotation_template = str_replace('{quotation_customer_note_lbl}', '', $quotation_template);
	$quotation_template = str_replace('{quotation_customer_note}', '', $quotation_template);
}
elseif ($quotationDetail->quotation_status == '3')
{
	$frm = "<form method='post'>
	<input type='hidden' name='quotation_id' value='$quoid'>
	<input type='hidden' name='option' value='com_redshop'>
	<input type='hidden' name='Itemid' value='$Itemid'>
	<input type='hidden' name='encr' value='$encr'>
	<input type='hidden' name='task' value='checkout'>
	<input type='hidden' name='view' value='quotation_detail'>
	<input type='submit' name='submit' value='" . JText::_("COM_REDSHOP_CHECKOUT") . "' />
	</form>";
}
elseif ($quotationDetail->quotation_status == '5')
{
	$frm = " (" . JText::_('COM_REDSHOP_ORDER_ID') . "-" . $quotationDetail->order_id . " )";
}
else
{
	$frm = '';
}

$search [] = "{quotation_status}";
$replace[] = $statustext . $frm;

$search [] = "{quotation_note_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_NOTE');

$search [] = "{quotation_note}";
$replace[] = $quotationDetail->quotation_note;

$billadd = "";

if ($quotationDetail->user_id != 0)
{
	$billadd = JLayoutHelper::render('cart.billing', array('billingaddresses' => $quotationDetail));
}
else
{
	if (!isset($quotationDetail->user_info_id))
	{
		$quotationDetail->user_info_id = 0;
	}

	if ($quotationDetail->quotation_email != "")
	{
		$billadd .= JLayoutHelper::render('fields.display',
			array(
				'extra_field_label' => JText::_("COM_REDSHOP_EMAIL"),
				'extra_field_value' => $quotationDetail->quotation_email
				)
		);
	}
}

if (strstr($quotation_template, "{quotation_custom_field_list}"))
{
	$billadd .= $extra_field->list_all_field_display(16, $quotationDetail->user_info_id, 0, $quotationDetail->quotation_email);
	$quotation_template = str_replace("{quotation_custom_field_list}", "", $quotation_template);
}
else
{
	$quotation_template = $extra_field->list_all_field_display(16, $quotationDetail->user_info_id, 0, $quotationDetail->quotation_email, $quotation_template);
}

$search []  = "{account_information}";
$replace [] = $billadd;

$product_name = "";

$product_note = "";

$product_price = "";

$product_quantity = "";

$product_total_price = "";

$template_start  = $quotation_template;
$template_end    = "";
$template_middle = "";
$template_sdata  = explode('{product_loop_start}', $quotation_template);

if (count($template_sdata) > 0)
{
	$template_start = $template_sdata [0];

	if (count($template_sdata) > 1)
	{
		$template_edata = explode('{product_loop_end}', $template_sdata[1]);

		if (count($template_edata) > 1)
		{
			$template_end    = $template_edata [1];
			$template_middle = $template_edata [0];
		}
	}
}

$cart_mdata        = '';
$subtotal_excl_vat = 0;

for ($i = 0, $in = count($quotationProducts); $i < $in; $i++)
{
	$cart_mdata .= $template_middle;
	$wrapper_name = "";

	if ($quotationProducts[$i]->product_wrapperid)
	{
		$wrapper = $producthelper->getWrapper($quotationProducts[$i]->product_id, $quotationProducts[$i]->product_wrapperid);

		if (count($wrapper) > 0)
		{
			$wrapper_name = JText::_('COM_REDSHOP_WRAPPER') . ":<br/>" . $wrapper[0]->wrapper_name . "(" . $producthelper->getProductFormattedPrice($quotationProducts[$i]->wrapper_price) . ")";
		}
	}

	if ($quotationProducts [$i]->is_giftcard == 1)
	{
		$productUserFields = $quotationHelper->displayQuotationUserfield($quotationProducts[$i]->quotation_item_id, 13);
		$giftcardData       = $producthelper->getGiftcardData($quotationProducts[$i]->product_id);

		$product_number = "";
	}
	else
	{
		$productUserFields = $quotationHelper->displayQuotationUserfield($quotationProducts[$i]->quotation_item_id, 12);

		$product = $producthelper->getProductById($quotationProducts[$i]->product_id);

		$product_number = $product->product_number;

		$product_image_path = "";

		if ($product->product_full_image)
		{
			if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
			{
				$product_image_path = $product->product_full_image;
			}
			else
			{
				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
				{
					$product_image_path = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
				}
			}
		}
		else
		{
			if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
			{
				$product_image_path = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
			}
		}

		if ($product_image_path)
		{
			$thumbUrl = RedShopHelperImages::getImagePath(
							$product_image_path,
							'',
							'thumb',
							'product',
							Redshop::getConfig()->get('CART_THUMB_WIDTH'),
							Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
			$product_image = "<div class='product_image'><img src='" . $thumbUrl . "'></div>";
		}
		else
		{
			$product_image = "<div class='product_image'></div>";
		}
	}

	$product_name       = "<div class='product_name'>" . $quotationProducts[$i]->product_name . "</div>";
	$product_note       = "<div  class='product_note'>" . $wrapper_name . "</div>";
	$product_price      = "<div class='product_price'>" . $producthelper->getProductFormattedPrice($quotationProducts[$i]->product_price) . "</div>";
	$product_excl_price = "<div class='product_excl_price'>" . $producthelper->getProductFormattedPrice($quotationProducts[$i]->product_excl_price) . "</div>";
	$product_quantity   = '<div class="product_quantity">' . $quotationProducts[$i]->product_quantity . '</div>';

	$product_total_price      = $quotationProducts[$i]->product_quantity * $quotationProducts[$i]->product_price;
	$product_total_excl_price = $quotationProducts[$i]->product_quantity * $quotationProducts[$i]->product_excl_price;

	$cart_mdata = str_replace("{product_thumb_image}", $product_image, $cart_mdata);
	$cart_mdata = str_replace("{product_name}", $product_name, $cart_mdata);
	$cart_mdata = str_replace("{product_s_desc}", $product->product_s_desc, $cart_mdata);

	$cart_mdata = RedshopTagsReplacer::_(
						'attribute',
						$cart_mdata,
						array(
							'product_attribute' 	=> $quotationProducts[$i]->product_attribute,
						)
					);

	$cart_mdata = str_replace("{product_accessory}", $quotationProducts[$i]->product_accessory, $cart_mdata);

	$cart_mdata = str_replace("{product_number}", $product_number, $cart_mdata);
	$cart_mdata = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $cart_mdata);
	$cart_mdata = str_replace("{product_userfields}", $productUserFields, $cart_mdata);
	$cart_mdata = str_replace("{product_wrapper}", $product_note, $cart_mdata);

	// ProductFinderDatepicker Extra Field Start

	$cart_mdata = $producthelper->getProductFinderDatepickerValue($cart_mdata, $quotationProducts[$i]->product_id, $fieldArray);

	// ProductFinderDatepicker Extra Field End

	if ($quotationDetail->quotation_status == 1)
	{
		$cart_mdata = str_replace("{product_price}", "", $cart_mdata);
		$cart_mdata = str_replace("{product_total_price}", "", $cart_mdata);
		$cart_mdata = str_replace("{product_price_excl_vat}", "", $cart_mdata);
		$cart_mdata = str_replace("{product_total_price_excl_vat}", "", $cart_mdata);
	}
	else
	{
		$cart_mdata = str_replace("{product_price}", $product_price, $cart_mdata);
		$cart_mdata = str_replace("{product_total_price}", $producthelper->getProductFormattedPrice($product_total_price), $cart_mdata);
		$cart_mdata = str_replace("{product_price_excl_vat}", $product_excl_price, $cart_mdata);
		$cart_mdata = str_replace("{product_total_price_excl_vat}", $producthelper->getProductFormattedPrice($product_total_excl_price), $cart_mdata);
	}

	$cart_mdata = str_replace("{product_quantity}", $product_quantity, $cart_mdata);
}

$quotation_template = $template_start . $cart_mdata . $template_end;

if ($quotationDetail->quotation_status == 1)
{
	$quotation_total    = "";
	$quotation_subtotal = "";
	$quotation_tax      = "";
	$quotation_discount = "";
}
else
{
	$quotation_total    = $producthelper->getProductFormattedPrice($quotationDetail->quotation_total);
	$quotation_subtotal = $producthelper->getProductFormattedPrice($quotationDetail->quotation_subtotal);
	$quotation_tax      = $producthelper->getProductFormattedPrice($quotationDetail->quotation_tax);
	$quotation_discount = $producthelper->getProductFormattedPrice($quotationDetail->quotation_discount);
}

$search []  = "{quotation_discount}";
$replace [] = $quotation_discount;

$search[]  = "{quotation_discount_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_DISCOUNT_LBL');

$search []  = "{quotation_subtotal}";
$replace [] = $quotation_subtotal;

$search[]  = "{quotation_id_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_ID');

$search[]  = "{quotation_number_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_NUMBER');

$search[]  = "{quotation_date_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_DATE');

$search[]  = "{quotation_status_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_STATUS');

$search[]  = "{quotation_information_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_INFORMATION');

$search[]  = "{account_information_lbl}";
$replace[] = JText::_('COM_REDSHOP_ACCOUNT_INFORMATION');

$search[]  = "{quotation_detail_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_DETAILS');

$search[]  = "{product_name_lbl}";
$replace[] = JText::_('COM_REDSHOP_PRODUCT_NAME');

$search[]  = "{note_lbl}";
$replace[] = JText::_('COM_REDSHOP_NOTE_LBL');

$search[]  = "{price_lbl}";
$replace[] = JText::_('COM_REDSHOP_PRICE_LBL');

$search[]  = "{quantity_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUANTITY_LBL');

$search[]  = "{total_price_lbl}";
$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_LBL');

$search[]  = "{quotation_subtotal_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL');

$search[]  = "{total_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_TOTAL');

$search []  = "{quotation_total}";
$replace [] = $quotation_total;

$search[]  = "{quotation_tax_lbl}";
$replace[] = JText::_('COM_REDSHOP_QUOTATION_TAX');

$search []  = "{quotation_tax}";
$replace [] = $quotation_tax;

$message = str_replace($search, $replace, $quotation_template);

$message = $redTemplate->parseredSHOPplugin($message);
echo eval("?>" . $message . "<?php ");
