<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Promotion;

defined('_JEXEC') or die;

/**
 * Voucher helper
 *
 * @since 3.0
 */
class Voucher
{
    /**
     * @var $globalVoucher
     * @since 3.0
     */
    public static $globalVoucher;

    /**
     * @var $couponRemain
     * @since 3.0
     */
    public static $couponRemain;


    /**
     * @param $voucherCode
     *
     * @return bool
     * @since __DEPLOY_DATA__
     */
    public static function getVoucherData($voucherCode)
    {
        $db = \JFactory::getDbo();

        $user           = \JFactory::getUser();
        $voucher        = array();
        $currentTime    = \JFactory::getDate()->toSql();
        $globalVouchers = self::getGlobalVoucher($voucherCode);

        if (self::$globalVoucher != 1) {
            if ($user->id) {
                $subQuery = $db->getQuery(true)
                    ->select('GROUP_CONCAT(DISTINCT pv.product_id SEPARATOR ' . $db->quote(', ') . ') AS product_id')
                    ->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
                    ->where('v.id = pv.voucher_id');

                $query = $db->getQuery(true)
                    ->select(
                        array(
                            'vt.transaction_voucher_id',
                            'vt.amount AS total',
                            'vt.product_id',
                            'v.*',
                            '(' . $subQuery . ') AS nproduct'
                        )
                    )
                    ->from($db->qn('#__redshop_voucher', 'v'))
                    ->leftJoin($db->qn('#__redshop_product_voucher_transaction', 'vt') . ' ON vt.voucher_id = v.id')
                    ->where('vt.voucher_code = ' . $db->quote($voucherCode))
                    ->where('vt.amount > 0')
                    ->where('v.type = ' . $db->quote('Total'))
                    ->where('v.published = 1')
                    ->where(
                        '('
                        . '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
                        . ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($currentTime) . ')'
                        . ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
                        . ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($currentTime) . ')'
                        . ')'
                    )
                    ->where('vt.user_id = ' . (int)$user->id)
                    ->order('vt.transaction_voucher_id DESC');

                $voucher = $db->setQuery($query)->loadObject();

                if (count($voucher) > 0) {
                    return false;
                }
            }

            if (count($voucher) <= 0) {
                $subQuery = $db->getQuery(true)
                    ->select('GROUP_CONCAT(DISTINCT pv.product_id SEPARATOR ' . $db->quote(', ') . ') AS product_id')
                    ->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
                    ->where($db->qn('v.id') . ' = ' . $db->qn('pv.voucher_id'));

                $query = $db->getQuery(true)
                    ->select(
                        array(
                            '(' . $subQuery . ') AS nproduct',
                            'v.amount AS total',
                            'v.type',
                            'v.free_ship',
                            'v.id',
                            'v.code',
                            'v.voucher_left'
                        )
                    )
                    ->from($db->qn('#__redshop_voucher', 'v'))
                    ->where($db->qn('v.published') . ' = 1')
                    ->where($db->qn('v.code') . ' = ' . $db->quote($voucherCode))
                    ->where(
                        '('
                        . '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
                        . ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($currentTime) . ')'
                        . ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
                        . ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($currentTime) . ')'
                        . ')'
                    )
                    ->where($db->qn('v.voucher_left') . ' > 0');

                return $db->setQuery($query)->loadObject();
            }
        }

        return $globalVouchers;
    }

    /**
     * @param $voucherCode
     *
     * @return mixed
     * @since 3.0
     */
    public static function getGlobalVoucher($voucherCode)
    {
        $db = \JFactory::getDbo();

        $currentTime = \JFactory::getDate()->toSql();

        $query = $db->getQuery(true)
            ->select($db->qn('pv.product_id'))
            ->select('v.*')
            ->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
            ->leftJoin($db->qn('#__redshop_voucher', 'v') . ' ON ' . $db->qn('v.id') . ' = ' . $db->qn('pv.voucher_id'))
            ->where($db->qn('v.published') . ' = 1')
            ->where($db->qn('v.code') . ' = ' . $db->quote($voucherCode))
            ->where(
                '('
                . '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
                . ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($currentTime) . ')'
                . ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
                . ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($currentTime) . ')'
                . ')'
            )
            ->where($db->qn('v.voucher_left') . ' > 0');

        $voucher = $db->setQuery($query)->loadObject();

        if ($voucher) {
            return $voucher;
        }

        self::$globalVoucher = 1;

        $query->clear()
            ->select('v.*')
            ->select($db->qn('v.amount', 'total'))
            ->from($db->qn('#__redshop_voucher', 'v'))
            ->where($db->qn('v.published') . ' = 1')
            ->where($db->qn('v.code') . ' = ' . $db->quote($voucherCode))
            ->where(
                '('
                . '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
                . ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($currentTime) . ')'
                . ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
                . ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($currentTime) . ')'
                . ')'
            )
            ->where($db->qn('v.voucher_left') . ' > 0');

        return $db->setQuery($query)->loadObject();
    }

    /**
     * @param   string   $couponCode  Coupon code
     * @param   integer  $subtotal    Subtotal
     *
     * @return   array|mixed
     * @since 2.0.7
     */
    public static function getCouponData($couponCode, $subtotal = 0)
    {
        $db = \JFactory::getDbo();

        $today  = \JFactory::getDate()->toSql();
        $user   = \JFactory::getUser();
        $coupon = array();

        // Create the base select statement.
        $query = $db->getQuery(true)
            ->select('c.*')
            ->from($db->qn('#__redshop_coupons', 'c'))
            ->where($db->qn('c.published') . ' = 1')
            ->where(
                '('
                . '(' . $db->qn('c.start_date') . ' = ' . $db->quote($db->getNullDate())
                . ' OR ' . $db->qn('c.start_date') . ' <= ' . $db->quote($today) . ')'
                . ' AND (' . $db->qn('c.end_date') . ' = ' . $db->quote($db->getNullDate())
                . ' OR ' . $db->qn('c.end_date') . ' >= ' . $db->quote($today) . ')'
                . ')'
            );

        if ($user->id) {
            $userQuery = clone($query);
            $userQuery->select(
                array(
                    $db->qn('ct.coupon_value', 'coupon_value'),
                    $db->qn('ct.userid'),
                    $db->qn('ct.transaction_coupon_id')
                )
            )
                ->leftjoin(
                    $db->qn('#__redshop_coupons_transaction', 'ct')
                    . ' ON ' . $db->qn('ct.coupon_id') . ' = ' . $db->qn('c.id')
                )
                ->where($db->qn('ct.coupon_value') . ' > 0')
                ->where($db->qn('ct.coupon_code') . ' = ' . $db->quote($couponCode))
                ->where($db->qn('ct.userid') . ' = ' . (int)$user->id)
                ->order($db->qn('ct.transaction_coupon_id') . ' DESC');

            $db->setQuery($userQuery, 0, 1);
            $coupon = $db->loadObject();

            if (count($coupon) > 0) {
                self::$couponRemain = 1;
            }
        }

        if (count($coupon) <= 0) {
            $query->where($db->qn('c.code') . ' = ' . $db->quote($couponCode))
                ->where($db->qn('c.amount_left') . ' > 0')
                ->where(
                    '('
                    . $db->quote($subtotal) . ' >= ' . $db->qn('c.subtotal')
                    . ' OR ' . $db->qn('c.subtotal') . ' = 0'
                    . ')'
                );

            $db->setQuery($query, 0, 1);
            $coupon = $db->loadObject();
        }

        return $coupon;
    }
}