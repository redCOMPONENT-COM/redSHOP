<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractExportPlugin;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Category
 *
 * @since  1.0
 */
class PlgRedshop_ExportCategory extends AbstractExportPlugin
{
	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxCategory_Config()
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
	public function onAjaxCategory_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$headers = $this->getHeader();

		if (!empty($headers))
		{
			$this->writeData($headers, 'w+');
		}

		return (int) $this->getTotal();
	}

	/**
	 * Event run on export process
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function onAjaxCategory_Export()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$input = JFactory::getApplication()->input;
		$limit = $input->getInt('limit', 0);
		$start = $input->getInt('start', 0);

		return $this->exporting($start, $limit);
	}

	/**
	 * Event run on export process
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxCategory_Complete()
	{
		$this->downloadFile();

		JFactory::getApplication()->close();
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
			->select('c.*')
			->select($this->db->qn('cx.category_parent_id'))
			->from($this->db->qn('#__redshop_category', 'c'))
			->leftJoin(
				$this->db->qn('#__redshop_category_xref', 'cx') . ' ON ' . $this->db->qn('c.category_id') . ' = ' . $this->db->qn('cx.category_child_id')
			)
			->where($this->db->qn('cx.category_parent_id') . ' IS NOT NULL')
			->order($this->db->qn('c.category_id'));
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
				if ($column == 'category_full_image' && $value != "")
				{
					if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $value))
					{
						$item[$column] = REDSHOP_FRONT_IMAGES_ABSPATH . 'category/' . $value;
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
