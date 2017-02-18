<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Plugin;

defined('_JEXEC') or die;

/**
 * Abstract class for import plugin
 *
 * @since  2.0.3
 */
class AbstractImportPlugin extends \JPlugin
{
	/**
	 * @var  string
	 */
	protected $separator = ',';

	/**
	 * @var string
	 */
	protected $folder = '';

	/**
	 * @var string
	 */
	protected $encoding = 'UTF-8';

	/**
	 * @var int
	 */
	protected $maxLine = 1;

	/**
	 * @var  \JDatabaseDriver
	 */
	protected $db;

	/**
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * @var string
	 */
	protected $nameKey = 'name';

	/**
	 * List of columns for encoding UTF8
	 *
	 * @var array
	 */
	protected $encodingColumns = array();

	/**
	 * List of columns for number format
	 *
	 * @var array
	 */
	protected $numberColumns = array();

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                              Recognized key values include 'name', 'group', 'params', 'language'
	 *                              (this list is not meant to be comprehensive).
	 *
	 * @since   2.0.3
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
		$this->db = \JFactory::getDbo();
	}

	/**
	 * Event run when upload file success.
	 *
	 * @param   string  $plugin  Plugin name.
	 * @param   array   $file    File data in array format.
	 * @param   array   $data    Other input data.
	 *
	 * @return  mixed            Array of data (file path, lines) if success. False otherwise.
	 *
	 * @since   2.0.3
	 */
	public function onUploadFile($plugin = '', $file = array(), $data = array())
	{
		if (empty($plugin) || $plugin != $this->_name || empty($file))
		{
			return null;
		}

		if (!empty($data) && !empty($data['separator']))
		{
			$this->separator = $data['separator'];
		}

		$this->folder = md5(time());

		if (\JFolder::exists($this->getPath()))
		{
			\JFolder::delete($this->getPath());
		}

		\JFolder::create($this->getPath() . '/' . $this->folder);

		if (!\JFile::move($file['tmp_name'], $this->getPath() . '/' . $file['name']))
		{
			return false;
		}

		$result = array('folder' => $this->folder, 'lines' => $this->countLines($this->getPath() . '/' . $file['name']));

		$this->splitFiles($this->getPath() . '/' . $file['name']);

		return $result;
	}

	/**
	 * Method for count lines of an specific file.
	 *
	 * @param   string  $path  File path.
	 *
	 * @return  int            Lines of file.
	 *
	 * @since  1.2.1
	 */
	public function countLines($path)
	{
		$count = 0;

		$handle = fopen($path, "r");

		while (!feof($handle))
		{
			if (fgets($handle) !== false)
			{
				$count++;
			}
		}

		fclose($handle);

		return $count;
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
		$files          = \JFolder::files($this->getPath() . '/' . $this->folder, '.', true);
		$result         = new \stdClass;
		$result->status = 0;
		$result->data   = array();

		if (empty($files))
		{
			\JFolder::delete($this->getPath() . '/' . $this->folder);

			return $result;
		}

		$file   = array_shift($files);
		$handle = fopen($this->getPath() . '/' . $this->folder . '/' . $file, 'r');
		$header = fgetcsv($handle, null, $this->separator, '"');

		while ($data = fgetcsv($handle, null, $this->separator, '"'))
		{
			$table = $this->getTable();

			// Do mapping data to table.
			$data = $this->processMapping($header, $data);

			// Do convert encoding.
			$this->doEncodingData($data);

			// Do format number.
			$this->doFormatNumber($data);

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
			}

			$result->data[] = $rowResult;
		}

		fclose($handle);
		unlink($this->getPath() . '/' . $this->folder . '/' . $file);

		$result->status = 1;

		return $result;
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
	 * Method for get absolute path of temporary folder for import.
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public function getPath()
	{
		return JPATH_ROOT . '/tmp/redshop/import/' . $this->_name;
	}

	/**
	 * Method for split uploaded file to smaller files.
	 *
	 * @param   string  $file  Path of file.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.3
	 */
	public function splitFiles($file)
	{
		if (empty($file) || !\JFile::exists($file))
		{
			return false;
		}

		$handler = fopen($file, 'r');
		$rows    = array();

		while ($row = fgetcsv($handler, null, $this->separator))
		{
			$rows[] = $row;
		}

		fclose($handler);

		$headers = array_shift($rows);
		$rows    = array_chunk($rows, $this->maxLine);
		$fileExt = \JFile::getExt($file);

		// Remove old file
		unlink($file);

		foreach ($rows as $index => $fileRows)
		{
			$fileHandle = fopen($this->getPath() . '/' . $this->folder . '/' . ($index + 1) . '.' . $fileExt, 'w');

			// Write headers
			fwrite($fileHandle, '"' . implode('"' . $this->separator . '"', $headers) . '"' . "\n");

			foreach ($fileRows as $row)
			{
				// Add slash for data
				foreach ($row as $index => $value)
				{
					$row[$index] = addslashes($value);
				}

				fwrite($fileHandle, '"' . implode('"' . $this->separator . '"', $row) . '"' . "\n");
			}

			fclose($fileHandle);
		}

		return true;
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
}
