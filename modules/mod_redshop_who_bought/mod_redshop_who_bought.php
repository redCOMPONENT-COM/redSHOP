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
// no direct access
defined('_JEXEC') or die('Restricted access');

function getDefaultModuleCategoriesbought(){
	$db	=	JFactory::getDBO();

	$sql = "SELECT category_id FROM #__redshop_category WHERE published=1 ORDER BY category_id ASC";
		$db->setQuery($sql);
		$cats = $db->loadObjectList();

		$category = array();
		for ($i=0;$i<count($cats);$i++){

			$category[] = $cats[$i]->category_id;
		}
		if (count($category)>0)
			$cids = implode(",",$category);
		else
			$cids = 0;

		return $cids;
}
$db	=	JFactory::getDBO();

$cids = getDefaultModuleCategoriesbought();

$category = trim( $params->get( 'category','') );
$number_of_items = trim( $params->get( 'number_of_items',5) );	// get show number of products
$thumbwidth = trim( $params->get( 'thumbwidth',100) ); 	// get show image thumbwidth size
$thumbheight = trim( $params->get( 'thumbheight',100) ); 	// get show image thumbheight size

$sliderwidth = trim( $params->get( 'sliderwidth',500) ); 	// get show product name linkable
$sliderheight = trim( $params->get( 'sliderheight',350) ); 	// get show product price


$show_product_image = trim( $params->get( 'show_product_image',1) ); 	// get show product image
$show_addtocart_button = trim( $params->get( 'show_addtocart_button',1) ); 	// get show add to cart button
$show_product_name = trim( $params->get( 'show_product_name',1) ); 	// get show product name
$product_title_linkable = trim( $params->get( 'product_title_linkable',1) ); 	// get show product name linkable
$show_product_price = trim( $params->get( 'show_product_price',1) ); 	// get show product price

$and = "";
if ($category != ""){
	$and = "AND xc.category_id IN (".$category.") ";
}
$sql	=	"SELECT p.*,xc.category_id FROM #__redshop_order_item as oi
				LEFT JOIN #__redshop_product p ON p.product_id=oi.product_id
				LEFT JOIN #__redshop_product_category_xref xc ON xc.product_id=oi.product_id "
				."WHERE p.published=1 ".$and." group by oi.product_id";

$db->setQuery($sql);
$productlists	=	$db->loadObjectList();

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php');
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'configuration.php' );
$Redconfiguration = new Redconfiguration();
$Redconfiguration->defineDynamicVars();

require_once(JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'product.php');

require_once(JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'helper.php');

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'template.php');

require_once(JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'extra_field.php');


require(JModuleHelper::getLayoutPath('mod_redshop_who_bought'));
