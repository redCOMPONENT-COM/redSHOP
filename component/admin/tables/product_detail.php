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
jimport('joomla.application.component.model');

class Tableproduct_detail extends JTable
{
	var $product_id 		= 0;
	var $product_parent_id	= 0;
	var $product_number		= null;
	var $product_price		= null;
	var $discount_price		= null;
	var $product_type		= null;
	var $product_volume		= null;
	var $manufacturer_id	= null;
	var $supplier_id		= null;
	var $product_on_sale 	= null;
	var $product_special 	= 0;
	var $product_download 	= 0;
	var $product_name 		= null;
	var $product_s_desc 	= null;
	var $product_desc 		= null;
	var $visited			= 0;
	var $product_template 	= 0;
	var $publish_date       = null;
	var $product_thumb_image = null;
	var $product_full_image = null;
	var $metakey 			= null;
	var	$metadesc 			= null;
	var	$metalanguage_setting = null;
	var	$metarobot_info 	= null;
	var	$append_to_global_seo 	= 'append';
	var	$pagetitle 			= null;
	var	$pageheading		= null;
	var	$sef_url 			= null;
	var	$cat_in_sefurl		= null;
	var $product_tax_id     = null;
	var $product_tax_group_id = null;
	var $published 			= null;
	var $weight				= 0;
	var $expired            = 0;
	var $discount_stratdate = null;
	var $discount_enddate = null;
	var $not_for_sale		= 0;
	var $use_discount_calc 	  = 0;
	var $discount_calc_method = null;
	var $min_order_product_quantity = 0;
	var $max_order_product_quantity = 0;
	var $attribute_set_id   = 0;
	var $product_length = 0;
	var $product_height = 0;
	var $product_width = 0;
	var $product_diameter = 0;
	var $product_availability_date = 0;
	var $use_range	=	0;
	var $product_download_days	=	0;
	var $product_download_limit	=	0;
	var $product_download_clock	=	0;
	var $product_download_clock_min = 0;
	var $product_download_infinite = 0;
	var $product_back_full_image = null;
	var $product_back_thumb_image = null;
	var $product_preview_image = null;
	var $product_preview_back_image = null;
	var $accountgroup_id = 0;
	var $preorder = null;
	var $quantity_selectbox_value=null;
	var	$canonical_url 			= null;

	/**
	 * @var boolean
	 */
	var $checked_out = 0;

	/**
	 * @var time
	 */
	var $checked_out_time = 0;


	function Tableproduct_detail(& $db)
	{
	  $this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix.'product', 'product_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if(defined('OVERWRITE_BLANK_FIELDS_VAL') && OVERWRITE_BLANK_FIELDS_VAL==0)
		{
			$array=$this->removeNullVal($array);
			
		}
		if (key_exists( 'params', $array ) && is_array( $array['params'] )) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}
		return parent::bind($array, $ignore);
	}

	/**
	 * Check for the product ID
	 */
	function check() {

		$db = JFactory::getDBO();
		$q = "SELECT product_id
			FROM ".$this->_table_prefix."product
			WHERE product_number = ".$db->Quote($this->product_number);
		$db->setQuery($q);

		$xid = intval($db->loadResult());

		if ($xid && $xid != intval($this->product_id)) {

	 		$this->setError( JText::_( 'PRODUCT_NUMBER_ALREADY_EXISTS' ) );
			//$this->_error = JText::sprintf('WARNNAMETRYAGAIN', JText::_('PRODUCT_NUMBER_ALREADY_EXISTS'));
			return false;
		}
		return true;
	}
	function removeNullVal($row)
	{

	
		if(OVERWRITE_BLANK_FIELDS_VAL==0)
		{ 
			if(is_array($row))
			{
				foreach($row as $key=>$val)
				{
					
						if(empty($val)  && !is_array($val) &&  !is_object($val))
						{//var_dump($row[$key]);
							if(strlen($val)==0)
							{
								unset($row[$key]);
							}
						}

					
						
				}
			}
			else if(is_object($row))
			{
				foreach($row as $key=>$val)
				{ 	if(!is_object($val) && @strlen($val)==0)
					{
						unset($row->$key);
					}
				}
			}

			return $row;
		}
	}
}
?>