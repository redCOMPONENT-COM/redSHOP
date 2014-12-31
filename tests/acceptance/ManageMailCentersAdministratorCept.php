<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$scenario->group('Joomla2');
$scenario->group('Joomla3');

// Load the Step Object Page
$I = new AcceptanceTester\LoginSteps($scenario);

$I->wantTo('Test Mail Centers Manager in Administrator');
$I->doAdminLogin();
$config = $I->getConfig();
$className = 'AcceptanceTester\MailCenterManager' . $config['env'] . 'Steps';
$I = new $className($scenario);

if ($config['env'] == 'Joomla2')
{
	$I->addMail();
}
else
{
	$name = 'Testing Mail' . rand(100, 1000);
	$subject = 'Subject' . rand(10, 100);
	$bcc = 'BCC Test' . rand(100, 1000);
	$mailSection = 'Ask question about product';
	$newName = 'Updated ' . $name;
	$I->addMail($name, $subject, $bcc, $mailSection);
	$I->searchMail($name);
	$I->editMail($name, $newName);
	$I->searchMail($newName);
	$I->changeState($newName);
	$I->verifyState('unpublished', $I->getState($newName));
	$I->deleteMailTemplate($newName);
	$I->searchMail($newName, 'Delete');
}

