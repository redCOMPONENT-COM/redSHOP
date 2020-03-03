<?php
/**
 * @package     redSHOP
 * @subpackage  Steps ModuleManagerJoomla
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Administrator\System;
use AcceptanceTester\AdminManagerJoomla3Steps;
use AdminJ3Page;

/**
 * Class SystemSteps
 * @package Administrator\System
 * @since 2.1.6
 */
class SystemSteps  extends AdminManagerJoomla3Steps
{
	/**
	 * @throws \Exception
	 * @since 2.1.6
	 */
	public function disableSEOSettings()
	{
		$i = $this;
		$i->amOnPage(AdminJ3Page::$configUrl);
		$i->waitForElementVisible(AdminJ3Page::$siteTab, 30);
		$i->click(AdminJ3Page::$siteTab);
		$i->waitForElementVisible(AdminJ3Page::$seoNO, 30);
		$i->click(AdminJ3Page::$seoNO);
		$i->waitForElementVisible(AdminJ3Page::$saveCloseButton, 30);
		$i->click(AdminJ3Page::$saveCloseButton);
		$i->waitForText(AdminJ3Page::$messageSaveConfigSuccess, 30, AdminJ3Page::$idInstallSuccess);
	}
}
