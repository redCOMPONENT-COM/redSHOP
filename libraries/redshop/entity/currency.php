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
 * @since       __DEPLOY_VERSION__
 */
class RedshopEntityCurrency extends RedshopEntity
{
	/**
	 * @var    array
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $codeInstance;

	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 */
	public function getTable($name = "Tablecurrency_detail")
	{
		return JTable::getInstance('Currency_detail', 'Table');
	}

	/**
	 * Default loading is trying to use the associated table
	 *
	 * @param   string  $key       Field name used as key
	 * @param   string  $keyValue  Value used if it's not the $this->id property of the instance
	 *
	 * @return  self
	 */
	public function loadItem($key = 'currency_id', $keyValue = null)
	{
		if ($key == 'currency_id' && !$this->hasId())
		{
			return $this;
		}

		if (($table = $this->getTable()) && $table->load(array($key => ($key == 'currency_id' ? $this->id : $keyValue))))
		{
			$this->loadFromTable($table);
		}

		return $this;
	}

	/**
	 * Method for load currency instance base on currency code
	 *
	 * @param   string  $code  Currency Code
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
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
