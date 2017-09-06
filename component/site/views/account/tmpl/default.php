<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$url = JURI::base();


$producthelper   = productHelper::getInstance();
$quotationHelper = quotationHelper::getInstance();
$order_functions = order_functions::getInstance();
$configobj       = Redconfiguration::getInstance();
$redTemplate     = Redtemplate::getInstance();
$extra_field     = extra_field::getInstance();
$extraField      = extraField::getInstance();
$carthelper      = rsCarthelper::getInstance();

$user         = JFactory::getUser();
$app          = JFactory::getApplication();
$Itemid       = $app->input->getInt('Itemid');
$params       = $app->getParams('com_redshop');
$returnitemid = $params->get('logout', $Itemid);

$accountbillto_link = JRoute::_("index.php?option=com_redshop&view=account_billto&Itemid=" . $Itemid);
$accountshipto_link = JRoute::_("index.php?option=com_redshop&view=account_shipto&Itemid=" . $Itemid);
$logout_link        = JRoute::_("index.php?option=com_redshop&view=login&task=logout&logout=" . $returnitemid . "&Itemid=" . $Itemid);
$compare_link       = JRoute::_("index.php?option=com_redshop&view=product&layout=compare&Itemid=" . $Itemid);
$mytags_link        = JRoute::_("index.php?option=com_redshop&view=account&layout=mytags&Itemid=" . $Itemid);
$wishlist_link      = JRoute::_("index.php?option=com_redshop&view=wishlist&task=viewwishlist&Itemid=" . $Itemid);

$model    = $this->getModel('account');
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

JPluginHelper::importPlugin('redshop_account');
$dispatcher = RedshopHelperUtility::getDispatcher();
$dispatcher->trigger('onReplaceAccountTemplate', array(&$template_desc, $this->userdata));

$template_desc = str_replace('{welcome_introtext}', Redshop::getConfig()->get('WELCOMEPAGE_INTROTEXT'), $template_desc);

$logoutimg     = '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'account/logout.jpg" align="absmiddle" />';
$logout        = '<a href="' . $logout_link . '">' . JText::_('COM_REDSHOP_LOGOUT') . '</a>';
$template_desc = str_replace('{logout_link}', $logoutimg . $logout, $template_desc);

$account_img   = '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'account/home.jpg" align="absmiddle">';
$template_desc = str_replace('{account_image}', $account_img, $template_desc);
$template_desc = str_replace('{account_title}', JText::_('COM_REDSHOP_ACCOUNT_INFORMATION'), $template_desc);

$template_desc = $carthelper->replaceBillingAddress($template_desc, $this->userdata);

$edit_account_link = '<a class="btn btn-primary" href="' . $accountbillto_link . '">' . JText::_('COM_REDSHOP_EDIT_ACCOUNT_INFORMATION') . '</a>';
$template_desc     = str_replace('{edit_account_link}', $edit_account_link, $template_desc);

$template_desc = $carthelper->replaceNewsletterSubscription($template_desc, 1);

