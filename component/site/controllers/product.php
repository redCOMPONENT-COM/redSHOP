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
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'template.php');

/**
 * Product Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class productController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	function displayProductaddprice()
	{
		ob_clean();
		$get = JRequest::get('get');
		$data = array();

		$producthelper = new producthelper();
		$carthelper = new rsCarthelper();
		$total_attribute = 0;

		$product_id = $get['product_id'];
		$quantity = $get['qunatity'];

		$data['attribute_data'] = str_replace("::","##",$get['attribute_data']);
		$data['property_data'] = str_replace("::","##",$get['property_data']);
		$data['subproperty_data'] = str_replace("::","##",$get['subproperty_data']);

		$data['accessory_data'] = $get['accessory_data'];
		$data['acc_quantity_data'] = $get['acc_quantity_data'];

		$data['acc_attribute_data'] = str_replace("::","##",$get['acc_attribute_data']);
		$data['acc_property_data'] = str_replace("::","##",$get['acc_property_data']);
		$data['acc_subproperty_data'] = str_replace("::","##",$get['acc_subproperty_data']);

		$data['quantity'] = $quantity;

		$cartdata = $carthelper->generateAttributeArray($data);
		$retAttArr = $producthelper->makeAttributeCart($cartdata,$product_id,0,'',$quantity);

		$ProductPriceArr = $producthelper->getProductNetPrice($product_id,0,$quantity);

		$acccartdata = $carthelper->generateAccessoryArray($data);
//		print_r($acccartdata);
		$retAccArr = $producthelper->makeAccessoryCart($acccartdata,$product_id);
		$accessory_price = $retAccArr[1];
		$accessory_vat = $retAccArr[2];
//		echo "<pre>";print_r($retAccArr);
//		exit;

		$product_price 				= (($retAttArr[1] + $retAttArr[2])*$quantity) + $accessory_price + $accessory_vat;
		$product_main_price			= (($retAttArr[1] + $retAttArr[2])*$quantity) + $accessory_price + $accessory_vat;
		$product_old_price 			= $ProductPriceArr['product_old_price']*$quantity;
		$product_price_saving 		= $ProductPriceArr['product_price_saving']*$quantity;
		$product_discount_price 	= $ProductPriceArr['product_discount_price']*$quantity;
		$product_price_novat 		= ($retAttArr[1]*$quantity) + $accessory_price;
		$product_price_incl_vat		= ($ProductPriceArr['product_price_incl_vat']*$quantity) + $accessory_price + $accessory_vat;
		$price_excluding_vat 		= ($retAttArr[1]*$quantity) + $accessory_price;
		$seoProductPrice 			= $ProductPriceArr['seoProductPrice']*$quantity;
		$seoProductSavingPrice		= $ProductPriceArr['seoProductSavingPrice']*$quantity;


		echo $product_price.":".$product_main_price.":".$product_old_price.":".$product_price_saving.":".$product_discount_price.":".$product_price_novat.":".$product_price_incl_vat.":".$price_excluding_vat.":".$seoProductPrice.":".$seoProductSavingPrice;
		exit;
	}
	function sendtomail(){
		$post = JRequest::get('post');
		$config 		= &JFactory::getConfig ();
		$from 			= $config->getValue ( 'mailfrom' );
		$fromname 		= $config->getValue ( 'fromname' );
		$email			= $post['sendmailto'];
		$product_id 	= $post['product_id'];
		$ImageName		= $post['imageName'];
		$friend_name  	= $post['friend_name'];
		$producthelper 	= new producthelper();
		$redshopMail 	= new redshopMail();
		$mailbcc=NULL;
		$url			= JURI::base();
		$option 		= JRequest::getVar('option','com_redshop');
		$mailinfo 		= $redshopMail->getMailtemplate(0,"product");
		$data_add 		= "";
		$subject 		= "";
		$product 		= $producthelper->getProductById($product_id);
		$rlink 			= JRoute::_( $url."index.php?option=".$option."&view=product&pid=".$product_id);
		$product_url 	= "<a href=".$rlink.">".$rlink."</a>";

		if(count($mailinfo)>0)
		{
			$data_add = $mailinfo[0]->mail_body;
			$subject = $mailinfo[0]->mail_subject;
			if(trim($mailinfo[0]->mail_bcc)!="")
			{
				$mailbcc= explode(",",$mailinfo[0]->mail_bcc);
			}
		} else {
			$data_add = "<p>Hi {friend_name} ,</p>\r\n<p>New Product  : {product_name}</p>\r\n<p>{product_desc} Please check this link : {product_url}</p>\r\n<p> </p>\r\n<p> </p>";
			$subject = "Send to friend";
		}

		$data_add = str_replace("{friend_name}",$friend_name,$data_add);
//		$data_add = str_replace("{your_name}",$your_name,$data_add);
		$data_add = str_replace("{product_name}",$product->product_name,$data_add);
		$data_add = str_replace("{product_desc}",$product->product_desc,$data_add);
		$data_add = str_replace("{product_url}",$product_url,$data_add);
		$subject  = str_replace("{product_name}",$product->product_name,$subject);
		$subject  = str_replace("{shopname}",SHOP_NAME,$subject);

		$config		= &JFactory::getConfig();
		$from		= $config->getValue('mailfrom');
		$fromname	= $config->getValue('fromname');

		if(!file_exists(JPATH_COMPONENT_SITE.DS."assets/images/mergeImages/".$ImageName))
		{
			$producthelper = new producthelper ();
			$product 	   = $producthelper->getProductById($pid);

			$ImageName = $product->product_full_image;
			if(!$imagename)
			{
				$ImageName = $product->product_thumb_image;;
			}
			$attachment = JPATH_COMPONENT_SITE.DS.'assets'.DS.'images'.DS.'product'.DS.$ImageName;
		}else{
			$attachment = JPATH_COMPONENT_SITE.DS.'assets'.DS.'images'.DS.'mergeImages'.DS.$ImageName;
		}

		$attachment = JPATH_COMPONENT_SITE.DS.'assets'.DS.'images'.DS.'mergeImages'.DS.$ImageName;

		if($email!="")
		{
			if(JUtility::sendMail($from, $fromname, $email, $subject, $data_add, 1, NULL, $mailbcc, $attachment ))
			{
				echo "<div class='' align='center'>".JText::_('EMAIL_HAS_BEEN_SENT_SUCCESSFULLY')."</div>";
			}
			else
			{
				echo "<div class='' align='center'>".JText::_('EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY')."</div>";
			}
		}	exit;

	}
	function displaySubProperty()
	{
		$propid = $subpropid = array();
		$get = JRequest::get('get');
		$producthelper = new producthelper();

		$product_id = $get['product_id'];
		$accessory_id = $get['accessory_id'];
		$relatedprd_id = $get['relatedprd_id'];
		$attribute_id = $get['attribute_id'];
		$isAjaxBox = $get['isAjaxBox'];
		if(isset($get['property_id']) && $get['property_id'])
		{
			$propid = explode(",",$get['property_id']);
		}
		if(isset($get['subproperty_id']) && $get['subproperty_id'])
		{
			$subpropid = explode(",",$get['subproperty_id']);
		}
		$subatthtml = htmlspecialchars_decode(base64_decode(JRequest::getVar( 'subatthtml', '', 'get', 'string', JREQUEST_ALLOWRAW )));

		$response = "";
		for($i=0;$i<count($propid);$i++)
		{
			$property_id = $propid[$i];
			$response .= $producthelper->replaceSubPropertyData($product_id,$accessory_id,$relatedprd_id,$attribute_id,$property_id,$subatthtml,$isAjaxBox,$subpropid);
		}
		echo $response;
		exit;
	}

	function displayAdditionImage()
	{
		$url = JURI::base();
		$get = JRequest::get('get');
        $option = JRequest::getVar ( 'option' );
		$producthelper = new producthelper();

		$property_id = urldecode($get['property_id']);
		$subproperty_id = urldecode($get['subproperty_id']);

		$product_id = $get['product_id'];
	 	$accessory_id = $get['accessory_id'];
		$relatedprd_id = $get['relatedprd_id'];
		$main_imgwidth = $get['main_imgwidth'];
		$main_imgheight = $get['main_imgheight'];
		$redview = $get['redview'];
		$redlayout = $get['redlayout'];
		$dispatcher 				=& JDispatcher::getInstance();
		JPluginHelper::importPlugin('redshop_product');
		$pluginResults = $dispatcher->trigger('onBeforeImageLoad', array ($get));

		if(!empty($pluginResults))
		{
			$mainImageResponse  = $pluginResults[0]['mainImageResponse'];
			$imageTitle 		= $pluginResults[0]['imageTitle'];
			$result = $producthelper->displayAdditionalImage($product_id, $accessory_id, $relatedprd_id, $property_id, $subproperty_id);
		}else{
			$result = $producthelper->displayAdditionalImage($product_id, $accessory_id, $relatedprd_id, $property_id, $subproperty_id, $main_imgwidth, $main_imgheight, $redview, $redlayout);
			$mainImageResponse = $result['mainImageResponse'];
			$imageTitle = $result['imageTitle'];
		}

		$response = $result['response'];
		$aHrefImageResponse = $result['aHrefImageResponse'];
		$aTitleImageResponse = $result['aTitleImageResponse'];
		$stockamountSrc = $result['stockamountSrc'];
		$stockamountTooltip = $result['stockamountTooltip'];
		$ProductAttributeDelivery = $result['ProductAttributeDelivery'];
		$attrbimg = $result['attrbimg'];
		$pr_number = $result['pr_number'];
		$productinstock = $result['productinstock'];
		$stock_status = $result['stock_status'];
		$ImageName = $result['ImageName'];
		$view = $result['view'];
		echo "`_`".$response."`_`".$aHrefImageResponse."`_`".$aTitleImageResponse."`_`".$mainImageResponse."`_`".$stockamountSrc."`_`".$stockamountTooltip."`_`".$ProductAttributeDelivery."`_`".$product_img."`_`".$pr_number."`_`".$productinstock."`_`".$stock_status."`_`".$attrbimg;
		exit;
	}

	/**
	 * Add to wishlist function
	 *
	 * @access public
	 * @return void
	 */
	function addtowishlist(){
		ob_clean();
		global $mainframe;
		$uri =& JURI::getInstance();
		$url= $uri->root();
		$extraField = new extraField();
		$section = 12;
		$row_data = $extraField->getSectionFieldList($section);
		// getVariables
		$cid = JRequest::getInt('cid');
		$producthelper = new producthelper();
		$user = &JFactory::getUser();

		$Itemid = JRequest::getVar('Itemid');

		$option = JRequest::getVar ('option');
		$mywid=JRequest::getVar ('wid');
		$ajaxvar=JRequest::getVar('ajaxon');

		if($ajaxvar==1 && ($mywid==1 || $mywid==2) ){
		$post = JRequest::get('post');

		$post['product_id']=JRequest::getVar('product_id');
		$proname=$producthelper->getProductById($post['product_id']);
		$post['view']=JRequest::getVar('view');
		$post['task']=JRequest::getVar('task');
			for($i=0;$i<count($row_data);$i++)
			{
		    	$field_name=$row_data[$i]->field_name;

		    	$type=$row_data[$i]->field_type;

		    	if(isset($post[$row_data[$i]->field_name]))

					$data_txt= $post[$row_data[$i]->field_name];
				else
					$data_txt='';
				//$tmparray = explode('`',$data_txt);
				$tmpstr = strpbrk($data_txt, '`');
				if($tmpstr){
					$tmparray = explode('`',$data_txt);
					$tmp = new stdClass;
					$tmp = @array_merge($tmp,$tmparray);
					if(is_array($tmparray))
					{
						$data_txt=implode(",",$tmparray);
					}
				}
				//$cart[$idx][$field_name] = $data_txt;
				$post['productuserfield_'.$i] = $data_txt;

			}


		}else{
		$post = JRequest::get('post');
		$proname=$producthelper->getProductById($post['product_id']);
			for($i=0;$i<count($row_data);$i++)
			{
		    	$field_name=$row_data[$i]->field_name;

		    	$type=$row_data[$i]->field_type;

		    	if(isset($post[$row_data[$i]->field_name]))

					$data_txt= $post[$row_data[$i]->field_name];
				else
					$data_txt='';
				//$tmparray = explode('`',$data_txt);
				$tmpstr = strpbrk($data_txt, '`');
				if($tmpstr){
					$tmparray = explode('`',$data_txt);
					$tmp = new stdClass;
					$tmp = @array_merge($tmp,$tmparray);
					if(is_array($tmparray))
					{
						$data_txt=implode(",",$tmparray);
					}
				}
				//$cart[$idx][$field_name] = $data_txt;
				$post['productuserfield_'.$i] = $data_txt;

			}
		}

		$rurl = "";
		if(isset($post['rurl']))
			$rurl = base64_decode($post['rurl']);
		// initiallize variable

		$post['user_id'] = $user->id;

		$post['cdate'] = time();

		$model = $this->getModel ( 'product' );
		if($user->id && $ajaxvar!='1')
		{
			if($model->checkWishlist($post['product_id']) == null){

				if ($model->addToWishlist($post)){
					$mainframe->enqueueMessage(JText::_('WISHLIST_SAVE_SUCCESSFULLY'));
				}else {
					$mainframe->enqueueMessage(JText::_('ERROR_SAVING_WISHLIST'));
				}
			}else{

				$mainframe->enqueueMessage(JText::_('ALLREADY_ADDED_TO_WISHLIST'));
			}
		}
		else
		{
			// user can store wishlist in session
			if($model->addtowishlist2session($post))
			   $mainframe->enqueueMessage(JText::_('WISHLIST_SAVE_SUCCESSFULLY'));
			else
			   $mainframe->enqueueMessage(JText::_('ALLREADY_ADDED_TO_WISHLIST'));
		}
		if($ajaxvar==1)
		{
			sleep(2);
			$getproductimage=$producthelper->getProductById($post['product_id']);
			$finalproductimgname=$getproductimage->product_full_image;
			if($finalproductimgname!=''){
			$mainimg="product/".$finalproductimgname;
			}else{
			$mainimg='noimage.jpg';
			}
			//echo "<div id='my'><a href='index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=".JRequest::getVar('Itemid')."&pid=".$post['product_id']."'>".$proname->product_name."</a></div><br>:-:".$proname->product_name."";
			echo "<span id='basketWrap' ><a href='index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=".JRequest::getVar('Itemid')."&pid=".$post['product_id']."'><img src='".$url."components/com_redshop/assets/images/".$mainimg."' height='30' width='30'/></a></span>:-:".$proname->product_name."";
			exit;
		}elseif($mywid==1){

			$this->setRedirect ( 'index.php?option=' . $option . 'wishlist=1&view=login&Itemid='.$Itemid);

		}
 		if($rurl!="")
 			$this->setRedirect ( $rurl );
 		else
 			$this->setRedirect ( 'index.php?option=' . $option . '&view=product&pid='.$post['product_id'].'&cid='.$cid.'&Itemid='.$Itemid);
	}
	 /**
	 * Add product tag function
	 *
	 * @access public
	 * @return void
	 */

	function addProductTags(){

		global $mainframe;

		// getVariables
		$cid = JRequest::getInt('cid');
		$Itemid = JRequest::getVar('Itemid');
		$option = JRequest::getVar ('option');

		$post = JRequest::get('post');

		// initiallize variable

		$tagnames = $post['tags_name'];
		$productid = $post['product_id'];
		$userid = $post['users_id'];

		$model = $this->getModel ( 'product' );

		$tagnames = preg_split(" ",$tagnames);

		for($i=0;$i<count($tagnames);$i++)
		{
			$tagname = $tagnames[$i];

			if ($model->checkProductTags($tagname,$productid) == null){

				$tags = $model->getProductTags($tagname,$productid);

				if(count($tags) != 0){

					foreach ($tags as $tag){

						if ($tag->product_id == $productid)
						{
							if ($tag->users_id != $userid)
								$counter = 	$tag->tags_counter+1;
						}else
							$counter = 	$tag->tags_counter+1;

						$ntag['tags_id'] = $tag->tags_id;
						$ntag['tags_name'] = $tag->tags_name;
						$ntag['tags_counter'] = $counter;
						$ntag['published'] = $tag->published;
						$ntag['product_id'] = $productid;
						$ntag['users_id'] = $userid;
					}
				}else{

					$ntag['tags_id'] = 0;
					$ntag['tags_name'] = $tagname;
					$ntag['tags_counter'] = 1;
					$ntag['published'] = 1;
					$ntag['product_id'] = $productid;
					$ntag['users_id'] = $userid;
				}

				if ($tags = $model->addProductTags ( $ntag )) {

					$model->addProductTagsXref ( $ntag,$tags );

					$mainframe->enqueueMessage($tagname.'&nbsp;'.JText::_('TAGS_ARE_ADDED'));

				} else {
					$mainframe->enqueueMessage($tagname .'&nbsp;'.JText::_('ERROR_ADDING_TAGS'));
				}

			}else{

				$mainframe->enqueueMessage($tagname.'&nbsp;'.JText::_('ALLREADY_ADDED'));
			}
		}

		$this->setRedirect ( 'index.php?option=' . $option . '&view=product&pid='.$post['product_id'].'&cid='.$cid.'&Itemid='.$Itemid);
	}
	/**
	 * Add to compare function
	 *
	 * @access public
	 * @return product compare list through ajax
	 */
	function addtocompare(){

		ob_clean();
		require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php');

		$producthelper = new producthelper ( );
		// getVariables
		$post = JRequest::get('REQUEST');

		// initiallize variable

		$model = $this->getModel ( 'product' );

		if($post['cmd']=='add')
		{
			$checkCompare = $model->checkComparelist($post['pid']);

			$countCompare = $model->checkComparelist(0);

			if ($countCompare < PRODUCT_COMPARE_LIMIT)
			{
				if($checkCompare)
				{
					if ($model->addtocompare($post))
					{
						$Message = "1`";//.JText::_('PRODUCT_ADDED_TO_COMPARE_SUCCESSFULLY');
					}else{
						$Message = "1`". JText::_('ERROR_ADDING_PRODUCT_TO_COMPARE');
					}
				}else{
					$Message = JText::_('ALLREADY_ADDED_TO_COMPARE');
				}
				$Message .= $producthelper->makeCompareProductDiv();
				echo $Message;
			}else{
				$Message = "0`".JText::_('LIMIT_CROSS_TO_COMPARE');
				echo $Message;
			}
		}
		else if($post['cmd']=='remove')
		{
			$model->removeCompare($post['pid']);
			$Message = "1`".$producthelper->makeCompareProductDiv();
			echo $Message;
		}
		exit;
	}
	/**
	 * remove compare function
	 *
	 * @access public
	 * @return void
	 */
	function removecompare()
	{
		$post = JRequest::get('REQUEST');

		// initiallize variable

		$model = $this->getModel ( 'product' );
		$model->removeCompare($post['pid']);
		parent::display();
	}
	/**
	 * Download Product function
	 *
	 * @access public
	 * @return void
	 */
	function downloadProduct()
	{
		$Itemid = JRequest::getVar('Itemid');
		$model = $this->getModel('product');

		$tid = JRequest::getCmd('download_id',"");

		$data = $model->downloadProduct($tid);

		// today at the end of the day
		$today = time();

		if (count($data) != 0)
		{
			$download_id = $data->download_id;

			// download Product end date
			$end_date = $data->end_date;

			if ( $end_date == 0 || ($data->download_max != 0 && $today <= $end_date ))
			{
				$msg = JText::_("DOWNLOADABLE_THIS_PRODUCT");

				$this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct&tid=".$download_id."&Itemid=".$Itemid,$msg);

			}else {

				$msg = JText::_("DOWNLOAD_LIMIT_OVER");
				$this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct&Itemid=".$Itemid,$msg);
			}
		}else{

			$msg = JText::_("TOKEN_VERIFICATION_FAIL");
			$this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct&Itemid=".$Itemid,$msg);
		}

	}
	/**
	 * Download function
	 *
	 * @access public
	 * @return void
	 */
	function Download()
	{
		$post = JRequest::get('POST');

		$model = $this->getModel('product');

		$tid = $post['tid'];

		$data = $model->downloadProduct($tid);

		$limit = $data->download_max;

		// today at the end of the day
		$today = time();

		// download Product end date
		$end_date = $data->end_date;

		if ( $end_date != 0 && ($limit == 0 || $today > $end_date)){

			$msg = JText::_("DOWNLOAD_LIMIT_OVER");
			$this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct",$msg);

		}else if(isset($post['mainindex']) && isset($post['additional'])){

			$task = $post['mainindex'];

			$id = $post['additional'];

			if($task == "main"){

				$finalname = $model->AdditionaldownloadProduct($id,0,1);

				$name = $finalname[0]->media_name;

			}else if($task == "additional"){

				$finalname = $model->AdditionaldownloadProduct(0,$id);

				$name = $finalname[0]->name;
			}

		}else{
			$msg = JText::_('NO_FILE_SELECTED');
			$this->setRedirect ( 'index.php?option=com_redshop&view=product&layout=downloadproduct&tid='.$tid, $msg );
			return;
		}


		if(isset($post['additional']) && $tid!="" && $end_date == 0 ||  ($limit != 0 && $today <= $end_date)){

			if($model->setDownloadLimit($tid)){

				$baseURL = JURI::root();
				$tmp_name = JPATH_SITE.'/components/com_redshop/assets/download/product/'.$name;

				$tmp_type = strtolower(JFile::getExt($name));

				$downloadname = substr(basename($name),11);

				switch( $tmp_type )
				{
				  case "pdf": $ctype="application/pdf"; break;
				  case "psd": $ctype="application/psd"; break;
				  case "exe": $ctype="application/octet-stream"; break;
				  case "zip": $ctype="application/x-zip"; break;
				  case "doc": $ctype="application/msword"; break;
				  case "xls": $ctype="application/vnd.ms-excel"; break;
				  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
				  case "gif": $ctype="image/gif"; break;
				  case "png": $ctype="image/png"; break;
				  case "jpeg":
				  case "jpg": $ctype="image/jpg"; break;
				  default: $ctype="application/force-download";
				}

				ob_clean();

				header("Pragma: public");
				header('Expires: 0');
				header("Content-Type: $ctype",FALSE);
				header('Content-Length: ' . filesize($name));
				header('Content-Disposition: attachment; filename='.$downloadname);



				// red file using chunksize
				$this->readfile_chunked($name);
				exit;
			}
		}
	}

	/**
	 * file read function
	 *
	 * @access public
	 * @return file data
	 */
	function readfile_chunked($filename,$retbytes=true) {

		$chunksize = 10*(1024*1024); // how many bytes per chunk
	   	$buffer = '';
	   	$cnt =0;

	   	$handle = fopen($filename, 'rb');
	   	if ($handle === false) {
	       return false;
	   	}
	   	while (!feof($handle)) {
	       $buffer = fread($handle, $chunksize);
	       echo $buffer;
	       ob_flush();
	       flush();
	       if ($retbytes) {
	           $cnt += strlen($buffer);
	       }
	   	}

	       $status = fclose($handle);
	  	if ($retbytes && $status) {
	       return $cnt; // return num. bytes delivered like readfile() does.
	   	}
	   	return $status;

	}

	/**
	 * catalog sample send function
	 *
	 * @access public
	 * @return void
	 */
	function catalog_sample_send()
	{
		$post = JRequest::get ( 'post' );

		$option = JRequest::getVar('option','','request','string');

		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'helpers'.DS.'extra_field.php');

		$extra_field = new extra_field();

		$model = $this->getModel ( 'product' );

		$colour_id= @implode(",",$post["sample_code"]);

		$post ['colour_id'] = $colour_id;

		$post ['registerdate'] = time();

		if ($row=$model->catalog_sample_send ( $post )) {

				$extra_field->extra_field_save($post,9,$row->request_id);

				$news_sub = $model->NewsLetter_subscribe($post);

				$msg = JText::_ ( 'SAMPLE_SEND_SUCCSEEFULLY' );

		} else {

				$msg = JText::_ ( 'ERROR_SAMPLE_SEND_SUCCSEEFULLY' );
		}

		$this->setRedirect ( 'index.php?option=' . $option . '&view=product&layout=sample', $msg );

	}
	/**
	 * ajax upload function
	 *
	 * @access public
	 * @return filename on successfull file upload
	 */
	function ajaxupload()
	{
		$uploaddir = JPATH_COMPONENT_SITE.DS.'assets'.DS.'document'.DS.'product'.DS;
		$name = JRequest::getVar('mname');
		$filename = time().'_'. basename($_FILES[$name]['name']);
		$uploadfile = $uploaddir .$filename;
		if (move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)) {
		  echo $filename;
		} else {
		  // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
		  // Otherwise onSubmit event will not be fired
		  echo "error";
		}
		exit;
	}
	/**
	 * ajax upload function
	 *
	 * @access public
	 * @return filename on successfull file download
	 */
	 function downloadDocument()
	{
		$fname = JRequest::getVar ( 'fname', '', 'request', 'string' );
		$fpath = JPATH_SITE.'/components/com_redshop/assets/document/product/'.$fname;
		if(is_file($fpath))
		{
			$tmp_type = strtolower(JFile::getExt($fpath));

			$downloadname = substr(basename($fpath),11);

			switch( $tmp_type )
			{
			  case "pdf": $ctype="application/pdf"; break;
			  case "psd": $ctype="application/psd"; break;
			  case "exe": $ctype="application/octet-stream"; break;
			  case "zip": $ctype="application/x-zip"; break;
			  case "doc": $ctype="application/msword"; break;
			  case "xls": $ctype="application/vnd.ms-excel"; break;
			  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			  case "gif": $ctype="image/gif"; break;
			  case "png": $ctype="image/png"; break;
			  case "jpeg":
			  case "jpg": $ctype="image/jpg"; break;
			  default: $ctype="application/force-download";
			}

			ob_clean();

			header("Pragma: public");
			header('Expires: 0');
			header("Content-Type: $ctype",FALSE);
			header('Content-Length: ' . filesize($fpath));
			header('Content-Disposition: attachment; filename='.$downloadname);



			// red file using chunksize
			$this->readfile_chunked($fpath);
			exit;
		}
	}


	function gotochild(){

		$producthelper = new producthelper();
		$objhelper = new redhelper();

		$post = JRequest::get('post');

		$cid = $producthelper->getCategoryProduct($post['pid']);

		$ItemData = $producthelper->getMenuInformation(0,0,'','product&pid='.$post['pid']);

		if(count($ItemData)>0){
			$pItemid = $ItemData->id;
		}else{
			$pItemid = $objhelper->getItemid($product->product_id,$cid);
		}

		$link = JRoute::_( 'index.php?option=com_redshop&view=product&pid='.$post['pid'].'&cid='.$cid.'&Itemid='.$pItemid, false);

		$this->setRedirect($link);
	}
	
	function gotonavproduct(){

		$producthelper = new producthelper();
		$objhelper = new redhelper();

		$post = JRequest::get('post');
		
		$cid = $producthelper->getCategoryProduct($post['pid']);

		$ItemData = $producthelper->getMenuInformation(0,0,'','product&pid='.$post['pid']);

		if(count($ItemData)>0){
			$pItemid = $ItemData->id;
		}else{
			$pItemid = $objhelper->getItemid($product->product_id,$cid);
		}

		$link = JRoute::_( 'index.php?option=com_redshop&view=product&pid='.$post['pid'].'&cid='.$cid.'&Itemid='.$pItemid, false);

		$this->setRedirect($link);
	}

}