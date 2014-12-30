<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class MailCenterManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class MailCenterManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to add a new Mail Template
	 *
	 * @param   string  $mailName     Name on the Email
	 * @param   string  $mailSubject  Mail Subject
	 * @param   string  $mailBcc      Mail BCC
	 * @param   string  $mailSection  Mail Section
	 *
	 * @return void
	 */
	public function addMail($mailName = 'Name on the Email', $mailSubject = 'Subject on the email', $mailBcc = 'Sample', $mailSection = 'Ask question about product')
	{
		$I = $this;
		$I->amOnPage(\MailCenterManagerJoomla3Page::$URL);
		$mailCenterManagerPage = new \MailCenterManagerJoomla3Page;
		$I->verifyNotices(false, $this->checkForNotices(), 'Mail Center Manager Page');
		$I->click('New');
		$I->waitForElement(\MailCenterManagerJoomla3Page::$mailName, 30);
		$I->fillField(\MailCenterManagerJoomla3Page::$mailName, $mailName);
		$I->fillField(\MailCenterManagerJoomla3Page::$mailSubject, $mailSubject);
		$I->fillField(\MailCenterManagerJoomla3Page::$mailBcc, $mailBcc);
		$I->click(\MailCenterManagerJoomla3Page::$mailSectionDropDown);
		$I->click($mailCenterManagerPage->mailSection($mailSection));
		$I->click('Save & Close');
		$I->waitForText(\MailCenterManagerJoomla3Page::$mailSuccessMessage, 60);
		$I->see(\MailCenterManagerJoomla3Page::$mailSuccessMessage);
		$I->click('ID');
		$I->click('ID');
		$I->see($mailName, \MailCenterManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to edit an email Template
	 *
	 * @param   string  $mailName     Mail Name for the template which is to be edited
	 * @param   string  $newMailName  New name for the email Template
	 *
	 * @return void
	 */
	public function editMail($mailName = 'Sample', $newMailName = 'Update Name')
	{
		$I = $this;
		$I->amOnPage(\MailCenterManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($mailName, \MailCenterManagerJoomla3Page::$firstResultRow);
		$I->click(\MailCenterManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\MailCenterManagerJoomla3Page::$mailName, 30);
		$I->fillField(\MailCenterManagerJoomla3Page::$mailName, $newMailName);
		$I->click('Save & Close');
		$I->waitForText(\MailCenterManagerJoomla3Page::$mailSuccessMessage);
		$I->see(\MailCenterManagerJoomla3Page::$mailSuccessMessage);
		$I->see($newMailName, \MailCenterManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to change State of a Mail Template
	 *
	 * @param   string  $name   Name of the Mail Template
	 * @param   string  $state  State of the Mail Template
	 *
	 * @return void
	 */
	public function changeState($name, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\MailCenterManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($name, \MailCenterManagerJoomla3Page::$firstResultRow);
		$I->click(\MailCenterManagerJoomla3Page::$selectFirst);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
		}
		else
		{
			$I->click("Publish");
		}

		$I->click('ID');

	}

	/**
	 * Function to Search for a Mail Template
	 *
	 * @param   string  $name          Name of the Mail Template
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchMail($name, $functionName = 'Search')
	{
		$I = $this;
		$I->amOnPage(\MailCenterManagerJoomla3Page::$URL);
		$I->click('ID');

		if ($functionName == 'Search')
		{
			$I->see($name, \MailCenterManagerJoomla3Page::$firstResultRow);
		}
		else
		{
			$I->dontSee($name, \MailCenterManagerJoomla3Page::$firstResultRow);
		}

		$I->click('ID');
	}

	/**
	 * Function to get State of the Mail Template
	 *
	 * @param   String  $name  Name of the Template
	 *
	 * @return string
	 */
	public function getState($name)
	{
		$I = $this;
		$I->amOnPage(\MailCenterManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($name, \MailCenterManagerJoomla3Page::$firstResultRow);
		$text = $I->grabAttributeFrom(\MailCenterManagerJoomla3Page::$mailTemplateStatePath, 'onclick');

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}

		if (strpos($text, 'publish') > 0)
		{
			$result = 'unpublished';
		}

		$I->click('ID');

		return $result;
	}

	/**
	 * Function to Delete Mail Template
	 *
	 * @param   String  $name  Name of the Template which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteMailTemplate($name)
	{
		$I = $this;
		$I->amOnPage(\MailCenterManagerJoomla3Page::$URL);
		$I->click('ID');
		$I->see($name, \MailCenterManagerJoomla3Page::$firstResultRow);
		$I->click(\MailCenterManagerJoomla3Page::$selectFirst);
		$I->click('Delete');
		$I->dontSee($name, \MailCenterManagerJoomla3Page::$firstResultRow);
		$I->click('ID');
	}
}
