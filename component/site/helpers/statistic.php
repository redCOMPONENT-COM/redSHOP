<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class statistic
{
	public $_table_prefix = null;

	public $_db = null;

	public function __construct()
	{
		$this->_table_prefix = '#__redshop_';
		$this->_db           = JFactory::getDbo();

		// Add entry to statistics if Statistics is enabled in configuration
		if (STATISTICS_ENABLE)
		{
			$this->reshop_visitors();
			$this->reshop_pageview();
		}
	}

	public function reshop_visitors()
	{
		$db = JFactory::getDbo();

		$sid  = session_id();
		$user = JFactory::getUser();

		$q = "SELECT * FROM " . $this->_table_prefix . "siteviewer "
			. "WHERE session_id = " . $db->quote($sid);
		$this->_db->setQuery($q);
		$data = $this->_db->loadObjectList();
		$date = time();

		if (!count($data))
		{
			$query = "INSERT INTO " . $this->_table_prefix . "siteviewer "
				. "(session_id, user_id, created_date) "
				. "VALUES (" . $db->quote($sid) . ", " . (int) $user->id . "," . (int) $date . ")";
			$this->_db->setQuery($query);

			if ($this->_db->query())
			{
				return true;
			}
		}
	}

	public function reshop_pageview()
	{
		$db      = JFactory::getDbo();
		$sid     = session_id();
		$user    = JFactory::getUser();
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
			$q = "SELECT * FROM " . $this->_table_prefix . "pageviewer "
				. "WHERE session_id = " . $db->quote($sid) . " "
				. "AND section = " . $db->quote($view) . " "
				. "AND section_id = " . (int) $sectionid;
			$this->_db->setQuery($q);
			$data = $this->_db->loadObjectList();
			$date = time();

			$hit = count($data) + 1;

			if (!count($data))
			{
				$query = "INSERT INTO " . $this->_table_prefix . "pageviewer "
					. "(session_id, user_id, section, section_id, created_date) "
					. "VALUES (" . $db->quote($sid) . "," . (int) $user->id . "," . $db->quote($view) . "," . (int) $sectionid . "," . (int) $date . ")";
				$this->_db->setQuery($query);

				if ($this->_db->query())
				{
					return true;
				}
			}
		}
	}
}

$statistic = new statistic;
