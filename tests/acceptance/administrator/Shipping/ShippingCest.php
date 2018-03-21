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
	 * @var int
	 */
	public $shippingRateEdit;

	/**
	 * ShippingCest constructor.
	 */
	public function __construct()
	{
		// Shipping info
		$this->shippingMethod = 'redSHOP - Standard Shipping';
		$this->shipping       = array(
			'shippingName' => 'TestingShippingRate' . rand(99, 999),
			'shippingRate' => 10
		);

		$this->shippingSaveClose = array(
			'shippingName' => 'TestingShippingRate' . rand(99, 999),
			'shippingRate' => 10
		);

		$this->shippingNameEdit      = $this->shipping['shippingName'] . ' edit';
		$this->shippingNameSaveClose = "TestingSave" . rand(1, 100);
		$this->shippingRateEdit      = rand(100, 1000);
	}

	public function createShippingRate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create a shipping Rate');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ShippingSteps($scenario);
		$I->wantTo('Check create new Shipping rate with save button');
		$I->createShippingRateStandard($this->shippingMethod, $this->shipping);

		$I->wantTo('Check create new Shipping rate with save & close button');
		$I->createShippingRateStandard($this->shippingMethod, $this->shippingSaveClose, 'saveclose');
	}

	public function editShippingRateStandard(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Edit a shipping Rate');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ShippingSteps($scenario);
		$I->editShippingRateStandard($this->shipping['shippingName'], $this->shippingNameEdit, $this->shippingRateEdit, 'save');
		$I->editShippingRateStandard($this->shippingNameEdit, $this->shipping['shippingName'], $this->shipping['shippingRate'], 'saveclose');
	}
}
