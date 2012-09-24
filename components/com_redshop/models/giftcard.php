<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

class giftcardModelgiftcard extends JModelLegacy
{
    var $_id = null;

    var $_data = null;

    var $_product = null; /// product data
    var $_table_prefix = null;

    var $_template = null;

    var $_limit = null;

    function __construct()
    {
        global $mainframe;
        parent::__construct();

        $this->_table_prefix = '#__redshop_';
        $Id                  = JRequest::getInt('gid', 0);

        $this->setId(( int )$Id);
    }

    function setId($id)
    {
        $this->_id   = $id;
        $this->_data = null;
    }

    function _buildQuery()
    {
        global $mainframe;

        $and = "";
        if ($this->_id)
        {
            $and .= "AND giftcard_id='" . $this->_id . "' ";
        }
        $query = "SELECT * FROM " . $this->_table_prefix . "giftcard " . "WHERE published = 1 " . $and;
        return $query;
    }

    function getData()
    {
        if (empty ($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }
        return $this->_data;
    }

    function getGiftcardTemplate()
    {
        global $mainframe, $context;

        $redTemplate = new Redtemplate();
        if (!$this->_id)
        {
            $carttemplate = $redTemplate->getTemplate("giftcard_list");
        }
        else
        {
            $carttemplate = $redTemplate->getTemplate("giftcard");
        }
        return $carttemplate;
    }
}
