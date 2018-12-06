<?php
/**
 *
 */


use Codeception\Configuration;
class RedshopSteps extends \AcceptanceTester
{
	/**
	 * Clear Aesir tables
	 *
	 * @return  void
	 * @since   3.0.0
	 * @throws  \Exception
	 */
	public function clearAesirTables()
	{
		$config = self::getConfiguration();
		$dbName = $config['modules']['config']['JoomlaBrowser']['database name'];

		$tables = $this->loadColumnQuerySelect('SHOW TABLES FROM ' . $dbName
			. ' WHERE Tables_in_' . $dbName . ' LIKE ' . $this->quoteQueryString('%redshop%')
			. ' AND Tables_in_' . $dbName . ' NOT LIKE ' . $this->quoteQueryString('%redshop_category%')
			. ' AND Tables_in_' . $dbName . ' NOT LIKE ' . $this->quoteQueryString('%redshop_product%')
			. ' AND Tables_in_' . $dbName . ' NOT LIKE ' . $this->quoteQueryString('%redshop_coupons%')
			. ' AND Tables_in_' . $dbName . ' NOT LIKE ' . $this->quoteQueryString('%redshop_discount_product%')
			. ' AND Tables_in_' . $dbName . ' NOT LIKE ' . $this->quoteQueryString('%redshop_discount%')
			. ' AND Tables_in_' . $dbName . ' NOT LIKE ' . $this->quoteQueryString('%redshop_mass_discount%')
			. ' AND Tables_in_' . $dbName . ' NOT LIKE ' . $this->quoteQueryString('%redshop_voucher%')
			. ' AND Tables_in_' . $dbName . ' NOT LIKE ' . $this->quoteQueryString('%redshop_orders')
		);

		$this->clearTables($tables);

		$this->dropTables($tables);
	}

	/**
	 * Clear all tables.
	 *
	 * @return  void
	 */
	public function clearAllData()
	{
		$this->clearAllCategories();
		$this->clearAllProducts();
		$this->clearAllCoupons();
		$this->clearAllDiscountTotal();
		$this->clearAllOrders();
		$this->clearTaxRate();
		$this->clearAllMassDiscount();
		$this->clearAllVoucher();
		$this->clearAllDiscountOnProduct();
	}

	/**
	 * Function clear all data categories at database
	 */
	public function clearAllCategories()
	{
		try
		{
			$this->executeDeleteTable(
				'#__redshop_category',
				[
					'`title` NOT LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` <> 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}

		try
		{
			$this->executeUpdateTable(
				'#__redshop_category',
				[
					[ 'lft' => 0 ],
					[ 'rgt' => 1 ]
				],
				[
					'`title` LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` = 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}
	}

	/**
	 * Function clear all data products at database
	 */
	public function clearAllProducts(){

		try
		{
			$this->executeDeleteTable(
				'#____redshop_product',
				[
					'`title` NOT LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` <> 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}

		try
		{
			$this->executeUpdateTable(
				'#____redshop_product',
				[
					[ 'lft' => 0 ],
					[ 'rgt' => 1 ]
				],
				[
					'`title` LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` = 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}
	}

	/**
	 * Function clear all data coupons at database
	 */
	public function clearAllCoupons(){

		try
		{
			$this->executeDeleteTable(
				'#__redshop_coupons',
				[
					'`title` NOT LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` <> 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}

		try
		{
			$this->executeUpdateTable(
				'#__redshop_coupons',
				[
					[ 'lft' => 0 ],
					[ 'rgt' => 1 ]
				],
				[
					'`title` LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` = 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}
	}

	/**
	 * Function clear all mass discount at database
	 */
	public function clearAllMassDiscount(){

		try
		{
			$this->executeDeleteTable(
				'#__redshop_mass_discount',
				[
					'`title` NOT LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` <> 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}

		try
		{
			$this->executeUpdateTable(
				'#__redshop_mass_discount',
				[
					[ 'lft' => 0 ],
					[ 'rgt' => 1 ]
				],
				[
					'`title` LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` = 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}
	}

	/**
	 * Function clear all discount on product at database
	 */
	public function clearAllDiscountOnProduct(){

		try
		{
			$this->executeDeleteTable(
				'#__redshop_discount_product',
				[
					'`title` NOT LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` <> 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}

		try
		{
			$this->executeUpdateTable(
				'#__redshop_discount_product',
				[
					[ 'lft' => 0 ],
					[ 'rgt' => 1 ]
				],
				[
					'`title` LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` = 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}
	}

	/**
	 * Function clear all discount total at database
	 */
	public function clearAllDiscountTotal(){

		try
		{
			$this->executeDeleteTable(
				'#__redshop_discount',
				[
					'`title` NOT LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` <> 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}

		try
		{
			$this->executeUpdateTable(
				'#__redshop_discount',
				[
					[ 'lft' => 0 ],
					[ 'rgt' => 1 ]
				],
				[
					'`title` LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` = 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}
	}

	/**
	 * Function clear all data voucher at database
	 */
	public function clearAllVoucher(){

		try
		{
			$this->executeDeleteTable(
				'#__redshop_voucher',
				[
					'`title` NOT LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` <> 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}

		try
		{
			$this->executeUpdateTable(
				'#__redshop_voucher',
				[
					[ 'lft' => 0 ],
					[ 'rgt' => 1 ]
				],
				[
					'`title` LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` = 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}
	}

	/**
	 * Function clear all data tax rate at database
	 */
	public function clearTaxRate(){

		try
		{
			$this->executeDeleteTable(
				'#__redshop_tax_rate',
				[
					'`title` NOT LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` <> 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}

		try
		{
			$this->executeUpdateTable(
				'#__redshop_tax_rate',
				[
					[ 'lft' => 0 ],
					[ 'rgt' => 1 ]
				],
				[
					'`title` LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` = 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}
	}

	/**
	 * Function clear all data orders at database
	 */
	public function clearAllOrders(){

		try
		{
			$this->executeDeleteTable(
				'#__redshop_orders',
				[
					'`title` NOT LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` <> 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}

		try
		{
			$this->executeUpdateTable(
				'#__redshop_orders',
				[
					[ 'lft' => 0 ],
					[ 'rgt' => 1 ]
				],
				[
					'`title` LIKE ' . $this->quoteQueryString('ROOT'),
					'`id` = 1'
				]
			);
		}
		catch (\Exception $exception)
		{
		}
	}

}
