<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Plugin\Export;

defined('_JEXEC') or die;

/**
 * Abstract class for export plugin
 *
 * @since  2.0.3
 */
class AbstractBase extends \Redshop\Plugin\AbstractBase
{
	/**
	 * @var  string
	 *
	 * @since  2.0.3
	 */
	protected $separator = ',';

	/**
	 * @var  \JDatabaseDriver
	 *
	 * @since  2.0.3
	 */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);

		$this->db = \JFactory::getDbo();
	}

	/**
	 * Method for get path of temporary file.
	 *
	 * @return  string  Path of temporary file.
	 *
	 * @since  2.0.3
	 */
	protected function getFilePath()
	{
		return JPATH_ROOT . '/tmp/redshop/export/product/redshop_' . $this->_name . '.csv';
	}

	/**
	 * Method for write data into file.
	 *
	 * @param   array     $row      Array of data.
	 * @param   string    $mode     Mode for open file.
	 * @param   resource  &$handle  Resource handle if necessary.
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
			$fileHandle = fopen($this->getFilePath(), $mode);
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
	 * @param   array  &$data  Array of data.
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
	 * @return int
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
	 * @param   int  $start  Start row for write.
	 * @param   int  $limit  Limit for row.
	 *
	 * @return array|mixed
	 *
	 * @since  2.0.3
	 */
	protected function getData($start, $limit)
	{
		$query = $this->getQuery();
		$query->setLimit($limit, $start);
		$data  = $this->db->setQuery($query)->loadObjectList();

		$this->processData($data);

		return $data;
	}

	/**
	 * Method for get headers data.
	 *
	 * @return array|bool
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
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	protected function downloadFile()
	{
		/* Start output to the browser */
		if (preg_match('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
		{
			$UserBrowser = "Opera";
		}
		elseif (preg_match('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
		{
			$UserBrowser = "IE";
		}
		else
		{
			$UserBrowser = '';
		}

		$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

		/* Clean the buffer */
		ob_clean();

		header('Content-Type: ' . $mime_type);
		header('Content-Encoding: UTF-8');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		if (!\JFile::exists($this->getFilePath()))
		{

		}

		$filename = basename($this->getFilePath());

		if ($UserBrowser == 'IE')
		{
			header('Content-Disposition: inline; filename="' . $filename . '"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}
		else
		{
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Pragma: no-cache');
		}

		// Converting
		$phpExcel = \Redshop\File\Parser\Excel::load($this->getFilePath());
		// Generate temporary file for exporting;
		$toFile = JPATH_ROOT . '/tmp/redshop/export/product/' . \Redshop\String\Helper::getUserRandomString();
		$phpExcel->saveToFile($toFile , 'Excel2007');

		readfile($toFile);

		// Clean up file.
		JFile::delete($this->getFilePath());
		JFile::delete($toFile);
	}

	/**
	 * Method for exporting data.
	 *
	 * @param   int  $start  Start row for write.
	 * @param   int  $limit  Limit for row.
	 *
	 * @return  int
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

		$handle = fopen($this->getFilePath(), 'a');

		foreach ($data as $item)
		{
			$this->writeData((array) $item, '', $handle);
		}

		fclose($handle);

		return 1;
	}
}
