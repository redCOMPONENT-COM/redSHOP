<?php


class TAXRatesJoomla3Page
{

    //name page
    public static $nameNewPage = "VAT / Tax Rates Management New";

    public static $nameManagement = "VAT Rates";

    public static $nameEditPage = 'VAT Rates: [ Edit ]';

    //URL
    public static $URL = '/administrator/index.php?option=com_redshop&view=tax_rates';
    public static $TAXRatesName = "#jform_name";

    public static $TaxRatesValue = "#jform_tax_rate";

    public static $TaxState = "#rs_state_jformtax_state";

    public static $fieldCountry = '#jform_tax_country';
    public static $fieldGroup = '#jform_tax_group_id';

    public static $taxStateNamePath = "//div[@class='table-responsive']/table/tbody/tr/td[4]/a[2]";

    public static $statePath = "#rs_state_jformtax_state";

    public static $countryJform = ['xpath' => '//div[@id="s2id_jform_tax_country"]//a'];

    public static $countrySelect = ['id' => "s2id_jform_tax_country"];

    public static $taxJform = ['xpath' => '//div[@id="s2id_jform_tax_group_id"]//a'];

    public static $waitSearch = ['id' => "s2id_autogen3_search"];

    public static $headXPath=['xpath' => "//h1"];


    //Button
    public static $newButton = "New";

    public static $saveButton = "Save";

    public static $saveCloseButton = "Save & Close";

    public static $deleteButton = "Delete";

    public static $editButton = "Edit";

    public static $saveNewButton = "Save & New";

    public static $cancelButton = "Cancel";

    public static $checkInButton = "Check-in";


    //Message
    public static $messageSaveSuccess = "Item saved.";

    public static $messageError = "Error";

    public static $messageSuccess = "Message";

    public static $messageDeleteSuccess = "1 item successfully deleted";


    //selector

    public static $selectorSuccess = '.alert-success';

    public static $selectorError = '.alert-danger';

    public static $selectorNamePage = '.page-title';

    public static $selectorErrorHead = '.alert-heading';

    //return search
    public function resultsSelect($typeSelect)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $typeSelect . "')]"];
        return $path;
    }

}