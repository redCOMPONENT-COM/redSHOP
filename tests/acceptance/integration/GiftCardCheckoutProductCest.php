<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class GiftCardCheckoutProductCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class GiftCardCheckoutProductCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->userName = $this->faker->bothify('UserNameCheckoutProductCest ?##?');
        $this->password = 'test';
        $this->email = $this->faker->email;
        $this->shopperGroup = 'Default Private';
        $this->group = 'Administrator';
        $this->firstName = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
        $this->lastName = 'Last';
        $this->userInformation = array(
            "email" => $this->email,
            "firstName" => "Tester",
            "lastName" => "User",
            "address" => "Some Place in the World",
            "postalCode" => "23456",
            "city" => "Bangalore",
            "country" => "India",
            "state" => "Karnataka",
            "phone" => "8787878787"
        );
        $this->randomCardName = $this->faker->bothify('ManageGiftCardAdministratorCest Card ?##?');
        $this->cardPrice = '10';
        $this->cardValue = '10';
        $this->cardValidity = $this->faker->numberBetween(2, 10);
        $this->couponCode = null;
    }

    /**
     * Test to Verify the Gift Card Checkout
     *
     * @param   AcceptanceTester $I Actor Class Object
     * @param   String $scenario Scenario Variable
     *
     * @return void
     */
    public function testProductsGiftCardFrontEnd(AcceptanceTester $I, $scenario)
    {
        $I = new AcceptanceTester($scenario);

        $I->wantTo('Test Giftcard checkout on Frontend, Applying Giftcard to a Product, using Authorize payment plugin for purchasing gift card');
        $I->doAdministratorLogin();

        $I = new AcceptanceTester\ProductCheckoutManagerJoomla3Steps($scenario);
        $checkoutAccountInformation = array(
            "accessId" => "62qpC9xN9nN4",
            "transactionId" => "97sRY6pGTea3E48d",
            "password" => "Pull416!t",
            "debitCardNumber" => "4012888818888",
            "cvv" => "1234",
            "cardExpiryMonth" => '2',
            "cardExpiryYear" => '2016',
            "shippingAddress" => "some place on earth",
            "customerName" => 'Testing Customer'
        );
		$this->createUser($I, $scenario);
        $this->createGiftCard($I, $scenario);
        $I->installExtensionFromUrl($I->getConfig('redshop packages url') . 'plugins/plg_redshop_payment_rs_payment_paypal.zip');
        $I->wait(3);
        $I->enablePlugin('PayPal');
        $this->checkoutGiftCardWithAuthorizePayment($I, $scenario, $this->userInformation, $this->userInformation, $checkoutAccountInformation, $this->randomCardName);
        $this->couponCode = $this->fetchCouponCode($I, $scenario);
        $productName = 'redCOOKIE';
        $categoryName = 'Events and Forms';

        $this->checkoutProductWithCouponCode($I, $scenario, $this->userInformation, $this->userInformation, $productName, $categoryName, $this->couponCode);
        $this->deleteGiftCard($I, $scenario);
    }

    /**
     * Function to Update the Payment Plugin
     *
     * @param   AcceptanceTester $I Actor Class Object
     * @param   String $accessId Access Id of API
     * @param   String $transactionKey Transaction Key
     *
     * @return void
     */
    private function updateAuthorizePlugin(AcceptanceTester $I, $accessId, $transactionKey)
    {
        $I->amOnPage('/administrator/index.php?option=com_plugins');
//		$I->checkForPhpNoticesOrWarnings();
        $I->searchForItem('Authorize Payments');
        $pluginManagerPage = new \PluginManagerJoomla3Page;
        $I->waitForElement($pluginManagerPage->searchResultPluginName('Authorize Payments'), 60);
        $I->checkExistenceOf('Authorize Payments');
        $I->click(['id' => "cb0"]);
        $I->click(['xpath' => "//div[@id='toolbar-edit']/button"]);
        $I->waitForElement(['id' => "jform_params_access_id"], 60);
        $I->fillField(['id' => "jform_params_access_id"], $accessId);
        $I->fillField(['id' => "jform_params_transaction_id"], $transactionKey);
        $I->click(['xpath' => "//div[@id='jform_params_is_test_chzn']/a"]);

        // Choosing Test Mode to Yes
        $I->click(['xpath' => "//div[@id='jform_params_is_test_chzn']/div/ul/li[contains(text(), 'Yes')]"]);
        $I->click(['link' => 'Advanced']);
        $I->click(['xpath' => "//li//label[text()='Visa']"]);
        $I->click(['xpath' => "//li//label[text()='MasterCard']"]);
        $I->click(['xpath' => "//div[@id='toolbar-save']/button"]);
        $I->see('successfully saved', ['id' => 'system-message-container']);
    }

    /**
     * Function to Fetch the Coupon Code Generated by Gift Card
     *
     * @param   AcceptanceTester $I Actor Class Object
     * @param   String $scenario Scenario Variable
     *
     * @return String
     */
    private function fetchCouponCode(AcceptanceTester $I, $scenario)
    {
        $I->amOnPage(\CouponManagerJ3Page::$URL);
        $I->executeJS('window.scrollTo(0,0)');
        $I->click(['link' => 'ID']);
        $I->click(\CouponManagerJ3Page::$selectFirst);
        $couponCode = $I->grabTextFrom(['xpath' => "//div[@id='editcell']//table[2]//tbody/tr[1]//td[3]//a"]);
        $I->comment($couponCode);

        return $couponCode;

    }

    /**
     * Function to Test Checkout Process of a Gift Card using the Authorize Payment Plugin
     *
     * @param   AcceptanceTester $I Actor Class Object
     * @param   String $scenario Scenario Variable
     * @param   Array $addressDetail Address Detail
     * @param   Array $shipmentDetail Shipping Address Detail
     * @param   Array $checkoutAccountDetail 2Checkout Account Detail
     * @param   string $giftCardName Name of the Gift Card which needs to be purchased
     *
     * @return void
     */
    private function checkoutGiftCardWithAuthorizePayment(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $checkoutAccountDetail, $giftCardName)
    {
        $I->doFrontEndLogin($this->userName, $this->password);
        // here , can't get this link
        $I->amOnPage('/index.php?option=com_redshop&view=giftcard');
        $I->waitForElement(['link' => $giftCardName], 60);
        $productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
        $I->click(['link' => $giftCardName]);
        $I->waitForElement(['id' => "reciver_name"]);
        $I->fillField(['id' => "reciver_name"], $this->firstName);
        $I->fillField(['id' => "reciver_email"], $this->email);
        $I->click(['xpath' => "//span[text() = 'Add to cart']"]);
        $I->waitForText("Product has been added to your cart.", 60, '.alert-message');
        $I->see("Product has been added to your cart.", '.alert-message');
        $I->amOnPage('index.php?option=com_redshop&view=cart');
        $I->seeElement(['link' => $giftCardName]);
        $I->click(['xpath' => "//input[@value='Checkout']"]);
        $I->waitForElement(['xpath' => "//div[@id='rs_payment_authorize']//label//input"], 30);
        $I->click("Checkout");
        $I->waitForElementVisible(\FrontEndProductManagerJoomla3Page::$addressEmail);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressEmail, $addressDetail['email']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressFirstName, $addressDetail['firstName']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressLastName, $addressDetail['lastName']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressAddress, $addressDetail['address']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressPostalCode, $addressDetail['postalCode']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressCity, $addressDetail['city']);
        $I->selectOption(\FrontEndProductManagerJoomla3Page::$addressCountry, $addressDetail['country']);
        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$addressState, 20);
        $I->selectOption(\FrontEndProductManagerJoomla3Page::$addressState, $addressDetail['state']);
        $I->fillField(\FrontEndProductManagerJoomla3Page::$addressPhone, $addressDetail['phone']);
        $I->click("Save");
        $I->waitForElement(['link' => "Add address"], 30);
        $I->click(['xpath' => "//div[@id='rs_payment_authorize']//label//input"]);
        $I->click("Checkout");
        $I->waitForElement(['id' => "order_payment_name"], 60);
        $I->fillField(['id' => "order_payment_name"], $checkoutAccountDetail['customerName']);
        $I->fillField(['id' => "order_payment_number"], $checkoutAccountDetail['debitCardNumber']);
        $I->fillField(['id' => "credit_card_code"], $checkoutAccountDetail['cvv']);
        $I->click(['xpath' => "//input[@value='VISA']"]);
        $I->click(['xpath' => "//input[@value='Checkout: next step']"]);
        $I->waitForElement($productFrontEndManagerPage->product($giftCardName), 60);
        $I->seeElement($productFrontEndManagerPage->product($giftCardName));
        $I->click(['id' => "termscondition"]);
        $I->click(['id' => "checkout_final"]);
        $I->waitForText('Order placed', 60, ['xpath' => "//div[@class='alert alert-success']"]);
        $I->see('Order placed', "//div[@class='alert alert-success']");
        $I->doFrontendLogout();
        $I->doAdministratorLogin();
        $I->uninstallExtension('Authorize Payments', true);
    }

    /**
     * Function to Test Checkout Process of a Product using the Coupon Code
     *
     * @param   AcceptanceTester $I Actor Class Object
     * @param   String $scenario Scenario Variable
     * @param   Array $addressDetail Address Detail
     * @param   Array $shipmentDetail Shipping Address Detail
     * @param   string $productName Name of the Product
     * @param   string $categoryName Name of the Category
     * @param   string $couponCode Code for the Coupon
     *
     * @return void
     */
    private function checkoutProductWithCouponCode(AcceptanceTester $I, $scenario, $addressDetail, $shipmentDetail, $productName = 'redCOOKIE', $categoryName = 'Events and Forms', $couponCode)
    {
        $I->doFrontEndLogin($this->userName, $this->password);
        $I->amOnPage(\FrontEndProductManagerJoomla3Page::$URL);
        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
//		$I->checkForPhpNoticesOrWarnings();
        $productFrontEndManagerPage = new \FrontEndProductManagerJoomla3Page;
        $I->click($productFrontEndManagerPage->productCategory($categoryName));
        $I->waitForElement(\FrontEndProductManagerJoomla3Page::$productList, 30);
        $I->click($productFrontEndManagerPage->product($productName));
        $I->click(\FrontEndProductManagerJoomla3Page::$addToCart);
        $I->waitForText("Product has been added to your cart.", 10, '.alert-message');
        $I->see("Product has been added to your cart.", '.alert-message');
        $I->amOnPage('index.php?option=com_redshop&view=cart');
//		$I->checkForPhpNoticesOrWarnings();
        $I->seeElement(['link' => $productName]);
        $I->fillField(['id' => 'coupon_input'], $couponCode);
        $I->click(['id' => 'coupon_button']);
        $I->waitForText("The discount code is valid", 10, '.alert-success');
        $I->see("The discount code is valid", '.alert-success');
        $I->see("$ 24,00", ['class' => "lc-subtotal"]);
        $I->see("$ 10,00", ['class' => "lc-discount"]);
        $I->see("$ 14,00", ['class' => "lc-total"]);
    }

    /**
     * Function to Test Gift Cards Creation in Backend
     *
     */
    private function createGiftCard(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test Gift Card creation in Administrator');
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->addCardNew($this->randomCardName, $this->cardPrice, $this->cardValue, $this->cardValidity, 'save');
    }

    /**
     * Function to Test Gift Card Deletion
     *
     */
    private function deleteGiftCard(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deletion of Gift Card in Administrator');
        $I = new AcceptanceTester\GiftCardManagerJoomla3Steps($scenario);
        $I->deleteCard($this->randomCardName);
    }

    /**
     * Function to Test User Creation in Backend
     *
     */
    private function createUser(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation in Administrator');
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');
        $I->searchUser($this->firstName);
    }

    /**
     * Function to Test User Deletion
     *
     */
    private function deleteUser(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deletion of User in Administrator');
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->deleteUser($this->firstName);
    }
}
