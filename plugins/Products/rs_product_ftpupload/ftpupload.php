<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.plugin.plugin');
require_once ( JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
class plgredshop_productftpupload extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	 function plgredshop_productftpupload( &$subject )
	 {
	    parent::__construct( $subject );

	    // load plugin parameters
	     $this->_table_prefix = '#__redshop_';
	    $this->_plugin = JPluginHelper::getPlugin( 'redshop_product', 'ftpupload' );
	    $this->_params = new JParameter( $this->_plugin->params );
	 }

	/**
	 * Example prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param 	object		The Product Template Data
	 * @param 	object		The product params
	 * @param 	object		The product object
	 */
	 function afterOrderPlace($cart,$order)
	 {
	 	
        $db = JFactory::getDBO();
	 	$order_id= $order->order_id;
	 	$order_functions = new order_functions();
	 	// get params from plugin
	 	
	 	$ftpupload_parameters=$this->getparameters('ftpupload');
		$labelinfo = $ftpupload_parameters[0];
		$labelparams = new JParameter( $labelinfo->params );

		
		$ftp_host = $labelparams->get('ftp_host','');
		$ftp_username = $labelparams->get('ftp_username','');
		$ftp_password = $labelparams->get('ftp_password','');
		$path_for_sharing_folder = $labelparams->get('path_for_sharing_folder','');
	 	
	 	
	 	
	   	$orderproducts = $order_functions->getOrderItemDetail ( $order_id );
	   	
	   	for($p=0;$p<count($orderproducts);$p++)
	   	{
	   		$order_item_id = $orderproducts[$p]->order_item_id;
	   		
	    	$sel = "select o.*,fd.*,f.* from ".$this->_table_prefix."fields_data fd left outer join  ".$this->_table_prefix."order_item o on o.order_item_id=fd.itemid  left outer join  ".$this->_table_prefix."fields f on f.field_id =fd.fieldid  where fd.section=12 and f.field_type = 10  and o.order_item_id=".$order_item_id;
    		$db->setQuery($sel);
			$params=$db->loadObjectList();
			
			for($f=0;$f<count($params);$f++)
			{
				$upload_file[] = $params[$f]->data_txt;
				$upload_file_path[] =JPATH_COMPONENT_SITE.DS."assets/document/product/".$params[$f]->data_txt;
			}
			
	   		
	   	}
	   
	   	
	   			//Connect to the FTP server
				$ftpstream = @ftp_connect($ftp_host);
				$str=$path_for_sharing_folder;
				$last = $str[strlen($str)-1];
				if($last== "/")
				{
					$slash="";
				}else {
					$slash="/";
				}
				//Login to the FTP server
				$login = @ftp_login($ftpstream, $ftp_username, $ftp_password);
				if($login) {
					
					@ftp_mkdir($ftpstream, $path_for_sharing_folder.$slash.$order_id);
					for($u=0;$u<count($upload_file_path);$u++)
					{
						$upload = ftp_put($ftpstream, $path_for_sharing_folder.$slash.$order_id."/".$upload_file[$u], $upload_file_path[$u], FTP_ASCII);
					}
					
				}
			
				//Close FTP connection
				ftp_close($ftpstream);
	   	
	 }
	 
	 
	 
	function getparameters($payment){
		$db = JFactory::getDBO();
		$sql="SELECT * FROM #__plugins WHERE `element`='".$payment."'";
		$db->setQuery($sql);
		$params=$db->loadObjectList();
		return $params;
	}

	 /**
	 * Example after display title method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param 	object		The Product Template Data
	 * @param 	object		The product params
	 * @param 	int			The product object
	 * @return	string
	 */
	 function onAfterDisplayProductTitle(&$template, &$params ,$product){

	 	$string = "";

	 	return $string;
	 }

	 /**
	 * Example before display redSHOP Product method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param 	object		The Product Template Data
	 * @param 	object		The product params
	 * @param 	int			The product object
	 * @return	string
	 */
	 function onBeforeDisplayProduct(&$template, &$params ,$product){

	 	$string = "";

	 	return $string;
	 }

	 /**
	 * Example after display redSHOP Product method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param 	object		The Product Template Data
	 * @param 	object		The Product params
	 * @param 	int			The product object
	 * @return	string
	 */
	 function onAfterDisplayProduct(&$template, &$params ,$product){

	 	$string = "";

	 	return $string;
	 }

	 /**
	 * Example before save Product method
	 *
	 * Method is called right before product is saved into the database.
	 * Product object is passed by reference, so any changes will be saved!
	 * NOTE:  Returning false will abort the save with an error.
	 * 	You can set the error by calling $product->setError($message)
	 *
	 * @param 	object		A JTableproduct_detail object
	 * @param 	bool		If the product is just about to be created
	 * @return	bool		If false, abort the save
	 */
	 function onBeforeProductSave(&$product,$isnew){
		return true;
	 }

	 /**
	 * Example after save product method
	 * Product is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the product is saved
	 *
	 *
	 * @param 	object		A JTableproduct_detail object
	 * @param 	bool		If the product is just about to be created
	 * @return	void
	 */
	 function onAfterProductSave(&$product,$isnew){
	 	return ;
	 }
}
?>