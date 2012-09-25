<?php
/**
 * @package     redSHOP
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

if (!defined('_VALID_MOS') && !defined('_JEXEC'))
{
    die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
}
JHTML::_('behavior.tooltip');

class text_library
{
    public $_data = null;

    public $_table_prefix = null;

    public $_db = null;

    function __construct()
    {
        global $mainframe, $context;
        $this->_table_prefix = '#__redshop_';
        $this->_db           = JFactory::getDbo();
    }

    function getTextLibraryData()
    {
        $query = "SELECT * FROM " . $this->_table_prefix . "textlibrary " . "WHERE published=1 ";
        $this->_db->setQuery($query);
        $textdata = $this->_db->loadObjectlist();
        return $textdata;
    }

    function getTextLibraryTagArray()
    {
        $result   = array();
        $textdata = $this->getTextLibraryData();
        for ($i = 0; $i < count($textdata); $i++)
        {
            $result[] = $textdata[$i]->text_name;
        }
        return $result;
    }

    function replace_texts($data)
    {
        $textdata = $this->getTextLibraryData();
        for ($i = 0; $i < count($textdata); $i++)
        {
            $textname    = "{" . $textdata[$i]->text_name . "}";
            $textreplace = $textdata[$i]->text_field;
            $data        = str_replace($textname, $textreplace, $data);
        }
        return $data;
    }
}
