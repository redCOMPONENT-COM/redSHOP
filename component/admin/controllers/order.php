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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'order.php');
require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'mail.php');
require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'shipping.php');
require_once (JPATH_SITE . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'helper.php');

class orderController extends JController {
	function __construct($default = array()) {
		parent::__construct ( $default );
	}
	function multiprint_order()
	{	$mypost=JRequest::getVar('cid');
		$mycid=implode(",",$mypost);
		$option = JRequest::getVar ('option');
		$order_function = new order_functions ();
		$invoicePdf= $order_function->createMultiprintInvoicePdf($mypost);
		?>
			<script type="text/javascript">
		<?php if($invoicePdf!="")
		{
			if(file_exists(JPATH_COMPONENT_SITE.DS."assets/document/invoice/".$invoicePdf.".pdf"))
			{
			?>
				window.open("<?php echo JURI::root()?>/components/com_redshop/assets/document/invoice/<?php echo $invoicePdf?>.pdf");
				
			<?php } 
		}  
		 for($i=0;$i<count($mypost);$i++) {
		if(file_exists(JPATH_COMPONENT_SITE.DS."assets/labels/label_".$mypost[$i].".pdf"))
		{
		?>
			window.open("<?php echo JURI::root()?>/components/com_redshop/assets/labels/label_<?php echo $mypost[$i]?>.pdf");

		<?php } } ?>
		//window.open("index3.php?json=1&option=com_redshop&view=order&layout=multiprint_order&cid=<?php echo $mycid?>","mywindow","scrollbars=1","location=1");
		window.parent.location = 'index.php?option=com_redshop&view=order';
		</script>
		<?php

		//$this->setRedirect ( 'index.php?option=com_redshop&view=order');
	}

    function cancel()
	{
		$option = JRequest::getVar ('option');
		$this->setRedirect ( 'index.php?option='.$option.'&view=order');
	}
	function display() {
		parent::display ();
	}
	function update_status() {
		$model = $this->getModel ( 'order' );
		$model->update_status ();
	}

	function allstatus() {
		$model = $this->getModel ( 'order' );
		$model->update_status_all ();
	}
	function bookInvoice()
	{
		$post = JRequest::get ( 'post' );
		$bookInvoiceDate = $post ['bookInvoiceDate'];
		$order_id = JRequest::getCmd ( 'order_id' );
		$ecomsg = JText::_ ( 'INVOICE_NOT_BOOKED_IN_ECONOMIC' );
		// Economic Integration start for invoice generate and book current invoice
		if (ECONOMIC_INTEGRATION == 1)
		{
			$economic = new economic ();

			$bookinvoicepdf = $economic->bookInvoiceInEconomic ( $order_id, 0, 0, $bookInvoiceDate );
			if (is_file ( $bookinvoicepdf ))
			{
				$redshopMail = new redshopMail ();
				$ecomsg = JText::_ ( 'SUCCESSFULLY_BOOKED_INVOICE_IN_ECONOMIC' );
				$ret = $redshopMail->sendEconomicBookInvoiceMail ( $order_id, $bookinvoicepdf );
			}
		}
		// End Economic
		$this->setRedirect ( 'index.php?option=com_redshop&view=order', $ecomsg );
	}

	function createInvoice()
	{
		if (ECONOMIC_INTEGRATION == 1 && ECONOMIC_INVOICE_DRAFT!=2)
		{
			$order_id = JRequest::getCmd ( 'order_id' );
			$order_function = new order_functions ();
			$paymentInfo = $order_function->getOrderPaymentDetail($order_id);
			if(count($paymentInfo)>0)
			{
				$payment_name = $paymentInfo[0]->payment_method_class;
				$paymentArr = explode("rs_payment_",$paymentInfo[0]->payment_method_class);
				if(count($paymentArr)>0)
				{
					$payment_name = $paymentArr[1];
				}
				$economicdata['economic_payment_method']= $payment_name;
				$paymentmethod = $order_function->getPaymentMethodInfo($paymentInfo[0]->payment_method_class);
				if(count($paymentmethod)>0)
				{
					$paymentparams 						= new JParameter( $paymentmethod[0]->params );
					$economicdata['economic_payment_terms_id']	= $paymentparams->get('economic_payment_terms_id');
					$economicdata['economic_design_layout']		= $paymentparams->get('economic_design_layout');
					$economicdata['economic_is_creditcard']		= $paymentparams->get('is_creditcard');
				}
			}
			$economic = new economic ();
			$economicdata ['split_payment'] = 0;
			$invoiceHandle = $economic->createInvoiceInEconomic ( $order_id, $economicdata );
			if(ECONOMIC_INVOICE_DRAFT==0)
			{
				$bookinvoicepdf = $economic->bookInvoiceInEconomic($order_id,1);
				if(is_file($bookinvoicepdf))
				{
					$redshopMail = new redshopMail ();
					$ret = $redshopMail->sendEconomicBookInvoiceMail($order_id,$bookinvoicepdf);
				}
			}
		}
		$this->setRedirect ( 'index.php?option=com_redshop&view=order' );
	}
	function export_fullorder_data()
	{
			$extrafile =  JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_redshop' . DS . 'extras' . DS . 'order_export.php';
		if(file_exists($extrafile)){
			require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'extras' . DS . 'order_export.php');
			$orderExport = new orderExport();
			$orderExport->createOrderExport();
			exit;
		}
		# End

