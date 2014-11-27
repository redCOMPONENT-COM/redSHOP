<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
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

$txtextra_info = $this->_params->get("txtextra_info");
$o = new stdClass;
$o->text = $txtextra_info;
JPluginHelper::importPlugin('content');

$dispatcher = JDispatcher::getInstance();

$x = array();

$results = $dispatcher->trigger('onPrepareContent', array(&$o, &$x, 0));

echo $o->text;
