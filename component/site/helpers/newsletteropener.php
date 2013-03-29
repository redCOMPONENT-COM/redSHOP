<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/*** access Joomla's configuration file ***/
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
$db         = JFactory::getDBO();
$query      = "UPDATE `#__redshop_newsletter_tracker` SET `read` = '1' WHERE tracker_id = '" . $tracker_id . "' ";
$db->setQuery($query);
$db->query();

$uri        = JURI::getInstance();
$requesturl = $uri->toString();
$url        = parse_url($requesturl);

$img = $url['scheme'] . "://" . $url['host'] . '/components/com_redshop/assets/images/spacer.gif';
header("Content-type: image/gif");
readfile($img);
