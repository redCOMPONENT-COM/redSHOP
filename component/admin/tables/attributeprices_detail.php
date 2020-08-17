<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * redSHOP Product Attribute Price table
 *
 * @package     Redshop
 * @subpackage  Attribute Price Detail
 * @since       1.2
 */
class Tableattributeprices_detail extends JTable
{
    public $price_id = 0;

    public $section_id = null;

    public $section = null;

    public $product_price = null;

    public $product_currency = null;

    public $cdate = null;

    public $shopper_group_id = null;

    public $price_quantity_start = 0;

    public $price_quantity_end = 0;

    public $discount_price = 0;

    public $discount_start_date = 0;

    public $discount_end_date = 0;

    /**
     * Construct
     * @since 3.0.1
     */
    public function __construct(&$db)
    {
        $this->_table_prefix = '#__redshop_';
        parent::__construct($this->_table_prefix . 'product_attribute_price', 'price_id', $db);
    }

    /**
     * Method to check user entered valid quantity start and end for shopper group based price.
     *
     * @return bool
     * @throws Exception
     * @since __DEPLOY_VERSION))
     */
    public function check()
    {
        $xid    = \Redshop\Attribute\Helper::getAttributePriceStartId($this);
        $xidEnd = \Redshop\Attribute\Helper::getAttributePriceEndId($this);


        if (($xid || $xidEnd)
            && (($xid != intval($this->price_id)
                    && $xid != 0)
                || ($xidEnd != intval($this->price_id)
                    && $xidEnd != 0))
        ) {
            $this->_error = \Joomla\CMS\Language\Text::sprintf(
                'WARNNAMETRYAGAIN',
                \Joomla\CMS\Language\Text::_('COM_REDSHOP_PRICE_ALREADY_EXISTS')
            );

            return false;
        }

        if ($this->price_quantity_start > $this->price_quantity_end) {
            throw new \Exception(
                \Joomla\CMS\Language\Text::_('COM_REDSHOP_PRODUCT_PRICE_QUANTITY_END_MUST_MORE_THAN_QUANTITY_START')
            );

            return false;
        }

        if ($this->discount_start_date > $this->discount_end_date) {
            throw new \Exception(
                \Joomla\CMS\Language\Text::_('COM_REDSHOP_PRODUCT_PRICE_END_DATE_MUST_MORE_THAN_START_DATE')
            );

            return false;
        }

        return true;
    }
}
