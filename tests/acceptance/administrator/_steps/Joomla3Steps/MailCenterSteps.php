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
class MailCenterSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to add a new Mail Template
	 *
	 * @param   string $mailName    Name on the Email
	 * @param   string $mailSubject Mail Subject
	 * @param   string $mailBcc     Mail BCC
	 * @param   string $mailSection Mail Section
	 *
	 * @return void
	 */
	public function addMail($mailName = 'Name on the Email', $mailSubject = 'Subject on the email', $mailBcc = 'Sample', $mailSection = 'Ask question about product')
	{
		$client = $this;
		$client->amOnPage(\MailCenterPage::$url);
		$client->verifyNotices(false, $this->checkForNotices(), 'Mail Center Manager Page');
		$client->click(\MailCenterPage::$buttonNew);
		$client->waitForElement(\MailCenterPage::$fieldName, 30);
		$client->fillField(\MailCenterPage::$fieldName, $mailName);
		$client->fillField(\MailCenterPage::$fieldSubject, $mailSubject);
		$client->fillField(\MailCenterPage::$fieldBcc, $mailBcc);
		$client->chooseOnSelect2(\MailCenterPage::$fieldSection, $mailSection);
		$client->click(\MailCenterPage::$buttonSaveClose);
		$client->waitForText(\MailCenterPage::$messageItemSaveSuccess, 60, \MailCenterPage::$selectorSuccess);
		$client->see(\MailCenterPage::$messageItemSaveSuccess, \MailCenterPage::$selectorSuccess);
	}

	/**
	 * Function to edit an email Template
	 *
	 * @param   string $mailName    Mail Name for the template which is to be edited
	 * @param   string $newMailName New name for the email Template
	 *
	 * @return void
	 */
	public function editMail($mailName = 'Sample', $newMailName = 'Update Name')
	{
		$client = $this;
		$client->amOnPage(\MailCenterPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchMail($mailName);
		$client->wait(3);
		$client->click($mailName);
		$client->waitForElement(\MailCenterPage::$fieldName, 30);
		$client->fillField(\MailCenterPage::$fieldName, $newMailName);
		$client->click(\MailCenterPage::$buttonSaveClose);
		$client->waitForText(\MailCenterPage::$messageItemSaveSuccess, 60, \MailCenterPage::$selectorSuccess);
		$client->see(\MailCenterPage::$messageItemSaveSuccess, \MailCenterPage::$selectorSuccess);
	}

	/**
	 * Function to change State of a Mail Template
	 *
	 * @param   string $name  Name of the Mail Template
	 *
	 * @return void
	 */
	public function changeMailState($name)
	{
		$client = $this;
		$client->amOnPage(\MailCenterPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchMail($name);
		$client->wait(3);
		$client->see($name, \MailCenterPage::$resultRow);
		$client->checkAllResults();
		$client->click(\MailCenterPage::$mailTemplateStatePath);
	}

	/**
	 * Function to Search for a Mail Template
	 *
	 * @param   string $name Name of the Mail Template
	 *
	 * @return void
	 */
	public function searchMail($name)
	{
		$client = $this;
		$client->amOnPage(\MailCenterPage::$url);
		$client->waitForText(\MailCenterPage::$namePage, 30, \MailCenterPage::$headPage);
		$client->filterListBySearching($name);
	}

	/**
	 * Function to get State of the Mail Template
	 *
	 * @param   String $name Name of the Template
	 *
	 * @return string
	 */
	public function getMailState($name)
	{
		$client = $this;
		$client->amOnPage(\MailCenterPage::$url);
		$client->searchMail($name);
		$client->wait(3);
		$client->see($name, \MailCenterPage::$resultRow);
		$text = $client->grabAttributeFrom(\MailCenterPage::$mailTemplateStatePath, 'onclick');
		echo "Get status text " . $text;

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}
		else
		{
			$result = 'unpublished';
		}

		echo "Status need show" . $result;

		return $result;
	}

	/**
	 * Function to Delete Mail Template
	 *
	 * @param   String $name Name of the Template which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteMailTemplate($name)
	{
		$client = $this;
		$client->amOnPage(\MailCenterPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchCountry($name);
		$client->checkAllResults();
		$client->click(\MailCenterPage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForText(\MailCenterPage::$messageItemDeleteSuccess, 60, \MailCenterPage::$selectorSuccess);
		$client->see(\MailCenterPage::$messageItemDeleteSuccess, \MailCenterPage::$selectorSuccess);
		$client->fillField(\MailCenterPage::$searchField, $name);
		$client->pressKey(\MailCenterPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->dontSee($name, \MailCenterPage::$resultRow);
	}
}
