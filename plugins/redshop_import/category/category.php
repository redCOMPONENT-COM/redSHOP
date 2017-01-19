<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractImportPlugin;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Category
 *
 * @since  1.0
 */
class PlgRedshop_ImportCategory extends AbstractImportPlugin
{
	/**
	 * @var string
	 */
	protected $primaryKey = 'category_id';

	/**
	 * @var string
	 */
	protected $nameKey = 'category_name';

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
	 * Event run when run importing.
	 *
	 * @return  mixed
	 *
	 * @since  1.0.0
	 */
	public function onAjaxCategory_Import()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$input           = JFactory::getApplication()->input;
		$this->encoding  = $input->getString('encoding', 'UTF-8');
		$this->separator = $input->getString('separator', ',');
		$this->folder    = $input->getCmd('folder', '');

		return json_encode($this->importing());
	}

	/**
	 * Method for get table object.
	 *
	 * @return  \JTable
	 *
	 * @since   1.0.0
	 */
	public function getTable()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		return JTable::getInstance('Category_Detail', 'Table');
	}

	/**
	 * Process import data.
	 *
	 * @param   \JTable  $table  Header array
	 * @param   array    $data   Data array
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function processImport($table, $data)
	{
		$isNew = false;
		$db    = $this->db;

		if (array_key_exists($this->primaryKey, $data) && $data[$this->primaryKey])
		{
			$isNew = $table->load($data[$this->primaryKey]);
		}

		if (!$table->bind($data))
		{
			return false;
		}

		if ((!$isNew && !$db->insertObject('#__redshop_category', $table, $this->primaryKey)) || !$table->store())
		{
			return false;
		}

		// Image process
		if (!empty($data['category_full_image']))
		{
			$categoryImage = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . basename($data['category_full_image']);

			if (!JFile::exists($categoryImage))
			{
				JFile::copy($data['category_full_image'], $categoryImage);
			}
		}

		// Update category parent
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__redshop_category_xref'))
			->where($db->qn('category_parent_id') . ' = ' . $data['category_parent_id'])
			->where($db->qn('category_child_id') . ' = ' . $table->category_id);
		$result = $db->setQuery($query)->loadResult();

		if ($result)
		{
			return true;
		}

		// Remove existing
		$query->clear()
			->delete($db->qn('#__redshop_category_xref'))
			->where($db->qn('category_child_id') . ' = ' . $table->category_id);
		$db->setQuery($query)->execute();

		$query->clear()
			->insert($db->qn('#__redshop_category_xref'))
			->values($data['category_parent_id'] . ',' . $table->category_id);

		$db->setQuery($query)->execute();

		return true;
	}
}
