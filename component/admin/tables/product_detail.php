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
 * Product table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.Product
 * @since       1.6
 */
class TableProduct_Detail extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database driver object.
	 *
	 * @since 11.1
	 */
	public function __construct($db)
	{
		parent::__construct('#__redshop_product', 'product_id', $db);
	}

	/**
	 * Method to check duplicate product number
	 *
	 * @return  boolean  True on success
	 */
	public function check()
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('product_id')
					->from($db->qn('#__redshop_product'))
					->where($db->qn('product_number') . ' = ' . $db->q($this->product_number));

		// Set the query and load the result.
		$db->setQuery($query);

		$duplicateProductId = intval($db->loadResult());

		// Make sure we are not checking it's own product number
		if ($duplicateProductId && $duplicateProductId != intval($this->product_id))
		{
			$this->setError(JText::_('COM_REDSHOP_PRODUCT_NUMBER_ALREADY_EXISTS'));

			return false;
		}

		return true;
	}
}
