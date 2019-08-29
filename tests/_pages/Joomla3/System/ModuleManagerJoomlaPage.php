<?php

use AcceptanceTester\AdminManagerJoomla3Steps;

/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

class ModuleManagerJoomlaPage extends AdminManagerJoomla3Steps
{
	public static $URL = '/administrator/index.php?option=com_modules';
	public static $searchButton = '//input[@id="filter_search"]';
	public static $buttonSearch = '(//button[@type="submit"])[1]';
	public static $searchResultRow = "//form[@id='adminForm']/div/table/tbody/tr[1]";
	public static $showButton = '//label[@class="btn active btn-success"]';
	public static $position = '//div[@id="jform_position_chzn"]';


	public function searchResultPluginName($moduleName)
	{
		$path = "//form[@id='adminForm']/div/table/tbody/tr[1]/td[4]/a[contains(text(), '" . $moduleName . "')]";

		return $path;
	}


}