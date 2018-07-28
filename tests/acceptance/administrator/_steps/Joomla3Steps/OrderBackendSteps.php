<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 7/27/2018
 * Time: 2:44 PM
 */
namespace AcceptanceTester;

class OrderBackendSteps extends ConfigurationSteps
{
    /**
     * @param $order
     * @param $firstName
     * @param $lastName
     * @param $productName
     * @param $categoryName
     * @param $priceProduct
     */
    public function totalProductCustomer($order, $firstName, $lastName, $productName, $categoryName, $priceProduct)
    {
        $I = $this;
        $I->wantTo('Check order');
        $I->checkPriceTotal($priceProduct,$order, $firstName, $lastName, $productName, $categoryName);
        $I->wait('1');
    }
}