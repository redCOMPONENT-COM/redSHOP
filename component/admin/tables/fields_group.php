<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.1
 */
class RedshopTableFields_group extends RedshopTable
{
	/**
	 * @var null
	 */
	public $id = null;

	/**
	 * @var null
	 */
	public $name = null;

	/**
	 * @var null
	 */
	public $description = null;

	/**
	 * @var null
	 */
	public $section = null;

	/**
	 * @var null
	 */
	public $created = null;

	/**
	 * @var null
	 */
	public $created_by = null;

	/**
	 * @var null
	 */
	public $created_by_alias= null;

	/**
	 * @var null
	 */
	public $checked_out = null;

	/**
	 * @var null
	 */
	public $checked_out_time;

	/**
	 * @var null
	 */
	public $modified = null;

	/**
	 * @var null
	 */
	public $modified_by = null;

	/**
	 * @var null
	 */
	public $ordering = null;

	/**
	 * @var null
	 */
	public $state = null;

	/**
	 * @var null
	 */
	public $params = null;

	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_fields_group';

	public function __construct(\JDatabaseDriver $db)
	{
		parent::__construct($db);

		$this->setColumnAlias('published', 'state');
	}

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
			$this->setError('COM_REDSHOP_TABLE_FIELDS_GROUP_MISSING_NAME');

			return false;
		}

		if (empty($this->created))
		{
			$this->created = JFactory::getDate()->toSql();
		}

		if (empty($this->created_by))
		{
			$this->created_by = JFactory::getUser()->id;
		}

		return true;
	}
}
