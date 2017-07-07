<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 7/7/2017
 * Time: 10:19 AM
 */
class product_style_giftCest
{
    public function install(AcceptanceTester $I){
        $I->am('adminstrator');
        $I->wantTo('Install style gift product/plg_redshop_product_type_gift');
        $I->doAdministratorLogin();
        $I->installExtensionFromUrl($I->getConfig('redshop packages url') . 'plugins/plg_redshop_product_type_gift.zip');
    }
}