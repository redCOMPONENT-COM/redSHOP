<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$url = JURI::base();

include_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
include_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/quotation.php';
include_once JPATH_COMPONENT . '/helpers/product.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';
require_once JPATH_COMPONENT . '/helpers/extra_field.php';

$producthelper = new producthelper;
$quotationHelper = new quotationHelper;
$order_functions = new order_functions;
$configobj = new Redconfiguration;
$redTemplate = new Redtemplate;
$extra_field = new extra_field;
$extraField = new extraField;
$carthelper = new rsCarthelper;
$user = JFactory::getUser();
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');

$app = JFactory::getApplication();
$params = $app->getParams($option);
$returnitemid = $params->get('logout', $Itemid);

$accountbillto_link = JRoute::_("index.php?option=" . $option . "&view=account_billto&Itemid=" . $Itemid);
$accountshipto_link = JRoute::_("index.php?option=" . $option . "&view=account_shipto&Itemid=" . $Itemid);
$logout_link = JRoute::_("index.php?option=" . $option . "&view=login&task=logout&logout=" . $returnitemid . "&Itemid=" . $Itemid);
$compare_link = JRoute::_("index.php?option=" . $option . "&view=product&layout=compare&Itemid=" . $Itemid);
$mytags_link = JRoute::_("index.php?option=" . $option . "&view=account&layout=mytags&Itemid=" . $Itemid);
$wishlist_link = JRoute::_("index.php?option=" . $option . "&view=wishlist&task=viewwishlist&Itemid=" . $Itemid);

$model = $this->getModel('account');

$template = $redTemplate->getTemplate("account_template");

if (count($template) > 0 && $template[0]->template_desc != "")
{
	$template_desc = $template[0]->template_desc;
}
else
{
	$template_desc = "<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>{welcome_introtext}</td>\r\n</tr>\r\n<tr>\r\n<td class=\"account_billinginfo\">\r\n<table border=\"0\" cellspacing=\"10\" cellpadding=\"10\" width=\"100%\">\r\n<tbody>\r\n<tr valign=\"top\">\r\n<td width=\"40%\">{account_image}<strong>{account_title}</strong><br /><br /> \r\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\r\n<tbody>\r\n<tr>\r\n<td class=\"account_label\">{fullname_lbl}</td>\r\n<td class=\"account_field\">{fullname}</td>\r\n</tr>\r\n<tr>\r\n<td class=\"account_label\">{state_lbl}</td>\r\n<td class=\"account_field\">{state}</td>\r\n</tr>\r\n<tr>\r\n<td class=\"account_label\">{country_lbl}</td>\r\n<td class=\"account_field\">{country}</td>\r\n</tr>\r\n<tr>\r\n<td class=\"account_label\">{vatnumber_lbl}</td>\r\n<td class=\"account_field\">{vatnumber}</td>\r\n</tr>\r\n<tr>\r\n<td class=\"account_label\">{email_lbl}</td>\r\n<td class=\"account_field\">{email}</td>\r\n</tr>\r\n<tr>\r\n<td class=\"account_label\">{company_name_lbl}</td>\r\n<td class=\"account_field\">{company_name}</td>\r\n</tr>\r\n<tr>\r\n<td colspan=\"2\">{edit_account_link}</td>\r\n</tr>\r\n<tr>\r\n<td colspan=\"2\">{newsletter_signup_chk} {newsletter_signup_lbl}</td>\r\n</tr>\r\n<tr><td colspan=\"2\">{customer_custom_fields}</td></tr></tbody>\r\n</table>\r\n</td>\r\n<td>\r\n<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>{order_image}<strong>{order_title}</strong></td>\r\n</tr>\r\n{order_loop_start}          \r\n<tr>\r\n<td>{order_index} {order_id} {order_detail_link}</td>\r\n</tr>\r\n{order_loop_end}          \r\n<tr>\r\n<td>{more_orders}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td class=\"account_shippinginfo\">{shipping_image}<strong>{shipping_title}</strong> <br /><br /> \r\n<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>{edit_shipping_link}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n<td>\r\n<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>{quotation_image}<strong>{quotation_title}</strong></td>\r\n</tr>\r\n{quotation_loop_start}          \r\n<tr>\r\n<td>{quotation_index} {quotation_id} {quotation_detail_link}</td>\r\n</tr>\r\n{quotation_loop_end}\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>{product_serial_image}<strong>{product_serial_title}</strong><br /><br /> \r\n<table border=\"0\">\r\n<tbody>\r\n{product_serial_loop_start}            \r\n<tr>\r\n<td>{product_name} {product_serial_number}</td>\r\n</tr>\r\n{product_serial_loop_end}\r\n</tbody>\r\n</table>\r\n</td>\r\n<td>\r\n<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>{coupon_image}<strong>{coupon_title}</strong></td>\r\n</tr>\r\n{coupon_loop_start}         \r\n<tr>\r\n<td>{coupon_code_lbl} {coupon_code}</td>\r\n</tr>\r\n<tr>\r\n<td>{coupon_value_lbl} {coupon_value}</td>\r\n</tr>\r\n{coupon_loop_end}\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>{wishlist_image}<strong>{wishlist_title}</strong><br /><br /> \r\n<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>{edit_wishlist_link}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n<td>{compare_image}<strong>{compare_title}</strong> <br /><br /> \r\n<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>{edit_compare_link}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>{logout_link}</td>\r\n<td>{tag_image}<strong>{tag_title}</strong><br /><br /> \r\n<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>{edit_tag_link}</td>\r\n</tr>\r\n</tbody>\r\n</table></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>";
}

