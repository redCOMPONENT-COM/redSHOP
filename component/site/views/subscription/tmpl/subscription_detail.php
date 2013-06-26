<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined ('_JEXEC') or die ('restricted access');
$objhelper      = new redhelper();
$Itemid         = JRequest::getVar('Itemid');
$user 	        =& JFactory::getUser();
$user_id        = $user->id;
$option         = JRequest::getVar('option');
$subid          = JRequest::getVar('catid');
$producthelper  = new producthelper();
$model 		    = $this->getModel('subscription');
$loadSubscriptionDetailTemplate = $this->loadSubscriptionDetailTemplate;
$subscription = new subscriptionModelsubscription();
$uri = & JURI::getInstance ();
$url = $uri->root ();
if(count($loadSubscriptionDetailTemplate)>0 && $loadSubscriptionDetailTemplate[0]->template_desc!="")
{
	$template_desc = $loadSubscriptionDetailTemplate[0]->template_desc;
}
else
{
	$template_desc = "";
}
$template_desc 		 		= 	 str_replace ( "{subscription_detail_frontpage_introtext}", JText::_('COM_REDSHOP_SUBSCRIPTION_FRONTPAGE_INTROTEXT_DETAIL'), $template_desc );
$template_desc 		 		= 	 str_replace ( "{subscription_product_type_lb}", JText::_('COM_REDSHOP_SUBSCRIPTION_DETAIL_TYPE_LABEL'), $template_desc );
$template_desc 		 		= 	 str_replace ( "{subscription_product_feature_lb}", JText::_('COM_REDSHOP_SUBSCRIPTION_DETAIL_FEATURE_LABEL'), $template_desc );
$template_desc 		 		= 	 str_replace ( "{subscription_single_sale}", JText::_('COM_REDSHOP_SUBSCRIPTION_DETAIL_SINGLE_SALE'), $template_desc );
$template_desc 		 		=    str_replace ( "{from_single_sale_label}", JText::_('COM_REDSHOP_SUBSCRIPTION_DETAIL_SINGLE_SALE_LABEL'), $template_desc );
preg_match_all("/{subscription_name:.+?}/",$template_desc, $matches_name, PREG_PATTERN_ORDER);
preg_match_all("/{subscription_price:.+?}/",$template_desc, $matches_price, PREG_PATTERN_ORDER);
preg_match_all("/{subscription_add_to_cart:.+?}/",$template_desc, $matches_add_to_cart, PREG_PATTERN_ORDER);
preg_match_all("/{check_product_main_in_subscription:.+?}/",$template_desc, $matches_check_main_product_in_subscription, PREG_PATTERN_ORDER);
preg_match_all("/{check_product_category_in_subscription:.+?}/",$template_desc, $matches_check_product_category_in_subscription, PREG_PATTERN_ORDER);
//preg_match_all("/{child_subscription_check:.+?}/",$template_desc, $matches_child_subscription_check, PREG_PATTERN_ORDER);
$matches_name                                   = $matches_name[0];
$matches_price                                  = $matches_price[0];
$matches_add_to_cart                            = $matches_add_to_cart[0];
$matches_check_main_product_in_subscription     = $matches_check_main_product_in_subscription[0];
$matches_check_product_category_in_subscription = $matches_check_product_category_in_subscription[0];
//$matches_child_subscription_check = $matches_child_subscription_check[0];
//Check shopper group
$check_shopper                                  = $model->checkShopperGroup($user_id);
if(count($matches_name) > 0)
{
	for($i=0;$i<count($matches_name);$i++)
	{
		$match_name      = explode(":",$matches_name[$i]);
		$product_id      = (int)(trim($match_name[1],'}'));
		$product         = $producthelper->getProductById($product_id);
		//replace name
		$template_desc   = str_replace ( $matches_name[$i], $product->product_name, $template_desc );
		//replace price
		$product_price   = $producthelper->getProductFormattedPrice($product->product_price);
		$template_desc   = str_replace ( $matches_price[$i], $product_price, $template_desc );
		//replace add_to_cart
		$add_to_cart 	 =  $producthelper->replaceCartTemplate($product->product_id,$catid,0,0);
		$template_desc   = str_replace ( $matches_add_to_cart[$i], $add_to_cart, $template_desc );
	}
}

if(strstr($template_desc,"{subscription_product_main_loop_start}") && strstr($template_desc,"{subscription_product_main_loop_end}"))
{
	$subtemplate_desc    = explode ( '{subscription_product_main_loop_start}', $template_desc );
	$subheader           = $subtemplate_desc [0];
	$subtemplate_desc    = explode ( '{subscription_product_main_loop_end}', $subtemplate_desc [1] );
	$middletemplate_desc = $subtemplate_desc[0];
}

