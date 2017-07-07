<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  redSHOP
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Routing class from com_redshop
 *
 * @since  2.0.3
 */
class RedshopRouter extends JComponentRouterBase
{
	/**
	 * Build the route for the com_reditem component
	 *
	 * @param   array $query An array of URL arguments
	 *
	 * @return  array          The URL arguments to use to assemble the subsequent URL.
	 */
	public function build(&$query)
	{
		$segments = array();
		$db       = JFactory::getDbo();
		$app      = JFactory::getApplication();
		$menu     = $app->getMenu();

		$menuItem = empty($query['Itemid']) ? $menu->getActive() : $menu->getItem($query['Itemid']);

		$itemId = is_object($menuItem) ? $menuItem->id : 101;

		$view = null;

		if (isset($query['view']))
		{
			$view = $query['view'];
			unset($query['view']);
		}

		$pid = null;

		if (isset($query['pid']))
		{
			$pid = $query['pid'];
			unset($query['pid']);
		}

		$cid = null;

		if (isset($query['cid']))
		{
			$cid = $query['cid'];
			unset($query['cid']);
		}

		$manufacturerId = null;

		if (isset($query['manufacturer_id']))
		{
			$manufacturerId = $query['manufacturer_id'];
			unset($query['manufacturer_id']);
		}

		$categoryId = null;

		if (isset($query['category_id']))
		{
			$categoryId = $query['category_id'];
			unset($query['category_id']);
		}

		$categoryTemplate = null;

		if (isset($query['category_template']))
		{
			$categoryTemplate = $query['category_template'];
			unset($query['category_template']);
		}

		$gid = null;

		if (isset($query['gid']))
		{
			$gid = $query['gid'];
			unset($query['gid']);
		}

		$layout = null;

		if (isset($query['layout']))
		{
			$layout = $query['layout'];
			unset($query['layout']);
		}

		$mid = null;

		if (isset($query['mid']))
		{
			$mid = $query['mid'];
			unset($query['mid']);
		}

		$task = null;

		if (isset($query['task']))
		{
			$task = $query['task'];
			unset($query['task']);
		}

		$inforId = null;

		if (isset($query['infoid']))
		{
			$inforId = $query['infoid'];
			unset($query['infoid']);
		}

		$oid = null;

		if (isset($query['oid']))
		{
			$oid = $query['oid'];
			unset($query['oid']);
		}

		$orderId = null;

		if (isset($query['order_id']))
		{
			$orderId = $query['order_id'];
			unset($query['order_id']);
		}

		$quotationId = null;

		if (isset($query['quoid']))
		{
			$quotationId = $query['quoid'];
			unset($query['quoid']);
		}

		// Tag id
		$tagId = null;

		if (isset($query['tagid']))
		{
			$tagId = $query['tagid'];
			unset($query['tagid']);
		}

		$edit = null;

		if (isset($query['edit']))
		{
			$edit = $query['edit'];
			unset($query['edit']);
		}

		// Remove flag
		$remove = null;

		if (isset($query['remove']))
		{
			$remove = $query['remove'];
			unset($query['remove']);
		}

		$wishlistId = null;

		if (isset($query['wishlist_id']))
		{
			$wishlistId = $query['wishlist_id'];
			unset($query['wishlist_id']);
		}

		if (is_object($menuItem))
		{
			$myParams = new Registry($menuItem->params);
		}
		else
		{
			$menuItem        = new stdClass;
			$menuItem->title = '';
			$myParams        = new Registry;
		}

		// Special char for replace
		$specialChars = array(".", " ");

		switch ($view)
		{
			case 'wishlist':
				$segments[] = 'wishlist';

				if (!empty($task))
				{
					$segments[] = $task;

					if (in_array($task, array('delwishlist', 'mysessdelwishlist')))
					{
						$segments[] = $wishlistId;
					}
				}

				break;

			case 'cart':
				$segments[] = 'cart';

				break;

			case 'search':
				$segments[] = 'search';

				if (!empty($layout))
				{
					$segments[] = $layout;
				}

				if (!empty($categoryId))
				{
					$segments[] = $categoryId;
				}

				break;

			case 'password':
				$segments[] = 'password';
				break;

			case 'registration':
				$segments[] = 'registration';
				break;

			case 'login':
				$segments[] = 'login';
				break;

			case 'checkout':
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

					$query = $db->getQuery(true)
						->select($db->qn('giftcard_name'))
						->from($db->qn('#__redshop_giftcard'))
						->where($db->qn('giftcard_id') . ' = ' . $db->quote($gid));

					if ($giftCardName = $db->setQuery($query)->loadResult())
					{
						$segments[] = RedshopHelperUtility::convertToNonSymbol($giftCardName);
					}
				}

				break;

			case 'account_shipto':
				$segments[] = 'account_shipto';

				if ($task == 'addshipping')
				{
					$segments[] = $task;

					if ($inforId > 0)
					{
						$segments[] = $inforId;
					}
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
				elseif ($orderId != '')
				{
					$segments[] = $orderId;
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

				if (!Redshop::getConfig()->get('ENABLE_SEF_NUMBER_NAME'))
				{
					if ($cid > 0)
					{
						$segments[] = $cid;
					}

					$segments[] = $itemId;
					$segments[] = $manufacturerId;
				}

				if ($cid && ($url = RedshopHelperCategory::getCategoryById($cid)))
				{
					if ($url->sef_url == "")
					{
						$cats = RedshopHelperCategory::getCategoryListReverseArray($cid);

						if (count($cats) > 0)
						{
							$cats = array_reverse($cats);

							for ($x = 0, $xn = count($cats); $x < $xn; $x++)
							{
								$cat = $cats[$x];

								if ($cat->parent_id == 0)
								{
									continue;
								}

								$segments[] = RedshopHelperUtility::convertToNonSymbol($cat->name);
							}
						}

						if (Redshop::getConfig()->get('ENABLE_SEF_NUMBER_NAME'))
						{
							$segments[] = $cid . '-' . RedshopHelperUtility::convertToNonSymbol($url->name);
						}
						else
						{
							$segments[] = RedshopHelperUtility::convertToNonSymbol($url->name);
						}
					}
					else
					{
						if (Redshop::getConfig()->get('ENABLE_SEF_NUMBER_NAME'))
						{
							$segments[] = $cid . '-' . RedshopHelperUtility::convertToNonSymbol($url->sef_url);
						}
						else
						{
							$segments[] = RedshopHelperUtility::convertToNonSymbol($url->sef_url);
						}
					}
				}
				elseif ($menuItem->title != '')
				{
					$segments[] = RedshopHelperUtility::convertToNonSymbol($menuItem->title);
				}

				if ($layout != 'detail' && $layout != '')
				{
					$segments[] = $layout;
				}

				break;

			case 'product':

				if (Redshop::getConfig()->get('ENABLE_SEF_NUMBER_NAME') && $layout != "")
				{
					$segments[] = $layout;
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

					$segments[] = $itemId;
				}

				$segments[]    = $task;
				$productHelper = productHelper::getInstance();
				$product       = $productHelper->getProductById($pid);

				if ($pid && $product)
				{
					$url                  = $product->sef_url;
					$showCategoryInSefUrl = $product->cat_in_sefurl;

					if ($url == "")
					{
						// Get cid from request for consistency
						$categoryId = $showCategoryInSefUrl;

						// If cid is not set than find cid
						if (!$categoryId)
						{
							$categoryId = $product->category_id;
						}

						if ($cats = RedshopHelperCategory::getCategoryListReverseArray($categoryId))
						{
							$cats = array_reverse($cats);

							foreach ($cats as $cat)
							{
								if ($cat->parent_id == 0)
								{
									continue;
								}

								$segments[] = RedshopHelperUtility::convertToNonSymbol($cat->name);
							}
						}

						$categoryName = '';

						if ($categoryData = RedshopHelperCategory::getCategoryById($categoryId))
						{
							$categoryName = $categoryData->name;
						}

						// Attach category id with name for consistency
						if (Redshop::getConfig()->get('ENABLE_SEF_NUMBER_NAME'))
						{
							$segments[] = $categoryId . '-' . RedshopHelperUtility::convertToNonSymbol($categoryName);
						}
						else
						{
							$segments[] = RedshopHelperUtility::convertToNonSymbol($categoryName);
						}

						// Add product number if config is enabled
						if (Redshop::getConfig()->get('ENABLE_SEF_PRODUCT_NUMBER'))
						{
							$segments[] = RedshopHelperUtility::convertToNonSymbol($product->product_number);
						}

						// Config option to generate sef using name : add product id to get parse in parseroute function
						if (Redshop::getConfig()->get('ENABLE_SEF_NUMBER_NAME'))
						{
							$segments[] = 'P' . $pid . '-' . RedshopHelperUtility::convertToNonSymbol($product->product_name);
						}
						else
						{
							$segments[] = 'P-' . RedshopHelperUtility::convertToNonSymbol($product->product_name);
						}
					}
					else
					{
						// Config option to generate sef using name : add product id to get parse in parseroute function
						if (Redshop::getConfig()->get('ENABLE_SEF_NUMBER_NAME'))
						{
							$segments[] = 'P' . $pid . '-' . RedshopHelperUtility::convertToNonSymbol($url);
						}
						else
						{
							$segments[] = 'P-' . RedshopHelperUtility::convertToNonSymbol($url);
						}
					}
				}

				break;

			case 'manufacturers':

				if (!$mid)
				{
					$mid = $myParams->get('manufacturer');
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
							$segments[] = str_replace($specialChars, "-", $url->manufacturer_name);
						}
						else
						{
							$segments[] = str_replace($specialChars, "-", $url->sef_url);
						}
					}
				}

				if (!$mid)
				{
					if ($menuItem->title != '')
					{
						$segments[] = str_replace($specialChars, "-", $menuItem->title);
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

					if ($tagId)
					{
						$segments[] = $tagId;

						$sql = "SELECT tags_name FROM `#__redshop_product_tags` WHERE `tags_id` = " . $tagId;
						$db->setQuery($sql);
						$tagname = $db->loadResult();

						$segments[] = str_replace($specialChars, "-", $tagname);

						if ($tagId && isset($edit))
						{
							$segments[] = 'edit';
						}

						if ($tagId && isset($remove))
						{
							$segments[] = 'remove';
						}
					}
				}
				elseif ($layout == 'mywishlist')
				{
					$segments[] = $layout;

					if (isset($wishlistId))
					{
						$segments[] = $wishlistId;
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
				$segments[] = $quotationId;

				break;

			default:
				break;
		}

		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array $segments The segments of the URL to parse.
	 *
	 * @return  array             The URL attributes to be used by the application.
	 */
	public function parse(&$segments)
	{
		$vars         = array();
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

				if (isset($segments[1]))
				{
					$vars['oid'] = $segments[1];
				}

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
				$vars['view'] = 'account';

				if (isset($segments[1]))
				{
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
				$last       = count($segments) - 1;
				$secondLast = $last - 1;
				$main       = explode(":", $segments[$last]);

				if (isset($segments[$last]))
				{
					if ($main[0][0] != 'P' && $segments[0] != 'compare')
					{
						if (Redshop::getConfig()->get('ENABLE_SEF_NUMBER_NAME'))
						{
							$vars['view'] = "category";

							if (isset($segments[$last]))
							{
								// Fetch category id
								$categories  = explode(":", $segments[$last]);
								$categoryId  = $categories[0];
								$vars['cid'] = $categoryId;

								if (isset($categories[2]))
								{
									$manufacturerId          = $categories[2];
									$vars['manufacturer_id'] = $manufacturerId;
								}

								$menu           = JFactory::getApplication()->getMenu();
								$item           = $menu->getActive();
								$vars['Itemid'] = $item->id;
							}
						}
						else
						{
							$vars['view'] = "category";

							$menu = JFactory::getApplication()->getMenu();
							$item = $menu->getActive();

							if (!empty($item))
							{
								$vars['Itemid'] = $item->id;
								$itemId         = $item->id;
							}
							else
							{
								$vars['Itemid'] = "";
								$itemId         = "";
							}

							if (isset($segments[0]) && $segments[0] != $itemId)
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
						if (Redshop::getConfig()->get('ENABLE_SEF_NUMBER_NAME'))
						{
							$vars['view'] = "product";

							if (isset($segments[0]))
							{
								$categories  = explode(":", $segments[0]);
								$categoryId  = $categories[0];
								$vars['cid'] = $categoryId;
							}

							if (isset($segments[0]) && $segments[0] == 'compare')
							{
								$vars['layout'] = $segments[0];
								$vars['task']   = $segments[1];
							}

							if (isset($segments[$last]))
							{
								$products    = explode(":", $segments[$last]);
								$productId   = substr($products[0], 1);
								$vars['pid'] = $productId;
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
								$vars['task']   = isset($segments[2]) ? $segments[2] : '';
							}
							else
							{
								$vars['pid'] = $segments[0];
							}

							if (isset($segments[1]))
							{
								$vars['Itemid'] = $segments[1];
							}

							if (isset($segments[$secondLast]))
							{
								$db    = JFactory::getDbo();
								$query = $db->getQuery(true)
									->select($db->qn('id'))
									->from($db->qn('#__redshop_category'))
									->where($db->qn('name') . ' = ' . $db->q($segments[$secondLast]));

								$vars['cid'] = $db->setQuery($query)->loadResult();
							}
						}
					}
				}

				break;
		}

		return $vars;
	}
}

/**
 * Build URL routes for redSHOP
 *
 * @param   array  $query  Request variables
 *
 * @return  array
 */
function redshopBuildRoute(&$query)
{
	$router = new RedshopRouter;

	return $router->build($query);
}

/**
 * Parse redSHOP sef url
 *
 * @param   array  $segments  Sef Url segments
 *
 * @return  array
 */
function redshopParseRoute($segments)
{
	$router = new RedshopRouter;

	return $router->parse($segments);
}
