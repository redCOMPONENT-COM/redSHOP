<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cest\Traits;

use Codeception\Scenario;
use Step\AbstractStep;
/**
 * Trait class for test with edit feature
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.0
 */
trait Edit
{
    /**
     * Method for click button "Edit" without choice
     *
     * @return  void
     */
    public function copyWithoutChoice(\AcceptanceTester $tester, Scenario $scenario)
    {
        $tester->wantTo('Administrator -> Button -> Check-in without choice.');

        $stepClass = $this->stepClass;

        /** @var \AdminJ3Page $pageClass */
        $pageClass = $this->pageClass;

        /** @var AbstractStep $step */
        $step = new $stepClass($scenario);

        $step->editWithoutChoice($pageClass);
        $step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
    }
}