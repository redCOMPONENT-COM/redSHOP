<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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
		$I->waitForElement(\MailCenterManagerJoomla3Page::$mailName,30);
		$I->fillField(\MailCenterManagerJoomla3Page::$mailName, $mailName);
		$I->fillField(\MailCenterManagerJoomla3Page::$mailSubject, $mailSubject);
		$I->fillField(\MailCenterManagerJoomla3Page::$mailBcc, $mailBcc);
		$I->click(\MailCenterManagerJoomla3Page::$mailSectionDropDown);
		$I->click($mailCenterManagerPage->mailSection($mailSection));
		$I->click('Save & Close');
		$I->waitForText(\MailCenterManagerJoomla3Page::$mailSuccessMessage,60,'.alert-success');
		$I->see(\MailCenterManagerJoomla3Page::$mailSuccessMessage, '.alert-success');
		$I->click(['link' => 'ID']);
		$I->click(['link' => 'ID']);
		$I->see($mailName);
		$I->click(['link' => 'ID']);
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
		$I->click(['link' => 'ID']);
		$I->click(['link' => 'ID']);
		$I->see($mailName);
		$I->click(\MailCenterManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\MailCenterManagerJoomla3Page::$mailName,30);
		$I->fillField(\MailCenterManagerJoomla3Page::$mailName, $newMailName);
		$I->click('Save & Close');
		$I->waitForText(\MailCenterManagerJoomla3Page::$mailSuccessMessage,30,'.alert-success');
		$I->see(\MailCenterManagerJoomla3Page::$mailSuccessMessage, '.alert-success');
		$I->see($newMailName);
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to change State of a Mail Template
	 *
	 * @param   string  $name   Name of the Mail Template
	 * @param   string  $state  State of the Mail Template
	 *
	 * @return void
	 */
	public function changeMailState($name, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\MailCenterManagerJoomla3Page::$URL);
		$I->click(['link' => 'ID']);
		$this->changeState(new \MailCenterManagerJoomla3Page, $name, $state, \MailCenterManagerJoomla3Page::$firstResultRow, \MailCenterManagerJoomla3Page::$selectFirst);
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
		$this->search(new \MailCenterManagerJoomla3Page, $name, \MailCenterManagerJoomla3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to get State of the Mail Template
	 *
	 * @param   String  $name  Name of the Template
	 *
	 * @return string
	 */
	public function getMailState($name)
	{
		$result = $this->getState(new \MailCenterManagerJoomla3Page, $name, \MailCenterManagerJoomla3Page::$firstResultRow, \MailCenterManagerJoomla3Page::$mailTemplateStatePath);

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
		$I->click(['link' => 'ID']);
		$this->delete(new \MailCenterManagerJoomla3Page, $name, \MailCenterManagerJoomla3Page::$firstResultRow, \MailCenterManagerJoomla3Page::$selectFirst);
	}
}
