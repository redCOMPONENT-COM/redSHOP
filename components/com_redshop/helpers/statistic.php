<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class statistic
{
    public $_table_prefix = null;

    public $_db = null;

    function statistic()
    {
        $this->_table_prefix = '#__' . TABLE_PREFIX . '_';
        $this->_db           = & JFactory :: getDBO();
        statistic::reshop_visitors();
        statistic::reshop_pageview();
    }

    function reshop_visitors()
    {
        $sid  = session_id();
        $user = & JFactory::getUser();

        $q = "SELECT * FROM " . $this->_table_prefix . "siteviewer " . "WHERE session_id = '" . $sid . "'";
        $this->_db->setQuery($q);
        $data = $this->_db->loadObjectList();
        $date = time();
        if (!count($data))
        {
            $query = "INSERT INTO " . $this->_table_prefix . "siteviewer " . "(session_id, user_id, created_date) " . "VALUES ('" . $sid . "', '" . $user->id . "','" . $date . "')";
            $this->_db->setQuery($query);
            if ($this->_db->query())
            {
                return true;
            }
        }
    }

    function reshop_pageview()
    {
        $sid     = session_id();
        $user    = & JFactory::getUser();
        $view    = JRequest::getVar('view');
        $section = "";

        switch ($view)
        {
            case "product":
                $section   = $view;
                $sectionid = JRequest::getVar('pid');
                break;
            case "category":
                $section   = $view;
                $sectionid = JRequest::getVar('cid');
                break;
            case "manufacturers":
                $section   = $view;
                $sectionid = JRequest::getVar('mid');
                break;
        }

        if ($section != "")
        {
            $q = "SELECT * FROM " . $this->_table_prefix . "pageviewer " . "WHERE session_id = '" . $sid . "' " . "AND section='" . $view . "' " . "AND section_id='" . $sectionid . "' ";
            $this->_db->setQuery($q);
            $data = $this->_db->loadObjectList();
            $date = time();

            $hit = count($data) + 1;
            if (!count($data))
            {
                $query = "INSERT INTO " . $this->_table_prefix . "pageviewer " . "(session_id, user_id, section, section_id, created_date) " . "VALUES ('" . $sid . "','" . $user->id . "','" . $view . "','" . $sectionid . "','" . $date . "')";
                $this->_db->setQuery($query);
                if ($this->_db->query())
                {
                    return true;
                }
            }
        }
    }
}

$statistic = new statistic();?>
