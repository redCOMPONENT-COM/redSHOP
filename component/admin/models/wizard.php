<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

require_once 'components' . DS . 'com_redshop' . DS . 'models' . DS . 'configuration.php';

class wizardModelwizard extends configurationModelconfiguration
{

	public $_tax_rates = null;

	function __construct()
	{
		parent::__construct();
	}

	function getTaxRates()
	{
		$query = "SELECT tax_group_id,tax_rate_id,tax_country,tax_rate FROM " . $this->_table_prefix . "tax_rate WHERE tax_group_id = 1";
		return $this->_getList($query);
	}
}

?>
