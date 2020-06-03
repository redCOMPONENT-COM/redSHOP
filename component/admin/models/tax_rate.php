<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Tax Rate
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       3.0.2
 */
class RedshopModelTax_Rate extends RedshopModelForm
{
	/**
	 * Method to get a single record.
	 *
	 * @param   integer $pk The id of the primary key.
	 *
	 * @return  JObject|boolean  Object on success, false on failure.
	 *
	 * @since   3.0.2
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		$item->shopper_group = RedshopEntityTax_Rate::getInstance($item->id)->getShopperGroups()->ids();

		return $item;
	}
}
