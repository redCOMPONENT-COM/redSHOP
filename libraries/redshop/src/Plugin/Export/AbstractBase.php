<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Plugin\Export;

use Redshop\Ajax\Response;
use Redshop\File\Helper;
use Redshop\Plugin\ImportExport;

defined('_JEXEC') or die;

/**
 * Abstract class for export plugin
 *
 * @since  2.0.3
 */
class AbstractBase extends ImportExport
{
	/**
	 * Limit records percent export
	 *
	 * @var    integer
	 * @since  2.0.7
	 */
	protected $limit = 150;

	/**
	 * Constructor
	 *
	 * @param   object  $subject     The object to observe
	 * @param   array   $config      An optional associative array of configuration settings.
	 *                              Recognized key values include 'name', 'group', 'params', 'language'
	 *                              (this list is not meant to be comprehensive).
	 *
	 * @since   2.0.3
	 */
	public function __construct($subject, array $config = array())
	{
		parent::__construct($subject, $config);

		\JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models');
	}

	/**
	 * Fetch config and respond HTML of config to render on page
	 *
	 * @param   string  $html  HTML data for responding
	 *
	 * @return  void
	 *
	 * @since   2.0.7
	 */
	protected function config($html = '')
	{
		$response = new Response;
		$response->addHtml($html)->success()->respond();
	}

	/**
	 * Start export process
	 * Get headers and write to file
	 *
	 * @return  void
	 *
	 * @since  2.0.7
	 */
	protected function start()
	{
		$headers = $this->getHeader();

		// Write headers to csv file
		if (!empty($headers))
		{
			// Init temporary folder
			\Redshop\Filesystem\Folder\Helper::create($this->getTemporaryFolder());
			$this->writeData($headers, 'w+');
		}

		$response = new Response;
		$data = new \stdClass;

		// Total rows for exporting
		$data->rows = (int) $this->getTotal();

		// Limit rows percent request
		$data->limit = $this->limit;
		$data->total = ceil($data->rows / $data->limit);


		$response->setData($data)->success()->respond();
	}

	/**
	 * Export process
	 * Get data and write to file
	 *
	 * @return  void
	 *
	 * @since  2.0.7
	 */
	protected function export()
	{
		$input = \JFactory::getApplication()->input;
		$this->exporting($input->getInt('from', 0) * $this->limit, $this->limit);

		$response = new Response;
		$response->success()->respond();
	}

	/**
	 * Method for exporting data.
	 *
	 * @param   int $start Start row for write.
	 * @param   int $limit Limit for row.
	 *
	 * @return  integer
	 *
	 * @since  2.0.3
	 */
	protected function exporting($start, $limit)
	{
		$data = $this->getData($start, $limit);

		if (empty($data))
		{
			return 0;
		}

		$handle = fopen($this->getTemporaryFile('export'), 'a');

		foreach ($data as $item)
		{
			$this->writeData((array) $item, '', $handle);
		}

		fclose($handle);
	}

	/**
	 * Method for write data into file.
	 * By default we always write to CSV format to make it faster
	 *
	 * @param   array     $row     Array of data.
	 * @param   string    $mode    Mode for open file.
	 * @param   resource  $handle  Resource handle if necessary.
	 *
	 * @return  boolean      True on success. False otherwise.
	 *
	 * @since  2.0.3
	 */
	protected function writeData($row, $mode = 'a+', &$handle = null)
	{
		if (empty($row))
		{
			return false;
		}

		$separator = \JFactory::getApplication()->input->getString('separator', $this->separator);

		$row = (array) $row;

		foreach ($row as $index => $column)
		{
			$row[$index] = '"' . str_replace('"', '""', $column) . '"';
		}

		if (is_null($handle))
		{
			$fileHandle = fopen($this->getTemporaryFile('export'), $mode);
			fwrite($fileHandle, implode($separator, $row) . "\r\n");
			fclose($fileHandle);
		}
		else
		{
			fwrite($handle, implode($separator, $row) . "\r\n");
		}

		return true;
	}

	/**
	 * Method for do some stuff for data return. (Like image path,...)
	 *
	 * @param   array  $data  Array of data.
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 */
	protected function processData(&$data)
	{
		return;
	}

	/**
	 * Method for get query
	 *
	 * @return \JDatabaseQuery
	 *
	 * @since  2.0.3
	 */
	protected function getQuery()
	{
		return $this->db->getQuery(true);
	}

	/**
	 * Method for get total count of data.
	 *
	 * @return integer
	 *
	 * @since  2.0.3
	 */
	protected function getTotal()
	{
		$query = $this->getQuery();
		$query->clear('select')
			->clear('group')
			->select('COUNT(*)');

		return (int) $this->db->setQuery($query)->loadResult();
	}

	/**
	 * Method for get data.
	 *
	 * @param   int $start Start row for write.
	 * @param   int $limit Limit for row.
	 *
	 * @return array|mixed
	 *
	 * @since  2.0.3
	 */
	protected function getData($start, $limit)
	{
		$query = $this->getQuery();
		$query->setLimit($limit, $start);
		$data = $this->db->setQuery($query)->loadObjectList();

		$this->processData($data);

		return $data;
	}

	/**
	 * Method for get headers data.
	 *
	 * @return array|boolean
	 *
	 * @since  2.0.3
	 */
	protected function getHeader()
	{
		$query = $this->getQuery();
		$data  = $this->db->setQuery($query, 0, 1)->loadObject();

		if (empty($data))
		{
			return false;
		}

		return array_keys((array) $data);
	}

	/**
	 * Method for download file.
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	protected function convertFile()
	{
		$csvExportedFile = $this->getTemporaryFile('export');

		$fileType = \JFactory::getApplication()->input->getString('export_file_type', 'csv');
		$convertFile = $this->getTemporaryFile('export_convert') . '.' . $fileType;

		$phpExcel = $this->loadFile($csvExportedFile);
		$phpExcel->saveToFile($convertFile, $fileType);

		// Delete old CSV file
		\JFile::delete($csvExportedFile);

		$response = new Response;
		$data = new \stdClass;
		$data->filePath = $convertFile;
		$data->fileUrl = str_replace(JPATH_ROOT, trim(\JUri::root(), '/'), $convertFile);

		return $response->setData($data)->success()->respond();
	}
}
