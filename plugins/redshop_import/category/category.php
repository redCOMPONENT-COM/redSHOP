<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\Import;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Category
 *
 * @since  1.0
 */
class PlgRedshop_ImportCategory extends Import\AbstractBase
{
	/**
	 * @var   string
	 *
	 * @since   2.0.3
	 */
	protected $primaryKey = 'id';

	/**
	 * @var   string
	 *
	 * @since   2.0.3
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
		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if (isset($data['parent_id']) && $table->parent_id != $data['parent_id'])
		{
			$table->setLocation($data['parent_id'], 'last-child');
		}

		if (array_key_exists($this->primaryKey, $data) && $data[$this->primaryKey])
		{
			$table->load($data[$this->primaryKey]);
		}

		if (!$table->bind($data) || !$table->check() || !$table->store())
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
