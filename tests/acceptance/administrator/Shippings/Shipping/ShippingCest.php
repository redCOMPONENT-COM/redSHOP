<?php
/**
 * Shipping rate page .
 */

/**
 * Class ShippingCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.0
 */
class ShippingCest
{
	/**
	 * @var string
	 */
	public $shippingMethod;

	/**
	 * @var array
	 */
	public $shipping;

	/**
	 * @var array
	 */
	public $shippingSaveClose;

	/**
	 * @var string
	 */
	public $shippingNameEdit;

	/**
	 * @var string
	 */
	public $shippingNameSaveClose;

	/**
	 * @var integer
	 */
	public $shippingRateEdit;

	/**
	 * @var \Faker\Generator
	 */
	public $faker;

	/**
	 * ShippingCest constructor.
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();

		// Shipping info
		$this->shippingMethod = 'redSHOP - Standard Shipping';
		$this->shipping       = array(
			'shippingName' => $this->faker->bothify('TestingShippingRate ?##?'),
			'shippingRate' => 10
		);

		$this->shippingSaveClose = array(
			'shippingName' => $this->faker->bothify('TestingShippingRate ?##?'),
			'shippingRate' => 10
		);

		$this->shippingNameEdit      = $this->shipping['shippingName'] . ' edit';
		$this->shippingNameSaveClose = "TestingSave" . rand(1, 100);
		$this->shippingRateEdit      = rand(100, 1000);
	}

    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

	/**
	 * @param   AcceptanceTester      $I        Tester
	 * @param   \Codeception\Scenario $scenario Scenario
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function createShippingRate(AcceptanceTester $I, \Codeception\Scenario $scenario)
	{
	 	$I = new AcceptanceTester\ShippingSteps($scenario);
	    $I->wantTo('Check create new Shipping rate with save button');
	    $I->createShippingRateStandard($this->shippingMethod, $this->shipping, 'save');

	    $I->wantTo('Check create new Shipping rate with save & close button');
	    $I->createShippingRateStandard(
			$this->shippingMethod, $this->shippingSaveClose, 'saveclose'
        );

	    $I->wantTo('Edit a shipping Rate');
	    $I->editShippingRateStandard(
			$this->shipping['shippingName'], $this->shippingNameEdit, $this->shippingRateEdit, 'save'
        );

	    $I->wantTo('Edit a shipping Rate with Save Close');
	    $I->editShippingRateStandard(
			$this->shippingNameEdit, $this->shipping['shippingName'], $this->shipping['shippingRate'], 'saveclose'
        );

	    $I->wantTo('Delete a shipping Rate');
	    $I->deleteShippingRate($this->shippingMethod, $this->shipping['shippingName']);
	}
}
