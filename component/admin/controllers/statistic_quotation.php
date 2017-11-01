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
 * Statistic Quotation controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Statistic Quotation
 * @since       2.0.0.3
 */
class RedshopControllerStatistic_Quotation extends RedshopControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.0.0.3
	 */
	public function getModel($name = 'Statistic_Quotation', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Export customers CSV.
	 *
	 * @return  mixed.
	 *
	 * @since   2.0.0.3
	 */
	public function exportQuotation()
	{
		$productHelper = productHelper::getInstance();
		$model         = $this->getModel();
		$data          = $model->getQuotations();

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=Quotation.csv');

		ob_clean();

		echo "Date, Quotation count, Total sale\n";

		foreach ($data as $key => $value)
		{
			echo $value->viewdate . " ,";
			echo $value->count . " ,";
			echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . ' ' . $value->quotation_total . "\n";
		}

		exit ();
	}
}
