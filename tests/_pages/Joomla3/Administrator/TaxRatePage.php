<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TaxRatePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class TaxRatePage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = 'VAT Rates';

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=tax_rates';

	/**
	 * @var string
	 */
	public static $nameEditPage = 'VAT Rates: [ Edit ]';

	/**
	 * @var array
	 */
	public static $fieldName = "#jform_name";

	/**
	 * @var array
	 */
	public static $fieldValue = "#jform_tax_rate";

	/**
	 * @var array
	 */
	public static $fieldState = "#s2id_jform_tax_state";

    /**
     * @var string
     */
    public static $fieldStateSearch = "#s2id_autogen1_search";

    /**
     * @var string
     */
    public static $fieldStateID = ".select2-result-label";

	/**
	 * @var array
	 */
	public static $stateDropdown = "//div[@id='s2id_rs_state_jformtax_state']";

	/**
	 * @var array
	 */
	public static $fieldCountry = "#s2id_jform_tax_country";

    /**
     * @var string
     */
	public static $fieldCountryID = "#jform_tax_country";

	/**
	 * @var array
	 */
	public static $fieldGroup = "#s2id_jform_tax_group_id";

    /**
     * @var string
     */
	public static $fieldGroupID = "#jform_tax_group_id";
	
}
