<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Workflow;

defined('_JEXEC') or die;

/**
 * Quotation Workflow
 *
 * @since __DEPLOY_VERSION__
 */
class Quotation
{
    /**
     * @param $action
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    protected static function checkCondition($action) {
        $condition = false;

        switch ($action) {
            case 'saveCartToDB':
                $isQuotationMode      = \Redshop\Quotation\Helper::is('DEFAULT_QUOTATION_MODE', false);
                $isShowQuotationPrice = \Redshop\Quotation\Helper::is('SHOW_QUOTATION_PRICE', false);
                return !$isQuotationMode || ($isQuotationMode && $isShowQuotationPrice);
            default:
                break;
        }

        return $condition;
    }

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function saveCartToDB() {
        $condition = self::checkCondition(__FUNCTION__);

        if ($condition) {
            \RedshopHelperCart::addCartToDatabase();
        }
    }
}