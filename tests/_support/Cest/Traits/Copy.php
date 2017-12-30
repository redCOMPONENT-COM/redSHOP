<?php
/**
 * Check Copy button without choice
 */

namespace Cest\Traits;

use Codeception\Scenario;
use Step\AbstractStep;
class Copy
{
    /**
     * Method for click button "Copy" without choice
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

        $step->copyWithoutChoice($pageClass);
        $step->see($pageClass::$namePage, $pageClass::$selectorPageTitle);
    }
}