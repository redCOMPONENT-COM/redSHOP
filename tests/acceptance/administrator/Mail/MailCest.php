<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cest\AbstractCest;

/**
 * Class MailCenterCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class MailCest extends AbstractCest
{
	use  Cest\Traits\Publish, Cest\Traits\Delete;

	/**
	 * Name field, which is use for search
	 *
	 * @var string
	 */
	public $nameField = 'mail_name';

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareNewData()
	{
		return array(
			'mail_name'    => $this->faker->bothify('MailCenterCest ?##?'),
			'mail_section' => 'Ask question about product',
			'mail_bcc'     => 'BCC Test' . $this->faker->numberBetween(100, 1000),
			'mail_subject' => $this->faker->bothify('MailCenterCest Subject ?##?'),
		);
	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreate
	 */
	public function deleteDataSave(\AcceptanceTester $tester,  $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$stepClass = $this->stepClass;

		/** @var AbstractStep $step */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New' . $this->dataNew['mail_name']);

	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester  $tester    Tester
	 * @param   Scenario           $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreateSaveClose
	 */
	public function deleteDataSaveClose(\AcceptanceTester $tester,  $scenario)
	{
		$tester->wantTo('Run after create item with save button ');
		$stepClass = $this->stepClass;

		/** @var \Step\AbstractStep $step */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New' . $this->dataNew['mail_name']);

	}

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareEditData()
	{
		$return = $this->dataNew;

		$return['mail_name'] = 'New' . $return['mail_name'];

		return $return;
	}
}
