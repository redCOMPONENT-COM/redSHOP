<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
     * @throws  Exception
     * @since   2.0.7
     *
     */
    public static function generate($sectionId = 0)
    {
        $app = JFactory::getApplication();

        /** @var JPathway $pathway */
        $pathway = $app->getPathway();

        $view           = $app->input->getCmd('view');
        $layout         = $app->input->getCmd('layout');
        $itemId         = $app->input->getInt('Itemid');
        $categoryId     = $app->input->getInt('cid');
        $customPathways = array();

        // Clean up current pathway.
        $paths     = $pathway->getPathway();
        $menuPaths = $paths;

        foreach ($paths as $j => $path) {
            unset($paths[$j]);
        }

        $pathway->setPathway($paths);

        switch ($view) {
            case "category":
                // Use menu path if menu of category detail is same.
                if ($sectionId) {
                    $manufacturerId = $app->input->getInt('manufacturer_id', 0);

                    $link = "index.php?option=com_redshop&view=category&layout=detail&cid=" . $sectionId . "&manufacturer_id=" . $manufacturerId;
                    $menu = RedshopHelperProduct::getMenuDetail($link);

                    if ($menu) {
                        $pathway->setPathway($menuPaths);

                        return;
                    }
                }

                $customPathways = array();
                $newLink        = 'index.php?option=com_redshop&view=category';

                if ($layout === 'categoryproduct') {
                    $newLink = 'index.php?option=com_redshop&view=category&layout=' . $layout;
                }

                $menu = RedshopHelperProduct::getMenuDetail($newLink);

                if (!empty($menu) && $menu->home !== 1) {
                    $main             = new stdClass;
                    $main->name       = $menu->title;
                    $main->link       = Redshop\IO\Route::_($newLink . '&Itemid=' . $menu->id);
                    $customPathways[] = $main;
                }

                if ($sectionId != 0) {
                    $category_list  = array_reverse(RedshopHelperProduct::getCategoryNavigationlist($sectionId));
                    $customPathways = array_merge($customPathways, self::getPathway($category_list));
                }

                break;

            case "product":
                $menu = RedshopHelperProduct::getMenuInformation($itemId);

                if (!is_null($menu)
                    && (strpos($menu->params, "manufacturer") !== false && strpos(
                            $menu->params,
                            '"manufacturer_id":"0"'
                        ) === false)) {
                    $customPathways = array();
                    $menu           = RedshopHelperProduct::getMenuDetail(
                        "index.php?option=com_redshop&view=manufacturers"
                    );

                    if (count($menu) > 0 && $menu->home != 1) {
                        if (isset($menu->parent)) {
                            $parentMenu = RedshopHelperProduct::getMenuInformation($menu->parent);

                            if (count($parentMenu) > 0) {
                                $main             = new stdClass;
                                $main->name       = $parentMenu->name;
                                $main->link       = Redshop\IO\Route::_($parentMenu->link . '&Itemid=' . $parentMenu->id);
                                $customPathways[] = $main;
                            }
                        }

                        $main             = new stdClass;
                        $main->name       = $menu->title;
                        $main->link       = Redshop\IO\Route::_(
                            'index.php?option=com_redshop&view=manufacturers&Itemid=' . $menu->id
                        );
                        $customPathways[] = $main;
                    }

                    if ($sectionId != 0) {
                        $prd  = \Redshop\Product\Product::getProductById($sectionId);
                        $menu = RedshopEntityManufacturer::getInstance($prd->manufacturer_id)->getItem();

                        if (!empty($menu)) {
                            $main             = new stdClass;
                            $main->name       = $menu->name;
                            $main->link       = Redshop\IO\Route::_(
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
                } else {
                    $customPathways = array();
                    $menu           = RedshopHelperProduct::getMenuDetail("index.php?option=com_redshop&view=category");

                    if (!empty($menu) && $menu->home != 1) {
                        $main             = new stdClass;
                        $main->name       = $menu->title;
                        $main->link       = Redshop\IO\Route::_('index.php?option=com_redshop&view=category&Itemid=' . $menu->id);
                        $customPathways[] = $main;
                    } else {
                        $menu = RedshopHelperProduct::getMenuDetail(
                            "index.php?option=com_redshop&view=product&pid=" . $sectionId
                        );

                        if (!empty($menu) && $menu->home != 1 && property_exists($menu, 'parent')) {
                            $parentMenu = RedshopHelperProduct::getMenuInformation($menu->parent);

                            if (!empty($parentMenu)) {
                                $main             = new stdClass;
                                $main->name       = $parentMenu->name;
                                $main->link       = Redshop\IO\Route::_($parentMenu->link . '&Itemid=' . $parentMenu->id);
                                $customPathways[] = $main;
                            }
                        }
                    }

                    if ($sectionId != 0) {
                        $prd = \Redshop\Product\Product::getProductById($sectionId);

                        if (!$categoryId) {
                            $categoryId = RedshopHelperProduct::getCategoryProduct($sectionId);
                        }

                        if ($categoryId) {
                            $category_list  = array_reverse(
                                RedshopHelperProduct::getCategoryNavigationlist($categoryId)
                            );
                            $customPathways = array_merge($customPathways, self::getPathway($category_list));
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
                $menu           = RedshopHelperProduct::getMenuDetail(
                    "index.php?option=com_redshop&view=manufacturers"
                );

                if (isset($menu->id) && $menu->home != 1) {
                    if (property_exists($menu, 'parent')) {
                        $parentMenu = RedshopHelperProduct::getMenuInformation($menu->parent);

                        if (count($parentMenu) > 0) {
                            $main             = new stdClass;
                            $main->name       = $parentMenu->name;
                            $main->link       = Redshop\IO\Route::_($parentMenu->link . '&Itemid=' . $parentMenu->id);
                            $customPathways[] = $main;
                        }
                    }

                    $main             = new stdClass;
                    $main->name       = $menu->title;
                    $main->link       = Redshop\IO\Route::_(
                        'index.php?option=com_redshop&view=manufacturers&Itemid=' . $menu->id
                    );
                    $customPathways[] = $main;
                }

                if ($sectionId != 0) {
                    $menu = RedshopHelperProduct::getMenuInformation(0, $sectionId, "manufacturerid", "manufacturers");

                    if (!empty((array)$menu)) {
                        $main             = new stdClass;
                        $main->name       = $menu->title;
                        $main->link       = "";
                        $customPathways[] = $main;
                    } else {
                        $menu = RedshopEntityManufacturer::getInstance($sectionId)->getItem();

                        if (!empty((array)$menu)) {
                            $main             = new stdClass;
                            $main->name       = $menu->name;
                            $main->link       = "";
                            $customPathways[] = $main;
                        }
                    }
                }

                break;

            case "account":
                $customPathways = array();
                $menu           = RedshopHelperProduct::getMenuInformation($itemId);

                if (isset($menu) && count((array)$menu) > 0) {
                    $main       = new stdClass;
                    $main->name = $menu->title;
                    $main->link = "";
                } else {
                    $main       = new stdClass;
                    $main->name = JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE');
                    $main->link = "";
                }

                $customPathways[] = $main;

                break;

            case "order_detail":
                $customPathways = array();
                $menu           = RedshopHelperProduct::getMenuInformation(0, 0, "", "account");

                if (!empty($menu)) {
                    $main             = new stdClass;
                    $main->name       = $menu->title;
                    $main->link       = Redshop\IO\Route::_('index.php?option=com_redshop&view=account&Itemid=' . $menu->id);
                    $customPathways[] = $main;
                } else {
                    $main             = new stdClass;
                    $main->name       = JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE');
                    $main->link       = Redshop\IO\Route::_('index.php?option=com_redshop&view=account&Itemid=' . $itemId);
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
                $menu           = RedshopHelperProduct::getMenuInformation(0, 0, "", "account");

                if (is_object($menu) && count(get_object_vars($menu)) > 0) {
                    $main             = new stdClass;
                    $main->name       = $menu->title;
                    $main->link       = Redshop\IO\Route::_('index.php?option=com_redshop&view=account&Itemid=' . $menu->id);
                    $customPathways[] = $main;
                } else {
                    $main             = new stdClass;
                    $main->name       = JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE');
                    $main->link       = Redshop\IO\Route::_('index.php?option=com_redshop&view=account&Itemid=' . $itemId);
                    $customPathways[] = $main;
                }

                if ($view == 'orders') {
                    $lastlink = JText::_('COM_REDSHOP_ORDER_LIST');
                } elseif ($view == 'account_billto') {
                    $lastlink = JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL');
                } elseif ($view == 'account_shipto') {
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

        if (empty($customPathways)) {
            return;
        }

        $customPathways[count($customPathways) - 1]->link = '';

        foreach ($customPathways as $customPathway) {
            $pathway->addItem($customPathway->name, $customPathway->link);
        }
    }

    /**
     * Method for get list of pathway
     *
     * @param   array  $categories  List of category
     *
     * @return  array               List of pathway
     *
     * @since   2.0.7
     */
    public static function getPathway($categories = array())
    {
        $items = array();

        foreach ($categories as $category) {
            $item       = new stdClass;
            $item->name = $category['category_name'];
            $item->link = Redshop\IO\Route::_(
                'index.php?option=com_redshop&view=category&layout=detail&cid=' . $category['category_id'] . '&Itemid=' . $category['catItemid']
            );

            $items[] = $item;
        }

        return $items;
    }
}
