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
 * Model Stockroom
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelStockroom extends RedshopModelForm
{
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   2.1.0
	 */
	public function save($data)
	{
		if ($data['min_stock_amount'] <= 0) {
			/** @scrutinizer ignore-deprecated */
			$this->setError(JText::_('COM_REDSHOP_PLEASE_ENTER_MIN_STOCK_AMOUNT_NOT_LESS_THAN_ZERO'));
			return false;
		}

		if ($data['min_del_time'] > $data['max_del_time']) {
			/** @scrutinizer ignore-deprecated */
			$this->setError(JText::_('COM_REDSHOP_MIN_DELIVERY_TIME_NOT_LESS_MAX_DELIVERY_TIME'));

			return false;
		}

		return parent::save($data);
	}
}
