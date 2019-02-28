<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * Category name is required
	 *
	 * @var   array
	 */
	protected $requiredFields = array('name');

	/**
	 * @var   string
	 */
	protected $primaryKey = 'id';

	/**
	 * @var   string
	 */
	protected $nameKey = 'name';

	/**
	 * List of alias columns. For backward compatible. Example array('category_id' => 'id')
	 *
	 * @var    array
	 *
	 * @since  2.0.6
	 */
	protected $aliasColumns = array(
		'category_id'                => 'id',
		'category_name'              => 'name',
		'category_short_description' => 'short_description',
		'category_description'       => 'description',
		'category_template'          => 'template',
		'category_more_template'     => 'more_template',
	);

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxCategory_Config()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		\Redshop\Ajax\Response::getInstance()->respond();
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
		\Redshop\Helper\Ajax::validateAjaxRequest();

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

		return RedshopTable::getInstance('Category', 'RedshopTable');
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
		foreach ($this->requiredFields as $required)
		{
			if (empty($data[$required]))
			{
				return false;
			}
		}

		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if (isset($data['parent_id']) && $table->parent_id != $data['parent_id'])
		{
			$table->setLocation($data['parent_id'], 'last-child');
		}

		if ($data['parent_id'] == null || $data['parent_id'] == 0)
		{
			$data['parent_id'] = RedshopHelperCategory::getRootId();
			$data['level']     = 1;
			$table->setLocation($data['parent_id'], 'last-child');
		}

		if (array_key_exists($this->primaryKey, $data) && $data[$this->primaryKey])
		{
			$table->load($data[$this->primaryKey]);
		}

		try
		{
			if (!$table->bind($data) || !$table->check() || !$table->store())
			{
				return false;
			}
		}
		catch (\Exception $e)
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

		return true;
	}
}
