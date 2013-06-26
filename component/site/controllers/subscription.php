<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );
class subscriptionController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}

	function addFavouriteAndBuyCheck()
	{
		$model = & $this->getModel("subscription");
		$post 	= JRequest::get('post');
		$view = $post['view'];
		$layout = $post['layout'];
		$tag = $post['tag'];
		$submit = $post['btnSubmit'];
		$array_product = explode(",", $post['products_checked']);
		$selected_file = $post['selected_file'];
		$selected_file_final = array();
		if(count($array_product) > 0 && count($selected_file ) > 0)
		{
			for($m=0;$m<count($array_product);$m++)
			{
				$key = $array_product[$m];
				$selected_file_final[] = $selected_file[$key];
			}
		}	
		$add_products = implode(",",$array_product);
		$session =& JFactory::getSession();
		$session->set( 'add_products', $add_products );
		$Itemid =	$post['Itemid'];
		$subid  = 	$post['sid'];
		$user =& JFactory::getUser();
		if($submit == "AddFavourite" )
		{
			if($user->id >0)
			{
				if(count($array_product) > 0 && $array_product[0] <> "" )
				{
					if($submit == "AddFavourite")
					{
						$this->setRedirect( 'index.php?option=com_redshop&view=subscription&layout=wishlist&Itemid='.$Itemid);
						
					}
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_NOT_HAVE_PRODUCTS_FAVOURITE_ARE_SELECTED');
					if($tag == "download")
					{
						$this->setRedirect( 'index.php?option=com_redshop&view=account&tag='.$tag.'&Itemid='.$Itemid,$msg );
					}
					else
					{
						$this->setRedirect( 'index.php?option=com_redshop&view='.$view.'&catid='.$subid.'&layout='.$layout.'&Itemid='.$Itemid,$msg );
					}
				}
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_LOGIN_NEWWISHLIST');
				$this->setRedirect( 'index.php?option=com_redshop&view='.$view.'&catid='.$subid.'&layout='.$layout.'&Itemid='.$Itemid,$msg );

			}
		}
		else if($submit == "BuyCheck")
		{
			if(count($array_product) > 0 && $array_product[0] <> "" )
			{
				if($model->AddProductToCart($add_products))
				{
					$msg = JText::_('COM_REDSHOP_PRODUCTS_ADDED_TO_CART');
					$this->setRedirect( 'index.php?option=com_redshop&view='.$view.'&catid='.$subid.'&layout='.$layout.'&Itemid='.$Itemid,$msg );
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_NO_PRODUCTS_ADDED_TO_CART');
					$this->setRedirect( 'index.php?option=com_redshop&view='.$view.'&catid='.$subid.'&layout='.$layout.'&Itemid='.$Itemid,$msg );
				}
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_NOT_HAVE_PRODUCTS_ADD_TO_CART_ARE_SELECTED');
				$this->setRedirect( 'index.php?option=com_redshop&view='.$view.'&catid='.$subid.'&layout='.$layout.'&Itemid='.$Itemid,$msg );
			}
		}
		else if($submit == "Downloadcheck")
		{
			
			if(count($array_product) > 0 && $array_product[0] <> "" )
			{
				if(count($selected_file_final) > 0 )
				{
					$this->download_check_product($array_product,$selected_file_final);
				}
				else
				{
				$check_shopper_group_12month 	 = $model->checkShopperGroup12month($user->id);
				$check_shopper_group_12month_pro = $model->checkShopperGroup12monthPro($user->id);
				if($check_shopper_group_12month && $check_shopper_group_12month_pro)
				{
					$check_shopper_group_12month = 0;
					$check_shopper_group_12month_pro = 1;
				}
				$subcription_total 				 = $model->getPriceSubscription12monthDetail($subid);
				$total = count($array_product);
				if($check_shopper_group_12month)
				{
					for($i=0;$i<$total;$i++)
					{
						$check_product_in_subs12month = $model->checkProductInSubscription12month($array_product[$i],$subcription_total[0]->product_id);
						if(!$check_product_in_subs12month)						
						{
							unset($array_product[$i]);
						}
					}
					sort($array_product);
					if(count($array_product) > 0)
					{
						$this->download_check_product($array_product);
					}
					else
					{
						$msg = JText::_('COM_REDSHOP_NOT_HAVE_PRODUCTS_DOWNLOAD_CHECK_ARE_SELECTED');
						if($tag == "download")
						{
							$this->setRedirect( 'index.php?option=com_redshop&view=account&tag='.$tag.'&Itemid='.$Itemid,$msg );
						}
						else
						{
							$this->setRedirect( 'index.php?option=com_redshop&view='.$view.'&catid='.$subid.'&layout='.$layout.'&Itemid='.$Itemid,$msg );
						}
					}
				}
				else if($check_shopper_group_12month_pro)
				{
					
					for($i=0;$i<$total;$i++)
					{
						$check_product_in_subs12month_pro = $model->checkProductInSubscription12monthpro($array_product[$i],$subcription_total[1]->product_id);
						if(!$check_product_in_subs12month_pro)						
						{
							unset($array_product[$i]);
						}
					}
					sort($array_product);
					if(count($array_product) > 0)
					{
						$this->download_check_product($array_product);
					}
					else
					{
						$msg = JText::_('COM_REDSHOP_NOT_HAVE_PRODUCTS_DOWNLOAD_CHECK_ARE_SELECTED');
						if($tag == "download")
						{
							$this->setRedirect( 'index.php?option=com_redshop&view=account&tag='.$tag.'&Itemid='.$Itemid,$msg );
						}
						else
						{
							$this->setRedirect( 'index.php?option=com_redshop&view='.$view.'&catid='.$subid.'&layout='.$layout.'&Itemid='.$Itemid,$msg );
						}
					}
				}
			  }
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_NOT_HAVE_PRODUCTS_DOWNLOAD_CHECK_ARE_SELECTED');
				if($tag == "download")
					{
						$this->setRedirect( 'index.php?option=com_redshop&view=account&tag='.$tag.'&Itemid='.$Itemid,$msg );
					}
					else
					{
						$this->setRedirect( 'index.php?option=com_redshop&view='.$view.'&catid='.$subid.'&layout='.$layout.'&Itemid='.$Itemid,$msg );
					}
			}
		}

	}

	function savewishlist()
	{
		global $mainframe;
		$model = & $this->getModel("subscription");
		$post 	= JRequest::get('post');
		$view = $post['view'];
		$layout = $post['layout'];
		$submit = $post['btnSubmit'];
		$array_product = explode(",", $post['products_checked']);
		$add_products = implode(",",$array_product);
		$Itemid =	$post['Itemid'];
		if($model->savewishlist($post))
		{
				$msg = JText::_('COM_REDSHOP_PRODUCT_SAVED_IN_WISHLIST_SUCCESSFULLY_POPUP');
				$this->setRedirect( 'index.php?option=com_redshop&view=subscription&layout=wishlist&Itemid='.$Itemid,$msg );
		}
		else
		{
				$msg = JText::_('COM_REDSHOP_PRODUCT_NOT_SAVED_IN_WISHLIST');
				$this->setRedirect( 'index.php?option=com_redshop&view=subscription&layout=wishlist&Itemid='.$Itemid,$msg );
		}
	}

	function createsave()
	{
		$user = &JFactory::getUser ();
		$model = & $this->getModel("subscription");
		$post 	= JRequest::get('post');
		$Itemid = $post['Itemid'];
		$post ['wishlist_name'] = JRequest :: getVar('txtWishlistname');
		$post ['user_id'] = $user->id;
		$post ['cdate'] = time();
		if($model->storeWishList($post))
		{
			$msg = JText::_('COM_REDSHOP_PRODUCT_SAVED_IN_WISHLIST_SUCCESSFULLY');
			$this->setRedirect( 'index.php?option=com_redshop&view=subscription&layout=wishlist&Itemid='.$Itemid,$msg );
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_PRODUCT_NOT_SAVED_IN_WISHLIST');
			$this->setRedirect( 'index.php?option=com_redshop&view=subscription&layout=wishlist&Itemid='.$Itemid,$msg );
		}
	}

	function download_check_product($array_product,$selected_file_final = array())
	{
			$user = &JFactory::getUser ();
			$user_id = $user->id;
			$model 	 = $this->getModel('subscription');
			$flag = false;
			if(count($selected_file_final) > 0)
			{
				for($i=0;$i<count($selected_file_final);$i++)
				{
					$namepro    		= $model->getNameProductMedia($selected_file_final[$i]);
					$nameprodl  		= substr(basename($namepro),0);
					$tmp_name   		= JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'assets'.DS.'download'.DS.'product'.DS.$nameprodl;
					$product[]			= $tmp_name;
				}
			}
			else if(count($array_product) > 0)
			{
				for($i=0;$i<count($array_product);$i++)
				{
					$namepro    		= $model->getNameProduct($array_product[$i]);
					$nameprodl  		= substr(basename($namepro),0);
					$tmp_name   		= JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'assets'.DS.'download'.DS.'product'.DS.$nameprodl;
					$product[]			= $tmp_name;
				}
			}
			if(count($product) > 0)
			{
				$baseURL = JURI::root();
				$datetime = &JFactory::getDate();
				$date = $datetime->toFormat("%Y-%m-%d-%H-%M-%S");
				$nameprod = $date . $user->id . '.zip';
				$base_name_zip = JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'assets'.DS.'download'.DS.'product'.DS.$nameprod;
				$check_create_zip = $this->create_zip($product,$base_name_zip,true);
				if($check_create_zip)
				{
					$tmp_type = strtolower(JFile::getExt($nameprod));
					$model->addProductInSubscriptionDownloadTableDownloadCheck($user_id,$array_product,$product);
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
					// Fix
					header('Content-Length: ' . filesize($base_name_zip));
					header('Content-Disposition: attachment; filename='.$nameprod);
					header('Connection: close');
					// red file using chunksize
					$this->readfile_chunked($base_name_zip);
					exit;
				}
			}
	}


   function create_zip($files = array(),$destination = '',$overwrite = false)
   {
 		$valid_files = array();
  		if(is_array($files))
  		{
   			foreach($files as $file)
   			{
   				if(file_exists($file))
      			{
        			$valid_files[] = $file;
      			}
    		}
  		}
  		if(count($valid_files))
  		{
    		$zip = new ZipArchive();
   			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true)
   			{
      			return false;
   	 		}
    		foreach($valid_files as $file)
    		{
     			$new_name  = substr(basename($file),11);
    			$zip->addFile($file,$new_name);
    		}
    		$zip->close();
    		return true;
  		}
	}


	function download()
	{
			//Bonus add number of download products in a subscription
			$user = &JFactory::getUser ();
			$model 	 = $this->getModel('subscription');
			$user_id = $user->id;
			if($user_id > 0 )
			{
				//$flag = false;
				$product_id = JRequest::getInt('id');
				$media_id   = JRequest::getInt('media_id');
				if($media_id > 0 )
				{
					$namepro = $model->getNameProductMedia($media_id);	
				}
				else
				{
					$namepro = $model->getNameProduct($product_id);
				}
				$nameprodl = substr(basename($namepro),11);
				$baseURL = JURI::root();
				$tmp_name = JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'assets'.DS.'download'.DS.'product'.DS.$nameprodl;
				$tmp_type = strtolower(JFile::getExt($namepro));
				if($namepro <> "")
				{
					//$flag = $model->addProductInSubscriptionDownloadTable($user_id,$product_id,$tmp_name);
					//if($flag)
					//{
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
						header('Content-Length: ' . filesize($namepro));
						header('Content-Disposition: attachment; filename='.$nameprodl);
						// red file using chunksize
						$this->readfile_chunked($namepro);
						exit;
					//}
				}
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_LOGIN_DESCRIPTION');
				$this->setRedirect( 'index.php',$msg );
			}
	}

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
}?>
