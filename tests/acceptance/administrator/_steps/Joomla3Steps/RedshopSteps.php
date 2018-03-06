<?php
/**
 *
 */

class RedshopSteps
{
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
		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_category'))
			->where($db->qn('parent_id') . ' != 0');

		$db->setQuery($query)->execute();
	}

	/**
	 * Function clear all data products at database
	 */
	public function clearAllProducts(){

		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_product'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	/**
	 * Function clear all data coupons at database
	 */
	public function clearAllCoupons(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_coupons'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	/**
	 * Function clear all mass discount at database
	 */
	public function clearAllMassDiscount(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_mass_discount'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	/**
	 * Function clear all discount on product at database
	 */
	public function clearAllDiscountOnProduct(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_discount_product'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	/**
	 * Function clear all discount total at database
	 */
	public function clearAllDiscountTotal(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_discount'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	/**
	 * Function clear all data voucher at database
	 */
	public function clearAllVoucher(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_voucher'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	/**
	 * Function clear all data tax rate at database
	 */
	public function clearTaxRate(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_tax_rate'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	/**
	 * Function clear all data orders at database
	 */
	public function clearAllOrders(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_orders'))
			->where('1');

		$db->setQuery($query)->execute();
	}
	
}
