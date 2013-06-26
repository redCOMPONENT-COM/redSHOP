<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined ('_JEXEC') or die ('restricted access');
$objhelper = new redhelper();
$producthelper = new producthelper();
$Itemid = JRequest::getVar('Itemid');
$catid  = JRequest::getVar('cid', 0, '', 'int');
$option = JRequest::getVar('option');
$model = $this->getModel('subscription');
$loadSubscriptionOverviewTemplate = $this->loadSubscriptionOverviewTemplate;
if(count($loadSubscriptionOverviewTemplate)>0 && $loadSubscriptionOverviewTemplate[0]->template_desc!="")
{
	$template_desc = $loadSubscriptionOverviewTemplate[0]->template_desc;
} 
else 
{
	$template_desc = "";
}
$template_desc 		 =   str_replace ( "{subscription_frontpage_introtext}", JText::_('COM_REDSHOP_SUBSCRIPTION_FRONTPAGE_INTROTEXT'), $template_desc );
$template_desc 		 =   str_replace ( "{subscription_frontpage_description}", JText::_('COM_REDSHOP_SUBSCRIPTION_FRONTPAGE_DESCRIPTION'), $template_desc );
preg_match_all("/{subscription_name:.+?}/",$template_desc, $matches_name, PREG_PATTERN_ORDER);
preg_match_all("/{subscription_price:.+?}/",$template_desc, $matches_price, PREG_PATTERN_ORDER);
preg_match_all("/{subscription_add_to_cart:.+?}/",$template_desc, $matches_add_to_cart, PREG_PATTERN_ORDER);
preg_match_all("/{child_subscription_check:.+?}/",$template_desc, $matches_child_subscription_check, PREG_PATTERN_ORDER);
$matches_name                     = $matches_name[0];
$matches_price                    = $matches_price[0];
$matches_add_to_cart              = $matches_add_to_cart[0];
$matches_child_subscription_check = $matches_child_subscription_check[0];
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
if(strstr($template_desc,"{subscription_loop_start}") && strstr($template_desc,"{subscription_loop_end}"))
{
	$subtemplate_desc = explode ( '{subscription_loop_start}', $template_desc );
	$subheader = $subtemplate_desc [0];
	$subtemplate_desc = explode ( '{subscription_loop_end}', $subtemplate_desc [1] );
	$middletemplate_desc = $subtemplate_desc[0];
}
$sub_detail = "";
for ($i=0;$i<count($this->detail);$i++)
{
	$row = & $this->detail[$i];
	$data_add = $middletemplate_desc;
	if(strstr($middletemplate_desc,"{child_subscription_loop_start}") && strstr($middletemplate_desc,"{child_subscription_loop_end}"))
	{
		$protemplate_desc = explode ( '{child_subscription_loop_start}', $middletemplate_desc );
		$proheader = $protemplate_desc [0];
		$protemplate_desc = explode ( '{child_subscription_loop_end}', $protemplate_desc [1] );
		$middleprotemplate_desc = $protemplate_desc[0];
	}
	$cItemid = $objhelper->getCategoryItemid ( $row->category_id );
	if ($cItemid != "")
	{
		$tmpItemid = $cItemid;
	}
	else
	{
		$tmpItemid = $Itemid;
	}
	$product_in_subscription = $model->getProductInSubscription($row->category_id);
	$pro_detail = "";
	for($n=0;$n<count($product_in_subscription);$n++)
	{
		$link_cat_detail 	= JRoute::_ ( 'index.php?option='.$option.'&view=subscription&catid='.$row->category_id.'&layout=detail&Itemid='.$tmpItemid . '#' . $product_in_subscription[$n]->category_name);
		$category_name_link = "<a href='".$link_cat_detail."'>".$product_in_subscription[$n]->category_name."</a>";
		$data_add_pro 	 	= $middleprotemplate_desc;
		$data_add_pro 	 	= str_replace ( "{child_subscription_name}",$category_name_link, $data_add_pro );
		if(count($matches_child_subscription_check) > 0 )
		{
			for($h=0;$h<count($matches_child_subscription_check);$h++)
			{
				$match_child_subscription_check        = explode(":",$matches_child_subscription_check[$h]);
				$product_id                            = (int)(trim($match_child_subscription_check[1],'}'));
				$result_check_child_subscription       =  $model->checkProductInSubscription($product_in_subscription[$n]->category_id,$product_id);
				$data_add_pro 	                       =  str_replace ( $matches_child_subscription_check[$h], $result_check_child_subscription, $data_add_pro );
			}
		}
		$pro_detail 	.= $data_add_pro;
	}
	$data_add  = str_replace ( $middleprotemplate_desc, $pro_detail, $data_add );
	$data_add = str_replace ( "{child_subscription_loop_start}", "", $data_add );
	$data_add = str_replace ( "{child_subscription_loop_end}", "", $data_add );
	$link = JRoute::_ ( 'index.php?option='.$option.'&view=subscription&catid='.$row->category_id.'&layout=detail&Itemid='.$tmpItemid );
	$subscription_link_detail = "<a href='".$link."' class='gray_button' styler='float: right' >".'Details</a>';
	$data_add 	 = str_replace ( "{subscription_link_detail}", $subscription_link_detail, $data_add );
	$data_add 	 = str_replace ( "{subscription_main}", $row->category_name, $data_add );
	$sub_detail .= $data_add;
}
$template_desc = str_replace ( $middletemplate_desc, $sub_detail, $template_desc );
$template_desc = str_replace ( "{subscription_loop_start}", "", $template_desc );
$template_desc = str_replace ( "{subscription_loop_end}", "", $template_desc );
echo eval("?>".$template_desc."<?php ");
?>
