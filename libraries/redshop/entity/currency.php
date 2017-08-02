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
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Currency_Detail', 'Table');
	}

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
			$table = $this->getTable();

			if (!$table->load(array('currency_code' => $code)))
			{
				return self::getInstance();
			}

			static::$codeInstance[$code] = $table->currency_id;
		}

		return self::getInstance(static::$codeInstance[$code]);
	}
}
