<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class product_containerController extends JController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function template()
	{
		$json = JRequest::getVar('json', '');

		$decoded = json_decode($json);

		$model = $this->getModel('product_container');

		$data_product = $model->product_template($decoded->template_id, $decoded->product_id, $decoded->section);

		$json = array();


		$json['data_product'] = $data_product;

		$encoded = json_encode($json);

		die($encoded);
	}

	public function export_data()
	{
		$model = $this->getModel('product_container');

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=product_container.csv');

		echo "Container,Product SKU,Product Name,Quantity,M3\n\n";

		$data = $model->getcontainerproducts();
		$totvol = 0;

		for ($i = 0; $i < count($data); $i++)
		{
			echo $data[$i]->ocontainer_id . ",";
			echo $data[$i]->product_number . ",";
			echo $data[$i]->product_name . ",";
			echo $data[$i]->product_quantity . ",";
			echo $data[$i]->product_quantity * $data[$i]->product_volume . "\n";
			$totvol = $totvol + ($data[$i]->product_quantity * $data[$i]->product_volume);
		}

		echo "  ,   ,   ,Total Volume," . $totvol . "\n\n";

		exit;
	}

	public function print_data()
	{
		echo '<script type="text/javascript" language="javascript">	window.print(); </script>';
	}
}