if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
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
		$ordermoreurl = JRoute::_('index.php?option=com_redshop&view=orders&Itemid=' . $Itemid);
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
		for ($j = 0, $jn = count($orderslist); $j < $jn; $j++)
		{
			if ($j >= 5)
			{
				break;
			}

			$order_data .= $order_desc;
			$orderdetailurl = JRoute::_('index.php?option=com_redshop&view=order_detail&oid=' . $orderslist[$j]->order_id . '&Itemid=' . $Itemid);
			$order_detail   = '<a href="' . $orderdetailurl . '">' . JText::_('COM_REDSHOP_DETAILS') . '</a>';

			$order_data = str_replace('{order_index}', JText::_('COM_REDSHOP_ORDER_NUM'), $order_data);
			$order_data = str_replace('{order_id}', $orderslist[$j]->order_id, $order_data);
			$order_data = str_replace('{order_number}', $orderslist[$j]->order_number, $order_data);
			$order_data = str_replace('{order_detail_link}', $order_detail, $order_data);
			$order_data = str_replace('{order_total}', $producthelper->getProductFormattedPrice($orderslist[$j]->order_total), $order_data);
		}
	}
	else
	{
		$order_data .= $order_desc;
		$order_data = str_replace('{order_index}', '', $order_data);
		$order_data = str_replace('{order_id}', '', $order_data);
		$order_data = str_replace('{order_number}', '', $order_data);
		$order_data = str_replace('{order_detail_link}', JText::_('COM_REDSHOP_NO_ORDERS_PLACED_YET'), $order_data);
		$order_data = str_replace('{order_total}', '', $order_data);
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

	if (Redshop::getConfig()->get('COUPONINFO'))
	{
		$coupon_imagelbl = JText::_('COM_REDSHOP_COUPON_INFO');
		$coupon_image    = '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'account/coupon.jpg" align="absmiddle">';
		$usercoupons     = $model->usercoupons($user->id);

		if (count($usercoupons))
		{
			for ($i = 0, $in = count($usercoupons); $i < $in; $i++)
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


if (strpos($template_desc, "{if coupon}") !== false && strpos($template_desc, "{coupon end if}") !== false)
{
	$template_d1 = explode("{if coupon}", $template_desc);
	$template_d2 = explode("{coupon end if}", $template_d1[1]);

	if (Redshop::getConfig()->get('COUPONINFO') && count($usercoupons))
	{
		$template_desc = str_replace("{if coupon}", "", $template_desc);
		$template_desc = str_replace("{coupon end if}", "", $template_desc);
	}
	else
	{
		$template_desc = $template_d1[0] . $template_d2[1];
	}
}

$tag_imagelbl = '';
$tag_image    = '';
$tag_link     = '';

if (Redshop::getConfig()->get('MY_TAGS'))
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

if (strpos($template_desc, "{if tag}") !== false && strpos($template_desc, "{tag end if}") !== false)
{
	$template_d1 = explode("{if tag}", $template_desc);
	$template_d2 = explode("{tag end if}", $template_d1[1]);

	if (Redshop::getConfig()->get('MY_TAGS') && $myTags > 0)
	{
		$template_desc = str_replace("{if tag}", "", $template_desc);
		$template_desc = str_replace("{tag end if}", "", $template_desc);
	}
	else
	{
		$template_desc = $template_d1[0] . $template_d2[1];
	}
}

$quotations = array();

if (strstr($template_desc, "{quotation_loop_start}") && strstr($template_desc, "{quotation_loop_end}"))
{
	$quotation_image = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'quotation_16.jpg" align="absmiddle">';
	$template_desc   = str_replace('{quotation_image}', $quotation_image, $template_desc);
	$template_desc   = str_replace('{quotation_title}', JText::_('COM_REDSHOP_QUOTATION_INFORMATION'), $template_desc);

	$quotations = $quotationHelper->getQuotationUserList();

	// More Order information
	if (!empty($quotations))
	{
		$quotationmoreurl = JRoute::_('index.php?option=com_redshop&view=quotation&Itemid=' . $Itemid);
		$template_desc    = str_replace('{more_quotations}', "<a href='" . $quotationmoreurl . "'>" . JText::_('COM_REDSHOP_MORE') . "</a>", $template_desc);
	}

	$template_d1    = explode("{quotation_loop_start}", $template_desc);
	$template_d2    = explode("{quotation_loop_end}", $template_d1[1]);
	$quotation_desc = $template_d2[0];

	$quotation_data = '';

	if (count($quotations))
	{
		for ($j = 0, $jn = count($quotations); $j < $jn; $j++)
		{
			if ($j >= 5)
			{
				break;
			}

			$quotation_data .= $quotation_desc;
			$quotationurl     = JRoute::_('index.php?option=com_redshop&view=quotation_detail&quoid=' . $quotations[$j]->quotation_id . '&Itemid=' . $Itemid);
			$quotation_detail = '<a href="' . $quotationurl . '" title="' . JText::_('COM_REDSHOP_VIEW_QUOTATION') . '"  alt="' . JText::_('COM_REDSHOP_VIEW_QUOTATION') . '">' . JText::_('COM_REDSHOP_DETAILS') . '</a>';

			$quotation_data = str_replace('{quotation_index}', JText::_('COM_REDSHOP_QUOTATION') . " #", $quotation_data);
			$quotation_data = str_replace('{quotation_id}', $quotations[$j]->quotation_id, $quotation_data);
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
$myWishlist = 0;

if (strpos($template_desc, "{if quotation}") !== false && strpos($template_desc, "{quotation end if}") !== false)
{
	$template_d1 = explode("{if quotation}", $template_desc);
	$template_d2 = explode("{quotation end if}", $template_d1[1]);

	if (!empty($quotations))
	{
		$template_desc = str_replace("{if quotation}", "", $template_desc);
		$template_desc = str_replace("{quotation end if}", "", $template_desc);
	}
	else
	{
		$template_desc = $template_d1[0] . $template_d2[1];
	}
}

if (Redshop::getConfig()->get('MY_WISHLIST'))
{
	$wishlist_imagelbl  = JText::_('COM_REDSHOP_MY_WISHLIST');
	$wishlist_image     = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'textlibrary16.png" align="absmiddle">';
	$edit_wishlist_link = JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST');
	$myWishlist         = $model->countMyWishlist();

	if ($myWishlist)
	{
		$edit_wishlist_link = '<a href="' . $wishlist_link . '" style="text-decoration: none;">' . JText::_("COM_REDSHOP_SHOW_WISHLIST_PRODUCTS") . '</a>';
	}
}

$template_desc = str_replace('{wishlist_image}', $wishlist_image, $template_desc);
$template_desc = str_replace('{wishlist_title}', $wishlist_imagelbl, $template_desc);
$template_desc = str_replace('{edit_wishlist_link}', $edit_wishlist_link, $template_desc);

if (strpos($template_desc, "{if wishlist}") !== false && strpos($template_desc, "{wishlist end if}") !== false)
{
	$template_d1 = explode("{if wishlist}", $template_desc);
	$template_d2 = explode("{wishlist end if}", $template_d1[1]);


	if ($myWishlist)
	{
		$template_desc = str_replace("{if wishlist}", "", $template_desc);
		$template_desc = str_replace("{wishlist end if}", "", $template_desc);
	}
	else
	{
		$template_desc = $template_d1[0] . $template_d2[1];
	}
}

$userDownloadProduct = array();

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

	if (!empty($userDownloadProduct))
	{
		for ($j = 0, $jn = count($userDownloadProduct); $j < $jn; $j++)
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

if (strpos($template_desc, "{if product_serial}") !== false && strpos($template_desc, "{product_serial end if}") !== false)
{
	$template_d1 = explode("{if product_serial}", $template_desc);
	$template_d2 = explode("{product_serial end if}", $template_d1[1]);

	if (!empty($userDownloadProduct))
	{
		$template_desc = str_replace("{if product_serial}", "", $template_desc);
		$template_desc = str_replace("{product_serial end if}", "", $template_desc);
	}
	else
	{
		$template_desc = $template_d1[0] . $template_d2[1];
	}
}

$cmp_imagelbl = '';
$cmp_image    = '';
$cmp_link     = '';

if (Redshop::getConfig()->get('COMPARE_PRODUCTS'))
{
	$cmp_imagelbl = JText::_('COM_REDSHOP_COMPARE_PRODUCTS');
	$cmp_image    = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'textlibrary16.png" align="absmiddle">';
	$cmp_link     = JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE');
	$compare      = new RedshopProductCompare;

	if (!$compare->isEmpty())
	{
		$cmp_link = '<a href="' . $compare_link . '" style="text-decoration: none;">' . JText::_("COM_REDSHOP_SHOW_PRODUCTS_TO_COMPARE") . '</a>';
	}
}

if (strpos($template_desc, "{if compare}") !== false && strpos($template_desc, "{compare end if}") !== false)
{
	$template_d1 = explode("{if compare}", $template_desc);
	$template_d2 = explode("{compare end if}", $template_d1[1]);

	if (Redshop::getConfig()->get('COMPARE_PRODUCTS') && !$compare->isEmpty())
	{
		$template_desc = str_replace("{if compare}", "", $template_desc);
		$template_desc = str_replace("{compare end if}", "", $template_desc);
	}
	else
	{
		$template_desc = $template_d1[0] . $template_d2[1];
	}
}

$template_desc = str_replace('{compare_image}', $cmp_image, $template_desc);
$template_desc = str_replace('{compare_title}', $cmp_imagelbl, $template_desc);
$template_desc = str_replace('{edit_compare_link}', $cmp_link, $template_desc);
$template_desc = str_replace('{if compare}', '', $template_desc);
$template_desc = str_replace('{compare end if}', '', $template_desc);

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
echo eval("?>" . $template_desc . "<?php ");
