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

class orderreddesignController extends JController
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

	function update_status()
	{
		$model = $this->getModel('orderreddesign');
		$model->update_status();
	}

	function allstatus()
	{

		$model = $this->getModel('orderreddesign');
		$model->update_status_all();


	}

	function export_data()
	{
		require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'order.php');

		$order_function = new order_functions();

		$model = $this->getModel('orderreddesign');

		$data = $model->export_data();


		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=Orderreddesign.csv');

		echo "Order id,Fullname,Order Status,Order Date,Total\n\n";


		for ($i = 0; $i < count($data); $i++)
		{
			echo $data[$i]->order_id . ",";
			echo $data[$i]->firstname . " " . $data[$i]->lastname . ",";

			echo $order_function->getOrderStatusTitle($data[$i]->order_status) . ",";
			echo date('d-m-Y H:i', $data[$i]->cdate) . ",";
			echo REDCURRENCY_SYMBOL . $data[$i]->order_total . "\n";
		}
		exit;
	}


	function downloaddesign()
	{
		$filename = JRequest::getVar('filename');
		$type = JRequest::getVar('type');

		//var_dump($_SERVER); die();
		//$path = $_SERVER['DOCUMENT_ROOT']."/path2file/"; // play with the path if the document root does noet exist

		if ($type == "pdf")
		{
			$filename = $filename . ".pdf";
			$file = JPATH_ROOT . "/components/com_reddesign/assets/order/pdf/" . $filename;
			header("Content-Type: application/force-download");
		}
		else if ($type == "eps")
		{
			$filename = $filename . ".eps";
			$file = JPATH_ROOT . "/components/com_reddesign/assets/order/eps/" . $filename;
			header("Content-Type: application/eps");
			// header("Content-Type: application/postscript");
		}
		else if ($type == "original")
		{
			$filename = "bg_" . $filename . ".jpeg";
			$file = JPATH_ROOT . "/components/com_reddesign/assets/order/eps/" . $filename;
			header('Content-Description: File Transfer');
			header('Content-Type: image/jpg');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

			header('Content-Length: ' . filesize($file));
		}
		else if ($type == "design")
		{
			$filename = $filename . ".jpeg";
			$file = JPATH_ROOT . "/components/com_reddesign/assets/order/design/" . $filename;

			header('Content-Type: image/jpg');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

			header('Content-Length: ' . filesize($file));
		}
		//echo $file ; exit;
		//$type = mime_content_type( $file); exit;
		//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

		//header("Content-Type: application/force-download");
		//
		//header("Content-Length: ".filesize($file));
		//header('Content-type: application/pdf');
		//header('Content-Disposition: attachment; filename="'.filesize($file).'"');

		//@header('Content-Disposition: attachment; filename='.$filename);


		flush(); // this doesn't really matter.
		ob_start();
		readfile($file);

		exit;

	}

}
