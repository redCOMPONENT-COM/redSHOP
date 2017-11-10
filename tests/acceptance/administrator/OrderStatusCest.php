<?php

/**
 * Function order status
 */
class OrderStatusCest
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
		$I = new AcceptanceTester\OrderStatusSteps($scenario);
		$I->addOrderStatus($this->statusName, $this->statusCode, 'save', 'publish');
		$I->deleteOrderStatus($this->statusName);
		$I->addOrderStatus($this->statusName, $this->statusCode, 'saveclose', 'unpublish');
		$I->editOrderStatus($this->statusName, $this->satusNameEdit, 'saveclose');
	}

	/**
	 *
	 * Check button when missing action
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function checkButtons(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test to validate different buttons on Gift Card Views');
		$I = new AcceptanceTester\OrderStatusSteps($scenario);
		$I->checkButtons('cancel');
		$I->checkButtons('publish');
		$I->checkButtons('unpublish');
		$I->checkButtons('delete');
	}

}