$pagetitle = JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE');

if ($this->params->get('show_page_heading', 1))
{
	?>
	<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php
		if ($this->params->get('page_title') != $pagetitle)
		{
			echo $this->escape($this->params->get('page_title'));
		}
		else
		{
			echo $pagetitle;
		}    ?>
	</h1>
<?php
}

$template_desc = str_replace('{welcome_introtext}', WELCOMEPAGE_INTROTEXT, $template_desc);

$logoutimg     = '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'account/logout.jpg" align="absmiddle" />';
$logout        = '<a href="' . $logout_link . '">' . JText::_('COM_REDSHOP_LOGOUT') . '</a>';
$template_desc = str_replace('{logout_link}', $logoutimg . $logout, $template_desc);

$account_img   = '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'account/home.jpg" align="absmiddle">';
$template_desc = str_replace('{account_image}', $account_img, $template_desc);
$template_desc = str_replace('{account_title}', JText::_('COM_REDSHOP_ACCOUNT_INFORMATION'), $template_desc);

$customer_fullname_lbl = '';
$customer_fullname     = '';

if ($this->userdata->firstname != "")
{
	$customer_fullname_lbl = JText::_('COM_REDSHOP_CUSTOMER_FULLNAME');
	$customer_fullname     = $this->userdata->firstname . ' ' . $this->userdata->lastname;
}

$template_desc = str_replace('{fullname_lbl}', $customer_fullname_lbl, $template_desc);
$template_desc = str_replace('{fullname}', $customer_fullname, $template_desc);

$company_name_lbl = '';
$company_name     = '';

if ($this->userdata->is_company && $this->userdata->company_name != "")
{
	$company_name_lbl = JText::_('COM_REDSHOP_COMPANY_NAME');
	$company_name     = $this->userdata->company_name;
}

$template_desc = str_replace('{company_name_lbl}', $company_name_lbl, $template_desc);
$template_desc = str_replace('{company_name}', $company_name, $template_desc);

$customer_state_lbl = '';
$customer_state     = '';

if (trim($this->userdata->state_code) != "-" && trim($this->userdata->state_code) != "")
{
	$customer_state_lbl = JText::_('COM_REDSHOP_CUSTOMER_STATE');
	$customer_state     = $order_functions->getStateName($this->userdata->state_code, $this->userdata->country_code);

	if (trim($customer_state == ''))
	{
		$customer_state_lbl = '';
		$customer_state     = '';
	}
}

$template_desc = str_replace('{state_lbl}', $customer_state_lbl, $template_desc);
$template_desc = str_replace('{state}', $customer_state, $template_desc);

$customer_country_lbl = '';
$customer_country     = '';

