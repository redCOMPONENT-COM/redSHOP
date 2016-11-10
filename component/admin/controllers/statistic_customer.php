<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Statistic Customer controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Statistic Customer
 * @since       2.0.0.3
 */
class RedshopControllerStatistic_Customer extends RedshopControllerAdmin
{
	/**
	 * Export customers CSV.
	 *
	 * @return  mixed.
	 *
	 * @since   2.0.0.3
	 */
	public function exportCustomer()
	{
		$model = $this->getModel();
		$data  = $model->getItems();

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=Customer.csv');

		ob_clean();

		echo "Date, Name, Email, Order count, Total sale\n";

		foreach ($data as $key => $value)
		{
			echo $value->viewdate . " ,";
			echo $value->firstname . ' ' . $value->lastname . " ,";
			echo $value->user_email . " ,";
			echo $value->count . " ,";
			echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . ' ' . $value->total_sale . "\n";
		}

		exit ();
	}
}
