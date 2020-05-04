<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TaxRatePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class TaxRatePage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = 'VAT Rates';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=tax_rates';

	/**
	 * @var string
	 * @since 1.4.0
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

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $editTitle = 'Tax Rate [ Edit ]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $tableTaxRate = '#table-tax_rates';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $searchTools = '.js-stools-btn-filter';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $selectorSearchTools = ".js-stools-container-filters";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $eUCountry = '#select2-chosen-4';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $selectorEUCountry = '#select2-results-4';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messageInvalid = 'Save failed with the following error: Invalid input of tax rate, it must be numeric & not less than zero';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $labelEU = "EU country";

	/**
	 * @param $nameField
	 * @return string
	 * @since 2.1.3
	 */
	public function messageMissing($nameField)
	{
		return $message = 'Field required: '.$nameField;
	}
}
