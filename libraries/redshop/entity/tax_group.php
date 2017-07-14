<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Tax group
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.4
 */
class RedshopEntityTax_Group extends RedshopEntity
{
	/**
	 * List of tax rates belong to this tax group
	 *
	 * @var    RedshopEntitiesCollection
	 *
	 * @since  2.0.4
	 */
	protected $taxRates;

	/**
	 * Method for get all associated tax rates
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   2.0.4
	 */
	public function getTaxRates()
	{
		if (is_null($this->taxRates))
		{
			$this->loadTaxRates();
		}

		return $this->taxRates;
	}

	/**
	 * Method for load all tax rates
	 *
	 * @return  self
	 *
	 * @since   2.0.4
	 */
	protected function loadTaxRates()
	{
		/** @var RedshopEntitiesCollection taxRates */
		$this->taxRates = new RedshopEntitiesCollection;

		if (!$this->hasId())
		{
			return $this;
		}

		$model = RedshopModel::getInstance('Tax_Rates', 'RedshopModel', array('ignore_request' => true));
		$model->setState('filter.tax_group', $this->getId());

		$taxRates = $model->getItems();

		if (empty($taxRates))
		{
			return $this;
		}

		foreach ($taxRates as $taxRate)
		{
			$this->taxRates->add(RedshopEntityTax_Rate::getInstance($taxRate->id)->bind($taxRate));
		}

		return $this;
	}
}
