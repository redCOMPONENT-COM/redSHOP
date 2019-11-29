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
 * Table Usercart
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.7
 */
class RedshopTableUsercart extends \Redshop\Table\AbstractTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_usercart';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'cart_id';

	/**
	 * @var integer
	 */
	public $cart_id = 0;

	/**
	 * @var integer
	 */
	public $user_id = 0;

	/**
	 * @var integer
	 */
	public $cdate = 0;

	/**
	 * @var integer
	 */
	public $mdate = 0;
}
