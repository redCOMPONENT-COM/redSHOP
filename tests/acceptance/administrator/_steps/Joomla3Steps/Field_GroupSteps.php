<?php

use Step\AbstractStep;

class Field_GroupSteps extends AbstractStep
{
    use Step\Traits\CheckIn, Step\Traits\Publish, Step\Traits\Delete;

    /**
     * Create field group with missing name
     */
    public function missingName()
    {
        $test = $this;
        $test->amOnPage(Field_GroupPage::$url);
        $test->see(Field_GroupPage::$namePage);
        $test->click(Field_GroupPage::$buttonNew);
        $test->click(Field_GroupPage::$buttonSave);
        $test->see(Field_GroupPage::$missingName);
    }
}