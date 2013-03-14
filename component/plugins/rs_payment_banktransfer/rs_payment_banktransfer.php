<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  rs_payment_banktransfer
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgRedshop_paymentrs_payment_banktransfer extends JPlugin
{
	var $_table_prefix = null;

	/**
	 * Constructor$mainframe =& JFactory::getApplication();
	$paymentpath=JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$plugin.DS.'extra_info.php';
	include($paymentpath);
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgRedshop_paymentrs_payment_banktransfer(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		JPluginHelper::getPlugin('redshop_payment', 'onPrePayment');
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_banktransfer');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment($element, $data)
	{
		$tag = JFactory::getLanguage()->getTag();
		if ($element != 'rs_payment_banktransfer')
		{
			return;
		}
		if (empty($plugin))
		{
			$plugin = $element;
		}

		return true;
	}
}
