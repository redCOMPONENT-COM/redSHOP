<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The zipcode table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.Catalog
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableRating extends RedshopTable
{
    /**
     * The table name without prefix.
     *
     * @var string
     */
    protected $_tableName = 'redshop_product_rating';
    /**
     * The table key column
     *
     * @var string
     */
    protected $_tableKey = 'id';
}
