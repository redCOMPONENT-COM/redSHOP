<?php
/**
 * @package     Redshop
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Stockroom listing View
 *
 * @package     Redshop
 * @subpackage  Views
 * @since       1.0
 */
class RedshopViewStockroom_Listing extends RedshopViewCsv
{
	/**
	 * Delimiter character for CSV columns
	 *
	 * @var string
	 */
	public $delimiter = ';';

	/**
	 * Get the columns for the csv file.
	 *
	 * @return  array  An associative array of column names as key and the title as value.
	 */
	protected function getColumns()
	{
		$model = $this->getModel();

		return $model->getCsvColumns();
	}

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
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
		$state = $this->get('State');
		$stockroom_type   = $state->get('stockroom_type');
		$stockrooms     = $this->get('Stockroom');
		$ids = array();

		if ($stockroom_type != 'product')
		{
			$nameId = 'section_id';
		}
		else
		{
			$nameId = 'product_id';
			unset($columns['section_id'], $columns['stockroom_type']);
		}

		foreach ($items as $item)
		{
			$ids[] = $item->$nameId;
		}

		$quantities = $model->getQuantity($stockroom_type, '', $ids);

		$csvLines[0] = $columns;
		$i = 1;

		if ($stockrooms)
		{
			foreach ($stockrooms as $stockroom)
			{
				foreach ($items as $item)
				{
					if (!isset($quantities[$item->$nameId . '.' . $stockroom->stockroom_id]))
					{
						continue;
					}

					$value = $quantities[$item->$nameId . '.' . $stockroom->stockroom_id];

					foreach ($columns as $name => $title)
					{
						if (property_exists($value, $name))
						{
							$csvLines[$i][$name] = $value->$name;
						}
					}

					$csvLines[$i]['stockroom_type'] = $state->get('stockroom_type');

					foreach ($columns as $name => $title)
					{
						if (property_exists($item, $name))
						{
							$csvLines[$i][$name] = $item->$name;
						}
					}

					$i++;
				}
			}
		}

		$stream = $this->initFIle();

		foreach ($csvLines as $line)
		{
			$orderLine = array();

			foreach ($columns as $name => $title)
			{
				if (array_key_exists($name, $line))
				{
					$orderLine[$name] = $line[$name];
				}
				else
				{
					$orderLine[$name] = '';
				}
			}

			fputcsv($stream, $orderLine, $this->delimiter, $this->enclosure);
		}

		fclose($stream);

		JFactory::getApplication()->close();
	}
}
