<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('Joomla2');

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Quotation Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\QuotationManagerJoomla3Steps';
$I = new $className($scenario);
$I->addQuotation();
