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

require JModuleHelper::getLayoutPath('mod_redshop_shoppergrouplogo');
