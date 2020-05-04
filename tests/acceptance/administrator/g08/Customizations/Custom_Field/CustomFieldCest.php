<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CustomFieldManagerJoomla3Steps;

/**
 * Class CustomFieldCest
 * @since 1.4.0
 */
class CustomFieldCest
{
    /**
     * @var \Faker\Generator
     * @since 1.4.0
     */
    protected $faker;

    /**
     * @var array
     * @since 1.4.0
     */
    protected $fieldType;

    /**
     * CustomFieldCest constructor.
     * @since 1.4.0
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create();

        $this->fieldType = array(
            "Check box",
            "Country selection box",
            "Date picker",
            "Documents",
            "Image with link",
            "Multiple select box",
            "Radio buttons",
            "Selection Based On Selected Conditions",
            "Single Select",
            "Text area",
            "WYSIWYG"
        );
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     * @throws Exception
     * @since 1.4.0
     */
    public function testCustomFields(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Custom Field CRUD in Administrator');
        $I->doAdministratorLogin();
        $I = new CustomFieldManagerJoomla3Steps($scenario);

        foreach ($this->fieldType as $type) {
            $I->wantTo("Test $type");
            $name = (string)$this->faker->bothify('ManageCustomFieldAdministratorCest ?##?');
            $title = (string)$this->faker->bothify("ManageCustomFieldAdministratorCest $type ?##?");
            $optionValue = (string)$this->faker->numberBetween(100, 1000);
            $section = 'Category';
            $I->addField($name, $title, $type, $section, $optionValue);
        }
    }
}
