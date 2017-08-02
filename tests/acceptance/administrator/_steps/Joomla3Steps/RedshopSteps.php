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
		$this->clearAllVocher();
		$this->clearAllDiscountOnProduct();
	}

	public function clearAllCategories()
	{
		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_category'))
			->where($db->qn('parent_id') . ' != 0');

		$db->setQuery($query)->execute();
	}

	public function clearAllProducts(){

		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_product'))
			->where('1');

		$db->setQuery($query)->execute();

	}

	public function clearAllCoupons(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_coupons'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	public function clearAllMassDiscount(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_mass_discount'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	public function clearAllDiscountOnProduct(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_discount_product'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	public function clearAllDiscountTotal(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_discount'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	public function clearAllVocher(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_voucher'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	public function clearTaxRate(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_tax_rate'))
			->where('1');

		$db->setQuery($query)->execute();
	}

	public function clearAllOrders(){

		$db= \JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_orders'))
			->where('1');

		$db->setQuery($query)->execute();
	}

}