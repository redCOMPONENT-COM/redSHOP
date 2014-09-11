<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Want to Test Mail Center Manager');
$I->doAdminLogin();
$I = new AcceptanceTester\MailCenterManagerSteps($scenario);
$I->addMail();