		$producthelper = new producthelper ();
		$order_function = new order_functions ();
		$model = $this->getModel ( 'order' );
		$data = $model->export_data ();
		$product_count = array ();
		$db = JFactory::getDBO ();

		$order_id = array();


		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
	 	$order_id = implode(',',$cid);
		$where = "";
		if($order_id!=0)
		{
			//$where .= " where order_id IN (".$order_id.") ";
		}


        $sql = "SELECT order_id,count(order_item_id) as noproduct FROM `#__redshop_order_item`  ".$where." GROUP BY order_id";

		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Content-type: text/x-csv" );
		header ( "Content-type: text/csv" );
		header ( "Content-type: application/csv" );
		header ( 'Content-Disposition: attachment; filename=Order.csv' );


		$db->setQuery ( $sql );
		$no_products = $db->loadObjectList ();

		for($i = 0; $i < count ( $data ); $i ++) {
			$product_count [] = $no_products [$i]->noproduct;
		}



		$no_products = max ( $product_count );


		$shipping_helper=new shipping();
		ob_clean();
		//echo "Order id,Buyer name,Address,Order Status,Order Date,";
		echo "Order number, Order status, Order date , Shipping method , Shipping user, Shipping address, Shipping postalcode, Shipping city, Shipping country, Company name, Email ,Billing address, Billing postalcode, Billing city, Billing country, Billing User ,";
		for($i = 1; $i <= $no_products; $i ++) {
			echo JText::_ ( 'PRODUCT_NAME' ) . $i . ' ,';
			echo JText::_ ( 'PRODUCT' ) . ' ' . JText::_ ( 'PRODUCT_PRICE' ) . $i . ' ,';
			echo JText::_ ( 'PRODUCT_ATTRIBUTE' ) . $i . ' ,';
			//			echo JText::_('PRODUCT_ACCESSORY').$i.',';
		}
		echo "Order Total\n";