$sub_detail = "";
for($i=0;$i<count($this->productmain_in_sub);$i++)
{
	$row            = & $this->productmain_in_sub[$i];
	$data_add       = $middletemplate_desc;
	$main_type      = $model->getProductMainType($row->product_id);
	$main_icon      = $model->getProductMainIcon($row->product_id);
	$cItemid        = $objhelper->getItemid ( $row->product_parent_id );
	if ($cItemid != "")
	{
		$tmpItemid = $cItemid;
	}
	else
	{
		$tmpItemid = $Itemid;
	}
	$link_detail_product_main = JRoute::_ ( 'index.php?option='.$option.'&view=product&pid='.$row->product_id.'&Itemid='.$tmpItemid );

	if($main_icon == "")
	{
		$image_path_no_icon = $url.'components/com_redshop/assets/images/media/no_image.png';
		$image_main_icon = "<a href='$link_detail_product_main'><img alt='image_main_icon' src=$image_path_no_icon  /></a>";
	}
	else
	{
		$image_path = $url.'/components/com_redshop/assets/images/media/'.$main_icon;
		if (@GetImageSize($image_path)) {
		} else {
			$image_path = $url.'components/com_redshop/assets/images/media/no_image.png';
		}
		$image_main_icon = "<a href='$link_detail_product_main'><img alt='image_main_icon' src=$image_path /></a>";
	}
	if(count($matches_check_main_product_in_subscription) > 0)
	{
		for($i=0;$i<count($matches_check_main_product_in_subscription);$i++)
		{
			$match_name_check                         = explode(":",$matches_check_main_product_in_subscription[$i]);
			$product_id                               = (int)(trim($match_name_check[1],'}'));
			$check_main_product_in_subscription       =  $model->checkProductInSubscriptionEx($row->product_id,$product_id);
			if($check_main_product_in_subscription)
			{
				if(($user_id > 0) && ($check_shopper > 0 ))
				{
					$link_download =  JRoute::_ ( 'index.php?option='.$option.'&view=subscription&task=download&id='.$row->product_id.'&Itemid='.$Itemid );
					$result_check  = "<a href='$link_download' ><img alt='checkmark' src='$url/components/com_redshop/assets/images/media/download.png'/></a><input type='checkbox' title='Checkbox for product_$row->product_id'  value=$row->product_id name='cid[]' id='cb$row->product_id'>";
				}
				else
				{
					$result_check  = "<div align='center'><img alt='checkmark' src='$url/components/com_redshop/assets/images/media/checkmark.png' /></div>";
				}
			}
			else
			{
				$result_check= "";
			}
			$data_add 	 = str_replace ( $matches_check_main_product_in_subscription[$i], $result_check, $data_add );
		}
	}
	if($row->product_price > 0 )
	{
		$product_price = $producthelper->getProductFormattedPrice($row->product_price);
	}
	else
	{
		$product_price= JText::_('COM_REDSHOP_SUBSCRIPTION_DETAIL_FREE');
	}
	$subscription_product_main_name         = "<a href='$link_detail_product_main'>".$row->product_name."</a>";
	$subscription_product_main_add_to_cart  = $producthelper->replaceCartTemplate($row->product_id);
	$subscription_product_main_checkbox     = "<input type='checkbox' title='Checkbox for product_$row->product_id'  value=$row->product_id name='cid[]' id='cb$row->product_id'>";
	$data_add 	                            = str_replace ( "{subscription_product_main_type}", $main_type, $data_add );
	$data_add 	                            = str_replace ( "{subscription_product_main_name}", $subscription_product_main_name, $data_add );
	$product_filenames                      = $producthelper->getAdditionMediaImage($row->product_id, 'product', 'download');
	krsort($product_filenames);
	if(count($product_filenames) > 0)
	{
		foreach($product_filenames as $medias)
		{
			$product_filename_main = end(explode('/', $medias->media_name));
			$product_filename_main = explode('_', $product_filename_main);
			$product_release_date_main  = $product_filename_main[0];
			unset($product_filename_main[0]);
			$product_filename_main = implode('_', $product_filename_main);
			break;
		}
		$subscription_product_release_date_main = JText::_('COM_REDSHOP_ACCOUNT_RELEASE_DATE').": ".date("d/m/Y", $product_release_date_main);
	}
	else
	{
		$subscription_product_release_date_main ="";
		$product_filename_main = "";
	}
	$data_add 	 = str_replace ( "{subscription_product_release_date_main}",$subscription_product_release_date_main, $data_add );
	$data_add 	 = str_replace ( "{subscription_product_filename_main}", $product_filename_main, $data_add );
	$data_add 	 = str_replace ( "{subscription_product_main_icon}", $image_main_icon, $data_add );
	$data_add 	 = str_replace ( "{subscription_product_main_price}", $product_price, $data_add );
	$data_add 	 = str_replace ( "{subscription_product_main_add_to_cart}", $subscription_product_main_add_to_cart, $data_add );
	$data_add 	 = str_replace ( "{subscription_product_main_checkbox}", $subscription_product_main_checkbox, $data_add );
	$sub_detail .= $data_add;
}
$template_desc = str_replace ( $middletemplate_desc, $sub_detail, $template_desc );
$template_desc = str_replace ( "{subscription_product_main_loop_start}", "", $template_desc );
$template_desc = str_replace ( "{subscription_product_main_loop_end}", "", $template_desc );