if ($this->userdata->country_code)
{
	$customer_country_lbl = JText::_('COM_REDSHOP_CUSTOMER_COUNTRY');
	$customer_country     = $order_functions->getCountryName($this->userdata->country_code, $this->userdata->country_code);

	if (trim($customer_country == ''))
	{
		$customer_country_lbl = '';
		$customer_country     = '';
	}
}

$template_desc = str_replace('{country_lbl}', $customer_country_lbl, $template_desc);
$template_desc = str_replace('{country}', $customer_country, $template_desc);

$customer_vatnumber_lbl = '';
$customer_vatnumber     = '';

if (($this->userdata->is_company == 1) && ($this->userdata->vat_number != ""))
{
	$customer_vatnumber_lbl = JText::_('COM_REDSHOP_CUSTOMER_VATNUMBER');
	$customer_vatnumber     = $this->userdata->vat_number;
}

$ean_number_lbl = '';
$ean_number     = '';

if (($this->userdata->is_company == 1) && ($this->userdata->ean_number != ""))
{
	$ean_number_lbl = JText::_('COM_REDSHOP_EAN_NUMBER');
	$ean_number     = $this->userdata->ean_number;
}

$requesting_tax_exempt_lbl = '';
$requesting_tax_exempt     = '';

if ($this->userdata->is_company == 1)
{
	$requesting_tax_exempt_lbl = JText::_('COM_REDSHOP_USER_TAX_EXEMPT_REQUEST_LBL');

	if ($this->userdata->requesting_tax_exempt == 1)
	{
		$requesting_tax_exempt = JText::_("COM_REDSHOP_YES");
	}
	else
	{
		$requesting_tax_exempt = JText::_("COM_REDSHOP_NO");
	}
}

$template_desc = str_replace('{requesting_tax_exempt_lbl}', $requesting_tax_exempt_lbl, $template_desc);
$template_desc = str_replace('{requesting_tax_exempt}', $requesting_tax_exempt, $template_desc);

$template_desc = str_replace('{vatnumber_lbl}', $customer_vatnumber_lbl, $template_desc);
$template_desc = str_replace('{vatnumber}', $customer_vatnumber, $template_desc);

$template_desc = str_replace('{ean_number_lbl}', $ean_number_lbl, $template_desc);
$template_desc = str_replace('{ean_number}', $ean_number, $template_desc);

$customer_email_lbl = '';
$customer_email     = '';

if ($this->userdata->email)
{
	$customer_email_lbl = JText::_('COM_REDSHOP_CUSTOMER_EMAIL');
	$customer_email     = $this->userdata->email;
}

$template_desc = str_replace('{email_lbl}', $customer_email_lbl, $template_desc);
$template_desc = str_replace('{email}', $customer_email, $template_desc);

$customer_city_lbl = '';
$customer_city     = '';

if ($this->userdata->city)
{
	$customer_city_lbl = JText::_('COM_REDSHOP_CITY');
	$customer_city     = $this->userdata->city;
}

$template_desc = str_replace('{city_lbl}', $customer_city_lbl, $template_desc);
$template_desc = str_replace('{city}', $customer_city, $template_desc);

$customer_phone_lbl = '';
$customer_phone     = '';

if ($this->userdata->phone)
{
	$customer_phone_lbl = JText::_('COM_REDSHOP_PHONE');
	$customer_phone     = $this->userdata->phone;
}

$template_desc = str_replace('{phone_lbl}', $customer_phone_lbl, $template_desc);
$template_desc = str_replace('{phone}', $customer_phone, $template_desc);

$customer_zipcode_lbl = '';
$customer_zipcode     = '';

if ($this->userdata->zipcode)
{
	$customer_zipcode_lbl = JText::_('COM_REDSHOP_ZIP');
	$customer_zipcode     = $this->userdata->zipcode;
}

$template_desc = str_replace('{zipcode_lbl}', $customer_zipcode_lbl, $template_desc);
$template_desc = str_replace('{zipcode}', $customer_zipcode, $template_desc);

$customer_add_lbl = '';
$customer_add     = '';

