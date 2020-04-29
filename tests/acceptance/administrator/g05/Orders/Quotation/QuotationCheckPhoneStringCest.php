<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\QuotationManagerJoomla3Steps;

/**
 * Class QuotationCheckPhoneStringCest
 * since 2.1.2
 */
class QuotationCheckPhoneStringCest
{
	/**
	 * @var \Faker\Generator
	 * @since 2.1.2
	 */
	protected $faker;

	/**
	 * @var string
	 * since 2.1.2
	 */
	protected $firstname;

	/**
	 * @var string
	 * since 2.1.2
	 */
	protected $lastName;

	/**
	 * @var
	 * since 2.1.2
	 */
	protected $address;

	/**
	 * @var
	 * since 2.1.2
	 */
	protected $postalCode;

	/**
	 * @var
	 * since 2.1.2
	 */
	protected $city;

	/**
	 * @var string
	 * since 2.1.2
	 */
	protected $stringPhone;

	/**
	 * @var string
	 * since 2.1.2
	 */
	protected $email;
	/**
	 * @var string
	 * since 2.1.2
	 */
	protected $username;

	/**
	 * @var string
	 * since 2.1.2
	 */
	protected $passWord;

	/**
	 * Function to Test Quotation Creation in Backend
	 * since 2.1.2
	 */
	public function __construct()
	{
		$this->faker           = Faker\Factory::create();
		$this->email           = $this->faker->email;
		$this->firstname       = $this->faker->bothify('ManageUserAdministrator ?##?');
		$this->lastName        = 'Last';
		$this->address         = $this->faker->streetAddress;
		$this->postalCode      = $this->faker->numberBetween(9999, 999999);
		$this->city            = $this->faker->city;
		$this->stringPhone     = 'enter string phone number';
		$this->username        = $this->faker->bothify('ManageUserAdministrator ?##?');
		$this->passWord        = $this->faker->bothify('Password ?##?');
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * since 2.1.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param QuotationManagerJoomla3Steps $I
	 * @throws Exception
	 * since 2.1.2
	 */
	public function checkStringPhone(QuotationManagerJoomla3Steps $I)
	{
		$I->wantTo('test string phone number');
		$I->checkPhoneNumber($this->firstname, $this->lastName, $this->address, $this->postalCode, $this->city, $this->stringPhone, $this->email, $this->username, $this->passWord);
	}
}
