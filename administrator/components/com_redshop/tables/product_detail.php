<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tableproduct_detail extends JTable
{
	public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'product', 'product_id', $db);
    }

    /**
     * Check for the product Number Duplicate
     */
    public function check()
    {
        $q  = "SELECT product_id
				FROM " . $this->_table_prefix . "product
				WHERE product_number = " . $this->_db->Quote($this->product_number);
        $this->_db->setQuery($q);
        $pid = intval($this->_db->loadResult());

        if ($pid && ($pid != intval($this->product_id)))
        {
            $this->setError(JText::_('COM_REDSHOP_PRODUCT_NUMBER_ALREADY_EXISTS'));
            return false;
        }
        return true;
    }
}
