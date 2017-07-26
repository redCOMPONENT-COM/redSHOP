<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\Export;
use Redshop\Ajax\Response;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Manufacturer
 *
 * @since  1.0
 */
class PlgRedshop_ExportManufacturer extends Export\AbstractBase
{
	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 *
	 * @TODO   : Need to load XML File instead
	 */
	public function onAjaxManufacturer_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return '';
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxManufacturer_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$headers = $this->getHeader();

		if (!empty($headers))
		{
			// Init temporary folder
			Redshop\Filesystem\Folder\Helper::create($this->getTemporaryFolder());
			$this->writeData($headers, 'w+');
		}

		$response = new Response;
		$data = new stdClass;

		// Total rows for exporting
		$data->rows = (int) $this->getTotal();

		// Limit rows percent request
		$data->limit = $this->limit;
		$data->total = ceil($data->rows / $data->limit);

		return $response->setData($data)->success()->respond();
	}

	/**
	 * Event run on export process
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function onAjaxManufacturer_Export()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$input = JFactory::getApplication()->input;

		return $this->exporting($input->getInt('from', 0) * $this->limit, $this->limit);
	}

	/**
	 * Event run on export process
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxManufacturer_Complete()
	{
		$this->downloadFile();

		return $this->convertFile();
	}

	/**
	 * Method for get query
	 *
	 * @return \JDatabaseQuery
	 *
	 * @since  1.0.0
	 */
	protected function getQuery()
	{
		return $this->db->getQuery(true)
			->select('m.*')
			->select(
				'GROUP_CONCAT(' . $this->db->qn('p.product_id') . ' SEPARATOR ' . $this->db->quote('|') . ') AS ' . $this->db->qn('product_id')
			)
			->select($this->db->qn('md.media_name', 'manufacturer_image'))
			->from($this->db->qn('#__redshop_manufacturer', 'm'))
			->leftJoin(
				$this->db->qn('#__redshop_product', 'p') . ' ON ' . $this->db->qn('m.manufacturer_id') . ' = ' . $this->db->qn('p.manufacturer_id')
			)
			->leftJoin(
				$this->db->qn('#__redshop_media', 'md') . ' ON ('
				. $this->db->qn('m.manufacturer_id') . ' = ' . $this->db->qn('md.section_id')
				. ' AND ' . $this->db->qn('md.media_section') . ' = ' . $this->db->quote('manufacturer')
				. ' AND ' . $this->db->qn('md.media_type') . ' = ' . $this->db->quote('images') . ')'
			)
			->group($this->db->qn('m.manufacturer_id'));
	}

	/**
	 * Method for do some stuff for data return. (Like image path,...)
	 *
	 * @param   array  &$data  Array of data.
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	protected function processData(&$data)
	{
		if (empty($data))
		{
			return;
		}

		foreach ($data as $index => $item)
		{
			$item = (array) $item;

			foreach ($item as $column => $value)
			{
				if ($column == 'manufacturer_image' && $value != "")
				{
					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'manufacturer/' . $value))
					{
						$item[$column] = REDSHOP_FRONT_IMAGES_ABSPATH . 'manufacturer/' . $value;
					}
					else
					{
						$item[$column] = "";
					}
				}
				else
				{
					$item[$column] = str_replace(array("\n", "\r"), "", $value);
				}
			}

			$data[$index] = $item;
		}
	}
}
