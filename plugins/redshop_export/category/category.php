<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
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
	 * @since   1.0.0
	 */
	public function onAjaxCategory_Config()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		\Redshop\Ajax\Response::getInstance()->respond();
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  integer
	 *
	 * @since   1.0.0
	 */
	public function onAjaxCategory_Start()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

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
	 * @return  integer
	 *
	 * @since   1.0.0
	 */
	public function onAjaxCategory_Export()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$input = JFactory::getApplication()->input;
		$limit = $input->getInt('limit', 0);
		$start = $input->getInt('start', 0);

		return $this->exporting($start, $limit);
	}

	/**
	 * Event run on export process
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAjaxCategory_Complete()
	{
		$this->downloadFile();

		JFactory::getApplication()->close();
	}

	/**
	 * Method for get headers data.
	 *
	 * @return  mixed
	 *
	 * @since   1.0.0
	 */
	protected function getHeader()
	{
		return array(
			'id', 'parent_id', 'name', 'short_description', 'description', 'template', 'more_template', 'products_per_page', 'category_thumb_image',
			'category_full_image', 'metakey', 'metadesc', 'metalanguage_setting', 'metarobot_info', 'pagetitle', 'pageheading', 'sef_url',
			'published', 'category_pdate', 'ordering', 'canonical_url', 'category_back_full_image', 'compare_template_id', 'append_to_global_seo',
			'alias', 'path', 'created_date', 'created_by', 'modified_by', 'modified_date', 'publish_up', 'publish_down'
		);
	}

	/**
	 * Method for get query
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.0.0
	 */
	protected function getQuery()
	{
		return $this->db->getQuery(true)
			->select(
				$this->db->qn(
					array(
						'id', 'parent_id', 'name', 'short_description', 'description', 'template', 'more_template', 'products_per_page',
						'category_thumb_image', 'category_full_image', 'metakey', 'metadesc', 'metalanguage_setting', 'metarobot_info', 'pagetitle',
						'pageheading', 'sef_url', 'published', 'category_pdate', 'ordering', 'canonical_url', 'category_back_full_image',
						'compare_template_id', 'append_to_global_seo', 'alias', 'path', 'created_date', 'created_by', 'modified_by', 'modified_date',
						'publish_up', 'publish_down'
					)
				)
			)
			->from($this->db->qn('#__redshop_category'))
			->where($this->db->qn('level') . ' > 0')
			->where($this->db->qn('id') . ' <> ' . RedshopHelperCategory::getRootId())
			->order($this->db->qn('level') . ' ASC');
	}

	/**
	 * Method for do some stuff for data return. (Like image path,...)
	 *
	 * @param   array  $data  Array of data.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
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
					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $value))
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
