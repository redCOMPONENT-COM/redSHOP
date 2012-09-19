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
$option=JRequest::getVar('option');


$document = JFactory::getDocument();
$document->addStyleSheet("modules/mod_redshop_discount/css/discount.css");


$db = JFactory::getDBO();

$query = "SELECT * FROM #__redshop_discount where published = 1 order by amount desc";
$db->setQuery($query);
$data = $db->LoadObjectList();


require(JModuleHelper::getLayoutPath('mod_redshop_discount'));