<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
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

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.controller' );

class stockroom_listingController extends JController
{
	function cancel()
    {
		$this->setRedirect( 'index.php' );
	}

	function saveStock()
	{
		$model = $this->getModel('stockroom_listing');
		$stockroom_type =  JRequest::getVar ( 'stockroom_type', 'product', 'post', 'string' );

		$pid = JRequest::getVar ( 'pid', array (0 ), 'post', 'array' );
		$sid = JRequest::getVar ( 'sid', array (0 ), 'post', 'array' );
		$quantity = JRequest::getVar ( 'quantity', array (0 ), 'post', 'array' );
		$preorder_stock = JRequest::getVar ( 'preorder_stock', array (0 ), 'post', 'array' );
		$ordered_preorder = JRequest::getVar ( 'ordered_preorder', array (0 ), 'post', 'array' );
//
		for($i=0;$i<count($sid);$i++)
		{

			$model->storeStockroomQuantity($stockroom_type,$sid[$i],$pid[$i],$quantity[$i], $preorder_stock[$i], $ordered_preorder[$i]);
		}
		$this->setRedirect( 'index.php?option=com_redshop&view=stockroom_listing&id=0&stockroom_type='.$stockroom_type);
	}

	function ResetPreorderStock()
	{
		$model = $this->getModel('stockroom_listing');
		$stockroom_type =  JRequest::getVar ( 'stockroom_type','product' );
		$pid = JRequest::getVar ( 'product_id');
		$sid = JRequest::getVar ( 'stockroom_id');


		$model->ResetPreOrderStockroomQuantity($stockroom_type,$sid,$pid);

		$this->setRedirect( 'index.php?option=com_redshop&view=stockroom_listing&id=0&stockroom_type='.$stockroom_type);
	}

	function export_data()
	{
		$model = $this->getModel('stockroom_listing');
		$cid = JRequest::getVar ( 'category_id');
		/* Start output to the browser */
		if (preg_match('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
			$UserBrowser = "Opera";
		}
		elseif (preg_match('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
			$UserBrowser = "IE";
		} else {
			$UserBrowser = '';
		}
		$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

		/* Clean the buffer */
		while( @ob_end_clean() );

		header('Content-Type: ' . $mime_type);
		header('Content-Encoding: UTF-8');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		if ($UserBrowser == 'IE') {
			header('Content-Disposition: inline; filename=StockroomProduct.csv');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		} else {
			header('Content-Disposition: attachment; filename=StockroomProduct.csv');
			header('Pragma: no-cache');
		}
//    	if(USE_CONTAINER){
//    		echo "Container Id,Container Name";
//    	}else
//		{
    		echo "Stockroom_Id,Stockroom_Name";
//    	}
    	echo ",Product_SKU,Product_Name,Quantity,M3\n\n";

    	$product_ids=0;
   		if($cid !="" && $cid !=0)
    	{
    		$product_list = $model->getProductIdsfromCategoryid($cid);

    		for($p=0;$p<count($product_list);$p++)
    		{
    			$product_ids = implode(",", $product_list);
    		}
   		}
    	$data = $model->getcontainerproducts($product_ids);

    	for($i=0;$i<count($data);$i++)
		{
//			if(USE_CONTAINER){
//				echo $data[$i]->container_id.",";
//				echo $data[$i]->container_name.",";
//			}else{
				echo $data[$i]->stockroom_id.",";
				echo $data[$i]->stockroom_name.",";
//			}
			echo $data[$i]->product_number.",";
			echo $data[$i]->product_name.",";
			echo $data[$i]->quantity.",";
			echo $data[$i]->quantity * $data[$i]->product_volume."\n";
		}

		exit;
	}

	function print_data()
	{
		echo '<script type="text/javascript" language="javascript">	window.print(); </script>';
	}
}
