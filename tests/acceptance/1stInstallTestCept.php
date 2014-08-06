<?php
$scenario->group('installation');
$I = new AcceptanceTester\InstallJoomla2Steps($scenario);
$I->wantTo('Execute Joomla Installation');
$I->installJoomla2($I->getConfig());

