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

class containerController extends JController
{
	function __construct($default = array())
	{
		parent::__construct($default);
	}

	function cancel()
	{
		$this->setRedirect('index.php');
	}

	function display()
	{

		parent::display();
	}

	function export_data()
	{
		$model = $this->getModel('container');

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=StockroomProduct.csv');

		echo "Container,Container Name,Container Desc,Creation Date\n\n";

		$data = $model->getData();

		for ($i = 0; $i < count($data); $i++)
		{
			echo $data[$i]->container_id . ",";
			echo $data[$i]->container_name . ",";
			echo $data[$i]->container_desc . ",";
			echo $data[$i]->creation_date . ",";
			//echo $data[$i]->quantity * $data[$i]->product_volume;
			echo "\n";
		}
		exit;
	}

	function print_data()
	{
		echo '<script type="text/javascript" language="javascript">	window.print(); </script>';
	}
}

