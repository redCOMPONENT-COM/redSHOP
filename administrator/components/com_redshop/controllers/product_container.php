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
 
class product_containerController extends JController 
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}	
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}
	function display() {
		
		parent::display();
			
	}
	function template(){
	
	    $json = JRequest::getVar( 'json', '');
	     
		$decoded = json_decode($json);
		
		$model = $this->getModel ( 'product_container' );
		
		$data_product = $model->product_template($decoded->template_id,$decoded->product_id,$decoded->section);
		
		$json = array();
				
		 
		$json['data_product'] = $data_product;
				
		$encoded = json_encode($json);
		
		die($encoded);
		
	}
	
	function export_data()
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
    	for($i=0;$i<count($data);$i++)
		{		
			echo $data[$i]->ocontainer_id.",";
			echo $data[$i]->product_number.",";
			echo $data[$i]->product_name.",";
			echo $data[$i]->product_quantity.",";
			echo $data[$i]->product_quantity * $data[$i]->product_volume."\n";
			$totvol = $totvol + ($data[$i]->product_quantity * $data[$i]->product_volume);
		} 
		
		echo "  ,   ,   ,Total Volume,".$totvol."\n\n";
		
    	exit;
	}
	function print_data()
	{
		echo '<script type="text/javascript" language="javascript">	window.print(); </script>';		
	}

}