if(strstr($template_desc,"{subscription_category_loop_start}") && strstr($template_desc,"{subscription_category_loop_end}"))
{
	$subtemplate_desc           = explode ( '{subscription_category_loop_start}', $template_desc );
	$subheader                  = $subtemplate_desc [0];
	$subtemplate_desc           = explode ( '{subscription_category_loop_end}', $subtemplate_desc [1] );
	$middletemplate_desc        = $subtemplate_desc[0];
}
$sub_detail = "";

for($v=0;$v<count($this->category_in_sub);$v++)
{
	$row        = & $this->category_in_sub[$v];
	$data_add   = $middletemplate_desc;
	if(strstr($middletemplate_desc,"{product_subscription_category_loop_start}") && strstr($middletemplate_desc,"{product_subscription_category_loop_end}"))
	{
		$protemplate_desc       = explode ( '{product_subscription_category_loop_start}', $middletemplate_desc );
		$proheader              = $protemplate_desc [0];
		$protemplate_desc       = explode ( '{product_subscription_category_loop_end}', $protemplate_desc [1] );
		$middleprotemplate_desc = $protemplate_desc[0];
	}
	$product_in_category = $model->getProductInCategory($row->category_id);
	$pro_detail = "";
	for($n=0;$n<count($product_in_category);$n++)
	{
		$cItemid = $objhelper->getCategoryItemid ( $row->category_id );
		if ($cItemid != "") 
		{
			$tmpItemid = $cItemid;
		} 
		else 
		{
			$tmpItemid = $Itemid;
		}
		$link_detail_product_in_category = JRoute::_ ( 'index.php?option='.$option.'&view=product&pid='.$product_in_category[$n]->product_id.'&cid='.$row->category_id.'&Itemid='.$tmpItemid );
		$product_id                      = $product_in_category[$n]->product_id;
		$data_add_pro 	                 = $middleprotemplate_desc;
		$main_type                       = $model->getProductMainType($product_id);
		$main_icon                       = $model->getProductMainIcon($product_id);
		if($main_icon == "")
		{
			$image_path_no_icon = $url.'components/com_redshop/assets/images/media/no_image.png';
			$image_main_icon = "<a href='$link_detail_product_in_category'><img alt='image_main_icon' src=$image_path_no_icon /></a>";
		}
		else
		{
			$image_path = $url.'components/com_redshop/assets/images/media/'.$main_icon;
			if (@GetImageSize($image_path)) {
			} else {
			$image_path = $url.'components/com_redshop/assets/images/media/no_image.png';
			}
			$image_main_icon = "<a href='$link_detail_product_in_category'><img alt='image_main_icon' src=$image_path  /></a>";
		}
		//check product in subscription
		if(count($matches_check_product_category_in_subscription) > 0)
		{
			for($i=0;$i<count($matches_check_product_category_in_subscription);$i++)
			{
				$match_name_check_ex                             = explode(":",$matches_check_product_category_in_subscription[$i]);
				$product_id_sub                                  = (int)(trim($match_name_check_ex[1],'}'));
				$check_product_in_subscription                   = $model->checkProductInSubscriptionEx($product_id,$product_id_sub);
				if($check_product_in_subscription)
				{
					if(($user_id > 0) && ($check_shopper > 0 ))
					{
						$link_download    =  JRoute::_ ( 'index.php?option='.$option.'&view=subscription&task=download&id='.$product_id.'&Itemid='.$Itemid );
						$result_check_ex  = "<a href='$link_download' ><img alt='checkmark' src='$url/components/com_redshop/assets/images/media/download.png'/></a><input type='checkbox' title='Checkbox for product_$row->product_id'  value=$product_id name='cid[]' id='1-cb$product_id'>";
					}
					else
					{
						$result_check_ex  = "<div align='center'><img alt='checkmark' src='$url/components/com_redshop/assets/images/media/checkmark.png' /></div>";
					}
				}
				else
				{
					$result_check_ex= "";
				}
				$data_add_pro 	 = str_replace ( $matches_check_product_category_in_subscription[$i], $result_check_ex, $data_add_pro );
			}
		}

		if($product_in_category[$n]->product_price > 0 )
		{
			$product_price =  $producthelper->getProductFormattedPrice($product_in_category[$n]->product_price) ;
			$price         =  $product_in_category[$n]->product_price ;
		}else{
			$product_price = JText::_('COM_REDSHOP_SUBSCRIPTION_DETAIL_FREE');
			$price         = 0;
		}

		$subscription_product_category_add_to_cart  = $producthelper->replaceCartTemplate($product_id);
		$subscription_product_category_checkbox 	= "<input type='checkbox' title='Checkbox for product_$product_id'  value='$product_id' name='cid[]' id='cb$product_id' rel='$price' onclick='calctotal($product_id)'>";
		$data_add_pro 	                            = str_replace ( "{subscription_product_category_type}", $main_type, $data_add_pro );
		$product_link_detail                        = "<a href='$link_detail_product_in_category'>".$product_in_category[$n]->product_name."</a>";
		$data_add_pro 	                            = str_replace ( "{subscription_product_category_name}", $product_link_detail, $data_add_pro );
		$product_filenames                          = $producthelper->getAdditionMediaImage($product_id, 'product', 'download');
		krsort($product_filenames);
		if(count($product_filenames) > 0)
		{
			foreach($product_filenames as $medias)
			{
				$product_filename     = end(explode('/', $medias->media_name));
				$product_filename     = explode('_', $product_filename);
				$product_release_date = $product_filename[0];
				unset($product_filename[0]);
				$product_filename     = implode('_', $product_filename);
				break;
			}
			$subscription_product_release_date = JText::_('COM_REDSHOP_ACCOUNT_RELEASE_DATE').date("d/m/Y", $product_release_date);
		}
		else
		{
			$subscription_product_release_date ="";
			$product_filename = "";
		}
		$data_add_pro 	 = str_replace ( "{subscription_product_release_date}",$subscription_product_release_date, $data_add_pro );
		$data_add_pro 	 = str_replace ( "{subscription_product_filename}", $product_filename, $data_add_pro );
		$data_add_pro 	 = str_replace ( "{subscription_product_category_icon}", $image_main_icon, $data_add_pro );
		$data_add_pro 	 = str_replace ( "{subscription_product_category_price}", $product_price, $data_add_pro );
		$data_add_pro 	 = str_replace ( "{subscription_product_category_add_to_cart}", $subscription_product_category_add_to_cart, $data_add_pro );
		$data_add_pro 	 = str_replace ( "{subscription_product_category_checkbox}",$subscription_product_category_checkbox, $data_add_pro );
		$pro_detail 	.= $data_add_pro;
	}
	$data_add  		     = str_replace ( $middleprotemplate_desc, $pro_detail, $data_add );
	$data_add 		     = str_replace ( "{product_subscription_category_loop_start}", "", $data_add );
	$data_add 		     = str_replace ( "{product_subscription_category_loop_end}", "", $data_add );
	$data_add 	 	     = str_replace ( "{subscription_category_name}", $row->category_name, $data_add );
	$sub_detail         .= $data_add;
}
$template_desc = str_replace ( $middletemplate_desc, $sub_detail, $template_desc );
$template_desc = str_replace ( "{subscription_category_loop_start}", "", $template_desc );
$template_desc = str_replace ( "{subscription_category_loop_end}", "", $template_desc );
$template_desc = str_replace ( "{subscription_12month_add_to_cart}",$add_cart_12month, $template_desc );
$template_desc = str_replace ( "{subscription_12monthpro_add_to_cart}",$add_cart_12monthpro, $template_desc );
echo eval("?>".$template_desc."<?php ");
?>

<script type="text/javascript">
	Array.prototype.remove= function(){
	     var what, a= arguments, L= a.length, ax;
	     while(L && this.length){
	         what= a[--L];
	         while((ax= this.indexOf(what))!= -1){
	             this.splice(ax, 1);
	         }
	     }
	     return this;
	 }

	 $(document).ready(function() {
	  var cids = new Array();
	  $("input[name='cid[]']").click(function(){
	   if($(this).is(':checked'))
	   {
	    cids.push($(this).val());
	   }
	   else
	   {
	    cids.remove($(this).val());
	   }
	   var a = cids.join(',');
	   $("#products_checked").val(a);
	  });
	})

	/*
	function calctotal(product_id)
	{
		var sum = parseInt($("#sum1").val());
		if($("#cb"+product_id).is(':checked')){
			 sum += parseInt($("#cb"+product_id).attr("rel"));
		} 
		else
		{
			sum -= $("#cb"+product_id).attr("rel");
		}
		if(sum<0){
			sum = 0;	
		}
		$("#sum1").val(sum);
		$("#total_id").html("$ "+$("#sum1").val());
	}
	*/
</script>