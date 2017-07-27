<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class CustomFieldManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CustomFieldManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
    /**
     * Function to add a new Custom Field
     *
<<<<<<< HEAD
     * @param   string $name Name of the new Field
     * @param   string $title Title of the new Field
     * @param   string $type Type of the new Field
     * @param   string $section Section of the new Field
     * @param   String $option Optional Field Value
=======
     * @param   string  $name     Name of the new Field
     * @param   string  $title    Title of the new Field
     * @param   string  $type     Type of the new Field
     * @param   string  $section  Section of the new Field
     * @param   String  $option   Optional Field Value
>>>>>>> 8f4be64df0909c812eab0b76006c5b76716e5886
     *
     * @return void
     */
    public function addField($name = 'SampleField', $title = 'Field Title', $type = 'Text area', $section = 'Category', $option = 'Testing Options')
    {
        $I = $this;
<<<<<<< HEAD
        $I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
        $I->checkForPhpNoticesOrWarnings(\CustomFieldManagerJoomla3Page::$URL);
        $customFieldsManagerPage = new \CustomFieldManagerJoomla3Page;
        $I->click('New');
        $I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldName, 30);
        $I->fillField(\CustomFieldManagerJoomla3Page::$fieldName, $name);
        $I->click(\CustomFieldManagerJoomla3Page::$fieldTypeDropDown);
        $I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldTypeSearch, 3);
        $I->fillField(\CustomFieldManagerJoomla3Page::$fieldTypeSearch, $type);

        $I->click($customFieldsManagerPage->xPathChoice($type));
        $I->fillField(\CustomFieldManagerJoomla3Page::$fieldTitle, $title);

        $I->click(\CustomFieldManagerJoomla3Page::$fieldSectionDropDown);
        $I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldSectionSearch, 3);
        $I->fillField(\CustomFieldManagerJoomla3Page::$fieldSectionSearch, $section);
        $I->click($customFieldsManagerPage->xPathChoice($section));
        //        $I->click('Save');
//        $I->wait(4);
//        $I->see(\CustomFieldManagerJoomla3Page::$messageSaveSuccess, \CustomFieldManagerJoomla3Page::$selectorSuccess);
=======
        $fieldsForOptions = array("Check box", "Image", "Image with link", "Multiple select box", );
        $I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
        $customFieldsManagerPage = new \CustomFieldManagerJoomla3Page;
        $I->verifyNotices(false, $this->checkForNotices(), 'Fields Manager Page');
        $I->click('New');
        $I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldName,30);
        $I->fillField(\CustomFieldManagerJoomla3Page::$fieldName, $name);
        $I->click(\CustomFieldManagerJoomla3Page::$fieldTypeDropDown);
        $I->fillField(\CustomFieldManagerJoomla3Page::$fieldTypeSearchField, $type);
        $I->waitForElement($customFieldsManagerPage->fieldType($type),60);
        $I->click($customFieldsManagerPage->fieldType($type));
        $I->fillField(\CustomFieldManagerJoomla3Page::$fieldTitle, $title);
        $I->click(\CustomFieldManagerJoomla3Page::$fieldSectionDropDown);
        $I->fillField(\CustomFieldManagerJoomla3Page::$fieldSectionSearchField, $section);
        $I->waitForElement($customFieldsManagerPage->fieldSection($section),60);
        $I->click($customFieldsManagerPage->fieldSection($section));
        //$I->fillField(\CustomFieldManagerJoomla3Page::$optionValueField, $option);
        $I->click('Save & Close');
        $I->waitForText(\CustomFieldManagerJoomla3Page::$fieldSuccessMessage,10,\CustomFieldManagerJoomla3Page::$fieldMessagesLocation);

        if ($type == "Check box")
        {
            $I->executeJS('window.scrollTo(0,0)');
            $I->click(['link' => 'ID']);
        }

        $I->executeJS('window.scrollTo(0,0)');
        $I->click(['link' => 'ID']);
        $I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
        $I->executeJS('window.scrollTo(0,0)');
        $I->click(['link' => 'ID']);
