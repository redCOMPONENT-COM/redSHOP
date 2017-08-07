<?php

/**
 * Function order status
 */
class OrderStatusAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->statusName = $this->faker->bothify('Status Name ##??');
		$this->statusCode = $this->faker->bothify('Status Code ##??');
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	public function addOrderStatus(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test to validate different Missing Fields in the Edit View');
		$I = new AcceptanceTester\OrderStatusJoomla3Steps($scenario);
		$I->addOrderStatus($this->statusName, $this->statusCode, 'save', 'publish');
//		$I->addOrderStatus($this->cardNameSave, $this->cardPrice, $this->cardValue, $this->cardValidity,'saveclose');
	}

}
