<?php

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * @package     Redshop.Test
 * @subpackage  Webdriver
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Page class for the back-end component tags menu.
 *
 * @package     Redshop.Test
 * @subpackage  Webdriver
 * @since       3.0
 */
class LanguageManagerPage extends AdminManagerPage
{
  /**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $waitForXpath =  "//ul/li/a[@href='index.php?option=com_languages&view=languages']";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_languages';

	/**
	 * Get the language tag of the current default language
	 *
	 * @param   string  $siteOrAdmin  "site" for the Site language, "admin" for the admin language
	 *
	 * @return  string  Language tag (for example, "en-GB")
	 */
	public function getDefaultLanguage($siteOrAdmin = 'admin')
	{
		if ($siteOrAdmin == 'admin')
		{
			$xpath = "//a[@href='index.php?option=com_languages&view=installed&client=1']";
		}
		else
		{
			$xpath = "//a[@href='index.php?option=com_languages&view=installed&client=0']";
		}
		$this->driver->findElement(By::xPath($xpath))->click();
		$this->test->getPageObject('LanguageManagerPage');
		$tableElements = $this->driver->findElements(By::xPath("//tbody"));
		if (isset($tableElements[0]))
		{
			$rowElements = $this->driver->findElement(By::xPath("//tbody"))->findElements(By::tagName('tr'));
			$count = count($rowElements);
			for ($i = 0; $i < $count; $i ++)
			{
				$columnElements = $rowElements[$i]->findElements(By::tagname("td"));
				$languageTag = $columnElements[2]->getText();
				$default = $columnElements[4]->findElement(By::tagName('i'));
				$featured = $default->getAttribute('class');
				if ($featured == 'icon-featured')
				{
					break;
				}
			}
		}
		return $languageTag;
	}
}
