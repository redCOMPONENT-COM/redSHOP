<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Breadcrumb Helper
 *
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 * @since       2.0.7
 */
class RedshopHelperBreadcrumb
{
	/**
	 * Method for generate breadcrumb base on specific section
	 *
	 * @param   integer  $sectionId  Section ID
	 *
	 * @return  void
	 *
	 * @since   2.0.7
	 */
	public static function generate($sectionId = 0)
	{
		$app            = JFactory::getApplication();
		$pathway        = $app->getPathway();
		$view           = $app->input->getCmd('view');
		$layout         = $app->input->getCmd('layout');
		$itemId         = $app->input->getInt('Itemid');
		$categoryId     = $app->input->getInt('cid');
		$customPathways = array();

		// Clean up current pathway.
		$paths     = $pathway->getPathway();
		$menuPaths = $paths;

		for ($j = 0, $total = count($paths); $j < $total; $j++)
		{
			unset($paths[$j]);
		}

		$pathway->setPathway($paths);

		switch ($view)
		{
			case "category":
				// Use menu path if menu of category detail is same.
				if ($sectionId)
				{
					$manufacturerId = $app->input->getInt('manufacturer_id', 0);
					$link = "index.php?option=com_redshop&view=category&layout=detail&cid=" . $sectionId . "&manufacturer_id=" . $manufacturerId;
					$menu = productHelper::getInstance()->getMenuDetail($link);

					if ($menu)
					{
						$pathway->setPathway($menuPaths);

						return;
					}
				}

				$customPathways = array();
				$newLink        = "index.php?option=com_redshop&view=category";

				if ($layout == "categoryproduct")
				{
					$newLink = "index.php?option=com_redshop&view=category&layout=" . $layout;
				}

				$menu = productHelper::getInstance()->getMenuDetail($newLink);

				if (count($menu) > 0 && $menu->home != 1)
				{
					$main             = new stdClass;
					$main->name       = $menu->title;
					$main->link       = JRoute::_($newLink . '&Itemid=' . $menu->id);
					$customPathways[] = $main;
				}

				if ($sectionId != 0)
				{
					$category_list  = array_reverse(productHelper::getInstance()->getCategoryNavigationlist($sectionId));
					$customPathways = array_merge($customPathways, productHelper::getInstance()->getBreadcrumbPathway($category_list));
				}

				break;

			case "product":
				$menu = productHelper::getInstance()->getMenuInformation($itemId);

				if (!is_null($menu)
					&& (strpos($menu->params, "manufacturer") !== false && strpos($menu->params, '"manufacturer_id":"0"') === false))
				{
					$customPathways = array();
					$menu           = productHelper::getInstance()->getMenuDetail("index.php?option=com_redshop&view=manufacturers");

					if (count($menu) > 0 && $menu->home != 1)
					{
						if (isset($menu->parent))
						{
							$parentMenu = productHelper::getInstance()->getMenuInformation($menu->parent);

							if (count($parentMenu) > 0)
							{
								$main             = new stdClass;
								$main->name       = $parentMenu->name;
								$main->link       = JRoute::_($parentMenu->link . '&Itemid=' . $parentMenu->id);
								$customPathways[] = $main;
							}
						}

						$main             = new stdClass;
						$main->name       = $menu->title;
						$main->link       = JRoute::_('index.php?option=com_redshop&view=manufacturers&Itemid=' . $menu->id);
						$customPathways[] = $main;
					}

					if ($sectionId != 0)
					{
						$prd  = productHelper::getInstance()->getSection("product", $sectionId);
						$menu = productHelper::getInstance()->getSection("manufacturer", $prd->manufacturer_id);

						if (count($menu) > 0)
						{
							$main             = new stdClass;
							$main->name       = $menu->manufacturer_name;
							$main->link       = JRoute::_(
								'index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $prd->manufacturer_id
								. '&Itemid=' . $itemId
							);
							$customPathways[] = $main;
						}

						$main             = new stdClass;
						$main->name       = $prd->product_name;
						$main->link       = "";
						$customPathways[] = $main;
					}
				}
				else
				{
					$customPathways = array();
					$menu           = productHelper::getInstance()->getMenuDetail("index.php?option=com_redshop&view=category");

					if (count($menu) > 0 && $menu->home != 1)
					{
						$main             = new stdClass;
						$main->name       = $menu->title;
						$main->link       = JRoute::_('index.php?option=com_redshop&view=category&Itemid=' . $menu->id);
						$customPathways[] = $main;
					}
					else
					{
						$menu = productHelper::getInstance()->getMenuDetail("index.php?option=com_redshop&view=product&pid=" . $sectionId);

						if (count($menu) > 0 && $menu->home != 1 && property_exists($menu, 'parent'))
						{
							$parentMenu = productHelper::getInstance()->getMenuInformation($menu->parent);

							if (count($parentMenu) > 0)
							{
								$main             = new stdClass;
								$main->name       = $parentMenu->name;
								$main->link       = JRoute::_($parentMenu->link . '&Itemid=' . $parentMenu->id);
								$customPathways[] = $main;
							}
						}
					}

					if ($sectionId != 0)
					{
						$prd = productHelper::getInstance()->getSection("product", $sectionId);

						if (!$categoryId)
						{
							$categoryId = productHelper::getInstance()->getCategoryProduct($sectionId);
						}

						if ($categoryId)
						{
							$category_list  = array_reverse(productHelper::getInstance()->getCategoryNavigationlist($categoryId));
							$customPathways = array_merge($customPathways, productHelper::getInstance()->getBreadcrumbPathway($category_list));
						}

						$main             = new stdClass;
						$main->name       = $prd->product_name;
						$main->link       = "";
						$customPathways[] = $main;
					}
				}

				break;

			case "manufacturers":

				$customPathways = array();
				$menu           = productHelper::getInstance()->getMenuDetail("index.php?option=com_redshop&view=manufacturers");

				if (count($menu) > 0 && $menu->home != 1)
				{
					if (property_exists($menu, 'parent'))
					{
						$parentMenu = productHelper::getInstance()->getMenuInformation($menu->parent);

						if (count($parentMenu) > 0)
						{
							$main             = new stdClass;
							$main->name       = $parentMenu->name;
							$main->link       = JRoute::_($parentMenu->link . '&Itemid=' . $parentMenu->id);
							$customPathways[] = $main;
						}
					}

					$main             = new stdClass;
					$main->name       = $menu->title;
					$main->link       = JRoute::_('index.php?option=com_redshop&view=manufacturers&Itemid=' . $menu->id);
					$customPathways[] = $main;
				}

				if ($sectionId != 0)
				{
					$menu = productHelper::getInstance()->getMenuInformation(0, $sectionId, "manufacturerid", "manufacturers");

					if (count($menu) > 0)
					{
						$main             = new stdClass;
						$main->name       = $menu->title;
						$main->link       = "";
						$customPathways[] = $main;
					}
					else
					{
						$menu = productHelper::getInstance()->getSection("manufacturer", $sectionId);

						if (count($menu) > 0)
						{
							$main             = new stdClass;
							$main->name       = $menu->manufacturer_name;
							$main->link       = "";
							$customPathways[] = $main;
						}
					}
				}

				break;

			case "account":
				$customPathways = array();
				$menu           = productHelper::getInstance()->getMenuInformation($itemId);

				if (count($menu) > 0)
				{
					$main       = new stdClass;
					$main->name = $menu->title;
					$main->link = "";
				}
				else
				{
					$main       = new stdClass;
					$main->name = JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE');
					$main->link = "";
				}

				$customPathways[] = $main;

				break;

			case "order_detail":
				$customPathways = array();
				$menu           = productHelper::getInstance()->getMenuInformation(0, 0, "", "account");

				if (count($menu) > 0)
				{
					$main             = new stdClass;
					$main->name       = $menu->title;
					$main->link       = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $menu->id);
					$customPathways[] = $main;
				}
				else
				{
					$main             = new stdClass;
					$main->name       = JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE');
					$main->link       = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $itemId);
					$customPathways[] = $main;
				}

