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
 * Table Text
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.1.0
 */
class RedshopTableText extends RedshopTable
{
	/**
	 * @var  string
	 */
	protected $_tableName = 'redshop_textlibrary';

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $desc;

	/**
	 * @var string
	 */
	public $section = 'product';

	/**
	 * @var  integer
	 */
	public $published = 1;

	/**
	 * @var  string
	 */
	public $content;

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
		if (empty($this->name))
		{
			return false;
		}

		return parent::doCheck();
	}
}
