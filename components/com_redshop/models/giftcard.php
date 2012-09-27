<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model.php';

class giftcardModelgiftcard extends RedshopCoreModel
{
    public $_product = null;

    public $_template = null;

    public $_limit = null;

    public function __construct()
    {
        parent::__construct();
        $this->_id = JRequest::getInt('gid', 0);
    }

    public function _buildQuery()
    {
        $and = "";
        if ($this->_id)
        {
            $and .= "AND giftcard_id='" . $this->_id . "' ";
        }
        $query = "SELECT * FROM " . $this->_table_prefix . "giftcard " . "WHERE published = 1 " . $and;
        return $query;
    }

    public function getData()
    {
        if (empty ($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }
        return $this->_data;
    }

    public function getGiftcardTemplate()
    {
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
