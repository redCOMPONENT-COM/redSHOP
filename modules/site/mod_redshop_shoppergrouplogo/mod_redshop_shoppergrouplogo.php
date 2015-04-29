<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergrouplogo
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
$thumbwidth  = (int) $params->get('thumbwidth', 100);
$thumbheight = (int) $params->get('thumbheight', 100);

// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::load('RedshopHelperAdminConfiguration');
JLoader::load('RedshopHelperAdminImages');
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

require JModuleHelper::getLayoutPath('mod_redshop_shoppergrouplogo');
