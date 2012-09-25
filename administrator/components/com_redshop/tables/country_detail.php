<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class Tablecountry_detail extends JTable
{
    public $country_id = null;

    public $country_name = null;

    public $country_3_code = null;

    public $country_jtext = null;

    public $country_2_code = null;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';

        parent::__construct($this->_table_prefix . 'country', 'country_id', $db);
    }

    public function check()
    {
        $db = JFactory::getDBO();

        $q = "SELECT country_id,country_3_code  FROM " . $this->_table_prefix . "country" . " WHERE country_3_code = '" . $this->country_3_code . "' AND country_id !=  " . $this->country_id;

        $db->setQuery($q);

        $xid = intval($db->loadResult());
        if ($xid)
        {

            $this->_error = JText::_('COM_REDSHOP_COUNTRY_CODE_3_ALREADY_EXISTS');
            JError::raiseWarning('', $this->_error);
            return false;
        }
        else
        {

            $q = "SELECT country_id,country_3_code,country_2_code  FROM " . $this->_table_prefix . "country" . " WHERE country_2_code = '" . $this->country_2_code . "' AND country_id !=  " . $this->country_id;

            $db->setQuery($q);
            $xid = intval($db->loadResult());
            if ($xid)
            {
                $this->_error = JText::_('COM_REDSHOP_COUNTRY_CODE_2_ALREADY_EXISTS');
                JError::raiseWarning('', $this->_error);
                return false;
            }
        }
        return true;
    }
}

