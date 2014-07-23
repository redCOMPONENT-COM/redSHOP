<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('login 2 admins in two different windows');
$admin2 = $I->haveFriend('$admin2');
$admin2->does(function(AcceptanceTester $I) {
		$I->amOnPage("tests/system/joomla-cms/administrator/index.php");
		$I->amGoingTo('Log In by providing user and password as admin:admin');
		$I->fillField("#mod-login-username", "admin");
		$I->fillField("#mod-login-password", "admin");
		$I->click("Log in");
	});
$I->amOnPage("tests/system/joomla-cms/administrator/index.php");
$I->amGoingTo('Log In by providing user and password as admin:admin');
$I->fillField("#mod-login-username", "admin");
$I->fillField("#mod-login-password", "admin");
$I->click("Log in");
$I->see("Administration");