if ($this->userdata->address)
{
	$customer_add_lbl = JText::_('COM_REDSHOP_ADDRESS');
	$customer_add     = $this->userdata->address;
}

$template_desc = str_replace('{address_lbl}', $customer_add_lbl, $template_desc);
$template_desc = str_replace('{address}', $customer_add, $template_desc);

$edit_account_link = '<a href="' . $accountbillto_link . '">' . JText::_('COM_REDSHOP_EDIT_ACCOUNT_INFORMATION') . '</a>';
$template_desc     = str_replace('{edit_account_link}', $edit_account_link, $template_desc);

$template_desc = $carthelper->replaceNewsletterSubscription($template_desc, 1);

if (SHIPPING_METHOD_ENABLE)
{
	$shipping_image = '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'account/order.jpg" align="absmiddle">';
	$update_link    = '<a href="' . $accountshipto_link . '">' . JText::_('COM_REDSHOP_UPDATE_SHIPPING_INFO') . '</a>';
	$template_desc  = str_replace('{shipping_image}', $shipping_image, $template_desc);
	$template_desc  = str_replace('{shipping_title}', JText::_('COM_REDSHOP_SHIPPING_INFO'), $template_desc);
	$template_desc  = str_replace('{edit_shipping_link}', $update_link, $template_desc);
}
else
{
	$template_desc = str_replace('{shipping_image}', '', $template_desc);
	$template_desc = str_replace('{shipping_title}', '', $template_desc);
	$template_desc = str_replace('{edit_shipping_link}', '', $template_desc);
}

// For redCRM user bulk order functionality
if (isset($this->userdata->debitor_id) && $this->userdata->debitor_id != "" && $this->userdata->debitor_id != 0)
{
	$bulkorder_link  = "<a href='" . JRoute::_('index.php?option=com_redcrm&view=bulk_order&Itemid=' . $Itemid) . "'>" . JText::_('COM_REDSHOP_BULK_ORDER_LINK') . "</a>";
	$bulkorder_image = '<img src="' . $url . 'administrator/components/com_redcrm/assets/images/bulk_order16.png" align="absmiddle">';
	$template_desc   = str_replace('{bulkorder_image}', $bulkorder_image, $template_desc);
	$template_desc   = str_replace('{bulkorder_title}', JText::_('COM_REDSHOP_BULK_ORDER'), $template_desc);
	$template_desc   = str_replace('{bulkorder_link}', $bulkorder_link, $template_desc);
}
else
{
	$template_desc = str_replace('{bulkorder_image}', "", $template_desc);
	$template_desc = str_replace('{bulkorder_title}', "", $template_desc);
	$template_desc = str_replace('{bulkorder_link}', "", $template_desc);
}

$is_company = $this->userdata->is_company;

if ($is_company == 1)
{
	$extrafields = $extra_field->list_all_field_display(8, $this->userdata->users_info_id);
}
else
{
	$extrafields = $extra_field->list_all_field_display(7, $this->userdata->users_info_id);
}

$template_desc = str_replace('{customer_custom_fields}', $extrafields, $template_desc);

if (strstr($template_desc, "{reserve_discount}"))
{
	$reserve_discount = $model->getReserveDiscount();
	$reserve_discount = $producthelper->getProductFormattedPrice($reserve_discount);

	$template_desc = str_replace('{reserve_discount}', $reserve_discount, $template_desc);
	$template_desc = str_replace('{reserve_discount_lbl}', JText::_('COM_REDSHOP_RESERVED_DISCOUNT_LBL'), $template_desc);
}

