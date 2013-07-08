<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  redSHOP
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.html.parameter');

/**
 *    Build URL routes for redSHOP
 *
 * @param   array  &$query  request variables
 *
 * @return    array
 */
function redshopBuildRoute(&$query)
{
	$view            = '';
	$layout          = '';
	$pid             = 0;
	$cid             = 0;
	$oid             = '';
	$order_id        = '';
	$manufacturer_id = '';

	$segments = array();
	$db       = JFactory::getDBO();
	$app      = JFactory::getApplication();
	$menu     = $app->getMenu();
	$item     = $menu->getActive();

	$Itemid = 101;

	if (isset($item->id) === true)
	{
		$Itemid = $item->id;
	}

	require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
	require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/category.php';

	$product_category = new product_category;
	$infoid           = '';
	$task             = '';

	if (isset($query['view']))
	{
		$view = $query['view'];
		unset($query['view']);
	}

	if (isset($query['pid']))
	{
		$pid = $query['pid'];
		unset($query['pid']);
	}

	if (isset($query['cid']))
	{
		$cid = $query['cid'];
		unset($query['cid']);
	}

	if (isset($query['limit']))
	{
		$limit = $query['limit'];
	}

	if (isset($query['limitstart']))
	{
		$limitstart = $query['limitstart'];
	}

	if (isset($query['start']))
	{
		$start = $query['start'];
	}

	if (isset($query['order_by']))
	{
		$order_by = $query['order_by'];
		unset($query['order_by']);
	}

	if (isset($query['texpricemin']))
	{
		$texpricemin = $query['texpricemin'];
		unset($query['texpricemin']);
	}

	if (isset($query['texpricemax']))
	{
		$texpricemax = $query['texpricemax'];
		unset($query['texpricemax']);
	}

	if (isset($query['manufacturer_id']))
	{
		$manufacturer_id = $query['manufacturer_id'];
		unset($query['manufacturer_id']);
	}

	if (isset($query['manufacture_id']))
	{
		$manufacture_id = $query['manufacture_id'];
		unset($query['manufacture_id']);
	}

	if (isset($query['category_id']))
	{
		$category_id = $query['category_id'];
		unset($query['category_id']);
	}

	if (isset($query['category_template']))
	{
		$category_template = $query['category_template'];
		unset($query['category_template']);
	}

	if (isset($query['gid']))
	{
		$gid = $query['gid'];
		unset($query['gid']);
	}

	if (isset($query['layout']))
	{
		$layout = $query['layout'];
		unset($query['layout']);
	}

	if (isset($query['mid']))
	{
		$mid = $query['mid'];
		unset($query['mid']);
	}

	if (isset($query['task']))
	{
		$task = $query['task'];
		unset($query['task']);
	}

	if (isset($query['infoid']))
	{
		$infoid = $query['infoid'];
		unset($query['infoid']);
	}

	if (isset($query['oid']))
	{
		$oid = $query['oid'];
		unset($query['oid']);
	}

	if (isset($query['order_id']))
	{
		$order_id = $query['order_id'];
		unset($query['order_id']);
	}

	if (isset($query['quoid']))
	{
		$quoid = $query['quoid'];
		unset($query['quoid']);
	}

	if (isset($query['Itemid']))
	{
		$Itemid = $query['Itemid'];
	}

	// Tag id
	if (isset($query['tagid']))
	{
		$tagid = $query['tagid'];
		unset($query['tagid']);
	}

	if (isset($query['edit']))
	{
		$edit = $query['edit'];
		unset($query['edit']);
	}

	// Remove flag
	if (isset($query['remove']))
	{
		$remove = $query['remove'];
		unset($query['remove']);
	}

	if (isset($query['wishlist_id']))
	{
		$wishlist_id = $query['wishlist_id'];
		unset($query['wishlist_id']);
	}

	$sql = "SELECT * FROM #__menu WHERE id = '$Itemid' "
		. "AND link like '%option=com_redshop%' AND link like '%view=$view%' ";
	$db->setQuery($sql);
	$menu = $db->loadObject();

	if (count($menu) == 0)
	{
		$menu         = new stdClass;
		$menu->params = '';
		$menu->title  = '';
	}

	$myparams = new JRegistry($menu->params);

	// Special char for replace
	$special_char = array(".", " ");

	switch ($view)
	{
		case 'wishlist':
			$segments[] = 'wishlist';

			if ($task == 'viewwishlist')
			{
				$segments[] = $task;
			}

			if ($task == 'delwishlist')
			{
				$segments[] = $task;

				if (isset($wishlist_id))
				{
					$segments[] = $wishlist_id;
				}
			}

			if ($task == 'mysessdelwishlist')
			{
				$segments[] = $task;

				if (isset($wishlist_id))
				{
					$segments[] = $wishlist_id;
				}
			}
			else
			{
				if ($task != '')
				{
					$segments[] = $task;
				}
			}
			break;
		case 'cart';
			$segments[] = 'cart';
			break;

		case 'search';
			$segments[] = 'search';

			if ($layout != '')
			{
				$segments[] = $layout;
			}

			if ($category_id != '')
			{
				$segments[] = $category_id;
			}
			break;

		case 'password';
			$segments[] = 'password';
			break;

		case 'registration';
			$segments[] = 'registration';
			break;

		case 'login';
			$segments[] = 'login';
			break;

		case 'checkout';
			$segments[] = 'checkout';
			break;

		case 'account_billto':
			$segments[] = 'account_billto';
			break;

		case 'giftcard':
			$segments[] = 'giftcard';

			if (isset($gid))
			{
				$segments[] = $gid;
				$sql        = "SELECT giftcard_name  FROM #__redshop_giftcard WHERE giftcard_id = '$gid'";
				$db->setQuery($sql);
				$giftcardname = $db->loadResult();
			}

			$segments[] = $giftcardname;

			break;

		case 'account_shipto':
			$segments[] = 'account_shipto';

			switch ($task)
			{
				case "addshipping":

					$segments[] = $task;

					if ($infoid > 0)
					{
						$segments[] = $infoid;
					}

					break;

				default:
					$segments[] = 'account_shipto';

					break;
			}
			break;
		case 'orders':

			$segments[] = 'orders';
			break;

		case 'order_detail':

			$segments[] = 'order_detail';

			if ($oid != '')
			{
				$segments[] = $oid;
			}
			elseif ($order_id != '')
			{
				$segments[] = $order_id;
			}

			if ($layout != '')
			{
				$segments[] = $layout;
			}

			if ($task == 'reorder')
			{
				$segments[] = $task;
			}
			break;

		case 'category':

			if (!ENABLE_SEF_NUMBER_NAME)
			{
				if ($cid > 0)
				{
					$segments[] = $cid;
				}

				$segments[] = $Itemid;
				$segments[] = $manufacturer_id;
			}

			if ($cid)
			{
				$sql = "SELECT sef_url,category_name FROM #__redshop_category WHERE category_id = '$cid'";
				$db->setQuery($sql);
				$url = $db->loadObject();

				if ($url->sef_url == "")
				{
					$GLOBALS['catlist_reverse'] = array();
					$cats                       = $product_category->getCategoryListReverceArray($cid);

					if (count($cats) > 0)
					{
						$cats = array_reverse($cats);

						for ($x = 0; $x < count($cats); $x++)
						{
							$cat        = $cats[$x];
							$segments[] = JFilterOutput::stringURLSafe($cat->category_name);
						}
					}

					if (ENABLE_SEF_NUMBER_NAME)
					{
						$segments[] = $cid . '-' . JFilterOutput::stringURLSafe($url->category_name);
					}
					else
					{
						$segments[] = JFilterOutput::stringURLSafe($url->category_name);
					}
				}
				else
				{
					if (ENABLE_SEF_NUMBER_NAME)
					{
						$segments[] = $cid . '-' . JFilterOutput::stringURLSafe($url->sef_url);
					}
					else
					{
						$segments[] = JFilterOutput::stringURLSafe($url->sef_url);
					}
				}
			}
			else
			{
				if ($menu->title != '')
				{
					$segments[] = JFilterOutput::stringURLSafe($menu->title);
				}
			}

			if ($layout != 'detail' && $layout != '')
			{
				$segments[] = $layout;
			}

			break;

		case 'product':

			if (ENABLE_SEF_NUMBER_NAME)
			{
				if ($layout != "")
				{
					$segments[] = $layout;
				}
			}
			else
			{
				if ($layout != "")
				{
					$segments[] = $layout;
				}
				elseif ($pid)
				{
					$segments[] = $pid;
				}

				$segments[] = $Itemid;
			}

			$segments[] = $task;

			if ($pid)
			{
				$sql = "SELECT sef_url,product_name,cat_in_sefurl,product_number FROM #__redshop_product WHERE product_id = '$pid'";
				$db->setQuery($sql);
				$product = $db->loadObject();

				$url           = $product->sef_url;
				$cat_in_sefurl = $product->cat_in_sefurl;

				if ($url == "")
				{
					$GLOBALS['catlist_reverse'] = array();
					$where                      = '';

					if ($cat_in_sefurl > 0)
					{
						$where = " AND c.category_id = '$cat_in_sefurl'";
					}

					// Get cid from request for consistency
					$category_id = $cat_in_sefurl;

					// If cid is not set than find cid
					if (!$category_id)
					{
						$sql = "SELECT c.category_id FROM #__redshop_category c,#__redshop_product_category_xref pc WHERE pc.product_id = '$pid' AND pc.category_id = c.category_id $where";
						$db->setQuery($sql);
						$category_id = $db->loadResult();
					}

					$cats = $product_category->getCategoryListReverceArray($category_id);

					if (count($cats) > 0)
					{
						$cats = array_reverse($cats);

						for ($x = 0; $x < count($cats); $x++)
						{
							$cat        = $cats[$x];
							$segments[] = JFilterOutput::stringURLSafe($cat->category_name);
						}
					}

					$sql = "SELECT category_name FROM #__redshop_category WHERE category_id = '$category_id'";
					$db->setQuery($sql);
					$catname = $db->loadResult();

					// Attach category id with name for consistency
					if (ENABLE_SEF_NUMBER_NAME)
					{
						$segments[] = $category_id . '-' . JFilterOutput::stringURLSafe($catname);
					}
					else
					{
						$segments[] = JFilterOutput::stringURLSafe($catname);
					}

					// Add product number if config is enabled
					if (ENABLE_SEF_PRODUCT_NUMBER)
					{
						$segments[] = JFilterOutput::stringURLSafe($product->product_number);
					}

					// Config option to generate sef using name : add product id to get parse in parseroute function
					if (ENABLE_SEF_NUMBER_NAME)
					{
						$segments[] = 'P' . $pid . '-' . JFilterOutput::stringURLSafe($product->product_name);
					}
					else
					{
						$segments[] = 'P-' . JFilterOutput::stringURLSafe($product->product_name);
					}
				}
				else
				{
					// Config option to generate sef using name : add product id to get parse in parseroute function
					if (ENABLE_SEF_NUMBER_NAME)
					{
						$segments[] = 'P' . $pid . '-' . JFilterOutput::stringURLSafe($url);
					}
					else
					{
						$segments[] = 'P-' . JFilterOutput::stringURLSafe($url);
					}
				}
			}

			break;

		case 'manufacturers':

			if (!$mid)
			{
				$mid = $myparams->get('manufacturer');
			}

			$segments[] = 'manufacturers';

			if ($mid)
			{
				$segments[] = $mid;
				$sql        = "SELECT sef_url,manufacturer_name FROM #__redshop_manufacturer WHERE manufacturer_id = '$mid'";
				$db->setQuery($sql);
				$url = $db->loadObject();

				if ($url)
				{
					if ($url->sef_url == "")
					{
						$segments[] = str_replace($special_char, "-", $url->manufacturer_name);
					}
					else
					{
						$segments[] = str_replace($special_char, "-", $url->sef_url);
					}
				}
			}

			if (!$mid)
			{
				if ($menu->title != '')
				{
					$segments[] = str_replace($special_char, "-", $menu->title);
				}
				else
				{
					$segments[] = 'manufactures';
				}
			}

			if ($layout != 'detail' && $layout != '')
			{
				$segments[] = $layout;
			}

			break;

		case 'account':

			$segments[] = 'account';

			if ($layout == 'mytags')
			{
				$segments[] = $layout;

				if ($tagid)
				{
					$segments[] = $tagid;

					$sql = "SELECT tags_name FROM `#__redshop_product_tags` WHERE `tags_id` = " . $tagid;
					$db->setQuery($sql);
					$tagname = $db->loadResult();

					$segments[] = str_replace($special_char, "-", $tagname);

					if ($tagid && isset($edit))
					{
						$segments[] = 'edit';
					}

					if ($tagid && isset($remove))
					{
						$segments[] = 'remove';
					}
				}
			}
			elseif ($layout == 'mywishlist')
			{
				$segments[] = $layout;

				if (isset($wishlist_id))
				{
					$segments[] = $wishlist_id;
				}

				if (isset($remove) && isset($pid))
				{
					$segments[] = $pid;
					$segments[] = 'delete';
				}
			}
			elseif ($layout == 'compare')
			{
				$segments[] = $layout;

				if (isset($remove) && isset($pid))
				{
					$segments[] = $pid;
					$segments[] = 'delete';
				}
			}

			break;
		case 'quotation':
			$segments[] = 'quotation';
			break;
		case 'quotation_detail':
			$segments[] = 'quotation_detail';
			$segments[] = $quoid;
			break;
	}

	return $segments;
}