		for($i = 0; $i < count ( $data ); $i ++) {

			$billing_info=$order_function->getOrderBillingUserInfo( $data [$i]->order_id );

			$details = explode ( "|", $shipping_helper->decryptShipping( str_replace(" ","+",$data[$i]->ship_method_id) ));

			echo $data [$i]->order_id . ",";
			echo $order_function->getOrderStatusTitle ( $data [$i]->order_status ) . " ,";
			echo date ( 'd-m-Y H:i', $data [$i]->cdate ) . " ,";

			echo str_replace ( ",", " ",$details[1])."(".str_replace ( ",", " ",$details[2]).") ,";
			$shipping_info=$order_function->getOrderShippingUserInfo( $data [$i]->order_id );

			echo str_replace ( ",", " ",$shipping_info->firstname)." ".str_replace ( ",", " ",$shipping_info->lastname)." ,";
			echo str_replace ( ",", " ",$shipping_info->address)." ,";
			echo $shipping_info->zipcode." ,";
			echo str_replace ( ",", " ",$shipping_info->city)." ,";
			echo $shipping_info->country_code." ,";
			echo str_replace ( ",", " ",$shipping_info->company_name)." ,";
			echo $shipping_info->user_email." ,";
			//echo $data [$i]->firstname . " " . $data [$i]->lastname . " ,";


			echo str_replace ( ",", " ",$billing_info->address)." ,";
			echo $billing_info->zipcode." ,";
			echo str_replace ( ",", " ",$billing_info->city)." ,";
			echo $billing_info->country_code." ,";
			echo str_replace ( ",", " ",$billing_info->firstname)." ".str_replace ( ",", " ",$billing_info->lastname)." ,";
			//$user_address = str_replace ( ",", "<br/>", $data [$i]->address );
			//$user_address = strip_tags ( $user_address );
			//echo trim ( $user_address ) . ",";
			//			echo "\"".trim($user_address)."\",";
			//			echo $data[$i]->address.",";





		 	 $no_items = $order_function->getOrderItemDetail ( $data [$i]->order_id );

			for($it = 0; $it < count ( $no_items ); $it ++) {
				echo str_replace ( ",", " ",$no_items [$it]->order_item_name) . " ,";
				echo REDCURRENCY_SYMBOL . $no_items [$it]->product_final_price . " ,";

				$product_attribute = $producthelper->makeAttributeOrder ( $no_items [$it]->order_item_id, 0, $no_items [$it]->product_id, 0, 1 );
				//				$product_attribute = $no_items[$it]->product_attribute;
				$product_attribute = strip_tags ( str_replace ( ",", " ",$product_attribute) );
				//				$product_attribute = str_replace ( "<br />", "\n", $product_attribute );
				//				$product_attribute = str_replace ( "<br/>", "\n", $product_attribute );
				//				echo "\"".trim($product_attribute)."\",";
				echo trim ( $product_attribute ) . " ,";

			//				$product_accessory = $no_items[$it]->product_accessory;
			////				$product_accessory = strip_tags($product_accessory);
			//				$product_accessory = str_replace ( "<br />", "\n", $product_accessory );
			//				$product_accessory = str_replace ( "<br/>", "\n", $product_accessory );
			//				echo "\"".trim($product_accessory)."\",";
			}
			$temp = $no_products - count ( $no_items );
			if($temp >=0)
			{
				echo str_repeat ( ' ,', $temp * 3 );
			}
			echo REDCURRENCY_SYMBOL . $data [$i]->order_total . "\n";
		}
		exit ();
	}
	function export_data() {

		/**
		 * new order export for paid customer support
		 */
		$extrafile =  JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_redshop' . DS . 'extras' . DS . 'order_export.php';
		if(file_exists($extrafile)){
			require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'extras' . DS . 'order_export.php');
			$orderExport = new orderExport();
			$orderExport->createOrderExport();
			exit;
		}
		# End

		$producthelper = new producthelper ();
		$order_function = new order_functions ();
		$model = $this->getModel ( 'order' );
		
		$product_count = array ();
		$db = JFactory::getDBO ();

		$order_id = array();


		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$data = $model->export_data ($cid);
		
	 	$order_id = implode(',',$cid);
		$where = "";
		if($order_id!=0)
		{
			$where .= " where order_id IN (".$order_id.") ";
		}


        $sql = "SELECT order_id,count(order_item_id) as noproduct FROM `#__redshop_order_item`  ".$where." GROUP BY order_id";

		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Content-type: text/x-csv" );
		header ( "Content-type: text/csv" );
		header ( "Content-type: application/csv" );
		header ( 'Content-Disposition: attachment; filename=Order.csv' );


		$db->setQuery ( $sql );
		$no_products = $db->loadObjectList ();

		for($i = 0; $i < count ( $data ); $i ++) {
			$product_count [] = $no_products [$i]->noproduct;
		}



		$no_products = max ( $product_count );

		echo "Order id,Buyer name,Email Id, PhoneNumber,Billing Address ,Billing City,Billing State,Billing Country,BillingPostcode,Shipping Address,Shipping City,Shipping State,Shipping Country,ShippingPostCode,Order Status,Order Date,";
		for($i = 1; $i <= $no_products; $i ++) {
			echo JText::_ ( 'PRODUCT_NAME' ) . $i . ',';
			echo JText::_ ( 'PRODUCT' ) . ' ' . JText::_ ( 'PRODUCT_PRICE' ) . $i . ',';
			echo JText::_ ( 'PRODUCT_ATTRIBUTE' ) . $i . ',';
			//			echo JText::_('PRODUCT_ACCESSORY').$i.',';
		}
		echo "Shipping Cost,Order Total\n";
			//echo "<pre>";
		//	print_r($data);
		for($i = 0; $i < count ( $data ); $i ++) {
			$shipping_address = $order_function->getOrderShippingUserInfo($data [$i]->order_id);
			//echo "<pre>";
		//	print_r($shipping_address);
			
			echo $data [$i]->order_id . ",";
			echo $data [$i]->firstname . " " . $data [$i]->lastname . ",";
			echo $data [$i]->user_email. ",";
			echo $data [$i]->phone. ",";
			$user_address = str_replace ( ",", "<br/>", $data [$i]->address );
			$user_address = strip_tags ( $user_address );
			$user_shipping_address = str_replace ( ",", "<br/>", $shipping_address->address );
			$user_shipping_address = strip_tags ( $user_shipping_address );
			
			echo trim ( $user_address ) . ",";
			echo $data [$i]->city. ",";
			echo $data [$i]->state_code. ",";
			echo $data [$i]->country_code. ",";
			echo $data [$i]->zipcode. ",";
			//			echo "\"".trim($user_address)."\",";
			//			echo $data[$i]->address.",";
			echo trim ( $user_shipping_address ) . ",";
			echo $shipping_address->city. ",";
			echo $shipping_address->state_code. ",";
			echo $shipping_address->country_code. ",";
			echo $shipping_address->zipcode. ",";

			echo $order_function->getOrderStatusTitle ( $data [$i]->order_status ) . ",";
			echo date ( 'd-m-Y H:i', $data [$i]->cdate ) . ",";

		 	 $no_items = $order_function->getOrderItemDetail ( $data [$i]->order_id );

			for($it = 0; $it < count ( $no_items ); $it ++) {
				echo $no_items [$it]->order_item_name . ",";
				echo REDCURRENCY_SYMBOL . $no_items [$it]->product_final_price . ",";

				$product_attribute = $producthelper->makeAttributeOrder ( $no_items [$it]->order_item_id, 0, $no_items [$it]->product_id, 0, 1 );
				//				$product_attribute = $no_items[$it]->product_attribute;
				$product_attribute = strip_tags ( $product_attribute );
				//				$product_attribute = str_replace ( "<br />", "\n", $product_attribute );
				//				$product_attribute = str_replace ( "<br/>", "\n", $product_attribute );
				//				echo "\"".trim($product_attribute)."\",";
				echo trim ( $product_attribute ) . ",";

			//				$product_accessory = $no_items[$it]->product_accessory;
			////				$product_accessory = strip_tags($product_accessory);
			//				$product_accessory = str_replace ( "<br />", "\n", $product_accessory );
			//				$product_accessory = str_replace ( "<br/>", "\n", $product_accessory );
			//				echo "\"".trim($product_accessory)."\",";
			}
			$temp = $no_products - count ( $no_items );
			echo str_repeat ( ',', $temp * 3 );
			if($data [$i]->order_shipping!="")
			{
				$shippingcost= $data [$i]->order_shipping;
			} else {
				$shippingcost=0;
			}
			echo REDCURRENCY_SYMBOL .$shippingcost.",";
			echo REDCURRENCY_SYMBOL . $data [$i]->order_total . "\n";
		}
		exit ();
	}

	function generateParcel() {
		$order_function = new order_functions ();
		$post = JRequest::get ( 'post' );
		$specifiedSendDate = $post ['specifiedSendDate'];
		$db = JFactory::getDBO ();
		$order_id = JRequest::getCmd ( 'order_id' );

		$generate_label= $order_function->generateParcel($order_id,$specifiedSendDate);
		if($generate_label == "success")
		{
			$this->setRedirect ( 'index.php?option=com_redshop&view=order' );
		} else {
			$this->setRedirect ( 'index.php?option=com_redshop&view=order', $generate_label );
		}
	}

	function download_token() {

		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar ( 'option', '', 'request', 'string' );
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		$model = $this->getModel ();

		$download_id_arr = $post ['download_id'];

		for($i = 0; $i < count ( $download_id_arr ); $i ++) {

			$download_id = $download_id_arr [$i];

			$product_download_infinite_var = 'product_download_infinite_' . $download_id;
			$product_download_infinite = $post [$product_download_infinite_var];

			$limit_var = 'limit_' . $download_id;
			$limit = $post [$limit_var];

			$days_var = 'days_' . $download_id;
			$days = $post [$days_var];

			$clock_var = 'clock_' . $download_id;
			$clock = $post [$clock_var];

			$clock_min_var = 'clock_min_' . $download_id;
			$clock_min = $post [$clock_min_var];

			$days = (date ( "H" ) > $clock && $days == 0) ? 1 : $days;

			$product_download_days_time = (time () + ($days * 24 * 60 * 60));

			$endtime = mktime ( $clock, $clock_min, 0, date ( "m", $product_download_days_time ), date ( "d", $product_download_days_time ), date ( "Y", $product_download_days_time ) );

			# if download product is set to infinit
			$endtime = ($product_download_infinite == 1) ? 0 : $endtime;

			$model->updateDownloadSetting ( $download_id, $limit, $endtime );

		}

		$this->setRedirect ( 'index.php?option=com_redshop&view=order_detail&cid[]=' . $cid [0] );
	}
	function gls_export()
	{
		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$model = $this->getModel();
		$model->gls_export($cid);
	}
	function business_gls_export()
	{
		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$model = $this->getModel();
		$model->business_gls_export($cid);
	}
}
?>
