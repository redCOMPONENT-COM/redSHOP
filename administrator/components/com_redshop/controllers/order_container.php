<?php
/**
 * @copyright  Copyright (C) 2010-2012 redCOMPONENT.com. All rights reserved.
 * @license    GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class order_containerController extends JController
{
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}

	function update_status()
	{
		$model = $this->getModel('order_container');
		$model->update_status();
	}

	function export_data()
	{
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'order.php' );

		$order_function = new order_functions();

		$model = $this->getModel('order_container');

		$data = $model->export_data();

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
       	header("Content-type: text/x-csv");
    	header("Content-type: text/csv");
    	header("Content-type: application/csv");
    	header('Content-Disposition: attachment; filename=Order.csv');

    	echo "Order id,Fullname,Order Status,Order Date,Total\n\n";


    	for($i=0;$i<count($data);$i++)
		{
			echo $data[$i]->order_id.",";
			echo $data[$i]->firstname." ".$data[$i]->lastname.",";

			echo $order_function->getOrderStatusTitle($data[$i]->order_status).",";
			echo date('d-m-Y H:i',$data[$i]->cdate).",";
			echo REDCURRENCY_SYMBOL.$data[$i]->order_total."\n";
		}
    	exit;
	}
}
