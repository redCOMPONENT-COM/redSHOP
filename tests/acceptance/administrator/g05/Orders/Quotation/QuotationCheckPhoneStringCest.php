<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/**
 * Class QuotationCheckPhoneStringCest
 * since 2.1.2
 */
class QuotationCheckPhoneStringCest
{
	/**
	 * @var
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
	protected $postalcode;

	/**
	 * @var
	 * since 2.1.2
	 */
	protected $city;

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
		$this->firstname        = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->lastName        = 'Last';
		$this->address         = $this->faker->streetAddress;
		$this->postalcode      = $this->faker->numberBetween(9999,999999);
		$this->city            = $this->faker->city;
		$this->username        = $this->faker->bothify('ManageUserAdministratorCest ?##?');
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
	 * @param \AcceptanceTester\QuotationManagerJoomla3Steps $I
	 * @throws \Codeception\Exception\ModuleException
	 * since 2.1.2
	 */
	public function checkStringPhone(\AcceptanceTester\QuotationManagerJoomla3Steps $I)
	{
		$I->wantTo('test string phone number');
		$I->checkPhoneNumber($this->firstname, $this->lastName, $this->address, $this->postalcode, $this->city, $this->stringPhone, $this->email,$this->username, $this->passWord, $this->passWord );
	}
}
