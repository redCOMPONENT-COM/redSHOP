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

class JFormFieldVmcategory extends JFormField
{
    /**
     * @access private
     */
	var	$_name = 'vmcategory';
	
	function getInput()
	{
		$db = &JFactory::getDBO();
		if(!is_dir(JPATH_ADMINISTRATOR.'/components/com_virtuemart')) return JText::_('Virtuemart is not installed');
		// Load the virtuemart main parse code
		if(file_exists(JPATH_ADMINISTRATOR.'/components/com_virtuemart/virtuemart_parser.php'))
		{
			require_once(JPATH_ADMINISTRATOR.'/components/com_virtuemart/virtuemart_parser.php');
			$mosConfig_absolute_path = realpath(dirname(__FILE__).'/../..');
		}
		else
		{
			require_once(JPATH_SITE.'/components/com_virtuemart/virtuemart_parser.php');
		}
		if(!is_array($this->value)) { $this->value = array(''. $this->value.'' => '1'); }
		else
		{
			foreach( $this->value as $_k => $tmpV)
			{
				 $this->value[$tmpV] = 1;
			}
		}
		
		require_once(CLASSPATH.'ps_product_category.php'); 
		$ps_product_category = new ps_product_category();
		ob_start();
		$output =  $ps_product_category->list_all( ''.$this->name.'[]','', ($this->value), 10, true, true);
		$output = ob_get_contents();
		ob_end_clean(); 
		return $output;
	}
}