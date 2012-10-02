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

class newsletter_detailModelnewsletter_detail extends RedshopCoreModelDetail
{
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
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'newsletter WHERE newsletter_id = ' . $this->_id;
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
            $detail                = new stdClass();
            $detail->newsletter_id = 0;
            $detail->name          = null;
            $detail->subject       = null;
            $detail->body          = null;
            $detail->template_id   = 0;
            $detail->published     = 1;
            $this->_data           = $detail;
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
        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return $row;
    }

    public function gettemplates()
    {
        $query = 'SELECT template_id AS value,template_name AS text FROM ' . $this->_table_prefix . 'template ' . 'WHERE template_section="newsletter" ' . 'AND published=1';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectlist();
    }

    public function getnewslettertexts()
    {
        $query = 'SELECT text_name,text_desc FROM ' . $this->_table_prefix . 'textlibrary ' . 'WHERE section="newsletter" ' . 'AND published=1';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectlist();
    }

    public function getNewsletterList($newsletter_id = 0)
    {
        $and = "";
        if ($newsletter_id != 0)
        {
            $and .= "AND n.newsletter_id='" . $newsletter_id . "' ";
        }
        $query = 'SELECT n.*,CONCAT(n.name," (",n.subject,")") AS text FROM ' . $this->_table_prefix . 'newsletter AS n ' . 'WHERE 1=1 ' . $and;
        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectlist();
        return $list;
    }

    public function getNewsletterTracker($newsletter_id = 0)
    {
        $data = $this->getNewsletterList($newsletter_id);

        $return = array();
        $qs     = array();
        $j      = 0;
        for ($d = 0; $d < count($data); $d++)
        {
            $query = "SELECT COUNT(*) AS total FROM " . $this->_table_prefix . "newsletter_tracker " . "WHERE newsletter_id='" . $data[$d]->newsletter_id . "' ";
            $this->_db->setQuery($query);
            $totalresult = $this->_db->loadResult();
            if (!$totalresult)
            {
                $totalresult = 0;
            }
            if ($newsletter_id != 0)
            {
                $totalread    = $this->getReadNewsletter($data[$d]->newsletter_id);
                $qs[0]->xdata = JText::_('COM_REDSHOP_NO_OF_UNREAD_NEWSLETTER');
                $qs[0]->ydata = $totalresult - $totalread;
                $qs[1]->xdata = JText::_('COM_REDSHOP_NO_OF_READ_NEWSLETTER');
                $qs[1]->ydata = $totalread;
            }
            else
            {
                $qs[$d]->xdata = $data[$d]->name;
                $qs[$d]->ydata = $totalresult;
            }
        }
        if ($newsletter_id != 0)
        {
            $return = array($qs, $data[0]->name);
        }
        else
        {
            $return = array($qs, JText::_('COM_REDSHOP_NO_OF_SENT_NEWSLETTER'));
        }
        return $return;
    }

    public function getReadNewsletter($newsletter_id)
    {
        $query = "SELECT COUNT(*) AS total FROM " . $this->_table_prefix . "newsletter_tracker " . "WHERE `newsletter_id`='" . $newsletter_id . "' " . "AND `read`='1' ";
        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();
        if (!$result)
        {
            $result->total = 0;
        }
        return $result->total;
    }
}
