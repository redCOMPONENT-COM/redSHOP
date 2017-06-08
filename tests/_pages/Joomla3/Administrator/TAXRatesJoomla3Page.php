<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 6/7/2017
 * Time: 11:06 AM
 */
class TAXRatesJoomla3Page
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=tax_rates';
    public static $TAXRatesName = "#jform_name";

    public static $TaxRatesValue="#jform_tax_rate";

    public static $TaxState="#rs_state_jformtax_state";

    public static $taxStateNamePath="//div[@class='table-responsive']/table/tbody/tr/td[4]/a[2]";


    public static $CheckAllTAXRates="//input[@onclick='Joomla.checkAll(this)']";

    public static $statePath="#rs_state_jformtax_state";


}