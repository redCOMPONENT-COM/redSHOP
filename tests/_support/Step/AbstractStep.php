<?php
/**
 * @package     RedShop
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Step;

/**
 * Class Redshop
 *
 * @package Step\Acceptance
 *
 * @since  2.1.0
 */
class AbstractStep extends \AcceptanceTester
{
	/**
	 * Asserts the system message contains the given message.
	 *
	 * @param   string $message The message
	 *
	 * @return  void
	 */
	public function assertSystemMessageContains($message)
	{
		$browser = $this;
		$browser->waitForElement(['id' => 'system-message-container'], 60);
		$browser->waitForText($message, 30, ['id' => 'system-message-container']);
	}

	/**
	 * Method for save item.
	 *
	 * @param   \AdminJ3Page  $pageClass   Page class
	 * @param   array         $formFields  Array of form fields
	 * @param   array         $data        Array of data.
	 *
	 * @return  void
	 */
	public function addNewItem($pageClass = null, $formFields = array(), $data = array())
	{
		$client = $this;
		$client->amOnPage($pageClass::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->click($pageClass::$buttonNew);
		$client->checkForPhpNoticesOrWarnings();

		foreach ($formFields as $index => $field)
		{
			if (!isset($data[$index]) || empty($data[$index]))
			{
				continue;
			}

			switch ($field['type'])
			{
				default:
					$client->fillField($field['xpath'], $data[$index]);
					break;
			}
		}

		$client->click($pageClass::$buttonSave);
		$client->assertSystemMessageContains($pageClass::$messageItemSaveSuccess);
	}
}
