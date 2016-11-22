<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_newsletter
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$user = JFactory::getUser();
$email = JRequest::getString('email');
$name = JRequest::getString('name');
$itemId = JRequest::getInt('Itemid');
$newsletterItemId = $params->get('redirectpage');

if ($user->id != "")
{
	$email = $user->email;
	$name  = $user->name;
}

$document = JFactory::getDocument();

require JModuleHelper::getLayoutPath('mod_redshop_newsletter');
