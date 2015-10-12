<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Media Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\MediaManagerJoomla3Steps';
$I = new $className($scenario);
$I->addMedia();
