<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_logingreeting
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$orderHelper 	 = order_functions::getInstance();
$user        	 = JFactory::getUser();
$greetingText    = $params->def('greeting_text', 1);
$classSuffix 	 = htmlspecialchars($params->get('moduleclass_sfx'));

JHtml::stylesheet('mod_redshop_logingreeting/logingreeting.css', array(), true);

require JModuleHelper::getLayoutPath('mod_redshop_logingreeting');
