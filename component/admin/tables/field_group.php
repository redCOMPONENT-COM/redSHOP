<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Field group
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.1.0
 */
class RedshopTableField_Group extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var    string
	 *
	 * @since  2.1.0
	 */
	protected $_tableName = 'redshop_fields_group';

	/**
	 * @var integer
	 *
	 * @since   2.1.0
	 */
	public $id = null;

	/**
	 * @var string
	 *
	 * @since   2.1.0
	 */
	public $name = null;

	/**
	 * @var string
	 *
	 * @since   2.1.0
	 */
	public $description = null;

	/**
	 * @var    string
	 *
	 * @since  2.1.0
	 */
	public $section = null;

	/**
	 * @var    integer
	 *
	 * @since  2.1.0
	 */
	public $created_by = null;

	/**
	 * @var    string
	 *
	 * @since  2.1.0
	 */
	public $created_date = null;

	/**
	 * @var    integer
	 *
	 * @since  2.1.0
	 */
	public $checked_out = null;

	/**
	 * @var    string
	 *
	 * @since  2.1.0
	 */
	public $checked_out_time;

	/**
	 * @var   string
	 *
	 * @since  2.1.0
	 */
	public $modified_date = null;

	/**
	 * @var    integer
	 *
	 * @since  2.1.0
	 */
	public $modified_by = null;

	/**
	 * @var    integer
	 *
	 * @since  2.1.0
	 */
	public $ordering = null;

	/**
	 * @var    integer
	 *
	 * @since  2.1.0
	 */
	public $published = null;

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
		if (!parent::doCheck())
		{
			return false;
		}

		if (empty($this->name))
		{
			/** @scrutinizer ignore-deprecated */ $this->setError('COM_REDSHOP_FIELD_GROUP_ERROR_MISSING_NAME');

			return false;
		}

		if (empty($this->section))
		{
			/** @scrutinizer ignore-deprecated */ $this->setError('COM_REDSHOP_FIELD_GROUP_ERROR_MISSING_SECTION');

			return false;
		}

		return true;
	}
}
