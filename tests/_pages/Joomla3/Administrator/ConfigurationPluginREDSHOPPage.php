<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ConfigurationPluginREDSHOPPage
 * @since 3.0.3
 */
class ConfigurationPluginREDSHOPPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=plugins';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $titlePage = 'Manager plugins';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $buttonSearchTool = '.js-stools-btn-filter';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectStatus = '#s2id_filter_enabled';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputSearchStatus = '#s2id_autogen2_search';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectComponent = '#s2id_filter_search_type';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputSearchComponent = '#s2id_autogen3_search';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectType = '#s2id_filter_folder';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputSearchType = '#s2id_autogen4_search';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectElement = '#s2id_filter_element';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputSearchElement = '#s2id_autogen5_search';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectAccess = '#s2id_filter_access';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputSearchAccess = '#s2id_autogen6_search';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $tablePlugin = '#table-plugins';
}