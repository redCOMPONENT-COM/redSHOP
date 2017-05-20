<?php
/**
 * @package     Redshop
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.viewlegacy');

/**
 * A csv view working with a RModelList.
 *
 * @package     Redshop
 * @subpackage  View
 * @since       1.0
 */
abstract class RedshopViewCsv extends JViewLegacy
{
	/**
	 * This is locale for UTF8 support in CSV files.
	 *
	 * @var string
	 */
	public $localeEncoding = 'en_GB.UTF-8';

	/**
	 * Delimiter character for CSV columns
	 *
	 * @var string
	 */
	public $delimiter = ',';

	/**
	 * Enclosure character for CSV columns
	 *
	 * @var string
	 */
	public $enclosure = '"';

	/**
	 * Get the columns for the csv file.
	 *
	 * @return  array  An associative array of column names as key and the title as value.
	 */
	abstract protected function getColumns();

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse.
	 *
	 * @return  mixed         A string if successful, otherwise a Error object.
	 *
	 * @throws  RuntimeException
	 */
	public function display($tpl = null)
	{
		// Get the columns
		$columns = $this->getColumns();

		if (empty($columns))
		{
			throw new RuntimeException(
				sprintf(
					'Empty columns not allowed for the csv view %s',
					get_class($this)
				)
			);
		}

		$model = $this->getModel();

		// For additional filtering and formating if needed
		$model->setState('streamOutput', 'csv');

		// Prepare the items
		$items = $model->getItems();
		$csvLines[0] = $columns;
		$i = 1;

		foreach ($items as $item)
		{
			$csvLines[$i] = array();

			foreach ($columns as $name => $title)
			{
				if (property_exists($item, $name))
				{
					$csvLines[$i][$name] = $item->$name;
				}
			}

			$i++;
		}

		$stream = $this->initFIle();

		foreach ($csvLines as $line)
		{
			fputcsv($stream, $line, $this->delimiter, $this->enclosure);
		}

		fclose($stream);

		JFactory::getApplication()->close();
	}

	/**
	 * Init file
	 *
	 * @return resource
	 */
	public function initFIle()
	{
		// Get the file name
		$fileName = $this->getFileName();
		setlocale(LC_ALL, $this->localeEncoding);

		// Send the headers
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		header("Content-type: text/csv; charset=UTF-8");
		header("Content-Disposition: attachment; filename=\"$fileName.csv\";");
		header("Content-Transfer-Encoding: binary");

		// Send the csv
		$stream = @fopen('php://output', 'w');

		if (!is_resource($stream))
		{
			throw new RuntimeException('Failed to open the output stream');
		}

		return $stream;
	}

	/**
	 * Get the csv file name.
	 *
	 * @return  string  The file name.
	 */
	protected function getFileName()
	{
		$date = md5(date('Y-m-d-h-i-s'));
		$fileName = $this->getName() . '_' . $date;

		return $fileName;
	}
}
