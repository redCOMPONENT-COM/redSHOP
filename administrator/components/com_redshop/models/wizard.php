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

require_once 'components'.DS.'com_redshop'.DS.'models'.DS.'configuration.php';

class wizardModelwizard extends configurationModelconfiguration
{

	var $_tax_rates = null;
	function __construct()
	{
		parent::__construct();
	}

	function getTaxRates(){
		$query = "SELECT tax_group_id,tax_rate_id,tax_country,tax_rate FROM ".$this->_table_prefix."tax_rate WHERE tax_group_id = 1";
		return $this->_getList($query);
	}
}
?>