if (strstr($template_desc, "{order_loop_start}") && strstr($template_desc, "{order_loop_end}"))
{
	$oder_image    = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'order16.png" align="absmiddle">';
	$template_desc = str_replace('{order_image}', $oder_image, $template_desc);
	$template_desc = str_replace('{order_title}', JText::_('COM_REDSHOP_ORDER_INFORMATION'), $template_desc);

	$orderslist = $order_functions->getUserOrderDetails($user->id);

	// More Order information

	if (count($orderslist) > 0)
	{
		$ordermoreurl_1 = JRoute::_('index.php?option=' . $option . '&view=orders&Itemid=' . $Itemid);
		$ordermoreurl   = strtolower($ordermoreurl_1);

		$template_desc = str_replace('{more_orders}', "<a href='" . $ordermoreurl . "'>" . JText::_('COM_REDSHOP_MORE') . "</a>", $template_desc);
	}
	else
	{
		$template_desc = str_replace('{more_orders}', "", $template_desc);
	}

	$template_d1 = explode("{order_loop_start}", $template_desc);
	$template_d2 = explode("{order_loop_end}", $template_d1[1]);
	$order_desc  = $template_d2[0];

	$order_data = '';

	if (count($orderslist))
	{
		for ($j = 0; $j < count($orderslist); $j++)
		{
			if ($j >= 5)
			{
				break;
			}

			$order_data .= $order_desc;
			$orderdetailurl = JRoute::_('index.php?option=' . $option . '&view=order_detail&oid=' . $orderslist[$j]->order_id . '&Itemid=' . $Itemid);
			$order_detail   = '<a href="' . $orderdetailurl . '">' . JText::_('COM_REDSHOP_DETAILS') . '</a>';

			$order_data = str_replace('{order_index}', JText::_('COM_REDSHOP_ORDER_NUM'), $order_data);
			$order_data = str_replace('{order_id}', $orderslist[$j]->order_id, $order_data);
			$order_data = str_replace('{order_detail_link}', $order_detail, $order_data);
		}
	}
	else
	{
		$order_data .= $order_desc;
		$order_data = str_replace('{order_index}', '', $order_data);
		$order_data = str_replace('{order_id}', '', $order_data);
		$order_data = str_replace('{order_detail_link}', JText::_('COM_REDSHOP_NO_ORDERS_PLACED_YET'), $order_data);
	}

	$template_desc = str_replace('{order_loop_start}', "", $template_desc);
	$template_desc = str_replace('{order_loop_end}', "", $template_desc);
	$template_desc = str_replace($order_desc, $order_data, $template_desc);
}

if (strstr($template_desc, "{coupon_loop_start}") && strstr($template_desc, "{coupon_loop_end}"))
{
	$ctemplate_d1 = explode("{coupon_loop_start}", $template_desc);
	$ctemplate_d2 = explode("{coupon_loop_end}", $ctemplate_d1[1]);
	$coupon_desc  = $ctemplate_d2[0];

	$coupon_image    = '';
	$coupon_imagelbl = '';
	$coupon_data     = '';

	if (COUPONINFO)
	{
		$coupon_imagelbl = JText::_('COM_REDSHOP_COUPON_INFO');
		$coupon_image    = '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'account/coupon.jpg" align="absmiddle">';
		$usercoupons     = $model->usercoupons($user->id);

		if (count($usercoupons))
		{
			for ($i = 0; $i < count($usercoupons); $i++)
			{
				$coupon_data .= $coupon_desc;
				$unused_amount = $model->unused_coupon_amount($user->id, $usercoupons[$i]->coupon_code);
				$coupon_data   = str_replace('{coupon_code_lbl}', JText::_('COM_REDSHOP_COUPON_CODE'), $coupon_data);
				$coupon_data   = str_replace('{coupon_code}', $usercoupons[$i]->coupon_code, $coupon_data);
				$coupon_data   = str_replace('{coupon_value_lbl}', JText::_('COM_REDSHOP_COUPON_VALUE'), $coupon_data);
				$coupon_data   = str_replace('{unused_coupon_lbl}', JText::_('COM_REDSHOP_UNUSED_COUPON_LBL'), $coupon_data);
				$coupon_data   = str_replace('{unused_coupon_value}', $unused_amount, $coupon_data);

				$coupon_value = ($usercoupons[$i]->percent_or_total == 0) ? $producthelper->getProductFormattedPrice($usercoupons[$i]->coupon_value) : $usercoupons[$i]->coupon_value . " %";
				$coupon_data  = str_replace('{coupon_value}', $coupon_value, $coupon_data);
			}
		}
		else
		{
			$coupon_data .= $coupon_desc;
			$coupon_data = str_replace('{coupon_code_lbl}', '', $coupon_data);
			$coupon_data = str_replace('{coupon_code}', '', $coupon_data);
			$coupon_data = str_replace('{coupon_value_lbl}', '', $coupon_data);
			$coupon_data = str_replace('{unused_coupon_value}', '', $coupon_data);
			$coupon_data = str_replace('{unused_coupon_lbl}', '', $coupon_data);
			$coupon_data = str_replace('{coupon_value}', JText::_('COM_REDSHOP_NO_COUPONS'), $coupon_data);
		}
	}

	$template_desc = str_replace('{coupon_loop_start}', "", $template_desc);
	$template_desc = str_replace('{coupon_loop_end}', "", $template_desc);
	$template_desc = str_replace($coupon_desc, $coupon_data, $template_desc);
	$template_desc = str_replace('{coupon_image}', $coupon_image, $template_desc);
	$template_desc = str_replace('{coupon_title}', $coupon_imagelbl, $template_desc);
}

