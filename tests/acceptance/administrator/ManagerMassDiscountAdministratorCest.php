<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 6/8/2017
 * Time: 2:01 PM
 */
class ManagerMassDiscountAdministratorCest
{

    public function __construct()
    {

        $this->ProductName = 'ProductName'.rand(100,999) ;
        $this->VATGroupNameSaveClose='Testing VAT Groups'.rand(10,100);
        $this->VATGroupNameEdit="Testing VAT Edit";

    }

}