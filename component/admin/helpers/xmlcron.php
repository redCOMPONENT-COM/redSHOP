<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$my_path = dirname(__FILE__);
if (file_exists($my_path . "/../../../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../../../configuration.php");
	require_once $my_path . "/../../../../configuration.php";
}
else
{
	die("Joomla Configuration File not found!");
}
$absolute_path = realpath($absolute_path);
// Set up the appropriate CMS framework

define('_JEXEC', 1);
define('JPATH_BASE', $absolute_path);
define('DS', DIRECTORY_SEPARATOR);

// Load the framework
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

// create the mainframe object
$app = JFactory::getApplication();

// Initialize the framework
$app->initialise();
/*** END of Joomla config ***/

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/xmlhelper.php';

class xmlcron
{
	public function xmlcron()
	{
		$this->_table_prefix = '#__redshop_';
		$this->xmlExportFileUpdate();
		$this->xmlImportFileUpdate();
	}

	public function xmlExportFileUpdate()
	{
		$currenttime = time();
		$xmlHelper = new xmlHelper;

		$db = JFactory::getDBO();
		$query = "SELECT * FROM " . $this->_table_prefix . "xml_export AS x "
			. "WHERE x.published=1 "
			. "AND x.auto_sync=1 "
			. "AND x.sync_on_request=0 "
			. "AND x.auto_sync_interval!=0 ";
		$db->setQuery($query);
		$exportlist = $db->loadObjectlist();

		for ($i = 0; $i < count($exportlist); $i++)
		{
			$db = JFactory::getDBO();
			$query = "SELECT * FROM " . $this->_table_prefix . "xml_export_log AS xl "
				. "WHERE xl.xmlexport_id='" . $exportlist[$i]->xmlexport_id . "' "
				. "ORDER BY xl.xmlexport_date DESC ";
			$db->setQuery($query);
			$lastrs = $db->loadObject();

			if (count($lastrs) > 0)
			{
				$difftime = $currenttime - $lastrs->xmlexport_date;
				$hours = $difftime / (60 * 60);

				if ($exportlist[$i]->auto_sync_interval < $hours)
				{
					$xmlHelper->writeXMLExportFile($lastrs->xmlexport_id);
				}
			}
		}
	}

	public function xmlImportFileUpdate()
	{
		$currenttime = time();
		$xmlHelper = new xmlHelper;

		$db = JFactory::getDBO();
		$query = "SELECT * FROM " . $this->_table_prefix . "xml_import AS x "
			. "WHERE x.published=1 "
			. "AND x.auto_sync=1 "
			. "AND x.sync_on_request=0 "
			. "AND x.auto_sync_interval!=0 ";
		$db->setQuery($query);
		$importlist = $db->loadObjectlist();

		for ($i = 0; $i < count($importlist); $i++)
		{
			$db = JFactory::getDBO();
			$query = "SELECT * FROM " . $this->_table_prefix . "xml_import_log AS xl "
				. "WHERE xl.xmlimport_id='" . $importlist[$i]->xmlimport_id . "' "
				. "ORDER BY xl.xmlimport_date DESC ";
			$db->setQuery($query);
			$lastrs = $db->loadObject();

			if (count($lastrs) > 0)
			{
				$difftime = $currenttime - $lastrs->xmlimport_date;
				$hours = $difftime / (60 * 60);

				if ($importlist[$i]->auto_sync_interval < $hours)
				{
					$xmlHelper->importXMLFile($lastrs->xmlimport_id);
				}
			}
		}
	}
}

$xmlcron = new xmlcron;
