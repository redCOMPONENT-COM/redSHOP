<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
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

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgRedshop_paymentrs_payment_googlecheckout extends JPlugin
{
	var $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgRedshop_paymentrs_payment_googlecheckout(&$subject)
	{
		parent::__construct($subject);

		// Load plugin parameters
		$this->_table_prefix = '#__redshop_';
		//    JPluginHelper::getPlugin( 'redshop', 'onPrePayment' );
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_googlecheckout');
		$this->_params = new JRegistry($this->_plugin->params);


	}


	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment($element, $data)
	{

		if ($element != 'rs_payment_googlecheckout')
		{
			return;
		}

		if (empty($this->_plugin))
		{
			$this->_plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . 'rs_payment_googlecheckout' . DS . 'rs_payment_googlecheckout' . DS . 'extra_info.php';
		include_once($paymentpath);
	}

}

