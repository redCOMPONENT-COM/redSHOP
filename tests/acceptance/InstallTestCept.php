<?php
$cfg = new SeleniumConfig;
$I = new AcceptanceTester($scenario);
$I->wantTo('Execute Joomla Installation');
InstallTestPage::of($I)->install($cfg);

