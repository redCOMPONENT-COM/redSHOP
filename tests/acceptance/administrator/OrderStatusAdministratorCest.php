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
		$this->satusNameEdit = $this->statusName . 'edit';
	}

	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 *
	 * Function create order status , edit and delete 
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function addOrderStatus(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test to validate different Missing Fields in the Edit View');
		$I = new AcceptanceTester\OrderStatusJoomla3Steps($scenario);
		$I->addOrderStatus($this->statusName, $this->statusCode, 'save', 'publish');
		$I->deleteOrderStatus($this->statusName);
		$I->addOrderStatus($this->statusName, $this->statusCode, 'saveclose', 'unpublish');
		$I->editOrderStatus($this->statusName, $this->satusNameEdit, 'saveclose');
	}

}
