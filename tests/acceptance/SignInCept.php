<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('login to site using the LoginPage Page object located in _Pages folder');
$I->amOnPage(LoginPage::$URL);
$I->fillField(LoginPage::$usernameField, 'admin');
$I->fillField(LoginPage::$passwordField, 'admin');
$I->click(LoginPage::$loginButton);
$I->see("Administration");
