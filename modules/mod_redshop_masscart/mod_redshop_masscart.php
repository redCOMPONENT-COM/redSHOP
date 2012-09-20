<?php
/**
 * @copyright Copyright (C) 2008 redCOMPONENT.com. All rights reserved.
 * @license can be read in this package of software in the file license.txt or
 * read on http://redcomponent.com/license.txt
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 */

// no direct access
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

// Include the syndicate functions only once
$module_base     = JURI::base() . 'modules/mod_redshop_masscart/';
$document =& JFactory::getDocument();
$document->addStyleSheet($module_base . 'css/style.css');

require(JModuleHelper::getLayoutPath('mod_redshop_masscart'));
?>
