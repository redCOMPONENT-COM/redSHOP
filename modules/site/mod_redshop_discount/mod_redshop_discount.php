<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_discount
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$option = JRequest::getVar('option');


$document = JFactory::getDocument();
$document->addStyleSheet("modules/mod_redshop_discount/css/discount.css");


$db = JFactory::getDBO();

$query = "SELECT * FROM #__redshop_discount where published = 1 order by amount desc";
$db->setQuery($query);
$data = $db->LoadObjectList();


require JModuleHelper::getLayoutPath('mod_redshop_discount');