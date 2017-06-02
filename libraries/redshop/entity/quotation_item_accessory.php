<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Quotation Item Accessory Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityQuotation_Item_Accessory extends RedshopEntity
{
	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Quotation_Accessory_Item', 'Table');
	}

	/**
	 * Default loading is trying to use the associated table
	 *
	 * @param   string  $key       Field name used as key
	 * @param   string  $keyValue  Value used if it's not the $this->id property of the instance
	 *
	 * @return  self
	 */
	public function loadItem($key = 'quotation_item_acc_id', $keyValue = null)
	{
		if ($key == 'quotation_item_acc_id' && !$this->hasId())
		{
			return $this;
		}

		if (($table = $this->getTable()) && $table->load(array($key => ($key == 'quotation_item_acc_id' ? $this->id : $keyValue))))
		{
			$this->loadFromTable($table);
		}

		return $this;
	}
}
