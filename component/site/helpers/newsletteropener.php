<?php
/**
 * Redirecting to new function
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       1.0
 * @deprecated  This file will be removed in redSHOP 1.6
 */
$theHost = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

header('Location: ' . $theHost . '/index.php?option=com_redshop&view=newsletter&task=tracker&tmpl=component&tracker_id=' . (int) $_REQUEST['tracker_id']);
