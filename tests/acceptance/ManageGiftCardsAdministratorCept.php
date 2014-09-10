<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Want to Test Gift Cards Manager');
$I->doAdminLogin();
$I = new AcceptanceTester\GiftCardManagerSteps($scenario);
$I->addCard();
