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
		$currentTime = time();
		$xmlHelper   = new xmlHelper;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select(
						$db->qn(
							[
								'xmlexport_id', 'filename', 'display_filename',
								'parent_name', 'section_type', 'auto_sync',
								'sync_on_request', 'auto_sync_interval', 'xmlexport_date',
								'xmlexport_filetag', 'element_name', 'published',
								'use_to_all_users', 'xmlexport_billingtag', 'billing_element_name',
								'xmlexport_shippingtag', 'shipping_element_name', 'xmlexport_orderitemtag',
								'orderitem_element_name', 'xmlexport_stocktag', 'stock_element_name',
								'xmlexport_prdextrafieldtag', 'prdextrafield_element_name',
								'xmlexport_on_category'
							]
						)
					)
					->from($db->qn('#__redshop_xml_export'))
					->where($db->qn('published') . ' = 1')
					->where($db->qn('auto_sync') . ' = 1')
					->where($db->qn('sync_on_request') . ' = 0')
					->where($db->qn('auto_sync_interval') . ' != 0');

		// Set the query and load the result.
		$db->setQuery($query);
		$exportList = $db->loadObjectlist();

		for ($i = 0, $n = count($exportList); $i < $n; $i++)
		{
			$query = $db->getQuery(true)
						->select(
							$db->qn(
								[
									'xmlexport_log_id', 'xmlexport_id', 'xmlexport_filename', 'xmlexport_date'
								]
							)
						)
						->from($db->qn('#__redshop_xml_export_log'))
						->where($db->qn('xmlexport_id') . ' = ' . (int) $exportList[$i]->xmlexport_id)
						->order($db->qn('xmlexport_date') . ' DESC');

			$db->setQuery($query);
			$lastResult = $db->loadObject();

			if (count($lastResult) > 0)
			{
				$difftime = $currentTime - $lastResult->xmlexport_date;
				$hours    = $difftime / (60 * 60);

				if ($exportList[$i]->auto_sync_interval < $hours)
				{
					$xmlHelper->writeXMLExportFile($lastResult->xmlexport_id);
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
		$currentTime = time();
		$xmlHelper   = new xmlHelper;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select(
						$db->qn(
							[
								'xmlimport_id', 'filename', 'display_filename',
								'xmlimport_url', 'section_type', 'auto_sync',
								'sync_on_request', 'auto_sync_interval', ' 	override_existing',
								'add_prefix_for_existing', 'xmlimport_date',
								'xmlimport_filetag', 'xmlimport_billingtag', 'xmlimport_shippingtag',
								'xmlimport_orderitemtag', 'xmlimport_stocktag', 'xmlimport_prdextrafieldtag',
								'published', 'element_name', 'billing_element_name', 'xmlexport_orderitemtag',
								'shipping_element_name', 'orderitem_element_name', 'stock_element_name',
								'prdextrafield_element_name', 'xmlexport_billingtag', 'xmlexport_shippingtag'
							]
						)
					)
					->from($db->qn('#__redshop_xml_import'))
					->where($db->qn('published') . ' = 1')
					->where($db->qn('auto_sync') . ' = 1')
					->where($db->qn('sync_on_request') . ' = 0')
					->where($db->qn('auto_sync_interval') . ' != 0');
		$db->setQuery($query);
		$importList = $db->loadObjectlist();

		for ($i = 0, $n = count($importList); $i < $n; $i++)
		{
			$query = $db->getQuery(true)
						->select(
							$db->qn(
								[
									'xmlimport_log_id', 'xmlimport_id', 'xmlimport_filename', 'xmlimport_date'
								]
							)
						)
						->from($db->qn('#__redshop_xml_import_log'))
						->where($db->qn('xmlimport_id') . ' = ' . (int) $importList[$i]->xmlimport_id)
						->order($db->qn('xmlimport_date') . ' DESC');
			$db->setQuery($query);
			$lastResult = $db->loadObject();

			if (count($lastResult) > 0)
			{
				$difftime = $currentTime - $lastResult->xmlimport_date;
				$hours = $difftime / (60 * 60);

				if ($importList[$i]->auto_sync_interval < $hours)
				{
					$xmlHelper->importXMLFile($lastResult->xmlimport_id);
				}
			}
		}
	}
}
