<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Plugin\Import;

use Redshop\Ajax\Response;
use Redshop\Filesystem\Folder\Helper;
use Redshop\Plugin\ImportExport;

defined('_JEXEC') or die;

/**
 * Abstract class for import plugin
 *
 * @since  2.0.3
 */
class AbstractBase extends ImportExport
{
	/**
	 * @var string
	 *
	 * @since  2.0.3
	 */
	protected $encoding = 'UTF-8';

	/**
	 * @var string
	 *
	 * @since  2.0.3
	 */
	protected $primaryKey = 'id';

	/**
	 * @var string
	 *
	 * @since  2.0.3
	 */
	protected $nameKey = 'name';

	/**
	 * List of columns for encoding UTF8
	 *
	 * @var array
	 *
	 * @since  2.0.3
	 */
	protected $encodingColumns = array();

	/**
	 * List of columns for number format
	 *
	 * @var array
	 *
	 * @since  2.0.3
	 */
	protected $numberColumns = array();

	/**
	 * List of alias columns. For backward compatibility. Example array('category_id' => 'id')
	 *
	 * @var array
	 *
	 * @since   2.0.6
	 */
	protected $aliasColumns = array();

	/**
	 * Constructor
	 *
	 * @param   object $subject     The object to observe
	 * @param   array  $config      An optional associative array of configuration settings.
	 *                              Recognized key values include 'name', 'group', 'params', 'language'
	 *                              (this list is not meant to be comprehensive).
	 *
	 * @since   2.0.3
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);

		\JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');
	}

	/**
	 * @param   string $html HTML of configuratio
	 *
	 * @since   2.0.7
	 */
	protected function config($html = '')
	{
		$response = new Response;
		$response->addHtml($html)->success()->respond();
	}

	/**
	 *
	 * @return string
	 *
	 * @since  2.0.7
	 */
	protected function import()
	{
		$input           = \JFactory::getApplication()->input;
		$this->encoding  = $input->getString('encoding', 'UTF-8');
		$this->separator = $input->getString('separator', ',');
		$this->folder    = $input->getCmd('folder', '');

		// @TODO Use Response object instead
		return json_encode($this->importing());
	}

	/**
	 * Event run when upload file success.
	 * This event is triggered via import.uploadFile
	 *
	 * @param   string  $plugin  Plugin name.
	 * @param   array   $file    File data in array format.
	 * @param   array   $data    Other input data.
	 *
	 * @return  mixed            Array of data (file path, lines) if success. False otherwise.
	 *
	 * @TODO    Should we execute this method directly via ajax instead request to controller
	 * @since   2.0.3
	 */
	public function onUploadFile($plugin = '', $file = array(), $data = array())
	{
		if (empty($plugin) || $plugin != $this->_name || empty($file))
		{
			return null;
		}

		// Init default separator
		if (!empty($data) && !empty($data['separator']))
		{
			$this->separator = $data['separator'];
		}

		$importDir = $this->getTemporaryFolder();

		// At upload time we'll renew temporary name every upload
		$saveTo    = $importDir . '/' . \Redshop\String\Helper::getUserRandomStringByKey(__FUNCTION__, true);

		// Create temporary directory
		Helper::create($importDir);

		// Try to move upload file to $importDir
		if (!\JFile::move($file['tmp_name'], $saveTo))
		{
			return false;
		}

		// @TODO Should we move splitFiles into another ajax to make it more clearly
		// @TODO File detect before process
		return $this->splitFiles($saveTo);
	}

	/**
	 * Method for split uploaded file to smaller files.
	 *
	 * @param   string  $file  Path of file.
	 *
	 * @return  false|array
	 *
	 * @since   2.0.3
	 */
	public function splitFiles($file)
	{
		if (empty($file) || !\JFile::exists($file))
		{
			return false;
		}

		// Create splitting dir
		$this->folder  = \Redshop\String\Helper::getUserRandomStringByKey(__FUNCTION__, true);
		$workingFolder = $this->getTemporaryFolder() . '/' . $this->folder;

		// Create temporary for this import
		if (!Helper::create($workingFolder))
		{
			return false;
		}

		// Load request file
		$phpExcel = $this->loadFile($file);

		// File load success than go to process
		if (!$phpExcel)
		{
			return false;
		}

		$maxRows       = $phpExcel->countRows();
		$importMaxRows = \Redshop::getConfig()->get('IMPORT_MAX_LINE', 10);

		// Prepare array of return data
		$returnData = array(
			'folder'        => $this->folder,
			// Total rows in file
			'rows'          => $maxRows,
			// Total rows in smaller file which one we'll split into
			'rows_per_file' => ($importMaxRows > $maxRows) ? $maxRows : $importMaxRows,
			// Array of header row
			'header'        => $phpExcel->getHeaderArray()[0],
			// Init total files
			'files'         => 0
		);

		// Get all data as array
		$contentArray = $phpExcel->getDataArray();

		// Split it into smaller
		$smallerContentArray = array_chunk($contentArray, $returnData['rows_per_file']);

		// Create new file
		$phpExcel->reset();

		// And now write to file
		foreach ($smallerContentArray as $index => $contentOfFile)
		{
			$saveToFile = $workingFolder . '/' . ($index + 1) . '.' . $this->defaultExtension;

			// Write header
			$phpExcel->writeHeader($returnData['header']);
			$phpExcel->writeData($contentOfFile);
			$phpExcel->saveToFile($saveToFile, $this->defaultExtension, $this->separator);

			// Increase total files
			$returnData['files'] = $returnData['files'] + 1;
		}

		// Clean up uploaded file
		\JFile::delete($file);

		return $returnData;
	}

