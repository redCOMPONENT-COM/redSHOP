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
 * Table Fields group
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableFields_group extends RedshopTable
{
	/**
	 * @var integer
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $id = null;

	/**
	 * @var string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $name = null;

	/**
	 * @var string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $description = null;

	/**
	 * @var    string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $section = null;

	/**
	 * @var    integer
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $created_by = null;

	/**
	 * @var    string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $created_date = null;

	/**
	 * @var    integer
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $checked_out = null;

	/**
	 * @var    string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $checked_out_time;

	/**
	 * @var   string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $modified_date = null;

	/**
	 * @var    integer
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $modified_by = null;

	/**
	 * @var    integer
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $ordering = null;

	/**
	 * @var    integer
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $published = null;

	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var    string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $_tableName = 'redshop_fields_group';

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

		if (empty($this->created_date))
		{
			$this->created_date = JFactory::getDate()->toSql();
		}

		if (empty($this->created_by))
		{
			$this->created_by = JFactory::getUser()->id;
		}

		return true;
	}
}