>>>>>>> 8f4be64df0909c812eab0b76006c5b76716e5886
    }

    /**
     * Function to Edit a Field
     *
<<<<<<< HEAD
     * @param   string $title Current Title of the Field
     * @param   string $updatedTitle New Title for the Field
=======
     * @param   string  $title         Current Title of the Field
     * @param   string  $updatedTitle  New Title for the Field
>>>>>>> 8f4be64df0909c812eab0b76006c5b76716e5886
     *
     * @return void
     */
    public function editField($title = 'Field Title', $updatedTitle = 'New Title')
    {
        $I = $this;
        $I->amOnPage(\CustomFieldManagerJoomla3Page::$URL);
        $I->click(['link' => 'ID']);
        $I->see($title, \CustomFieldManagerJoomla3Page::$firstResultRow);
        $I->click(\CustomFieldManagerJoomla3Page::$selectFirst);
        $I->click('Edit');
<<<<<<< HEAD
        $I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldName, 30);
        $I->fillField(\CustomFieldManagerJoomla3Page::$fieldTitle, $updatedTitle);
        $I->click('Save & Close');
        $I->waitForText(\CustomFieldManagerJoomla3Page::$fieldSuccessMessage, 10, \CustomFieldManagerJoomla3Page::$fieldMessagesLocation);
=======
        $I->waitForElement(\CustomFieldManagerJoomla3Page::$fieldName,30);
        $I->fillField(\CustomFieldManagerJoomla3Page::$fieldTitle, $updatedTitle);
        $I->click('Save & Close');
        $I->waitForText(\CustomFieldManagerJoomla3Page::$fieldSuccessMessage,10,\CustomFieldManagerJoomla3Page::$fieldMessagesLocation);
>>>>>>> 8f4be64df0909c812eab0b76006c5b76716e5886
        $I->see($updatedTitle, \CustomFieldManagerJoomla3Page::$firstResultRow);
        $I->executeJS('window.scrollTo(0,0)');
        $I->click(['link' => 'ID']);
    }

    /**
     * Function to change State of a Custom Field
     *
<<<<<<< HEAD
     * @param   string $title Title of the Custom Field
     * @param   string $state State of the Mail Template
=======
     * @param   string  $title  Title of the Custom Field
     * @param   string  $state  State of the Mail Template
>>>>>>> 8f4be64df0909c812eab0b76006c5b76716e5886
     *
     * @return void
     */
    public function changeFieldState($title, $state = 'unpublish')
    {
        $this->changeState(new \CustomFieldManagerJoomla3Page, $title, $state, \CustomFieldManagerJoomla3Page::$firstResultRow, \CustomFieldManagerJoomla3Page::$selectFirst);
    }

    /**
     * Function to Search for a Custom Field
     *
<<<<<<< HEAD
     * @param   string $title Title of the Field
     * @param   string $functionName Name of the function After Which search is being Called
=======
     * @param   string  $title         Title of the Field
     * @param   string  $functionName  Name of the function After Which search is being Called
>>>>>>> 8f4be64df0909c812eab0b76006c5b76716e5886
     *
     * @return void
     */
    public function searchField($title, $functionName = 'Search')
    {
        $this->search(new \CustomFieldManagerJoomla3Page, $title, \CustomFieldManagerJoomla3Page::$firstResultRow, $functionName);
    }

    /**
     * Function to get State of the Custom Field
     *
<<<<<<< HEAD
     * @param   String $title Title of the Custom Field
=======
     * @param   String  $title  Title of the Custom Field
>>>>>>> 8f4be64df0909c812eab0b76006c5b76716e5886
     *
     * @return string
     */
    public function getFieldState($title)
    {
        $result = $this->getState(new \CustomFieldManagerJoomla3Page, $title, \CustomFieldManagerJoomla3Page::$firstResultRow, \CustomFieldManagerJoomla3Page::$fieldStatePath);

        return $result;
    }

    /**
     * Function to Delete Custom Field
     *
<<<<<<< HEAD
     * @param   String $title Title of the Field which is to be Deleted
=======
     * @param   String  $title  Title of the Field which is to be Deleted
>>>>>>> 8f4be64df0909c812eab0b76006c5b76716e5886
     *
     * @return void
     */
    public function deleteCustomField($title)
    {
        $this->delete(new \CustomFieldManagerJoomla3Page, $title, \CustomFieldManagerJoomla3Page::$firstResultRow, \CustomFieldManagerJoomla3Page::$selectFirst);
    }
}
