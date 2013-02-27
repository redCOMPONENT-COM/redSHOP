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

class Redaccesslevel
{
    /**
     * define default path
     *
     */
    public function __construct()
    {
        $this->_table_prefix = '#__redshop_';
    }

    public function checkaccessofuser($group_id)
    {
        $db    = JFactory::getDBO();
        $query = "SELECT  section_name FROM " . $this->_table_prefix . "accessmanager" . " WHERE `view`=1 and `gid` = '" . $group_id . "'";
        $db->setQuery($query);
        $access_section = $db->loadResultArray();
        return $access_section;
    }

    public function checkgroup_access($view, $task, $group_id)
    {

        if ($task == '')
        {
            $this->getgroup_access($view, $group_id);
        }
        else
        {
            if ($task == 'add')
            {
                $this->getgroup_accesstaskadd($view, $task, $group_id);
            }
            else if ($task == 'edit')
            {
                $this->getgroup_accesstaskedit($view, $task, $group_id);
            }
            else if ($task == 'remove')
            {
                $this->getgroup_accesstaskdelete($view, $task, $group_id);
            }
        }
    }

    public function getgroup_access($view, $group_id)
    {
        $app = JFactory::getApplication();

        $db    = JFactory::getDBO();
        $query = "SELECT view  FROM " . $this->_table_prefix . "accessmanager" . " WHERE `section_name` = '" . $view . "' AND `gid` = '" . $group_id . "'";
        $db->setQuery($query);
        $accessview = $db->loadResult();

        if ($accessview != 1)
        {
            $msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
            $app->redirect($_SERVER['HTTP_REFERER'], $msg);
        }
    }

    public function getgroup_accesstaskadd($view, $task, $group_id)
    {
        $app   = JFactory::getApplication();
        $db    = JFactory::getDBO();
        $query = "SELECT *  FROM  " . $this->_table_prefix . "accessmanager" . " WHERE `section_name` = '" . str_replace('_detail', '', $view) . "' AND `gid` = '" . $group_id . "'";
        $db->setQuery($query);
        $accessview = $db->loadObjectList();

        if ($accessview[0]->add != 1)
        {
            $msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
            $app->redirect($_SERVER['HTTP_REFERER'], $msg);
        }
    }

    public function getgroup_accesstaskedit($view, $task, $group_id)
    {
        $app   = JFactory::getApplication();
        $db    = JFactory::getDBO();
        $query = "SELECT *  FROM  " . $this->_table_prefix . "accessmanager" . " WHERE `section_name` = '" . str_replace('_detail', '', $view) . "' AND `gid` = '" . $group_id . "'";
        $db->setQuery($query);
        $accessview = $db->loadObjectList();

        if ($accessview[0]->edit != 1)
        {
            $msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
            $app->redirect($_SERVER['HTTP_REFERER'], $msg);
        }
    }

    public function getgroup_accesstaskdelete($view, $task, $group_id)
    {
        $app   = JFactory::getApplication();
        $db    = JFactory::getDBO();
        $query = "SELECT *  FROM  " . $this->_table_prefix . "accessmanager" . " WHERE `section_name` = '" . str_replace('_detail', '', $view) . "' AND `gid` = '" . $group_id . "'";
        $db->setQuery($query);
        $accessview = $db->loadObjectList();

        if ($accessview[0]->delete != 1)
        {
            $msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
            $app->redirect($_SERVER['HTTP_REFERER'], $msg);
        }
    }
}
