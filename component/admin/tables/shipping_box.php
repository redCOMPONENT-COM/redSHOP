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
 * Shipping box table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.State
 * @since       __DEPLOY_VERSION__
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
	 * @var integer
	 */
	public $shipping_box_name = null;

	/**
	 * @var integer
	 */
	public $shipping_box_length = null;

	/**
	 * @var integer
	 */
	public $shipping_box_width = null;

	/**
	 * @var integer
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
}
