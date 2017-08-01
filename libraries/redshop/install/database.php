<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Install database
 *
 * @since  2.0.2
 */
class RedshopInstallDatabase
{
	// TODO Move to json file
	protected $dbUpdate = array(
		// 1.4
		'quotation'              => array(
			'add' => array(
				'field' => array(
					'quotation_customer_note' => "ALTER TABLE `#__redshop_quotation` ADD `quotation_customer_note` TEXT NOT NULL AFTER `quotation_note`"
				)
			)
		),
		'product'                => array(
			'add'  => array(
				'field' => array(
					'allow_decimal_piece' => "ALTER TABLE `#__redshop_product` ADD `allow_decimal_piece` int(4) NOT NULL"
				)
			),
			'drop' => array(
				'index' => array(
					'product_number' => "ALTER TABLE `#__redshop_product` DROP INDEX `product_number`"
				)
			)
		),
		'country'                => array(
			'drop' => array(
				'index' => array(
					'idx_country_name' => "ALTER TABLE `#__redshop_country` DROP INDEX `idx_country_name`"
				)
			)
		),
		'currency'               => array(
			'drop' => array(
				'index' => array(
					'idx_currency_name' => "ALTER TABLE `#__redshop_currency` DROP INDEX `idx_currency_name`"
				)
			)
		),
		'order_item'             => array(
			'drop' => array(
				'field' => array(
					'container_id' => "ALTER TABLE `#__redshop_order_item` DROP `container_id`"
				)
			)
		),
		// 1.5
		// 1.5.0.4.1
		'usercart_item'          => array(
			'add' => array(
				'field' => array(
					'attribs' => "ALTER TABLE `#__redshop_usercart_item` ADD `attribs` VARCHAR(5120) NOT NULL COMMENT 'Specified user attributes related with current item'"
				)
			)
		),
		// 1.5.0.5.1
		'orders'                 => array(
			'add' => array(
				'field' => array(
					'invoice_number'        => "ALTER TABLE `#__redshop_orders` ADD `invoice_number` VARCHAR( 255 ) NOT NULL COMMENT 'Formatted Order Invoice for final use' AFTER `order_number` , ADD INDEX `idx_orders_invoice_number` (`invoice_number`)",
					'invoice_number_chrono' => "ALTER TABLE `#__redshop_orders` ADD `invoice_number_chrono` INT NOT NULL COMMENT 'Order invoice number in chronological order' AFTER `order_number` , ADD INDEX `idx_orders_invoice_number_chrono` (`invoice_number_chrono`)"
				)
			)
		),
		// 1.5.0.5.3
		'order_payment'          => array(
			'add'  => array(
				'unique' => array(
					'order_id' => "ALTER TABLE `#__redshop_order_payment` ADD UNIQUE(`order_id`)"
				)
			),
			'drop' => array(
				'index' => array(
					'idx_order_id' => array(
						"ALTER TABLE `#__redshop_order_payment` DROP INDEX idx_order_id"
					)
				)
			)
		),
		// 1.6
		'discount'               => array(
			'add' => array(
				'field' => array(
					'name' => 'ALTER TABLE `#__redshop_discount` ADD `name` VARCHAR( 250 ) NOT NULL'
				),
				'index' => array(
					'idx_discount_name' => "ALTER TABLE `#__redshop_discount` ADD INDEX `idx_discount_name` (`name`)"
				)
			)
		),
		// 1.6.1
		'giftcard'               => array(
			'add' => array(
				'field' => array(
					'free_shipping' => "ALTER TABLE `#__redshop_giftcard` ADD `free_shipping` TINYINT NOT NULL"
				)
			)
		),
		// 1.6.3
		'product_stockroom_xref' => array(
			'add' => array(
				'index' => array(
					'idx_product_id' => 'ALTER TABLE `#__redshop_product_stockroom_xref` ADD INDEX `idx_product_id` (`product_id` ASC);',
					'idx_quantity'   => 'ALTER TABLE `#__redshop_product_stockroom_xref` ADD INDEX `idx_quantity` (`quantity` ASC);'
				)
			)
		)
	);

	private $_tablePrefix = '';

