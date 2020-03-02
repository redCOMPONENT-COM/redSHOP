<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelAccountgroup_detail extends RedshopModel
{
    /**
     * @var null
     */
    public $_id = null;

    /**
     * @var null
     */
    public $_data = null;

    /**
     * @var string|null
     */
    public $_table_prefix = null;

    /**
     * RedshopModelAccountgroup_detail constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';

        $array = JFactory::getApplication()->input->get('cid', 0, 'array');
        $this->setId((int)$array[0]);
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->_id = $id;
        $this->_data = null;
    }

    /**
     * @return mixed|null
     */
    public function &getData()
    {
        if ($this->_loadData()) {
        } else {
            $this->_initData();
        }

        return $this->_data;
    }

    /**
     * @return bool
     */
    public function _loadData()
    {
        if (empty($this->_data)) {
            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')
                ->from($db->qn('#__redshop_economic_accountgroup'))
                ->where($db->qn('accountgroup_id') . ' = ' . $db->q($this->_id));

            $db->setQuery($query);
            $this->_data = $db->loadObject();

            return (boolean)$this->_data;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function _initData()
    {
        if (empty($this->_data)) {
            $detail = new stdClass;

            $detail->accountgroup_id = 0;
            $detail->accountgroup_name = null;
            $detail->economic_vat_account = null;
            $detail->economic_nonvat_account = null;
            $detail->economic_discount_vat_account = null;
            $detail->economic_discount_nonvat_account = null;
            $detail->economic_shipping_vat_account = null;
            $detail->economic_shipping_nonvat_account = null;
            $detail->economic_discount_product_number = null;
            $detail->published = 1;
            $this->_data = $detail;

            return (boolean)$this->_data;
        }

        return true;
    }

    /**
     * @param $data
     * @return bool|JTable
     * @throws Exception
     */
    public function store($data)
    {
        $row = $this->getTable();

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());

            return false;
        }

        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());

            return false;
        }

        return $row;
    }

    /**
     * @param array $accountGroupIds
     * @return bool
     * @throws Exception
     */
    public function delete($accountGroupIds = [])
    {
        return \Redshop\Account\Group::deleteAccountGroups($accountGroupIds);
    }

    /**
     * @param array $accountGroupIds
     * @param int $publish
     * @return bool
     * @throws Exception
     */
    public function publish($accountGroupIds = [], $publish = 1)
    {
        return \Redshop\Account\Group::setPublishStatus($accountGroupIds, $publish);
    }
}
