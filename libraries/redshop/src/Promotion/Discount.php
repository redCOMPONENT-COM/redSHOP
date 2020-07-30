<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Promotion;

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/**
 * Discount Helper
 *
 * @since 3.0
 */
class Discount
{
    /**
     * @param $get
     *
     * @return array
     * @since 3.0
     * @deprecated
     * @see \Redshop\Promotion\Discount\Calculation::discountCalculator($get);
     */
    public static function discountCalculator($get)
    {
        return \Redshop\Promotion\Discount\Calculation::discountCalculator($get);
    }

    /**
     * @param   int  $area
     * @param   int  $pid
     * @param   int  $areaBetween
     *
     * @return mixed
     * @since 3.0
     */
    public static function getDiscountCalcData($area = 0, $pid = 0, $areaBetween = 0)
    {
        $area = floatval($area);

        $db    = \JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select("*")
            ->from($db->quoteName("#__redshop_product_discount_calc"))
            ->where($db->quoteName("product_id") . "=" . $db->quote((int)$pid))
            ->order("id ASC");

        if ($areaBetween) {
            $query->where($db->q($area) . " BETWEEN " . $db->qn('area_start') . " AND " . $db->qn('area_end'));
        }

        if ($area) {
            $query->where($db->quoteName("area_start_converted") . "<=" . $db->q($area))
                ->where($db->quoteName("area_end_converted") . ">=" . $db->q($area));
        }

        $db->setQuery($query);
        $list = $db->loadObjectlist();

        return $list;
    }

    /**
     * @param   string  $pdcExtraIds
     * @param   int     $productId
     *
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public static function getDiscountCalcDataExtra($pdcExtraIds = "", $productId = 0)
    {
        return \RedshopHelperCartDiscount::getDiscountCalcDataExtra($pdcExtraIds, $productId);
    }
}
