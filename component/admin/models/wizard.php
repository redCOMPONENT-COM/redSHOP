<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once 'components/com_redshop/models/configuration.php';

class RedshopModelWizard extends RedshopModelConfiguration
{
    /**
     * @var null
     */
    public $_tax_rates = null;

    /**
     * @return object[]
     * @deprecated
     */
    public function getTaxRates()
    {
        $query = \Redshop\Tax\Helper::getTaxRatesQuery(1);
        return $this->_getList($query);
    }

    /**
     * @param string $currencyCode
     * @return mixed
     * @deprecated Please consider using \Redshop\Currency\Helper::getCurrenciesListForSelectBox
     */
    public function getCurrency($currencyCode = "")
    {
        return \Redshop\Currency\Helper::getCurrenciesListForSelectBox($currencyCode);
    }
}