	/**
	 * Method for import data.
	 *
	 * @return  mixed
	 *
	 * @since  2.0.3
	 */
	public function importing()
	{
		// Ajax response object
		$response = new Response;

		// Generate working folder
		$workingDir = $this->getTemporaryFolder() . '/' . $this->folder;

		$fileName = \JFactory::getApplication()->input->getInt('index');

		// Get file to process
		$filePath = $workingDir . '/' . $fileName . '.' . $this->defaultExtension;

		if (!\JFile::exists($filePath))
		{
			return $response;
		}

		// Try to parse
		// This file is csv format with default separator ','
		$phpExcel = $this->loadFile($filePath);

		if ($phpExcel === false)
		{
			return $response;
		}

		$header    = $phpExcel->getHeaderArray()[0];
		$dataArray = $phpExcel->getDataArray();
		$table     = $this->getTable();

		foreach ($dataArray as $data)
		{
			$table->reset();

			// Do mapping data to table.
			$data = $this->processMapping($header, $data);

			// Do convert encoding.
			$this->doEncodingData($data);

			// Do format number.
			$this->doFormatNumber($data);

			// Do alias mapping
			$this->doAliasMapping($data);

			$rowResult = new \stdClass;

			if ($this->processImport($table, $data))
			{
				$rowResult->status  = 1;
				$rowResult->message = \JText::sprintf(
					'PLG_REDSHOP_IMPORT_' . strtoupper($this->_name) . '_SUCCESS_IMPORT',
					$data[$this->nameKey]
				);
			}
			else
			{
				$rowResult->status  = 0;
				$rowResult->message = \JText::sprintf(
					'PLG_REDSHOP_IMPORT_' . strtoupper($this->_name) . '_FAIL_IMPORT',
					$data[$this->nameKey]
				);
				$rowResult->message = $table->getError();
			}

			$response->addData($rowResult);
		}

		// Delete processed file;
		\JFile::delete($filePath);

		$response->success()->set('file', $fileName);

		// Try to clean up if this's last one
		$files = \JFolder::files($workingDir, '.', true, false, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'index.html'));

		if (empty($files))
		{
			\JFolder::delete($workingDir);
		}

		return $response;
	}

	/**
	 * Method for get table object.
	 *
	 * @return  \JTable
	 *
	 * @since   2.0.3
	 */
	public function getTable()
	{
		return null;
	}

	/**
	 * Method for get list of properties (column) of an object.
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public function getDataProperties()
	{
		return $this->getTable()->getProperties();
	}

	/**
	 * Process mapping data.
	 *
	 * @param   array  $header  Header array
	 * @param   array  $data    Data array
	 *
	 * @return  array           Mapping data.
	 *
	 * @since   2.0.3
	 */
	public function processMapping($header, $data)
	{
		$data = array_map("trim", $data);

		return array_combine($header, $data);
	}

	/**
	 * Process import data.
	 *
	 * @param   \JTable  $table  Header array
	 * @param   array    $data   Data array
	 *
	 * @return  boolean
	 *
	 * @since   2.0.3
	 */
	public function processImport($table, $data)
	{
		if (array_key_exists($this->primaryKey, $data))
		{
			$table->load($data[$this->primaryKey]);
		}

		if (!$table->bind($data))
		{
			return false;
		}

		if (!$table->store())
		{
			return false;
		}

		return true;
	}

	/**
	 * Method for do encoding utf8 necessary column
	 *
	 * @param   array  &$data  Data.
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 */
	public function doEncodingData(&$data = array())
	{
		if (empty($data))
		{
			return;
		}

		// Do remove strip slashes
		foreach ($data as $index => $value)
		{
			$data[$index] = stripslashes($value);
		}

		// Do encoding column
		if (empty($this->encodingColumns) || !function_exists('mb_convert_encoding'))
		{
			return;
		}

		foreach ($this->encodingColumns as $column)
		{
			if (empty($data[$column]))
			{
				continue;
			}

			$data[$column] = mb_convert_encoding($data[$column], 'UTF-8', $this->encoding);
		}
	}

	/**
	 * Method for do format number an column.
	 *
	 * @param   array  &$data  Data.
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 */
	public function doFormatNumber(&$data = array())
	{
		if (empty($data) || empty($this->numberColumns))
		{
			return;
		}

		foreach ($this->numberColumns as $column)
		{
			if (empty($data[$column]))
			{
				continue;
			}

			$data[$column] = (float) str_replace(',', '.', $data[$column]);
		}
	}

	/**
	 * Method for generate column with alias.
	 *
	 * @param   array  &$data  Data.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function doAliasMapping(&$data = array())
	{
		if (empty($data) || empty($this->aliasColumns))
		{
			return;
		}

		foreach ($this->aliasColumns as $alias => $column)
		{
			if (empty($data[$alias]) || !empty($data[$column]))
			{
				continue;
			}

			$data[$column] = $data[$alias];
		}
	}
}
