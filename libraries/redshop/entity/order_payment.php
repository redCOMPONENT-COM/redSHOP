<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Order Payment Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityOrder_Payment extends RedshopEntity
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
		return JTable::getInstance('Order_Payment', 'Table');
	}

	/**
	 * Default loading is trying to use the associated table
	 *
	 * @param   string  $key       Field name used as key
	 * @param   string  $keyValue  Value used if it's not the $this->id property of the instance
	 *
	 * @return  self
	 */
	public function loadItem($key = 'payment_order_id', $keyValue = null)
	{
		if ($key == 'payment_order_id' && !$this->hasId())
		{
			return $this;
		}

		if (($table = $this->getTable()) && $table->load(array($key => ($key == 'payment_order_id' ? $this->id : $keyValue))))
		{
			$this->loadFromTable($table);
		}

		return $this;
	}

	/**
	 * Method for load plugin data of this payment
	 *
	 * @return  self
	 *
	 * @since   2.0.6
	 */
	public function loadPlugin()
	{
		if (!$this->hasId() || !is_null($this->get('plugin', null)))
		{
			return $this;
		}

		if (!empty($this->get('payment_method_class')))
		{
			// Get plugin information
			$plugin = JPluginHelper::getPlugin('redshop_payment', $this->get('payment_method_class'));

			if ($plugin)
			{
				$plugin->params = new Registry($plugin->params);
			}

			$this->set('plugin', $plugin);
		}

		return $this;
	}
}
