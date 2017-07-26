<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\File\Parser;

use \PhpOffice\PhpSpreadsheet;

defined('_JEXEC') or die;

/**
 * Excel parser class
 *
 * @package     Redshop\File\Parser
 *
 * @since       2.0.7
 */
class Excel
{
	/**
	 * @var    PhpSpreadsheet\Spreadsheet
	 *
	 * @since  2.0.7
	 */
	private $spreadsheet = null;

	/**
	 * Parser constructor.
	 *
	 * @param   PhpSpreadsheet\Spreadsheet  $spreadsheet  Spreadsheet object
	 *
	 * @since   2.0.7
	 */
	public function __construct($spreadsheet = null)
	{
		if ($spreadsheet === null)
		{
			$spreadsheet = new PhpSpreadsheet\Spreadsheet;
		}

		$this->spreadsheet = $spreadsheet;
	}

	/**
	 * Magic method to call spreadsheet method
	 *
	 * @param   string  $name      Method
	 * @param   array   $arguments Args
	 *
	 * @return mixed
	 *
	 * @since   2.0.7
	 */
	public function __call($name, $arguments)
	{
		return call_user_func_array(array($this->spreadsheet, $name), $arguments);
	}

	/**
	 * @param   string  $filePath   File path to load
	 * @param   string  $separator  Separator
	 *
	 * @return  boolean|$this
	 *
	 * @since   2.0.7
	 */
	public static function load($filePath, $separator = ',')
	{
		// Return blank Excel object
		if (!\JFile::exists($filePath))
		{
			return new Excel;
		}

		$ext = strtolower(\JFile::getExt($filePath));

		// Specific case for CSV we'll provide delimiter
		if ($ext == 'csv')
		{
			$reader = PhpSpreadsheet\IOFactory::createReader('Csv');
			$reader->setDelimiter($separator);

			return new Excel($reader->load($filePath));
		}

		return new Excel(PhpSpreadsheet\IOFactory::load($filePath));
	}

	/**
	 * Reset current Spreadsheet
	 *
	 * @return  $this
	 *
	 * @since  2.0.7
	 */
	public function reset()
	{
		$this->spreadsheet = new PhpSpreadsheet\Spreadsheet;

		return $this;
	}

	/**
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	public function getHeaderArray()
	{
		return $this->spreadsheet->getActiveSheet()->rangeToArray('A1:' . $this->countColumns() . '1');
	}

	/**
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	public function getDataArray()
	{
		return $this->spreadsheet->getActiveSheet()->rangeToArray('A2:' . $this->countColumns() . $this->countRows());
	}

	/**
	 *
	 * @return   integer
	 *
	 * @since    2.0.7
	 */
	public function countRows()
	{
		return (int) $this->spreadsheet->setActiveSheetIndex(0)->getHighestRow();
	}

	/**
	 *
	 * @return   string
	 *
	 * @since    2.0.7
	 */
	public function countColumns()
	{
		return $this->spreadsheet->setActiveSheetIndex(0)->getHighestColumn();
	}

	/**
	 * Append new row
	 *
	 * @param   array  $data  Data for append
	 * @param   int    $row   Init row
	 *
	 * @return  $this
	 *
	 * @since   2.0.7
	 */
	public function appendRow($data, $row = 0)
	{
		$index = 1;
		$row   = $this->countRows() + 1;

		foreach ($data as $key => $value)
		{
			$column = PhpSpreadsheet\Cell::stringFromColumnIndex($index);
			$this->writeCell($column . $row, $value);
			$index++;
		}

		return $this;
	}

	/**
	 * @param   string  $cell   Cell coordinate
	 * @param   string  $value  Value
	 *
	 * @return  $this
	 *
	 * @since   2.0.7
	 */
	public function writeCell($cell, $value)
	{
		$this->spreadsheet->getActiveSheet()->setCellValue($cell, $value);

		return $this;
	}

	/**
	 * @param   array  $headerArray  Array of header
	 *
	 * @return  $this
	 *
	 * @since   2.0.7
	 */
	public function writeHeader($headerArray)
	{
		// Write header
		foreach ($headerArray as $index => $value)
		{
			$this->writeCell(PhpSpreadsheet\Cell::stringFromColumnIndex($index) . '1', $value);
		}

		return $this;
	}

	/**
	 * Write array to excel rows
	 *
	 * @param   array $dataArray  Data for writing
	 * @param   int   $startRow   Init row
	 *
	 * @return  $this
	 *
	 * @since   2.0.7
	 */
	public function writeData($dataArray, $startRow = 2)
	{
		foreach ($dataArray as $data)
		{
			foreach ($data as $index => $columnData)
			{
				$this->writeCell(PhpSpreadsheet\Cell::stringFromColumnIndex($index) . $startRow, $columnData);
			}

			$startRow++;
		}

		return $this;
	}

	/**
	 * Write to physical file
	 *
	 * @param   string  $toFile     File path to write
	 * @param   string  $type       File format
	 * @param   string  $separator  Separator for CSV
	 *
	 * @return  boolean
	 *
	 * @since   2.0.7
	 */
	public function saveToFile($toFile, $type, $separator = ',')
	{
		$pathInfo = pathinfo($toFile);

		if (!\JFolder::exists($pathInfo['dirname']))
		{
			\JFolder::create($pathInfo['dirname']);
		}

		// For CSV by default it's using ',' for separator
		$objWriter = PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, ucfirst($type));

		// Try to set separator if possible
		if (method_exists($objWriter, 'setDelimiter'))
		{
			$objWriter->setDelimiter($separator);
		}

		return $objWriter->save($toFile);
	}
}
