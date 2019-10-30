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
 * Shipping box table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.State
 * @since       2.1.0
 */
class RedshopTableShipping_Box extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_shipping_boxes';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'shipping_box_id';

	/**
	 * @var integer
	 */
	public $shipping_box_id = null;

	/**
	 * @var float
	 */
	public $shipping_box_name = null;

	/**
	 * @var float
	 */
	public $shipping_box_length = null;

	/**
	 * @var float
	 */
	public $shipping_box_width = null;

	/**
	 * @var float
	 */
	public $shipping_box_height = null;

	/**
	 * @var integer
	 */
	public $shipping_box_priority = null;

	/**
	 * @var integer
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
		$this->shipping_box_length = floatval($this->shipping_box_length);
		$this->shipping_box_width  = floatval($this->shipping_box_width);
		$this->shipping_box_height = floatval($this->shipping_box_height);

		if ($this->shipping_box_length <= 0.0)
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_SHIPPING_BOX_ERROR_LENGTH_INVALID'));

			return false;
		}

		if ($this->shipping_box_width <= 0.0)
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_SHIPPING_BOX_ERROR_WIDTH_INVALID'));

			return false;
		}

		if ($this->shipping_box_height <= 0.0)
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_SHIPPING_BOX_ERROR_HEIGHT_INVALID'));

			return false;
		}

		return parent::doCheck();
	}
}
