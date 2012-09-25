<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class manufacturer_detailModelmanufacturer_detail extends JModelLegacy
{
    public $_id = null;

    public $_data = null;

    public $_table_prefix = null;

    public $_copydata = null;

    public $_templatedata = null;

    function __construct()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';

        $array = JRequest::getVar('cid', 0, '', 'array');

        $this->setId((int)$array[0]);
    }

    function setId($id)
    {
        $this->_id   = $id;
        $this->_data = null;
    }

    function &getData()
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

    function _loadData()
    {
        if (empty($this->_data))
        {
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'manufacturer WHERE manufacturer_id = ' . $this->_id;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }

    function _initData()
    {
        if (empty($this->_data))
        {
            $detail                          = new stdClass();
            $detail->manufacturer_id         = 0;
            $detail->manufacturer_name       = null;
            $detail->manufacturer_desc       = null;
            $detail->manufacturer_email      = null;
            $detail->manufacturer_url        = null;
            $detail->product_per_page        = 0;
            $detail->template_id             = 0;
            $detail->metakey                 = null;
            $detail->metadesc                = null;
            $detail->metalanguage_setting    = null;
            $detail->metarobot_info          = null;
            $detail->pagetitle               = null;
            $detail->pageheading             = null;
            $detail->sef_url                 = null;
            $detail->excluding_category_list = null;
            $detail->published               = 1;
            $this->_data                     = $detail;
            return (boolean)$this->_data;
        }
        return true;
    }

    function store($data)
    {
        $order_functions  = new order_functions();
        $plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');
        if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
        {
            $data['excluding_category_list'] = @ implode(',', $data['excluding_category_list']);
        }

        $row              = $this->getTable();
        $data['ordering'] = $this->MaxOrdering();

        if (!$row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
        {
            if (!$row->excluding_category_list)
            {
                $row->excluding_category_list = '';
            }
        }
        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return $row;
    }

    function delete($cid = array())
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'manufacturer WHERE manufacturer_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function publish($cid = array(), $publish = 1)
    {
        if (count($cid))
        {
            $cids  = implode(',', $cid);
            $query = 'UPDATE ' . $this->_table_prefix . 'manufacturer' . ' SET published = ' . intval($publish) . ' WHERE manufacturer_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function copy($cid = array())
    {

        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'SELECT * FROM ' . $this->_table_prefix . 'manufacturer WHERE manufacturer_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $this->_copydata = $this->_db->loadObjectList();
        }
        foreach ($this->_copydata as $cdata)
        {
            $post['manufacturer_id']         = 0;
            $post['manufacturer_name']       = 'Copy Of ' . $cdata->manufacturer_name;
            $post['manufacturer_desc']       = $cdata->manufacturer_desc;
            $post['manufacturer_email']      = $cdata->manufacturer_email;
            $post['product_per_page']        = $cdata->product_per_page;
            $post['template_id']             = $cdata->template_id;
            $post['metakey']                 = $cdata->metakey;
            $post['metadata']                = $cdata->metadata;
            $post['metadesc']                = $cdata->metadesc;
            $post['excluding_category_list'] = $cdata->excluding_category_list;
            $post['published']               = $cdata->published;

            manufacturer_detailModelmanufacturer_detail::store($post);
        }
        return true;
    }

    function TemplateData()
    {
        $query = "SELECT template_id as value,template_name as text FROM " . $this->_table_prefix . "template WHERE template_section ='manufacturer_products' and published=1";
        $this->_db->setQuery($query);
        $this->_templatedata = $this->_db->loadObjectList();
        return $this->_templatedata;
    }

    function getMediaId($mid)
    {
        $query = 'SELECT media_id,media_name FROM ' . $this->_table_prefix . 'media ' . 'WHERE media_section="manufacturer" AND section_id = ' . $mid;
        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }

    // Manufacturer ordering
    /*function saveorder($cid = array(), $order)
     {
         $row =& $this->getTable();
         $groupings = array();

         // update ordering values
         for( $i=0; $i < count($cid); $i++ )
         {
             $row->load( (int) $cid[$i] );
             // track categories
             $groupings[] = $row->manufacturer_id;

             if ($row->ordering != $order[$i])
             {
                 $row->ordering = $order[$i];
                 if (!$row->store()) {
                     $this->setError($this->_db->getErrorMsg());
                     return false;
                 }
             }
         }
         // execute updateOrder for each parent group
         $groupings = array_unique( $groupings );
         foreach ($groupings as $group){
             $row->reorder('catid = '.(int) $group);
         }
         return true;
     }*/

    function saveOrder(&$cid)
    {
        global $mainframe;
        //$scope 		= JRequest::getCmd( 'scope' );
        $db  = JFactory::getDBO();
        $row = $this->getTable();

        $total = count($cid);
        $order = JRequest::getVar('order', array(0), 'post', 'array');
        JArrayHelper::toInteger($order, array(0));

        // update ordering values
        for ($i = 0; $i < $total; $i++)
        {
            $row->load((int)$cid[$i]);
            if ($row->ordering != $order[$i])
            {
                $row->ordering = $order[$i];
                if (!$row->store())
                {
                    JError::raiseError(500, $db->getErrorMsg());
                }
            }
        }
        $row->reorder();
        return true;
        //$msg 	= JText::_('COM_REDSHOP_NEW_ORDERING_SAVED' );
        //$mainframe->redirect( 'index.php?option=com_sections&scope=content', $msg );
    }

    /**
     * Method to get max ordering
     *
     * @access public
     * @return boolean
     */
    function MaxOrdering()
    {
        $query = "SELECT (max(ordering)+1) FROM " . $this->_table_prefix . "manufacturer";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    /**
     * Method to move
     *
     * @access  public
     * @return  boolean True on success
     * @since   0.9
     */
    function move($direction)
    {
        $row = JTable::getInstance('manufacturer_detail', 'Table');
        if (!$row->load($this->_id))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->move($direction))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }
}
