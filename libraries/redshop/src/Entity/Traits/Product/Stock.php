<?php
/**
 * @package     Redshop\Entity\Traits\Product
 * @subpackage  Stock
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity\Traits\Product;

/**
 * Trait Stock
 * @package Redshop\Entity\Traits\Product
 *
 * @since   2.1.0
 */
trait Stock
{
	/**
	 * @param   integer $totalAttribute        Total attribute
	 * @param   integer $selectedPropertyId    Selected property id
	 * @param   integer $selectedsubpropertyId Selected sub property id
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 * @throws  \Exception
	 */
	public function getStockstatus($totalAttribute = 0, $selectedPropertyId = 0, $selectedsubpropertyId = 0)
	{
		if (!$this->hasId())
		{
			return array();
		}

		$productPreOrder        = trim($this->get('preorder'));
		$data                   = array();
		$data['preorder']       = 0;
		$data['preorder_stock'] = 0;

		if ($selectedPropertyId)
		{
			if ($selectedsubpropertyId)
			{
				// Count status for selected subproperty
				$stockStatus = \RedshopHelperStockroom::isStockExists($selectedsubpropertyId, "subproperty");

				if (!$stockStatus && (($productPreOrder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "yes")))
				{
					$prestocksts            = \RedshopHelperStockroom::isPreorderStockExists($selectedsubpropertyId, "subproperty");
					$data['preorder']       = 1;
					$data['preorder_stock'] = $prestocksts;
				}
			}
			else
			{
				// Count status for selected property
				$stockStatus = \RedshopHelperStockroom::isStockExists($selectedPropertyId, "property");

				if (!$stockStatus && (($productPreOrder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "yes")))
				{
					$prestocksts            = \RedshopHelperStockroom::isPreorderStockExists($selectedPropertyId, "property");
					$data['preorder']       = 1;
					$data['preorder_stock'] = $prestocksts;
				}
			}
		}
		else
		{
			$stockStatus = \RedshopHelperStockroom::getFinalStockofProduct($this->getId(), $totalAttribute);

			if (!$stockStatus && (($productPreOrder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "yes")))
			{
				$prestocksts            = \RedshopHelperStockroom::getFinalPreorderStockofProduct($this->getId(), $totalAttribute);
				$data['preorder']       = 1;
				$data['preorder_stock'] = $prestocksts;
			}
		}

		$data['regular_stock'] = $stockStatus;

		return $data;
	}

	/**
	 * Get an item property
	 *
	 * @param   string $property Property to get
	 * @param   mixed  $default  Default value to assign if property === null | property === ''
	 *
	 * @return  string
	 * @since   2.1.0
	 */
	abstract public function get($property, $default = null);

	/**
	 * Check if we have an identifier loaded
	 *
	 * @return  boolean
	 * @since   2.1.0
	 */
	abstract public function hasId();

	/**
	 * Get the id
	 *
	 * @return  integer | null
	 * @since   2.1.0
	 */
	abstract public function getId();
}