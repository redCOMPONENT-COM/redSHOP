<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  redSHOP
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


// Access Joomla's configuration file
$my_path = dirname(__FILE__);

if (file_exists($my_path . "/../../../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../../../configuration.php");
	require_once $my_path . "/../../../../configuration.php";
}
elseif (file_exists($my_path . "/../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../configuration.php");
	require_once $my_path . "/../../configuration.php";
}
elseif (file_exists($my_path . "/configuration.php"))
{
	$absolute_path = dirname($my_path . "/configuration.php");
	require_once $my_path . "/configuration.php";
}
else
{
	die("Joomla Configuration File not found!");
}

$absolute_path = realpath($absolute_path);
define('_JEXEC', 1);
define('JPATH_BASE', $absolute_path);
define('DS', DIRECTORY_SEPARATOR);
define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_BASE . '/administrator/components/com_redshop');
define('JPATH_COMPONENT', JPATH_BASE . '/components/com_redshop');


// Load the framework
require_once $absolute_path . '/includes/defines.php';
require_once $absolute_path . '/includes/framework.php';

// Create the mainframe object
$app = JFactory::getApplication();

// Initialize the framework
$app->initialise();

// Load system plugin group
JPluginHelper::importPlugin('system');

// Create the mainframe object
$app = JFactory::getApplication();

// Trigger the onBeforeStart events
$app->triggerEvent('onBeforeStart');
$lang           = JFactory::getLanguage();
$mosConfig_lang = $GLOBALS['mosConfig_lang'] = strtolower($lang->getBackwardLang());
$session        = JFactory::getSession();

// Load redSHOP Library
JLoader::import('redshop.library');

$width       = isset($_GET['width']) ? $_GET['width'] : '120';
$height      = isset($_GET['height']) ? $_GET['height'] : '40';
$characters  = isset($_GET['characters']) && $_GET['characters'] > 1 ? $_GET['characters'] : '6';
$captchaname = isset($_GET['captcha']) ? $_GET['captcha'] : 'security_code';

$captcha = new RedshopSiteCaptcha($width, $height, $characters, $captchaname);
