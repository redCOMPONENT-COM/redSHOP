<?php
/**
 * @package     RedSHOP.sh404sef
 * @subpackage  sef_ext sh404sef
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG;
$sefConfig      = shRouter::shGetConfig();
$db             = JFactory::getDbo();
$shLangName     = '';
$shLangIso      = '';
$title          = array();
$shItemidString = '';

$dosef = shInitializePlugin($lang, $shLangName, $shLangIso, 'com_redshop');

if ($dosef == false)
{
	return;
}

// ------------------  load language file - adjust as needed ----------------------------------------
$shLangIso = shLoadPluginLanguage('com_redshop', $shLangIso, '_COM_SEF_SH_REDSHOP');

JLoader::import('redshop.library');
$product_category = new product_category;
$productHelper = productHelper::getInstance();

shRemoveFromGETVarsList('option');

if (!empty($lang))
{
	shRemoveFromGETVarsList('lang');
}

shRemoveFromGETVarsList('Itemid');
shRemoveFromGETVarsList('task');
$limitstart = isset($limitstart) ? @$limitstart : 0;

if (isset($limit))
{
	// V 1.2.4.r
	shRemoveFromGETVarsList('limit');
}

if (isset($limitstart))
{
	// V 1.2.4.r
	// limitstart can be zero
	shRemoveFromGETVarsList('limitstart');
}

$view           = isset($view) ? @$view : null;
$cid            = isset($cid) ? @$cid : null;
$gid            = isset($gid) ? @$gid : null;
$mid            = isset($mid) ? @$mid : null;
$Itemid         = isset($Itemid) ? @$Itemid : null;
$sid            = isset($sid) ? @$sid : null;
$pid            = isset($pid) ? @$pid : null;
$infoid         = isset($infoid) ? @$infoid : null;
$task           = isset($task) ? @$task : null;
$layout         = isset($layout) ? @$layout : null;
$oid            = isset($oid) ? @$oid : null;
$order_id       = isset($order_id) ? @$order_id : null;
$quoid          = isset($quoid) ? @$quoid : null;
$msg            = isset($msg) ? @$msg : null;
$wishlist_id    = isset($wishlist_id) ? @$wishlist_id : null;
$keyword        = isset($keyword) ? @$keyword : null;
$categoryid     = isset($categoryid) ? @$categoryid : null;
$category_id    = isset($category_id) ? @$category_id : null;
$manufacture_id = isset($manufacture_id) ? @$manufacture_id : null;
$remove         = isset($remove) ? @$remove : null;
$Treeid         = isset($Treeid) ? @$Treeid : null;
$print          = isset($print) ? @$print : null;
$protalid       = isset($protalid) ? @$protalid : 0;

// Get variables for pagination in category
$category_template = isset($category_template) ? @$category_template : null;
$manufacturer_id   = isset($manufacturer_id) ? @$manufacturer_id : null;

$order_by = isset($order_by) ? @$order_by : null;
shRemoveFromGETVarsList('order_by');

$texpricemax = isset($texpricemax) ? @$texpricemax : null;
$texpricemin = isset($texpricemin) ? @$texpricemin : null;

$payment_method_id = isset($payment_method_id) ? @$payment_method_id : null;
$shipping_rate_id  = isset($shipping_rate_id) ? @$shipping_rate_id : null;

if ($menu = $productHelper->getMenuInformation($Itemid, 0, '', $view))
{
	$myparams = $menu->params;
}
else
{
	$menu        = new stdClass;
	$menu->title = '';
	$menu->alias = '';
	$myparams    = new JRegistry;
}

// Set redSHOP prefix
$component_prefix = shGetComponentPrefix('com_redshop');

if (trim($component_prefix) != "")
{
	$title[] = $component_prefix;
}

switch ($view)
{
	case 'category':

		// If link set From Manufacturer detail Page
		if ($manufacturer_id)
		{
			$sql = "SELECT sef_url,manufacturer_name FROM #__redshop_manufacturer WHERE manufacturer_id = '$manufacturer_id'";
			$db->setQuery($sql);
			$manufacturer = $db->loadObject();
			$title[]      = RedshopHelperUtility::convertToNonSymbol($manufacturer->manufacturer_name);
		}

		if (!$cid)
		{
			$cid = $myparams->get('categoryid');
		}

		if ($cid)
		{
			$url = RedshopHelperCategory::getCategoryById($cid);

			if ($url->sef_url == "")
			{
				if (Redshop::getConfig()->get('CATEGORY_TREE_IN_SEF_URL'))
				{
					$GLOBALS['catlist_reverse'] = array();
					$cats                       = RedshopHelperCategory::getCategoryListReverseArray($cid);

					if (count($cats) > 0)
					{
						$cats = array_reverse($cats);

						for ($x = 0, $xn = count($cats); $x < $xn; $x++)
						{
							$cat     = $cats[$x];

							if ($cat->parent_id == 0)
							{
								continue;
							}

							$title[] = str_replace(".", "", $cat->name);
						}
					}
				}

				$title[] = str_replace(".", "", $url->name);
			}
			else
			{
				$title[] = str_replace(".", "", $url->sef_url);
			}

			shRemoveFromGETVarsList('view');
			shRemoveFromGETVarsList('cid');
		}
		else
		{
			if ($sefConfig->useMenuAlias && $menu->alias != '')
			{
				$title[] = $menu->alias;
			}
			else
			{
				if ($menu->title != '')
				{
					$title[] = $menu->title;
				}
			}

			shRemoveFromGETVarsList('view');
		}

		if ($layout != 'detail' && $layout != '')
		{
			$title[] = $sh_LANG[$shLangIso]['_REDSHOP_CATEGORY_PRODUCT_LAYOUT'];
			shRemoveFromGETVarsList('cid');
		}

		shRemoveFromGETVarsList('layout');

		if ($Treeid)
		{
			$title[] = "TigraTree";
			shRemoveFromGETVarsList('Treeid');
		}

		shRemoveFromGETVarsList('category_template');
		shRemoveFromGETVarsList('manufacturer_id');
		shRemoveFromGETVarsList('order_by');
		shRemoveFromGETVarsList('texpricemax');
		shRemoveFromGETVarsList('texpricemin');
		shRemoveFromGETVarsList('maxproduct');

		break;

	case 'product':
		if ($pid)
		{
			$product = RedshopHelperProduct::getProductById($pid);

			$url = trim($product->sef_url);

			if (trim($url) == "")
			{
				if (Redshop::getConfig()->get('CATEGORY_IN_SEF_URL'))
				{
					$GLOBALS['catlist_reverse'] = array();
					$where                      = '';

					if ($cid)
					{
						$category_id = $cid;
					}
					else
					{
						$cat_in_sefurl = $product->cat_in_sefurl;
						$category_id = 0;

						if ($cat_in_sefurl > 0)
						{
							$where = " AND c.category_id = '$cat_in_sefurl'";

							if ($categoryData = RedshopHelperCategory::getCategoryById($cat_in_sefurl))
							{
								$category_id = $categoryData->id;
							}
						}
						else
						{
							$category_id = $product->category_id;
						}
					}

					if (Redshop::getConfig()->get('CATEGORY_TREE_IN_SEF_URL'))
					{
						$cats = RedshopHelperCategory::getCategoryListReverseArray($category_id);

						if (count($cats) > 0)
						{
							$cats = array_reverse($cats);

							for ($x = 0, $xn = count($cats); $x < $xn; $x++)
							{
								$cat     = $cats[$x];

								if ($cat->parent_id == 0)
								{
									continue;
								}

								$title[] = $cat->name;
							}
						}
					}

					$catname = '';

					if ($categoryData = RedshopHelperCategory::getCategoryById($category_id))
					{
						$catname = $categoryData->name;
					}

					$title[] = RedshopHelperUtility::convertToNonSymbol($catname);
				}

				if (Redshop::getConfig()->get('ENABLE_SEF_PRODUCT_NUMBER'))
				{
					$title[] = RedshopHelperUtility::convertToNonSymbol($product->product_number);
				}

				$title[] = RedshopHelperUtility::convertToNonSymbol($product->product_name);
			}
			else
			{
				$title[] = $url;
			}
		}

		if ($layout)
		{
			if ($layout == "compare" && $cid != "" && $cid != '0')
			{
				$title[] = $cid;
				$title[] = $layout;
			}
			elseif ($layout == 'downloadproduct')
			{
				$title[] = $layout;
			}
			else
			{
				$title[] = $layout;
			}

			shRemoveFromGETVarsList('layout');
		}

		if ($print)
		{
			$title[] = $print;
			shRemoveFromGETVarsList('print');
		}

		shRemoveFromGETVarsList('pid');
		shRemoveFromGETVarsList('cid');
		shRemoveFromGETVarsList('view');

		break;

	case 'cart':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_CART'];
		shRemoveFromGETVarsList('view');

		if ($print)
		{
			$title[] = $print;
			shRemoveFromGETVarsList('print');
		}
		break;

	case 'giftcard':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_GIFTCARD'];
		shRemoveFromGETVarsList('view');

		if ($gid)
		{
			$sql = "SELECT giftcard_name  FROM #__redshop_giftcard WHERE giftcard_id = '$gid'";
			$db->setQuery($sql);
			$giftcardname = $db->loadResult();

			$title[] = $giftcardname;
			shRemoveFromGETVarsList('gid');
		}
		break;

	case 'checkout':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_CHECKOUT'];
		shRemoveFromGETVarsList('view');

		switch ($task)
		{
			case "checkoutfinal":
				$title[] = $sh_LANG[$shLangIso]['_REDSHOP_CHECKOUTFINAL'];
				shRemoveFromGETVarsList('task');
				shRemoveFromGETVarsList('ccinfo');
				$title[] = $payment_method_id;
				shRemoveFromGETVarsList('payment_method_id');
				$title[] = $shipping_rate_id;
				shRemoveFromGETVarsList('shipping_rate_id');
				shRemoveFromGETVarsList('users_info_id');

				break;
		}
		break;

	case 'login':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_LOGIN'];
		shRemoveFromGETVarsList('view');

		switch ($task)
		{
			case "logout":
				$title[] = $sh_LANG[$shLangIso]['_REDSHOP_LOGOUT'];
				shRemoveFromGETVarsList('view');
		}

		if ($layout)
		{
			$title[] = $layout;

			if ($layout == 'portal')
			{
				$user = JFactory::getUser();

				if ($user->id > 0)
				{
					$title[] = $sh_LANG[$shLangIso]['_REDSHOP_PORTAL_AFTERLOGIN'];
				}
			}

			shRemoveFromGETVarsList('layout');
		}

		shRemoveFromGETVarsList('protalid');
		break;

	case 'password':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_PASSWORD'];
		shRemoveFromGETVarsList('view');

		if ($layout != "")
		{
			$title[] = $layout;
			shRemoveFromGETVarsList('layout');
		}

		break;

	case 'registration':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_REGISTRATION'];
		shRemoveFromGETVarsList('view');
		break;

	case 'redshop':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_REDSHOP'];
		shRemoveFromGETVarsList('view');
		break;

	case 'account':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ACCOUNT'];
		shRemoveFromGETVarsList('view');

		if ($layout != '')
		{
			$title[] = $layout;
			shRemoveFromGETVarsList('layout');
		}

		// Get Wishlist name
		$sql = "SELECT * FROM `#__redshop_wishlist` WHERE wishlist_id = '$wishlist_id'";
		$db->setQuery($sql);
		$wishlist = $db->loadObject();

		if ($wishlist_id)
		{
			$title[] = $wishlist_id;

			if ($wishlist)
			{
				$title[] = RedshopHelperUtility::convertToNonSymbol($wishlist->wishlist_name);
			}

			shRemoveFromGETVarsList('wishlist_id');
		}

		if ($pid)
		{
			$product = RedshopHelperProduct::getProductById($pid);
			$title[] = $product->product_name;
			shRemoveFromGETVarsList('pid');
		}

		if ($remove)
		{
			$title[] = $sh_LANG[$shLangIso]['_REDSHOP_REMOVE'];
			shRemoveFromGETVarsList('remove');
		}

		break;

	case 'account_billto':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ACCOUNT_BILLTO'];
		shRemoveFromGETVarsList('view');
		break;

	case 'account_shipto':

		switch ($task)
		{
			case "addshipping":
				$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ACCOUNT_SHIPTO'];
				shRemoveFromGETVarsList('view');

				$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ADDSHIPPING'];
				shRemoveFromGETVarsList('task');

				if ($infoid > 0)
				{
					$title[] = $infoid;
					shRemoveFromGETVarsList('infoid');
				}
				break;

			case "remove":
				$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ACCOUNT_SHIPTO'];
				shRemoveFromGETVarsList('view');

				$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ACCOUNT_REMOVE'];
				shRemoveFromGETVarsList('task');

				if ($infoid > 0)
				{
					$title[] = $infoid;
					shRemoveFromGETVarsList('infoid');
				}
				break;

			default:
				$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ACCOUNT_SHIPTO'];
				shRemoveFromGETVarsList('view');
				break;
		}
		break;

	case 'send_friend':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_SEND_FRIEND'];
		shRemoveFromGETVarsList('view');
		break;

	case 'ratings':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_RATINGS'];
		shRemoveFromGETVarsList('view');
		break;

	case 'catalog':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_CATALOG'];
		shRemoveFromGETVarsList('view');
		break;

	case 'orders':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ORDERS'];
		shRemoveFromGETVarsList('view');
		break;

	case 'order_detail':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ORDER_DETAIL'];
		shRemoveFromGETVarsList('view');

		if ($layout != "")
		{
			$title[] = $layout;
			shRemoveFromGETVarsList('layout');
		}

		if ($order_id)
		{
			$title[] = $order_id;
			shRemoveFromGETVarsList('order_id');
		}

		if ($task)
		{
			$title[] = $task;
			shRemoveFromGETVarsList('task');
		}

		$title[] = $oid;
		shRemoveFromGETVarsList('oid');
		break;
	case 'quotation':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_QUOTATION'];
		shRemoveFromGETVarsList('view');
		break;

	case 'quotation_detail':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_QUOTATION_DETAIL'];
		shRemoveFromGETVarsList('view');
		$title[] = $quoid;
		shRemoveFromGETVarsList('quoid');
		break;

	case 'ordertracker':

		if ($sefConfig->useMenuAlias && $menu->alias != '')
		{
			$title[] = $menu->alias;
		}
		elseif ($menu->title != '')
		{
			$title[] = $menu->title;
		}
		else
		{
			$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ORDER_TRACKER'];
		}

		shRemoveFromGETVarsList('view');

		break;

	case 'stockroom':

		if ($sefConfig->useMenuAlias && $menu->alias != '')
		{
			$title[] = $menu->alias;
		}
		elseif ($menu->title != '')
		{
			$title[] = $menu->title;
		}
		else
		{
			$title[] = $sh_LANG[$shLangIso]['_REDSHOP_STOCKROOM'];
		}

		shRemoveFromGETVarsList('view');

		if ($layout)
		{
			$title[] = $layout;
			shRemoveFromGETVarsList('layout');
		}

		if ($sid)
		{
			$title[] = $sid;
			shRemoveFromGETVarsList('sid');
		}
		break;

	case 'search':

		if ($sefConfig->useMenuAlias && $menu->alias != '')
		{
			$title[] = $menu->alias;
		}
		elseif ($menu->title != '')
		{
			$title[] = $menu->title;
		}
		else
		{
			$title[] = $sh_LANG[$shLangIso]['_REDSHOP_SEARCH'];
		}

		shRemoveFromGETVarsList('view');

		if ($keyword != '')
		{
			$title[] = $keyword;
		}

		shRemoveFromGETVarsList('keyword');

		if ($category_id != '' && $category_id != 0)
		{
			$title[] = $category_id;
		}

		shRemoveFromGETVarsList('category_id');

		if ($categoryid != '' && $categoryid != 0)
		{
			$title[] = $categoryid;
		}

		shRemoveFromGETVarsList('categoryid');

		if ($manufacture_id != '' && $manufacture_id != 0)
		{
			$title[] = $manufacture_id;
		}

		shRemoveFromGETVarsList('manufacture_id');

		if ($layout)
		{
			$title[] = $layout;
		}

		$title[] = $Itemid;
		shRemoveFromGETVarsList('layout');
		shRemoveFromGETVarsList('Search');
		shRemoveFromGETVarsList('view');
		break;

	case 'shippingrate':

		if ($sefConfig->useMenuAlias && $menu->alias != '')
		{
			$title[] = $menu->alias;
		}
		elseif ($menu->title != '')
		{
			$title[] = $menu->title;
		}
		else
		{
			$title[] = $sh_LANG[$shLangIso]['_REDSHOP_SHIPPING_RATE'];
		}

		shRemoveFromGETVarsList('view');

		break;
	case 'shipping_rate_detail':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_SHIPPING_RATE_DETAIL'];
		shRemoveFromGETVarsList('view');

		break;

	case 'order_listing':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_ORDER_LISTING'];
		shRemoveFromGETVarsList('view');

		break;
	case 'manufacturers':

		if (!$mid)
		{
			$mid = $myparams->get('manufacturer');
		}

		if ($mid)
		{
			$menuMF = false;

			$sql = "SELECT sef_url,manufacturer_name FROM #__redshop_manufacturer WHERE manufacturer_id = '$mid'";
			$db->setQuery($sql);
			$url = $db->loadObject();

			if ($url && !$menuMF)
			{
				if ($url->sef_url == "")
				{
					$title[] = RedshopHelperUtility::convertToNonSymbol($url->manufacturer_name);
				}
				else
				{
					$title[] = RedshopHelperUtility::convertToNonSymbol($url->sef_url);
				}
			}

			shRemoveFromGETVarsList('mid');
			shRemoveFromGETVarsList('task');
		}

		if (!$mid)
		{
			if ($sefConfig->useMenuAlias && $menu->alias != '')
			{
				$title[] = $menu->alias;
			}
			elseif ($menu->title != '')
			{
				$title[] = $menu->title;
			}
			else
			{
				$title[] = $sh_LANG[$shLangIso]['_REDSHOP_MANUFACTURERS'];
			}
		}

		shRemoveFromGETVarsList('view');

		if ($layout != 'detail' && $layout != '')
		{
			$title[] = $layout;
		}

		shRemoveFromGETVarsList('layout');
		break;

	case 'manufacturer_products':

		if ($mid)
		{
			$sql = "SELECT sef_url,manufacturer_name FROM #__redshop_manufacturer WHERE manufacturer_id = '$mid'";
			$db->setQuery($sql);
			$url = $db->loadObject();

			if ($url->sef_url == "")
			{
				$title[] = $url->manufacturer_name;
			}
			else
			{
				$title[] = $url->sef_url;
			}

			shRemoveFromGETVarsList('view');
			shRemoveFromGETVarsList('mid');
		}

	case 'newsletter':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_NEWSLETTER'];
		shRemoveFromGETVarsList('view');

		if ($task)
		{
			$title[] = $task;
			shRemoveFromGETVarsList('task');
		}

		if ($msg)
		{
			$title[] = $msg;
			shRemoveFromGETVarsList('msg');
		}

		break;
	case 'wishlist':

		$title[] = $sh_LANG[$shLangIso]['_REDSHOP_WISHLIST'];
		shRemoveFromGETVarsList('view');

		if ($wishlist_id)
		{
			$title[] = $wishlist_id;
			shRemoveFromGETVarsList('wishlist_id');
		}

		if ($task)
		{
			$title[] = $task;
			shRemoveFromGETVarsList('task');
		}

		if ($layout)
		{
			if ($layout == 'viewloginwishlist')
			{
				$title[] = 'login';
			}
			elseif ($layout == 'viewwishlist')
			{
				$title[] = 'all';
			}

			shRemoveFromGETVarsList('layout');
		}

		break;
}

if ($limitstart)
{
	if (!isset($limit))
	{
		$limit = JFactory::getApplication()->get('list_limit');
	}

	$title[] = 'results' . ($limitstart + 1) . '-' . ($limitstart + $limit);
}

// ------------------  standard plugin finalize function - don't change ---------------------------
if ($dosef)
{
	$string = shFinalizePlugin(
		$string, $title, $shAppendString, $shItemidString, (isset($limit) ? $limit : null),
		(isset($limitstart) ? $limitstart : null), (isset($shLangName) ? $shLangName : null),
		(isset($showall) ? $showall : null), $suppressPagination = true
	);
}

// ------------------  standard plugin finalize function - don't change ---------------------------
