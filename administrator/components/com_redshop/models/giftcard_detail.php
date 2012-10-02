<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class giftcard_detailModelgiftcard_detail extends RedshopCoreModelDetail
{
    public $_copydata = null;

    public function &getData()
    {
        if ($this->_loadData())
        {
        }
        else
        {
            $this->_initData();
        }

        return $this->_data;
    }

    public function _loadData()
    {
        if (empty($this->_data))
        {
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'giftcard WHERE giftcard_id = ' . $this->_id;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }

    public function _initData()
    {
        if (empty($this->_data))
        {
            $detail                    = new stdClass();
            $detail->giftcard_id       = 0;
            $detail->giftcard_name     = null;
            $detail->giftcard_validity = null;
            $detail->giftcard_date     = null;
            $detail->giftcard_bgimage  = null;
            $detail->giftcard_image    = null;
            $detail->giftcard_price    = 0;
            $detail->giftcard_value    = 0;
            $detail->published         = 1;
            $detail->customer_amount   = 0;
            $detail->giftcard_desc     = null;
            $this->_data               = $detail;

            return (boolean)$this->_data;
        }
        return true;
    }

    public function store($data)
    {
        $row = $this->getTable();

        if (!$row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        //------------ Start Giftcard Image insertion --------------------

        $giftcardfile = JRequest::getVar('giftcard_image', '', 'files', 'array');
        $giftcardimg  = "";
        if ($giftcardfile['name'] != "")
        {
            $giftcardfile['name'] = str_replace(" ", "_", $giftcardfile['name']);
            $giftcardimg          = JPath::clean(time() . '_' . $giftcardfile['name']); //Make the filename unique

            $src  = $giftcardfile['tmp_name'];
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard' . DS . $giftcardimg; //specific path of the file

            $row->giftcard_image = $giftcardimg;
            JFile::upload($src, $dest);
        }

        //---------------------- End Giftcard Image -----------------------------------

        //------------ Start Giftcard BgImage insertion --------------------

        $giftcardbgfile = JRequest::getVar('giftcard_bgimage', '', 'files', 'array');
        $giftcardbgimg  = "";
        if ($giftcardbgfile['name'] != "")
        {
            $giftcardbgfile['name'] = str_replace(" ", "_", $giftcardbgfile['name']);
            $giftcardbgimg          = JPath::clean(time() . '_' . $giftcardbgfile['name']); //Make the filename unique
            $src                    = $giftcardbgfile['tmp_name'];
            $dest                   = REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard' . DS . $giftcardbgimg; //specific path of the file

            $row->giftcard_bgimage = $giftcardbgimg;
            JFile::upload($src, $dest);
        }

        //---------------------- End Giftcard BgImage -----------------------------------

        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (ECONOMIC_INTEGRATION == 1)
        {
            $economic = new economic();

            $giftdata                  = new stdClass();
            $giftdata->product_id      = $row->giftcard_id;
            $giftdata->product_number  = "gift_" . $row->giftcard_id . "_" . $row->giftcard_name;
            $giftdata->product_name    = $row->giftcard_name;
            $giftdata->product_price   = $row->giftcard_price;
            $giftdata->accountgroup_id = $row->accountgroup_id;
            $giftdata->product_volume  = 0;

            $ecoProductNumber = $economic->createProductInEconomic($giftdata);
        }

        return $row;
    }
}
