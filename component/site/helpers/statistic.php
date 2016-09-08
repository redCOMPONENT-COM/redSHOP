<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class statistic
{
	protected static $instance = null;

	/**
	 * Returns the productHelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  productHelper  The productHelper object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Add entry to statistics
	 *
	 * @return  void
	 */
	public function track()
	{
		// Only when enabled in configuration
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

		$q = "SELECT * FROM #__redshop_siteviewer "
			. "WHERE session_id = " . $db->quote($sid);
		$db->setQuery($q);
		$data = $db->loadObjectList();
		$date = time();

		if (!count($data))
		{
			$query = "INSERT INTO #__redshop_siteviewer "
				. "(session_id, user_id, created_date) "
				. "VALUES (" . $db->quote($sid) . ", " . (int) $user->id . "," . (int) $date . ")";
			$db->setQuery($query);

			if ($db->execute())
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
			$q = "SELECT * FROM #__redshop_pageviewer "
				. "WHERE session_id = " . $db->quote($sid) . " "
				. "AND section = " . $db->quote($view) . " "
				. "AND section_id = " . (int) $sectionid;
			$db->setQuery($q);
			$data = $db->loadObjectList();
			$date = time();

			if (!count($data))
			{
				$query = "INSERT INTO #__redshop_pageviewer "
					. "(session_id, user_id, section, section_id, created_date) "
					. "VALUES (" . $db->quote($sid) . "," . (int) $user->id . "," . $db->quote($view) . "," . (int) $sectionid . "," . (int) $date . ")";
				$db->setQuery($query);

				if ($db->execute())
				{
					return true;
				}
			}
		}
	}
}
