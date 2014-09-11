<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('perform actions and see result');
$I = new AcceptanceTester\TestingSteps($scenario);
$I->testingNotice();
