<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergrouplogo
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

$thumbwidth  = trim($params->get('thumbwidth', 100));
$thumbheight = trim($params->get('thumbheight', 100));

$db = JFactory::getDbo();
// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminConfiguration');
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

JLoader::load('RedshopHelperProduct');
$user = JFactory::getUser();

$sql = "SELECT s.*,u.user_id "
	. "FROM #__redshop_users_info AS u "
	. ", #__redshop_shopper_group AS s "
	. "WHERE u.shopper_group_id = s.shopper_group_id "
	. "AND s.published=1 "
	. "AND u.user_id=" . (int) $user->id . " ";
$db->setQuery($sql);
$rows = $db->loadObject();

require JModuleHelper::getLayoutPath('mod_redshop_shoppergrouplogo');?>