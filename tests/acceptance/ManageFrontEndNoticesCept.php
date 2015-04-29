<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

$scenario->group('Joomla3');

// Load the Step Object Page

$I = new AcceptanceTester($scenario);
$config = $I->getConfig();
$className = 'AcceptanceTester\FrontEndLogin' . $config['env'] . 'Steps';
$I = new $className($scenario);

$I->wantTo('Test Presence of Notices, Warnings on FrontEnd Menus');
$I->doFrontEndLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\FrontEndManager' . $config['env'] . 'Steps';
$I = new $className($scenario);
$I->CheckAllFrontEndLinks();
