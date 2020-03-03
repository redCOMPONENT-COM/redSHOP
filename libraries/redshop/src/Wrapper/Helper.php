<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Wrapper;

defined('_JEXEC') or die;

/**
 * Wrapper Helper
 *
 * @since 3.0
 */
class Helper
{
    /**
     * @param   array  $cart
     *
     * @return array
     * @since 3.0
     */
    public static function getWrapperPrice($cart = array())
    {
        $wrapper     = \RedshopHelperProduct::getWrapper($cart['product_id'], $cart['wrapper_id']);
        $wrapperVat = 0;
        $wrappers  = array();

        if (count($wrapper) > 0)
        {
            if ($wrapper[0]->wrapper_price > 0)
            {
                $wrapperVat = \RedshopHelperProduct::getProductTax($cart['product_id'], $wrapper[0]->wrapper_price);
            }

            $wrapperPrice = $wrapper[0]->wrapper_price;

            $wrappers['wrapper_vat']   = $wrapperVat;
            $wrappers['wrapper_price'] = $wrapperPrice;
        }

        return $wrappers;
    }

    /**
     * @param $id
     * @return bool|mixed
     * @throws \Exception
     */
    public static function getWrapperById($id)
    {
        try {
            $id = (int) $id;
            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('p.*, w.*')
                ->from($db->qn('#__redshop_wrapper', 'w'))
                ->leftJoin($db->qn('#__redshop_product', 'p')
                    . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('w.product_id'))
                ->where($db->qn('w.wrapper_id') . ' = ' .  $db->q((int) $id));

            $db->setQuery($query);
            return $db->loadObject();
        } catch (\Exception $e) {
            \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * @param array $wrapperIds
     * @return bool
     * @throws \Exception
     */
    public static function removeWrappers($wrapperIds = [])
    {
        if (is_array($wrapperIds)
            && (count($wrapperIds) > 0)) {

            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->delete($db->qn('#__redshop_wrapper'))
                ->where($db->qn('wrapper_id') . ' IN (' . $db->q(implode(',', $wrapperIds)) . ')');

            return \Redshop\DB\Tool::safeExecute($db, $query);
        }

        return true;
    }

    /**
     * @param array $wrapperIds
     * @param int $publish
     * @return bool
     * @throws \Exception
     */
    public static function setPublishStatus($wrapperIds = [], $publish = 1)
    {
        if (is_array($wrapperIds)
            && (count($wrapperIds) > 0)) {

            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->update($db->qn('#__redshop_wrapper'))
                ->set([
                    $db->qn('published') . ' = ' . $db->q($publish)
                ])->where($db->qn('wrapper_id') . ' IN (' . $db->q(implode(',', $wrapperIds)) . ')');

            return \Redshop\DB\Tool::safeExecute($db, $query);
        }

        return true;
    }

    /**
     * @param array $wrapperIds
     * @param int $status
     * @return bool
     * @throws \Exception
     */
    public static function enableWrapperUseToAll($wrapperIds = [], $status = 1)
    {
        if (count($wrapperIds)) {
            $wrapperIds = implode(',', $wrapperIds);

            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->update($db->qn('#__redshop_wrapper'))
                ->set([
                    $db->qn('wrapper_use_to_all') . ' = ' . $db->q((int) $status)
                ])->where($db->qn('wrapper_id') . ' IN (' . $db->q($wrapperIds) . ')');

            return \Redshop\DB\Tool::safeExecute($db, query);
        }

        return true;
    }
}