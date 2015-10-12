<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Load the Step Object Page
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Gift Cards Manager in Administrator');
$I->doAdministratorLogin();
$className = 'AcceptanceTester\GiftCardManagerJoomla3Steps';
$I = new $className($scenario);
$randomCardName = 'Test Card' . rand(99, 999);
$newRandomCardName = 'New Test Card' . $randomCardName;
$cardPrice = rand(99, 999);
$cardValue = rand(9, 99);
$cardValidity = rand(1, 15);
$I->addCard($randomCardName, $cardPrice, $cardValue, $cardValidity);
$I->searchCard($randomCardName);
$I->changeCardState($randomCardName);
$I->verifyState('unpublished', $I->getCardState($randomCardName), 'State Must be Unpublished');
$I->editCard($randomCardName, $newRandomCardName);
$I->searchCard($newRandomCardName);
$I->deleteCard($newRandomCardName);
$I->searchCard($newRandomCardName, 'Delete');
