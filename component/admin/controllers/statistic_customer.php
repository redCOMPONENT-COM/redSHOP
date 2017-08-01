<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Statistic Customer controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Statistic Customer
 * @since       2.0.3
 */
class RedshopControllerStatistic_Customer extends RedshopControllerAdmin
{
	/**
	 * Export customers CSV.
	 *
	 * @return  mixed.
	 *
	 * @since   2.0.3
	 */
	public function exportCustomer()
	{
		$model = $this->getModel();
		$data  = $model->getItems();
		$productHelper = productHelper::getInstance();

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=Customer.csv');

		ob_clean();

		echo "Name, Email, Order count, Total sale\n";

		foreach ($data as $value)
		{
			echo trim($value->customer_name) . ",";
			echo trim($value->user_email) . ",";
			echo $value->count . ",";
			echo $productHelper->getProductFormattedPrice($value->total_sale) . "\n";
		}

		JFactory::getApplication()->close();
	}
}
