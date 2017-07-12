<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Category Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerCategory extends RedshopController
{
	/**
	 *  Method to Export XML file
	 *
	 * @return void
	 */
	public function download()
	{
		$filename = JRequest::getVar('file', '', 'request', 'string');
		$db       = JFactory::getDbo();
		$this->_table_prefix = "#__redshop_";

		session_cache_limiter('public');

		// To avoid an error notice of an undefined index.
		if (empty($_SERVER['HTTP_REFERER']))
		{
			$_SERVER['HTTP_REFERER'] = 'NoRef';
		}

		if (empty($_SERVER['REMOTE_ADDR']))
		{
			return false;
		}

		$query = "SELECT x.* FROM " . $this->_table_prefix . "xml_export AS x "
			. "WHERE x.published=1 "
			. "AND x.filename='" . $filename . "' ";
		$db->setQuery($query);
		$data = $db->loadObject();

		if (count($data) > 0)
		{
			if (!$data->use_to_all_users && $_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR'])
			{
				$query = "SELECT x.*,xl.*,xi.* FROM " . $this->_table_prefix . "xml_export AS x "
					. "LEFT JOIN " . $this->_table_prefix . "xml_export_log AS xl ON x.xmlexport_id=xl.xmlexport_id "
					. "LEFT JOIN " . $this->_table_prefix . "xml_export_ipaddress AS xi ON x.xmlexport_id=xi.xmlexport_id "
					. "WHERE x.published=1 "
					. "AND (x.filename=" . $db->quote((string) $filename) . " "
					. "OR xl.xmlexport_filename=" . $db->quote((string) $filename) . ") "
					. "AND xi.access_ipaddress=" . $db->quote((string) $_SERVER['REMOTE_ADDR']) . " "
					. "ORDER BY xl.xmlexport_date DESC ";
				$db->setQuery($query);
				$data = $db->loadObject();

				if (count($data) <= 0)
				{
					echo $msg = JText::_('COM_REDSHOP_YOU_ARE_NOT_AUTHORIZED_TO_ACCESS');

					return false;
				}
			}
		}
		else
		{
			echo $msg = JText::_('COM_REDSHOP_XMLFILE_IS_UNPUBLISHED');

			return false;
		}

		// Clean them variables boys  (always clean variables at the start of your script to prevent injection attacks. Always limit input to expected chars and patterns.)
		if (preg_match('/^([A-Za-z0-9.?=_\-\/:\s(%20)]{1,255})$/', stripslashes($_SERVER['HTTP_REFERER']), $matchref))
		{
			$tempvar = $matchref[0];
		}
		else
		{
			$tempvar = 'NoRef';
		}

		define('HTTP_REF', $tempvar);

		if (preg_match('/^([0-9.]{7,24})$/', stripslashes($_SERVER['REMOTE_ADDR']), $matchadd))
		{
			$tempvar = $matchadd[0];
		}
		else
		{
			$tempvar = '1.1.1.1';
		}

		// Required for IE, otherwise Content-disposition is ignored
		if (ini_get('zlib.output_compression'))
		{
			ini_set('zlib.output_compression', 'Off');
		}

		$filepath = '#';

		if ($filename != "")
		{
			$filepath = JPATH_COMPONENT_SITE . "/assets/xmlfile/export/" . $filename;

			if (!JFile::exists($filepath))
			{
				JError::raiseError(500, "Oops. File not found");
				JFactory::getApplication()->close();
			}
		}
		else
		{
			JError::raiseError(500, "File name not specified");
		}

		session_write_close();

		// IE Bug in download name workaround
		if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT']))
		{
			try
			{
				ini_set('zlib.output_compression', 'Off');
			}
			catch (Exception $ex)
			{
				JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'error');
			}
		}

		if (!$this->downloadFile($filepath))
		{
			JError::raiseError('', 'The file transfer failed');
		}

		die();
	}

	/**
	 * Logic for download
	 *
	 * @param   string  $fil  path
	 * @param   null    $p    null variable not used
	 *
	 * @return bool
	 */
	public function downloadFile($fil, $p = null)
	{
		ob_clean();

		if (connection_status() != 0)
		{
			return (false);
		}

		$fn = basename($fil);
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: " . gmdate("D, d M Y H:i:s", mktime(date("H") + 2, date("i"), date("s"), date("m"), date("d"), date("Y"))) . " GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Content-Transfer-Encoding: binary");

		// TODO:  Not sure of this is working
		if (function_exists('mime_content_type'))
		{
			$ctype = mime_content_type($fil);
		}
		elseif (function_exists('finfo_file'))
		{
			$finfo = finfo_open(FILEINFO_MIME);
			$ctype = finfo_file($finfo, $fil);
			finfo_close($finfo);
		}
		else
		{
			$ctype = "application/octet-stream";
		}

		header('Content-Type: ' . $ctype);

		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
		{
			// Workaround for IE filename bug with multiple periods / multiple dots in filename
			// that adds square brackets to filename - eg. setup.abc.exe becomes setup[1].abc.exe
			$iefilename = preg_replace('/\./', '%2e', $fn, substr_count($fn, '.') - 1);
			header("Content-Disposition: attachment; filename=\"$iefilename\"");
		}
		else
		{
			header("Content-Disposition: attachment; filename=\"$fn\"");
		}

		header("Accept-Ranges: bytes");

		// Default to begining of file
		$range = 0;

		// @ToDo make the download speed configurable
		$size = filesize($fil);

		// Check if http_range is set. If so, change the range of the download to complete.
		if (isset($_SERVER['HTTP_RANGE']))
		{
			list($a, $range) = explode("=", $_SERVER['HTTP_RANGE']);
			str_replace($range, "-", $range);
			$size2 = $size - 1;
			$new_length = $size - $range;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range$size2/$size");
		}
		else
		{
			$size2 = $size - 1;
			header("HTTP/1.0 200 OK");
			header("Content-Range: bytes 0-$size2/$size");
			header("Content-Length: " . $size);
		}

		// Check to ensure it is not an empty file so the feof does not get stuck in an infinte loop.
		if ($size == 0)
		{
			JError::raiseError(500, 'ERROR.ZERO_BYE_FILE');
			JFactory::getApplication()->close();
		}

		if (version_compare(PHP_VERSION, '5.3.0', '<'))
		{
			// Disable magic quotes for older version of php
			set_magic_quotes_runtime(0);
		}

		// We should check to ensure the file really exits to ensure feof does not get stuck in an infite loop, but we do so earlier on, so no need here.
		$fp = fopen("$fil", "rb");

		// Go to the start of missing part of the file
		fseek($fp, $range);

		if (function_exists("set_time_limit"))
			set_time_limit(0);

		while (!feof($fp) && connection_status() == 0)
		{
			// Reset time limit for big files
			if (function_exists("set_time_limit"))
			{
				set_time_limit(0);
			}

			print(fread($fp, 1024 * 8));
			flush();
			ob_flush();
		}

		sleep(1);
		fclose($fp);

		return ((connection_status() == 0) and !connection_aborted());
	}

	/**
	 * Autofill city name
	 *
	 * @return string
	 */
	public function autofillcityname()
	{
		$db = JFactory::getDbo();
		ob_clean();
		$mainzipcode = JRequest::getString('q', '');
		$sel_zipcode = "select city_name from #__redshop_zipcode where zipcode='" . $mainzipcode . "'";
		$db->setQuery($sel_zipcode);
		echo $db->loadResult();
		JFactory::getApplication()->close();
	}

	/**
	 * Generate XML file.
	 *
	 * @return void
	 */
	public function generateXMLExportFile()
	{
		$app      = JFactory::getApplication();
		$exportId = $app->input->getInt('xmlexport_id');

		if ($exportId)
		{
			$xmlHelper = new xmlHelper;
			$xmlHelper->writeXMLExportFile($exportId);
			$row = $xmlHelper->getXMLExportInfo($exportId);
			$link = JRoute::_(JURI::root() . 'index.php?option=com_redshop&view=category&tmpl=component&task=download&file=' . $row->filename);
			$app->redirect($link);
		}
	}
}