$tag_imagelbl = '';
$tag_image    = '';
$tag_link     = '';

if (MY_TAGS)
{
	$tag_imagelbl = JText::_('COM_REDSHOP_MY_TAGS');
	$tag_image    = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'textlibrary16.png" align="absmiddle">';
	$tag_link     = JText::_('COM_REDSHOP_NO_TAGS_AVAILABLE');
	$myTags       = $model->countMyTags();

	if ($myTags > 0)
	{
		$tag_link = '<a href="' . $mytags_link . '" style="text-decoration: none;">' . JText::_("COM_REDSHOP_SHOW_TAG") . '</a>';
	}
}

$template_desc = str_replace('{tag_image}', $tag_image, $template_desc);
$template_desc = str_replace('{tag_title}', $tag_imagelbl, $template_desc);
$template_desc = str_replace('{edit_tag_link}', $tag_link, $template_desc);

if (strstr($template_desc, "{quotation_loop_start}") && strstr($template_desc, "{quotation_loop_end}"))
{
	$quotation_image = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'quotation_16.jpg" align="absmiddle">';
	$template_desc   = str_replace('{quotation_image}', $quotation_image, $template_desc);
	$template_desc   = str_replace('{quotation_title}', JText::_('COM_REDSHOP_QUOTATION_INFORMATION'), $template_desc);

	$quotationlist = $quotationHelper->getQuotationUserList();

	// More Order information
	if (count($quotationlist) > 0)
	{
		$quotationmoreurl = JRoute::_('index.php?option=' . $option . '&view=quotation&Itemid=' . $Itemid);
		$template_desc    = str_replace('{more_quotations}', "<a href='" . $quotationmoreurl . "'>" . JText::_('COM_REDSHOP_MORE') . "</a>", $template_desc);
	}

	$template_d1    = explode("{quotation_loop_start}", $template_desc);
	$template_d2    = explode("{quotation_loop_end}", $template_d1[1]);
	$quotation_desc = $template_d2[0];

	$quotation_data = '';

	if (count($quotationlist))
	{
		for ($j = 0; $j < count($quotationlist); $j++)
		{
			if ($j >= 5)
			{
				break;
			}

			$quotation_data .= $quotation_desc;
			$quotationurl     = JRoute::_('index.php?option=' . $option . '&view=quotation_detail&quoid=' . $quotationlist[$j]->quotation_id . '&Itemid=' . $Itemid);
			$quotation_detail = '<a href="' . $quotationurl . '" title="' . JText::_('COM_REDSHOP_VIEW_QUOTATION') . '"  alt="' . JText::_('COM_REDSHOP_VIEW_QUOTATION') . '">' . JText::_('COM_REDSHOP_DETAILS') . '</a>';

			$quotation_data = str_replace('{quotation_index}', JText::_('COM_REDSHOP_QUOTATION') . " #", $quotation_data);
			$quotation_data = str_replace('{quotation_id}', $quotationlist[$j]->quotation_id, $quotation_data);
			$quotation_data = str_replace('{quotation_detail_link}', $quotation_detail, $quotation_data);
		}
	}
	else
	{
		$quotation_data .= $quotation_desc;
		$quotation_data = str_replace('{quotation_index}', '', $quotation_data);
		$quotation_data = str_replace('{quotation_id}', '', $quotation_data);
		$quotation_data = str_replace('{quotation_detail_link}', JText::_('COM_REDSHOP_NO_QUOTATION_PLACED_YET'), $quotation_data);
	}

	$template_desc = str_replace('{quotation_loop_start}', "", $template_desc);
	$template_desc = str_replace('{quotation_loop_end}', "", $template_desc);
	$template_desc = str_replace($quotation_desc, $quotation_data, $template_desc);
}

