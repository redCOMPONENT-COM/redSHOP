<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once 'components' . DS . 'com_redshop' . DS . 'models' . DS . 'configuration.php';

class RedshopModelWizard extends configurationModelconfiguration
{
    public $_tax_rates = null;

    public function getTaxRates()
    {
        $query = "SELECT tax_group_id,tax_rate_id,tax_country,tax_rate FROM " . $this->_table_prefix . "tax_rate WHERE tax_group_id = 1";
        return $this->_getList($query);
    }
}
