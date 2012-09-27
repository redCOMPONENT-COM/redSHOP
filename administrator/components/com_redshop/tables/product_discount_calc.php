<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableproduct_discount_calc extends JTable
{
    public $id = 0;

    public $product_id = 0;

    public $area_start = 0;

    public $area_end = 0;

    public $area_price = 0;

    public $discount_calc_unit = null;

    public $area_start_converted = 0;

    public $area_end_converted = 0;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'product_discount_calc', 'id', $db);
    }

    /**
     * Check for the product ID
     */
    public function check()
    {
        $producthelper = new producthelper();

        $unit = 1;
        $unit = $producthelper->getUnitConversation("m", $discount_calc_unit[$c]);

        # updating value
        $converted_area_start = $this->area_start * $unit * $unit;
        $converted_area_end   = $this->area_end * $unit * $unit;
        # End

        /**** check for valid area *****/
        /*$query = 'SELECT id FROM '.$this->_table_prefix.'product_discount_calc '
                  .' WHERE product_id = "'.$this->product_id.'" '
                  .' AND area_end >= '.$this->area_start;*/

        $query = "SELECT *
					FROM `" . $this->_table_prefix . "product_discount_calc`
					WHERE product_id = " . $this->product_id . " AND (" . $converted_area_start . "
					BETWEEN `area_start_converted`
					AND `area_end_converted` || " . $converted_area_end . "
					BETWEEN `area_start_converted`
					AND `area_end_converted` )";

        $this->_db->setQuery($query);

        $xid = intval($this->_db->loadResult());
        if ($xid)
        {
            $this->_error = JText::_('COM_REDSHOP_SAME_RANGE');
            return false;
        }
        return true;
    }
}
