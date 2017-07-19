<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\File\Parser;

defined('_JEXEC') or die;

/**
 * Excel parser
 *
 * @package     Redshop\File\Parser
 *
 * @since       2.0.7
 */
class Excel
{
	/**
	 * @var    \Excel
	 *
	 * @since  2.0.7
	 */
	private $phpExcel = null;

	/**
	 * Parser constructor.
	 *
	 * @param   \Excel  $phpExcel  PhpExcel object
	 *
	 * @since  2.0.7
	 */
	public function __construct($phpExcel)
	{
		$this->phpExcel = $phpExcel;
	}

	/**
	 * Magic method for phpExcel object
	 *
	 * @param   string  $name       Method
	 * @param   array   $arguments  Parameters
	 *
	 * @return  mixed
	 *
	 * @since   2.0.7
	 */
	public function __call($name, $arguments)
	{
		return call_user_func_array(array($this->phpExcel, $name), $arguments);
	}

	/**
	 * @param   string  $filePath  File path
	 * @param   string  $separator Separator
	 *
	 * @return  boolean|Excel
	 *
	 * @since   2.0.7
	 */
	public static function load($filePath, $separator = ',')
	{
		if (!\JFile::exists($filePath))
		{
			return false;
		}

		$ext = strtolower(\JFile::getExt($filePath));

		// Specific case for CSV we'll provide delimiter
		if ($ext == 'csv')
		{
			$reader = \PHPExcel_IOFactory::createReader('CSV');
			$reader->setDelimiter($separator);

			return new \Redshop\File\Parser\Excel($reader->load($filePath));
		}

		// @TODO Verify extension for different library
		return new \Redshop\File\Parser\Excel(\PHPExcel_IOFactory::load($filePath));
	}

	/**
	 * Create new phpExcel
	 *
	 * @since  2.0.7
	 */
	public function create ()
	{
		$this->phpExcel = new  \PHPExcel();
	}

	/**
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	public function getHeaderArray()
	{
		return $this->phpExcel->getActiveSheet()->rangeToArray('A1:' . $this->countColumns() . '1');
	}

	/**
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	public function getDataArray()
	{
		return $this->phpExcel->getActiveSheet()->rangeToArray('A2:' . $this->countColumns() . $this->countRows());
	}

	/**
	 *
	 * @return   integer
	 *
	 * @since    2.0.7
	 */
	public function countRows()
	{
		return (int) $this->phpExcel->setActiveSheetIndex(0)->getHighestRow();
	}

	/**
	 *
	 * @return   string
	 *
	 * @since    2.0.7
	 */
	public function countColumns()
	{
		return $this->phpExcel->setActiveSheetIndex(0)->getHighestColumn();
	}

	/**
	 * @param   string  $cell
	 * @param   string  $value
	 *
	 *
	 * @since   2.0.7
	 */
	protected function writeCell($cell, $value)
	{
		$this->phpExcel->getActiveSheet()->setCellValue($cell, $value);
	}

	/**
	 * @param   array  $headerArray
	 *
	 *
	 * @since   2.0.7
	 */
	public function writeHeader($headerArray)
	{
		// Prepare headers
		$arrayOfColumns = range('A', 'Z');

		// Write header
		foreach ($headerArray as $index => $value)
		{
			$this->writeCell($arrayOfColumns[$index] . '1', $value);
		}
	}

	/**
	 * Write array to excel rows
	 *
	 * @param   array  $dataArray
	 *
	 *
	 * @since   2.0.7
	 */
	public function writeData($dataArray, $startRow = 2)
	{
		// Prepare headers
		$arrayOfColumns = range('A', 'Z');

		foreach ($dataArray as $data)
		{
			foreach ($data as $index => $columnData)
			{
				$columnName = $arrayOfColumns[$index];
				$this->writeCell($columnName . $startRow, $columnData);
			}

			$startRow++;
		}
	}

	/**
	 * Save to file
	 *
	 * @param   string  $toFile
	 *
	 * @return  mixed
	 *
	 * @since   2.0.7
	 */
	public function saveToFile($toFile)
	{
		$ext = strtoupper(\JFile::getExt($toFile));
		$objWriter = \PHPExcel_IOFactory::createWriter($this->phpExcel, $ext);

		return $objWriter->save($toFile);
	}
}
