<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product;


defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Class Product Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Product
{
    /**
     * Product info
     *
     * @var  array
     * @since __DEPLOY_VERSION__
     */
    protected static $products = array();

    /**
     * All product data
     *
     * @var  array
     * @since __DEPLOY_VERSION__
     */
    protected static $allProducts = array();

    /**
     * @var array  List of available product number
     *
     * @since  2.0.4
     */
    protected static $productNumbers = array();

    /**
     * @var array  List of available product number
     *
     * @since  2.0.6
     */
    protected static $productPrices = array();

    /**
     * @var array  List of  product special id
     *
     * @since  2.1.5
     */
    protected static $productSpecialIds = array();

    /**
     * @var array  List of  product date range
     *
     * @since  2.1.5
     */
    protected static $productDateRange = array();

    /**
     * @param   string  $templateData
     * @param   int     $giftCard
     *
     * @return array
     * @since __DEPLOY_VERSION__
     */
    public static function getProductUserFieldFromTemplate($templateData = "", $giftCard = 0)
    {
        $userFields     = array();
        $userFieldsLbl  = array();
        $result         = array();
        $templateMiddle = "";

        if ($giftCard) {
            $templateStart = explode("{if giftcard_userfield}", $templateData);

            if (isset($templateStart[1])) {
                if (!empty($templateStart)) {
                    $templateEnd = explode("{giftcard_userfield end if}", $templateStart[1]);

                    if (!empty($templateEnd)) {
                        $templateMiddle = $templateEnd[0];
                    }
                }
            }
        } else {
            $templateStart = explode("{if product_userfield}", $templateData);

            if (count($templateStart) > 1) {
                $templateEnd = explode("{product_userfield end if}", $templateStart[1]);

                if (!empty($templateEnd)) {
                    $templateMiddle = $templateEnd[0];
                }
            }
        }

        if ($templateMiddle != "") {
            $tmp = explode('}', $templateMiddle);

            for ($i = 0, $in = count($tmp); $i < $in; $i++) {
                $val   = strpbrk($tmp[$i], "{");
                $value = str_replace("{", "", $val);

                if ($value != "") {
                    if (strpos($templateMiddle, '{' . $value . '_lbl}') !== false) {
                        $userFieldsLbl[] = $value . '_lbl';
                        $userFields[]    = $value;
                    } else {
                        $userFieldsLbl[] = '';
                        $userFields[]    = $value;
                    }
                }
            }
        }

        $tmp = array();

        for ($i = 0, $in = count($userFields); $i < $in; $i++) {
            if (!in_array($userFields[$i], $userFieldsLbl)) {
                $tmp[] = $userFields[$i];
            }
        }

        $userFields = $tmp;
        $result[0]  = $templateMiddle;
        $result[1]  = $userFields;

        return $result;
    }

    /**
     * @param         $productId
     * @param   int   $userId
     * @param   bool  $setRelated
     *
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public static function getProductById($productId, $userId = 0, $setRelated = true)
    {
        if (!$userId) {
            $user   = \JFactory::getUser();
            $userId = $user->id;
        }

        $key = $productId . '.' . $userId;

        if (!array_key_exists($key, static::$products)) {
            // Check if data is already loaded while getting list
            if (array_key_exists($productId, static::$allProducts)) {
                static::$products[$key] = static::$allProducts[$productId];
            } // Otherwise load product info
            else {
                $db    = \JFactory::getDbo();
                $query = self::getMainProductQuery(false, $userId);

                // Select product
                $query->where($db->qn('p.product_id') . ' = ' . (int)$productId);

                $db->setQuery($query);
                static::$products[$key] = $db->loadObject();
            }

            if ($setRelated === true && static::$products[$key]) {
                \RedshopHelperProduct::setProductRelates(array($key => static::$products[$key]), $userId);
            }
        }

        return static::$products[$key];
    }

    /**
     * @param   bool  $query
     * @param   int   $userId
     *
     * @return bool
     * @since __DEPLOY_VERSION__
     */
    public static function getMainProductQuery($query = false, $userId = 0)
    {
        $shopperGroupId = \RedshopHelperUser::getShopperGroup($userId);
        $db             = \JFactory::getDbo();

        if (!$query) {
            $query = $db->getQuery(true);
        }

        $query->select(array('p.*', 'p.product_id'))
            ->from($db->qn('#__redshop_product', 'p'));

        // Require condition
        $query->group($db->qn('p.product_id'));

        // Select price
        $query->select(
            array(
                'pp.price_id',
                $db->qn('pp.product_price', 'price_product_price'),
                $db->qn('pp.product_currency', 'price_product_currency'),
                $db->qn('pp.discount_price', 'price_discount_price'),
                $db->qn('pp.discount_start_date', 'price_discount_start_date'),
                $db->qn('pp.discount_end_date', 'price_discount_end_date')
            )
        )
            ->leftJoin(
                $db->qn('#__redshop_product_price', 'pp')
                . ' ON p.product_id = pp.product_id AND ((pp.price_quantity_start <= 1 AND pp.price_quantity_end >= 1)'
                . ' OR (pp.price_quantity_start = 0 AND pp.price_quantity_end = 0)) AND pp.shopper_group_id = ' . (int)$shopperGroupId
            )
            ->order('pp.price_quantity_start ASC');

        // Select category
        $query->select(array('pc.category_id'))
            ->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON pc.product_id = p.product_id');

        // Getting cat_in_sefurl as main category id if it available
        $query->leftJoin(
            $db->qn(
                '#__redshop_product_category_xref',
                'pc3'
            ) . ' ON pc3.product_id = p.product_id AND pc3.category_id = p.cat_in_sefurl'
        )
            ->leftJoin($db->qn('#__redshop_category', 'c3') . ' ON pc3.category_id = c3.id AND c3.published = 1');

        $subQuery = $db->getQuery(true)
            ->select('GROUP_CONCAT(DISTINCT c2.id ORDER BY c2.id ASC SEPARATOR ' . $db->q(',') . ')')
            ->from($db->qn('#__redshop_category', 'c2'))
            ->leftJoin($db->qn('#__redshop_product_category_xref', 'pc2') . ' ON c2.id = pc2.category_id')
            ->where('p.product_id = pc2.product_id')
            ->where(
                '((p.cat_in_sefurl != ' . $db->q(
                    ''
                ) . ' AND p.cat_in_sefurl != pc2.category_id) OR p.cat_in_sefurl = ' . $db->q('') . ')'
            )
            ->where('c2.published = 1');

        // In first position set main category id
        $query->select('CONCAT_WS(' . $db->q(',') . ', c3.id, (' . $subQuery . ')) AS categories');

        // Select media
        $query->select(array('media.media_alternate_text', 'media.media_id'))
            ->leftJoin(
                $db->qn('#__redshop_media', 'media')
                . ' ON media.section_id = p.product_id AND media.media_section = ' . $db->q('product')
                . ' AND media.media_type = ' . $db->q('images') . ' AND media.media_name = p.product_full_image'
            );

        // Select ratings
        $subQuery = $db->getQuery(true)
            ->select('COUNT(pr1.rating_id)')
            ->from($db->qn('#__redshop_product_rating', 'pr1'))
            ->where('pr1.product_id = p.product_id')
            ->where('pr1.published = 1');

        $query->select('(' . $subQuery . ') AS count_rating');

        $subQuery = $db->getQuery(true)
            ->select('SUM(pr2.user_rating)')
            ->from($db->qn('#__redshop_product_rating', 'pr2'))
            ->where('pr2.product_id = p.product_id')
            ->where('pr2.published = 1');

        $query->select('(' . $subQuery . ') AS sum_rating');

        // Count Accessories
        $subQuery = $db->getQuery(true)
            ->select('COUNT(pa.accessory_id)')
            ->from($db->qn('#__redshop_product_accessory', 'pa'))
            ->leftJoin(
                $db->qn('#__redshop_product', 'parent_product') . ' ON parent_product.product_id = pa.child_product_id'
            )
            ->where('pa.product_id = p.product_id')
            ->where('parent_product.published = 1');

        $query->select('(' . $subQuery . ') AS total_accessories');

        // Count child products
        $subQuery = $db->getQuery(true)
            ->select('COUNT(child.product_id) AS count_child_products, child.product_parent_id')
            ->from($db->qn('#__redshop_product', 'child'))
            ->where('child.product_parent_id > 0')
            ->where('child.published = 1')
            ->group('child.product_parent_id');

        $query->select('child_product_table.count_child_products')
            ->leftJoin(
                '(' . $subQuery . ') AS child_product_table ON child_product_table.product_parent_id = p.product_id'
            );

        // Sum quantity
        if (\Redshop::getConfig()->get('USE_STOCKROOM') == 1) {
            $subQuery = $db->getQuery(true)
                ->select('SUM(psx.quantity)')
                ->from($db->qn('#__redshop_product_stockroom_xref', 'psx'))
                ->where('psx.product_id = p.product_id')
                ->where('psx.quantity >= 0')
                ->where('psx.stockroom_id > 0');

            $query->select('(' . $subQuery . ') AS sum_quanity');
        }

        return $query;
    }
}