	/**
	 * RedshopInstallDatabase constructor.
	 */
	public function __construct()
	{
		$this->_tablePrefix = JFactory::getConfig()->get('dbprefix') . 'redshop_';
	}

	/**
	 * Install database
	 *
	 * @return  void
	 *
	 * @since  2.0.2
	 */
	public function install()
	{
		$db = JFactory::getDbo();

		// Loop array of required tables / fields
		foreach ($this->dbUpdate as $table => $fields)
		{
			$redshopTable = $this->_tablePrefix . $table;
			$columns      = $this->_getColumns($redshopTable);

			// Check these table columns
			if (is_array($columns))
			{
				// Alter new column
				if (isset($fields['add']))
				{
					if (isset($fields['add']['field']))
					{
						// Execute all required alter new columns
						foreach ($fields['add']['field'] as $field => $query)
						{
							// Make sure this table have no this column before
							if (!array_key_exists($field, $columns))
							{
								$db->setQuery($query);
								$db->execute();
							}
						}
					}

					// Indexing
					if (isset($fields['add']['index']))
					{
						// Execute all required alter new columns
						foreach ($fields['add']['index'] as $field => $query)
						{
							// Working with INDEX
							$indexes = $this->_getIndex($redshopTable);

							// Make sure this table have no this column before
							if (!array_key_exists($field, $indexes))
							{
								$db->setQuery($query);
								$db->execute();
							}
						}
					}

					// Unique
					if (isset($fields['add']['unique']))
					{
						// Execute all required alter new columns
						foreach ($fields['add']['unique'] as $field => $query)
						{
							// Make sure this table have this column before
							if (array_key_exists($field, $columns))
							{
								if ($columns[$field]->Key != 'UNI')
								{
									$db->setQuery($query);
									$db->execute();
								}
							}
						}
					}
				}

				// Alter drop column
				$this->_alterDrop($fields, $columns);
			}

			// Working with INDEX
			$columns = $this->_getIndex($redshopTable);

			// Alter drop Index
			$this->_alterDrop($fields, $columns, true);
		}
	}

	/**
	 * Get columns of table
	 *
	 * @param   string $table Table name
	 *
	 * @return mixed
	 *
	 * @since  2.0.2
	 */
	private function _getColumns($table)
	{
		$db = JFactory::getDbo();

		$columnsQuery = "SHOW COLUMNS FROM " . $table;

		return $db->setQuery($columnsQuery)->loadObjectList('Field');
	}

	/**
	 * Get index of table
	 *
	 * @param   string $table Table name
	 *
	 * @return  mixed
	 *
	 * @since  2.0.2
	 */
	private function _getIndex($table)
	{
		$db = JFactory::getDbo();

		$indexQuery = "SHOW INDEX FROM " . $table;

		return $db->setQuery($indexQuery)->loadObjectList('Key_name');
	}

	/**
	 * Apply ALTER query for drop column or index
	 *
	 * @param   array $fields  Fields information
	 * @param   array $columns List of Columns
	 * @param   bool  $isIndex Drop index column
	 *
	 * @return  boolean          True on success.
	 */
	private function _alterDrop($fields, $columns, $isIndex = false)
	{
		if (!is_array($columns))
		{
			return false;
		}

		if ($isIndex === false)
		{
			if (!isset($fields['drop']['field']))
			{
				return false;
			}
		}
		else
		{
			if (!isset($fields['drop']['index']))
			{
				return false;
			}
		}

		$db = JFactory::getDbo();

		// Drop field
		if ($isIndex === false)
		{
			foreach ($fields['drop']['field'] as $field => $query)
			{
				if (array_key_exists($field, $columns))
				{
					if (is_array($query))
					{
						foreach ($query as $aQuery)
						{
							$db->setQuery($aQuery);
							$db->execute();
						}
					}
					else
					{
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
		}
		else
		{
			// Drop index
			foreach ($fields['drop']['index'] as $field => $query)
			{
				if (array_key_exists($field, $columns))
				{
					if (is_array($query))
					{
						foreach ($query as $aQuery)
						{
							$db->setQuery($aQuery);
							$db->execute();
						}
					}
					else
					{
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
		}

		return true;
	}
}
