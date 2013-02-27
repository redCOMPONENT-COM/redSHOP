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
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'configuration.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php');
require_once (JPATH_SITE. DS .'components'.DS.'com_redshop'.DS. 'helpers' . DS . 'product.php');
require_once (JPATH_SITE. DS .'components'.DS.'com_redshop'.DS. 'helpers' . DS . 'helper.php');
$uri =& JURI::getInstance();
$url= $uri->root();

$Itemid	= JRequest::getVar('Itemid');
$user = &JFactory::getUser();
$option = 'com_redshop';

$document = & JFactory::getDocument();
$document->addStyleSheet (JURI::base().'modules/mod_redshop_products/css/products.css');

// 	include redshop js file.
require_once(JPATH_SITE. DS .'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.js.php');

// lightbox Javascript
JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/',false);
JHTML::Script('attribute.js', 'components/com_redshop/assets/js/',false);
JHTML::Script('common.js', 'components/com_redshop/assets/js/',false);
JHTML::Stylesheet('fetchscript.css', 'components/com_redshop/assets/css/');
$config = new Redconfiguration();
$config->defineDynamicVars();

$producthelper = new producthelper();
$redhelper = new redhelper();

$view = JRequest::getCmd('view','category');
$module_id = "mod_".$module->id;

$document = JFactory::getDocument();
$document->addStyleSheet("modules/mod_redshop_category_scroller/css/jquery.css");
$document->addStyleSheet("modules/mod_redshop_category_scroller/css/skin_002.css");


if ($view == 'category')
{
	if(!$GLOBALS['product_price_slider'])
	{
		JHTML::Script('jquery.tools.min.js', 'components/com_redshop/assets/js/',false);
	}
} else {
	JHTML::Script('jquery.tools.min.js', 'components/com_redshop/assets/js/',false);
}
JHTML::Script('jquery.js', 'modules/mod_redshop_category_scroller/js/',false);
JHTML::Script('rscategoryscroller.js', 'modules/mod_redshop_category_scroller/js/',false);

echo $pretext;
echo "<div style='height:".$scrollerheight."px;'>";
echo "<div>
		<div class='red_product-skin-produkter'>
		<div style='display: block;' class='red_product-container red_product-container-horizontal'>
		<div style='display: block;' class='red_product-prev red_product-prev-horizontal'></div>
		<div style='display: block;left: ".($scrollerwidth+20)."px;' class='red_product-next red_product-next-horizontal'></div>
		<div class='red_product-clip red_product-clip-horizontal' style='width: ".$scrollerwidth."px;'>
		<ul id='rs_category_scroller' class='red_product-list red_product-list-horizontal'>";

for($i=0;$i<count($rows);$i++)
{
    $row = $rows[$i];

    $ItemData = $producthelper->getMenuInformation(0,0,'','product&pid='.$row->product_id);
	if(count($ItemData)>0){
			$Itemid = $ItemData->id;
	}else{
		$Itemid = $redhelper->getItemid($row->product_id);
	}
	$catattach = '';
	if($row->category_id)
	{
		$catattach = '&cid='.$row->category_id;
	}

	$link 	= JRoute::_( 'index.php?option=com_redshop&view=product&pid='.$row->product_id.$catattach.'&Itemid='.$Itemid);
	$url= JURI::base();
	echo "<li red_productindex='".$i."' class='red_product-item red_product-item-horizontal'><div class='listing-item'><div class='product-shop'>";
	if($show_product_name)
	{
		$pname = $config->maxchar ( $row->product_name , $product_title_max_chars, $product_title_end_suffix );
		echo "<a href='".$link."' title='".$row->product_name."'>".$pname."</a>";
	}
	if(SHOW_PRICE && !USE_AS_CATALOG && !DEFAULT_QUOTATION_MODE && $show_price && !$row->not_for_sale)
	{
		$productArr 			 = $producthelper->getProductNetPrice($row->product_id);
		$product_price 			 = $producthelper->getPriceReplacement($productArr['product_price']);
		$product_price_saving    = $producthelper->getPriceReplacement($productArr['product_price_saving']);
		$product_old_price       = $producthelper->getPriceReplacement($productArr['product_old_price']);
		if($show_discountpricelayout)
		{
			echo "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>".$product_old_price."</span></div>";
			echo "<div id='mod_redmainprice' class='mod_redmainprice'>".$product_price."</div>";
			echo "<div id='mod_redsavedprice' class='mod_redsavedprice'>".JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED').' '.$product_price_saving."</div>";
		} else {
			echo "<div class='mod_redproducts_price'>".$product_price."</div>";
		}
	}
	if($show_readmore)
	{
		echo "<div class='mod_redshop_category_scroller_readmore'><a href='".$link."'>".JText::_('COM_REDSHOP_TXT_READ_MORE')."</a></div>";
	}
	echo "</div>";
	if($show_image)
	{
		$prod_img="";

		if(is_file(REDSHOP_FRONT_IMAGES_RELPATH . "/product/".$row->product_full_image))
		$prod_img=$url."components/com_redshop/helpers/thumb.php?filename=product/".$row->product_full_image."&newxsize=".$thumbwidth."&newysize=".$thumbheight;
		else if(is_file(REDSHOP_FRONT_IMAGES_RELPATH . "/product/".$row->product_thumb_image))
		$prod_img=$url."components/com_redshop/helpers/thumb.php?filename=product/".$row->product_thumb_image."&newxsize=".$thumbwidth."&newysize=".$thumbheight;
		else
		$prod_img=REDSHOP_FRONT_IMAGES_ABSPATH."noimage.jpg";
		$thum_image = "<a href='".$link."'><img style='width:".$thumbwidth."px;height:".$thumbheight."px;' src='".$prod_img."'></a>";
		echo "<div class='product-image' style='width:".$thumbwidth."px;height:".$thumbheight."px;'>".$thum_image."</div>";

	}
	if($show_addtocart)
	{
		/////////////////////////////////// Product attribute  Start /////////////////////////////////
		$attributes_set = array();
		if($row->attribute_set_id > 0){
			$attributes_set = $producthelper->getProductAttribute(0,$row->attribute_set_id,0,1);
		}
		$attributes = $producthelper->getProductAttribute($row->product_id);
		$attributes = array_merge($attributes,$attributes_set);
		$totalatt = count($attributes);
		/////////////////////////////////// Product attribute  End /////////////////////////////////


		/////////////////////////////////// Product accessory Start /////////////////////////////////
		$accessory = $producthelper->getProductAccessory(0,$row->product_id);
		$totalAccessory = count ( $accessory );

		$addtocart_data = $producthelper->replaceCartTemplate($row->product_id,0,0,0,"",false,array(),$totalatt,$totalAccessory,0,$module_id);
		echo "<div class='form-button'>".$addtocart_data."<div>";
	}
	echo "</div></li>";
}
echo "</ul></div></div></div></div></div>";