<?php
/**
 * copy Button
 */

namespace Step\Traits;

trait Copy
{
    /**
     * Method for click button "Copy" without choice
     *
     * @return  void
     */
    public function copyWithoutChoice()
    {
        /** @var \AdminJ3Page $pageClass */
        $pageClass = $this->pageClass;
        $tester    = $this;

        $tester->amOnPage($pageClass::$url);
        $tester->click($pageClass::$buttonCopy);
        $tester->acceptPopup();
        $tester->waitForElement($pageClass::$searchField, 30);
    }

}