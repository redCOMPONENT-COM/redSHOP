<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class Tablesubscription extends JTable
{
	var $subscription_id  = 0;
	var $product_id = 0;
	var $subscription_period = 0;
	var $subscription_period_unit = null;
	var $subscription_period_lifetime = 0;
	var $subscription_applicable_products = null;
	var $subscription_applicable_categories = null;
	var $joomla_acl_groups = null;
	var $fallback_joomla_acl_groups = null;
	var $shoppergroup = null;
  	var $fallback_shoppergroup = null;
		
	function Tablesubscription(& $db) 
	{
	  $this->_table_prefix = '#__redshop_';
			
		parent::__construct($this->_table_prefix.'subscription', 'subscription_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] )) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
	
}
?>
