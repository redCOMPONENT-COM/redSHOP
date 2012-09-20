<?php
/**
 * $ModDesc
 *
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	$Subpackage
 * @copyright	Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website 	htt://landofcoder.com
 * @license		GNU General Public License version 2
 */
// no direct access
defined('_JEXEC') or die ('Restricted access');

class JElementRedshopcategoryremove extends JElement {

    /**
     * @access private
     */
	var	$_name = 'redshopcategoryremove';

	function fetchElement($nameElement, $valueElement, &$node, $control_name) {
		$db = &JFactory::getDBO();
		if( !is_dir( JPATH_ADMINISTRATOR.'/components/com_redshop' ) ) return JText::_('Redshop is not installed');
		if( !is_array($valueElement) ) { $valueElement = array( ''. $valueElement.'' ); }
		else {

			foreach(  $valueElement as $_k => $tmpV ){
				 $valueElement[$tmpV] = $tmpV;
			}
		}
		$option=JRequest::getVar('option');
		if($option!='com_redshop')
		{
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php');
			require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'configuration.php' );
			$Redconfiguration = new Redconfiguration();
			$Redconfiguration->defineDynamicVars();
		}

		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'category.php');
		$product_category = new product_category();
			ob_start();
		$output =  $product_category->list_all(  ''.$control_name.'['.$nameElement.'][]','', ($valueElement), 10, true, true );

		ob_end_clean();

		return $output;
	}
	
}
