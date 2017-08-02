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
 */
class TableAssociations extends JTable
{
	/** @var int Primary key */
	public $id = null;

	/** @var string Whether or not a product is published */
	public $published = null;

	/** @var string Whether or not a product is checked out */
	public $checked_out = null;

	/** @var string When a product is checked out */
	public $checked_out_time = null;

	/** @var integer The order of the product */
	public $ordering = 0;

	/** @var integer The ID of the Redshop product */
	public $product_id = 0;

	/**
	 * @param database A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__redproductfinder_associations', 'id', $db);
	}
}
