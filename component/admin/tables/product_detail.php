<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseDriver;

/**
 * Product table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.Product
 * @since       1.6
 */
class TableProduct_Detail extends JTable
{
    /**
     * @var  integer
     */
    public $attribute_set_id;

    /**
     * @var  integer
     */
    public $not_for_sale;

    /**
     * @var  integer
     */
    public $manufacturer_id;

    /**
     * @var  string
     */
    public $product_name;

    /**
     * @var  string
     */
    public $product_number;

    /**
     * @var  string
     */
    public $product_thumb_image;

    /**
     * @var  string
     */
    public $product_back_thumb_image;

    /**
     * @var  string
     */
    public $product_full_image;

    /**
     * @var  string
     */
    public $product_back_full_image;

    /**
     * @var  string
     */
    public $product_preview_image;

    /**
     * @var  string
     */
    public $product_preview_back_image;

    /**
     * @var  integer
     */
    public $product_id;

    /**
     * @var  float
     */
    public $weight;

    /**
     * @var  float
     */
    public $product_length;

    /**
     * @var  float
     */
    public $product_height;

    /**
     * @var  float
     */
    public $product_width;

    /**
     * @var  float
     */
    public $product_price;

    /**
     * @var  integer
     */
    public $min_order_product_quantity;

    /**
     * @var  integer
     */
    public $max_order_product_quantity;

    /**
     * @var  integer
     */
    public $product_special;

    /**
     * @var  integer
     */
    public $expired;

    /**
     * @var  integer
     */
    public $product_on_sale;

    /**
     * @var  integer
     */
    public $visited;

    /**
     * @var  integer
     */
    public $published;

    /**
     * @var  integer
     */
    public $product_template;

    /**
     * @var  integer
     */
    public $product_parent_id = 0;

    /**
     * @var  string
     */
    public $sef_url;

    /**
     * @var  string
     */
    public $metarobot_info;

    /**
     * @var  string
     */
    public $product_s_desc;

    /**
     * @var  string
     */
    public $product_desc;

    /**
     * @var  string
     */
    public $metadesc;

    /**
     * @var  string
     */
    public $metakey;

    /**
     * @var  string
     */
    public $pagetitle;

    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database driver object.
     *
     * @since 11.1
     */
    public function __construct($db)
    {
        parent::__construct('#__redshop_product', 'product_id', $db);
    }

    /**
     * Method to check duplicate product number
     *
     * @return  boolean  True on success
     */
    public function check()
    {
        // Initialiase variables.
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('product_id')
            ->from($db->qn('#__redshop_product'))
            ->where($db->qn('product_number') . ' = ' . $db->q($this->product_number));

        // Set the query and load the result.
        $db->setQuery($query);

        $duplicateProductId = intval($db->loadResult());

        // Make sure we are not checking it's own product number
        if ($duplicateProductId && $duplicateProductId != intval($this->product_id)) {
            $this->setError(Text::_('COM_REDSHOP_PRODUCT_NUMBER_ALREADY_EXISTS'));

            return false;
        }

        return true;
    }

    /**
     * @param   array|object  $src     Data
     * @param   array|string  $ignore  Ignore
     *
     * @return boolean
     * @since  __DEPLOY_VERSION__
     */
    public function bind($src, $ignore = [])
    {
        if (is_object($src)) {
            $src = get_object_vars($src);
        }

        if (is_array($src)) {
            $setZeroIfEmpty = [
                'discount_price',
                'attribute_set_id',
                'max_order_product_quantity',
                'product_parent_id',
                'discount_stratdate',
                'discount_enddate',
                'product_tax_group_id',
            ];

            foreach ($setZeroIfEmpty as $fieldName) {
                if (
                    array_key_exists($fieldName, $src)
                    && empty($src[$fieldName])
                ) {
                    $src[$fieldName] = 0;
                }
            }
        }

        return parent::bind($src, $ignore);
    }
}