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
 * Manufacturer Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.3
 */
class RedshopEntityManufacturer extends RedshopEntity
{
	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 */
	public function getTable($name = "Tablemanufacturer_detail")
	{
		return JTable::getInstance('Manufacturer_detail', 'Table');
	}
}
