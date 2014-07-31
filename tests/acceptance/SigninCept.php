<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Want to Login to the Application');
$I->amOnPage('/administrator/index.php');
$I->fillField('username', 'puneet');
$I->fillField('passwd', '1234');
$I->click('Log in');
$I->see('Joomla');