/**
 * Parse redSHOP sef url
 *
 * @param   array  $segments  Sef Url segments
 *
 * @return    array
 */
function redshopParseRoute($segments)
{
	$vars = array();
	require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';

	$db           = JFactory::getDBO();
	$firstSegment = $segments[0];

	switch ($firstSegment)
	{
		case 'giftcard':
			$vars['view'] = "giftcard";

			if (isset($segments[1]))
			{
				$vars['gid'] = $segments[1];
			}

			break;

		case 'cart':
			$vars['view'] = "cart";
			break;

		case 'search':
			$vars['view'] = "search";

			if (isset($segments[1]))
			{
				$vars['layout'] = $segments[1];
			}

			if (isset($segments[2]))
			{
				$vars['category_id'] = $segments[2];
			}
			break;

		case 'password':
			$vars['view'] = "password";
			break;

		case 'registration':
			$vars['view'] = "registration";
			break;

		case 'checkout':
			$vars['view'] = "checkout";
			break;

		case 'login':
			$vars['view'] = "login";
			break;

		case 'account_billto':
			$vars['view'] = 'account_billto';
			break;

		case 'account_shipto':
			$vars['view'] = 'account_shipto';

			if (isset($segments[1]))
			{
				$vars['task'] = $segments[1];
			}

			if (isset($segments[2]))
			{
				$vars['infoid'] = $segments[2];
			}
			break;

		case 'manufacturers':
			$vars['view'] = 'manufacturers';

			if (isset($segments[1]))
			{
				$vars['mid']    = $segments[1];
				$vars['layout'] = "detail";
			}

			if (isset($segments[3]))
			{
				$vars['layout'] = $segments[3];
			}
			break;

		case 'orders':

			$vars['view'] = 'orders';
			break;

		case 'order_detail':

			$vars['view'] = 'order_detail';
			$vars['oid']  = $segments[1];

			if (isset($segments[2]) && $segments[2] == 'reorder')
			{
				$vars['task']     = $segments[2];
				$vars['order_id'] = $segments[1];
			}
			elseif (isset($segments[2]))
			{
				$vars['layout'] = $segments[2];
			}
			break;

		case 'wishlist':

			$vars['view'] = 'wishlist';

			if (isset($segments[1]))
			{
				$vars['task'] = $segments[1];
			}

			if (isset($segments[2]))
			{
				$vars['wishlist_id'] = $segments[2];
			}
			break;

		case 'account':

			$vars['view']   = 'account';
			$vars['layout'] = $segments[1];

			if ($segments[1] == 'mytags')
			{
				if (isset($segments[2]))
				{
					$vars['tagid'] = $segments[2];

					if (isset($segments[4]))
					{
						if ($segments[4] == 'edit')
						{
							$vars['edit'] = 1;
						}
						else
						{
							$vars['remove'] = 1;
						}
					}
				}
			}
			elseif ($segments[1] == 'mywishlist')
			{
				if (isset($segments[2]))
				{
					$vars['wishlist_id'] = $segments[2];
				}

				if (isset($segments[3]))
				{
					$vars['pid'] = $segments[3];
				}

				if (isset($segments[4]))
				{
					$vars['remove'] = 1;
				}
			}
			elseif ($segments[1] == 'compare')
			{
				if (isset($segments[2]))
				{
					$vars['pid'] = $segments[2];
				}

				if (isset($segments[3]))
				{
					$vars['remove'] = 1;
				}
			}

			break;
		case 'quotation':
			$vars['view'] = 'quotation';
			break;
		case 'quotation_detail':
			$vars['view']  = 'quotation_detail';
			$vars['quoid'] = $segments[1];
			break;
		default:

			$last        = count($segments) - 1;
			$second_last = $last - 1;
			$main        = explode(":", $segments[$last]);

			if (isset($segments[$last]))
			{
				if ($main[0][0] != 'P' && $segments[0] != 'compare')
				{
					if (ENABLE_SEF_NUMBER_NAME)
					{
						$vars['view'] = "category";

						if (isset($segments[$last]))
						{
							// Fetch category id
							$cats        = explode(":", $segments[$last]);
							$category_id = $cats[0];
							$vars['cid'] = $category_id;

							if (isset($cats[2]))
							{
								$man_id                  = $cats[2];
								$vars['manufacturer_id'] = $man_id;
							}

							$menu           = JFactory::getApplication()->getMenu();
							$item           = $menu->getActive();
							$vars['Itemid'] = $item->id;
						}
					}
					else
					{
						$vars['view'] = "category";

						$menu           = JFactory::getApplication()->getMenu();
						$item           = $menu->getActive();
						$vars['Itemid'] = $item->id;

						if (isset($segments[0]) && $segments[0] != $item->id)
						{
							$vars['cid'] = $segments[0];
						}

						if (isset($segments[2]))
						{
							$vars['manufacturer_id'] = $segments[2];
						}
					}
				}
				else
				{
					if (ENABLE_SEF_NUMBER_NAME)
					{
						$vars['view'] = "product";

						if (isset($segments[0]))
						{
							$categories  = explode(":", $segments[0]);
							$cat_id      = $categories[0];
							$vars['cid'] = $cat_id;
						}

						if (isset($segments[0]) && $segments[0] == 'compare')
						{
							$vars['layout'] = $segments[0];
							$vars['task']   = $segments[1];
						}

						if (isset($segments[$last]))
						{
							$products    = explode(":", $segments[$last]);
							$product_id  = substr($products[0], 1);
							$vars['pid'] = $product_id;
						}

						$menu           = JFactory::getApplication()->getMenu();
						$item           = $menu->getActive();
						$vars['Itemid'] = $item->id;
					}
					else
					{
						$vars['view'] = "product";

						if (isset($segments[0]) && $segments[0] == 'compare')
						{
							$vars['layout'] = $segments[0];
							$vars['task']   = $segments[2];
						}
						else
						{
							$vars['pid'] = $segments[0];
						}

						if (isset($segments[1]))
						{
							$vars['Itemid'] = $segments[1];
						}

						if (isset($segments[$second_last]))
						{
							$sql = "SELECT category_id FROM #__redshop_category WHERE category_name = '$segments[$second_last]'";
							$db->setQuery($sql);
							$cat_id      = $db->loadResult();
							$vars['cid'] = $cat_id;
						}
					}
				}
			}

			break;
	}

	return $vars;
}
