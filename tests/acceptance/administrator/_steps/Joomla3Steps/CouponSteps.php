<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Step\AbstractStep;

/**
 * Class CouponManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CouponSteps extends AbstractStep
{
	use Step\Traits\CheckIn, Step\Traits\Publish, Step\Traits\Delete;

	public function checkStartDateLargerThanEndDate($data = array())
    {
        $pageClass = $this->pageClass;
        $I = $this;

        $I->amOnPage($pageClass::$url);
        $I->checkForPhpNoticesOrWarnings();
        $I->click($pageClass::$buttonNew);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillFormData($this->getFormFields(), $data);
        $I->wait(0.5);
        $I->click($pageClass::$buttonSave);
        $I->waitForText(CouponPage::$messageFail, 30, AdminJ3Page::$selectorMissing);
    }
}
