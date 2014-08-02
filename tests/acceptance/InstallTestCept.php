<?php
$scenario->group('installation');
$I = new AcceptanceTester\InstallSteps($scenario);
$I->wantTo('Execute Joomla Installation');
$I->installJoomla($I->getConfig());

