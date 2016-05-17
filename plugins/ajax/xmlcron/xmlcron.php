<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  Ajax.xmlcron
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

/**
 * XML import Cron file
 *
 * @since  1.5
 */
class PlgAjaxXmlCron extends JPlugin
{
	/**
	 * Call XML import function on request
	 *
	 * @return  void
	 */
	public function onAjaxXmlCron()
	{
		JLoader::import('redshop.library');

		$this->xmlExportFileUpdate();
		$this->xmlImportFileUpdate();
	}

	/**
	 * Update Exported file
	 *
	 * @return  void
	 */
	public function xmlExportFileUpdate()
	{
		$currenttime = time();
		$xmlHelper   = new xmlHelper;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__redshop_xml_export'))
					->where($db->qn('published') . ' = 1')
					->where($db->qn('auto_sync') . ' = 1')
					->where($db->qn('sync_on_request') . ' = 0')
					->where($db->qn('auto_sync_interval') . ' != 0');

		// Set the query and load the result.
		$db->setQuery($query);
		$exportlist = $db->loadObjectlist();

		for ($i = 0, $n = count($exportlist); $i < $n; $i++)
		{
			$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__redshop_xml_export_log'))
						->where($db->qn('xmlexport_id') . ' = ' . (int) $exportlist[$i]->xmlexport_id)
						->order($db->qn('xmlexport_date') . ' DESC');

			$db->setQuery($query);
			$lastrs = $db->loadObject();

			if (count($lastrs) > 0)
			{
				$difftime = $currenttime - $lastrs->xmlexport_date;
				$hours    = $difftime / (60 * 60);

				if ($exportlist[$i]->auto_sync_interval < $hours)
				{
					$xmlHelper->writeXMLExportFile($lastrs->xmlexport_id);
				}
			}
		}
	}

	/**
	 * Update xml import file
	 *
	 * @return  void
	 */
	public function xmlImportFileUpdate()
	{
		$currenttime = time();
		$xmlHelper   = new xmlHelper;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__redshop_xml_import'))
					->where($db->qn('published') . ' = 1')
					->where($db->qn('auto_sync') . ' = 1')
					->where($db->qn('sync_on_request') . ' = 0')
					->where($db->qn('auto_sync_interval') . ' != 0');
		$db->setQuery($query);
		$importlist = $db->loadObjectlist();

		for ($i = 0, $n = count($importlist); $i < $n; $i++)
		{
			$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__redshop_xml_import_log'))
						->where($db->qn('xmlimport_id') . ' = ' . (int) $importlist[$i]->xmlimport_id)
						->order($db->qn('xmlimport_date') . ' DESC');
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
