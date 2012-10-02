<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'mail.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class answer_detailModelanswer_detail extends RedshopCoreModelDetail
{
    public $_parent_id = null;

    public function __construct()
    {
        parent::__construct();
        $this->_parent_id = JRequest::getVar('parent_id');
    }

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
        $query = "SELECT q.* FROM " . $this->_table_prefix . "customer_question AS q " . "WHERE q.question_id=" . $this->_id;
        $this->_db->setQuery($query);
        $this->_data = $this->_db->loadObject();
        return (boolean)$this->_data;
    }

    public function getProduct()
    {
        $query = "SELECT * FROM " . $this->_table_prefix . "product ";
        $list  = $this->_getList($query);
        return $list;
    }

    public function _initData()
    {
        $user = JFactory::getUser();
        if (empty($this->_data))
        {
            $detail              = new stdClass();
            $detail->question_id = 0;
            $detail->product_id  = null;
            $detail->parent_id   = $this->_parent_id;
            $detail->user_id     = $user->id;
            $detail->user_name   = $user->name;
            $detail->user_email  = $user->email;
            $detail->question    = null;
            $detail->published   = 1;
            $this->_data         = $detail;
            return (boolean)$this->_data;
        }
        return true;
    }

    /**
     * Method to store the information
     *
     * @access public
     * @return boolean
     */
    public function store($data)
    {
        $row = $this->getTable('question_detail');
        if (!$data['question_id'])
        {
            $data['ordering'] = $this->MaxOrdering();
        }
        if (!$row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return $row;
    }

    /**
     * Method to get max ordering
     *
     * @access public
     * @return boolean
     */
    public function MaxOrdering()
    {
        $query = "SELECT (MAX(ordering)+1) FROM " . $this->_table_prefix . "customer_question " . "WHERE parent_id='" . $this->_parent_id . "' ";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    public function sendMailForAskQuestion($ansid)
    {
        $redshopMail = new redshopMail();
        $rs          = $redshopMail->sendAskQuestionMail($ansid);
        return $rs;
    }
}

