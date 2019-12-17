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
 * Table Usercart accessory item
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.7
 */
class RedshopTableUsercart_Accessory_Item extends \Redshop\Table\AbstractTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_usercart_accessory_item';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'cart_acc_item_id';

	/**
	 * @var integer
	 */
	public $cart_acc_item_id = 0;

	/**
	 * @var integer
	 */
	public $cart_item_id = 0;

	/**
	 * @var integer
	 */
	public $accessory_id = 0;

	/**
	 * @var integer
	 */
	public $accessory_quantity = 0;
}
