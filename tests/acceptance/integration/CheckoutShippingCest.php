<?php

/**
 * Created by PhpStorm.
 * User: nhung
 * Date: 29/11/2017
 * Time: 18:04
 */
use AcceptanceTester\ShippingSteps as ShippingSteps ;
class CheckoutShippingCest
{
    public function __construct()
    {

        $this->faker = Faker\Factory::create();
        $this->ProductName = 'ProductName' . rand(100, 999);
        $this->CategoryName = "CategoryName" . rand(1, 100);
        $this->minimumPerProduct = 1;
        $this->minimumQuantity = 1;
        $this->maximumQuantity = $this->faker->numberBetween(100, 1000);
        $this->discountStart = "12-12-2016";
        $this->discountEnd = "23-05-2017";
        $this->randomProductNumber = $this->faker->numberBetween(999, 9999);
        $this->randomProductPrice = 100;

        $this->subtotal="DKK 100,00";
        $this->Discount ="DKK 50,00";
        $this->Total="DKK 50,00";

        $this->discountName = 'Discount' . rand(1, 100);
        $this->amount = 150;
        $this->discountAmount = 50;
        $this->startDate = '13-06-2017';
        $this->endDate = '13-08-2017';
        $this->shopperGroup = 'Default Private';
        $this->discountType = 'Total';
        $this->discountCondition='Lower';

    }
    public function preCheckout()
    {
        
    }
}