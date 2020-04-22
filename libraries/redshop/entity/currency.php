<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Currency Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityCurrency extends RedshopEntity
{
	/**
	 * @var    array
	 * @since  2.0.6
	 */
	protected static $codeInstance;

	/**
	 * Method for load currency instance base on currency code
	 *
	 * @param   string  $code  Currency Code
	 *
	 * @return  self
	 *
	 * @since   2.0.6
	 */
	public function loadFromCode($code = '')
	{
		if (empty($code))
		{
			return self::getInstance();
		}

		if (!isset(static::$codeInstance[$code]))
		{
			/** @var RedshopTableCurrency $table */
			$table = $this->getTable();

			if (!$table->load(array('code' => $code)))
			{
				return self::getInstance();
			}

			static::$codeInstance[$code] = $table->id;
		}

		return self::getInstance(static::$codeInstance[$code]);
	}
}
