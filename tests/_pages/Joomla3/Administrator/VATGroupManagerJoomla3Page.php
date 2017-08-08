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
	public static $CheckAllVATGroup = "//input[@onclick='Joomla.checkAll(this)']";
	public static $VATGroupsNamePath = "//table[contains(@class, 'adminlist')]/tbody/tr[1]/td[4]/a";
	public static $VATGroupNameStatus = "//table[contains(@class, 'adminlist')]/tbody/tr[1]/td[3]/a";
	public static $VATGroupManagementSearch = "#filter_search";

	/**
	 * @var string
	 */
	public static $fieldMissing = "Field required: VAT / Tax Group Name";

	/**
	 * @var string
	 */
	public static $resultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";
}