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
 * Table Usercart item
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.7
 */
class RedshopTableUsercart_Item extends \Redshop\Table\AbstractTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_usercart_item';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'cart_item_id';

	/**
	 * @var integer
	 */
	public $cart_item_id = 0;

	/**
	 * @var integer
	 */
	public $cart_idx = 0;

	/**
	 * @var integer
	 */
	public $cart_id = 0;

	/**
	 * @var integer
	 */
	public $product_id = 0;

	/**
	 * @var integer
	 */
	public $product_quantity = 0;

	/**
	 * @var integer
	 */
	public $product_wrapper_id = 0;

	/**
	 * @var integer
	 */
	public $product_subscription_id = 0;

	/**
	 * @var integer
	 */
	public $giftcard_id = 0;

	/**
	 * @var string
	 */
	public $attribs = '';
}