				$main             = new stdClass;
				$main->name       = JText::_('COM_REDSHOP_ORDER_DETAILS');
				$main->link       = "";
				$customPathways[] = $main;

				break;

			case "orders":
			case "account_billto":
			case "account_shipto":
				$customPathways = array();
				$menu           = productHelper::getInstance()->getMenuInformation(0, 0, "", "account");

				if (count($menu) > 0)
				{
					$main             = new stdClass;
					$main->name       = $menu->title;
					$main->link       = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $menu->id);
					$customPathways[] = $main;
				}
				else
				{
					$main             = new stdClass;
					$main->name       = JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE');
					$main->link       = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $itemId);
					$customPathways[] = $main;
				}

				if ($view == 'orders')
				{
					$lastlink = JText::_('COM_REDSHOP_ORDER_LIST');
				}
				elseif ($view == 'account_billto')
				{
					$lastlink = JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL');
				}
				elseif ($view == 'account_shipto')
				{
					$lastlink = JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL');
				}

				$main             = new stdClass;
				$main->name       = $lastlink;
				$main->link       = "";
				$customPathways[] = $main;

				break;

			default:
				break;
		}

		if (empty($customPathways))
		{
			return;
		}

		$customPathways[count($customPathways) - 1]->link = '';

		foreach ($customPathways as $customPathway)
		{
			$pathway->addItem($customPathway->name, $customPathway->link);
		}
	}
}
