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
// Access Joomla's configuration file
$my_path = dirname(__FILE__);

if (file_exists($my_path . "/../../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../../configuration.php");
	require_once $my_path . "/../../../configuration.php";
}
else
{
	die("Joomla Configuration File not found!");
}

$absolute_path = realpath($absolute_path);

// Set flag that this is a parent file
define('_JEXEC', 1);

define('JPATH_BASE', $absolute_path);

define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

JDEBUG ? $_PROFILER->mark('afterLoad') : null;

define('_VALID_MOS', 1);
define('IMG_WIDTH', 50);

define('BASE_PATH', "../assets/images/");

include JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';

$tracker_id = @basename(urldecode($_REQUEST['tracker_id']));
$db         = JFactory::getDbo();
$query      = "UPDATE `#__redshop_newsletter_tracker` SET `read` = '1' WHERE tracker_id = " . (int) $tracker_id;
$db->setQuery($query);
$db->execute();

$uri        = JURI::getInstance();
$requesturl = JFilterOutput::cleanText($uri->toString());
$url        = parse_url($requesturl);

$img = $url['scheme'] . "://" . $url['host'] . '/components/com_redshop/assets/images/spacer.gif';
header("Content-type: image/gif");
readfile($img);
