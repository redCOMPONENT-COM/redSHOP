<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 6/6/2017
 * Time: 11:06 AM
 */
class VATGroupManagerJoomla3Page
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=tax_groups';
    public static $VATGroupName = "#jform_name";
    public static $CheckAllVATGroup="//input[@onclick='Joomla.checkAll(this)']";
    public static $VATGroupsNamePath="//div[@class='table-responsive']/table/tbody/tr/td[4]/a";
    public static $VATGroupNameStatus="//div[@class='table-responsive']/table/tbody/tr/td[3]/a";
    public static $VATGroupManagementSearch = "#filter_search";


}