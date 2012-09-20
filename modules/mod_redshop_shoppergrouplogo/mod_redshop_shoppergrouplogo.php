<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$thumbwidth = trim( $params->get( 'thumbwidth',100) );
$thumbheight = trim( $params->get( 'thumbheight',100) );

$db = JFactory::getDBO();
// Getting the configuration
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php');
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'configuration.php' );
$Redconfiguration = new Redconfiguration();
$Redconfiguration->defineDynamicVars();

require_once(JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'product.php');
$user = JFactory::getUser();

$sql = "SELECT s.*,u.user_id "
	  ."FROM #__redshop_users_info AS u "
	  .", #__redshop_shopper_group AS s "
	  ."WHERE u.shopper_group_id = s.shopper_group_id "
	  ."AND s.published=1 "
	  ."AND u.user_id='".$user->id."' ";
$db->setQuery($sql);
$rows = $db->loadObject();

require(JModuleHelper::getLayoutPath('mod_redshop_shoppergrouplogo'));?>