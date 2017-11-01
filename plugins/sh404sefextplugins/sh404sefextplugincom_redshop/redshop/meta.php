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

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// standard plugin initialize function - don't change

global $sh_LANG, $sefConfig;

$sefConfig = shRouter::shGetConfig();

$db = JFactory::getDbo();
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin( $lang='', $shLangName, $shLangIso, $option);

JLoader::import('redshop.library');

if ($dosef == false) return;

if (isset($limitstart))  // V 1.2.4.r
   shRemoveFromGETVarsList('limitstart'); // limitstart can be zero

	$view = isset($view) ? @$view : null;
	$cid = isset($cid) ? @$cid : null;
	$mid = isset($mid) ? @$mid : null;
	$pid = isset($pid) ? @$pid : null;
	$task = isset($task) ? @$task : null;

  switch ($view)
  {

	  case 'category':
	  	   if($cid){

	  	    $sql = "SELECT pagetitle FROM #__redshop_category WHERE id = '$cid'";
	  	   	$db->setQuery($sql);
	  	    $category = $db->loadObject();

            $shCustomTitleTag = $category->pagetitle;
	  	   }
	  	   break;

 	 	case 'product':
	  	   if($pid){

	  	    $sql = "SELECT pagetitle FROM #__redshop_product WHERE product_id = '$pid'";
	  	   	$db->setQuery($sql);
	  	    $product = $db->loadObject();

            $shCustomTitleTag = $product->pagetitle;

	  	   }
   		 break;

   		case 'manufacturers':
	  	   if($mid && $task=='manufacturer_detail'){

	  	    $sql = "SELECT pagetitle FROM #__redshop_manufacturer WHERE manufacturer_id = '$mid'";
	  	   	$db->setQuery($sql);
	  	    $url = $db->loadObject();
	  	    $shCustomTitleTag = $url->pagetitle;
	  	   }

  		 case 'manufacturer_products':
	  	   if($mid){

	  	    $sql = "SELECT pagetitle FROM #__redshop_manufacturer WHERE manufacturer_id = '$mid'";
	  	   	$db->setQuery($sql);
	  	    $url = $db->loadObject();
	  	    $shCustomTitleTag = $url->pagetitle;
	  	   }
  }
?>
