<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity;

defined('_JEXEC') or die;

/**
 * Currency Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class Currency extends Entity
{
    /**
     * @var    array
     * @since  __DEPLOY_VERSION__
     */
    protected static $codeInstance;

    /**
     * Method for load currency instance base on currency code
     *
     * @param   string  $code  Currency Code
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    public function loadFromCode($code = '')
    {
        if (empty($code)) {
            return self::getInstance();
        }

        if (!isset(static::$codeInstance[$code])) {
            /** @var \RedshopTableCurrency $table */
            $table = $this->getTable();

            if (!$table->load(array('code' => $code))) {
                return self::getInstance();
            }

            static::$codeInstance[$code] = $table->id;
        }

        return self::getInstance(static::$codeInstance[$code]);
    }
}