$wishlist_imagelbl  = '';
$wishlist_image     = '';
$edit_wishlist_link = '';

if (MY_WISHLIST)
{
	$wishlist_imagelbl  = JText::_('COM_REDSHOP_MY_WISHLIST');
	$wishlist_image     = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'textlibrary16.png" align="absmiddle">';
	$edit_wishlist_link = JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST');
	$myWishlist         = $model->countMyWishlist();

	if ($myWishlist > 0)
	{
		$edit_wishlist_link = '<a href="' . $wishlist_link . '" style="text-decoration: none;">' . JText::_("COM_REDSHOP_SHOW_WISHLIST_PRODUCTS") . '</a>';
	}
}

$template_desc = str_replace('{wishlist_image}', $wishlist_image, $template_desc);
$template_desc = str_replace('{wishlist_title}', $wishlist_imagelbl, $template_desc);
$template_desc = str_replace('{edit_wishlist_link}', $edit_wishlist_link, $template_desc);

if (strstr($template_desc, "{product_serial_loop_start}") && strstr($template_desc, "{product_serial_loop_end}"))
{
	$product_serial_image = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'products16.png" align="absmiddle">';
	$template_desc        = str_replace('{product_serial_image}', $product_serial_image, $template_desc);
	$template_desc        = str_replace('{product_serial_title}', JText::_('COM_REDSHOP_MY_SERIALS'), $template_desc);

	$template_d1 = explode("{product_serial_loop_start}", $template_desc);
	$template_d2 = explode("{product_serial_loop_end}", $template_d1[1]);
	$serial_desc = $template_d2[0];

	$userDownloadProduct = $model->getdownloadproductlist($user->id);

	$serial_data = '';

	if (count($userDownloadProduct))
	{
		for ($j = 0; $j < count($userDownloadProduct); $j++)
		{
			$serial_data .= $serial_desc;
			$serial_data = str_replace('{product_name}', $userDownloadProduct[$j]->product_name, $serial_data);
			$serial_data = str_replace('{product_serial_number}', $userDownloadProduct[$j]->product_serial_number, $serial_data);
		}
	}
	else
	{
		$serial_data .= $serial_desc;
		$serial_data = str_replace('{product_name}', "", $serial_data);
		$serial_data = str_replace('{product_serial_number}', "", $serial_data);
	}

	$template_desc = str_replace('{product_serial_loop_start}', "", $template_desc);
	$template_desc = str_replace('{product_serial_loop_end}', "", $template_desc);
	$template_desc = str_replace($serial_desc, $serial_data, $template_desc);
}

$cmp_imagelbl = '';
$cmp_image    = '';
$cmp_link     = '';

if (COMARE_PRODUCTS)
{
	$cmp_imagelbl = JText::_('COM_REDSHOP_COMPARE_PRODUCTS');
	$cmp_image    = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'textlibrary16.png" align="absmiddle">';
	$cmp_link     = JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE');
	$compare      = $producthelper->getcompare();

	if (isset($compare['idx']) && $compare['idx'] > 0)
	{
		$cmp_link = '<a href="' . $compare_link . '" style="text-decoration: none;">' . JText::_("COM_REDSHOP_SHOW_PRODUCTS_TO_COMPARE") . '</a>';
	}
}

$template_desc = str_replace('{compare_image}', $cmp_image, $template_desc);
$template_desc = str_replace('{compare_title}', $cmp_imagelbl, $template_desc);
$template_desc = str_replace('{edit_compare_link}', $cmp_link, $template_desc);

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
echo eval("?>" . $template_desc . "<?php ");
