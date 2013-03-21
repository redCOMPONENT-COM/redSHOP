<?php
/*
 * jQuery File Upload Plugin PHP Example 5.2.4
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://creativecommons.org/licenses/MIT/
 */
/*** access Joomla's configuration file ***/
$my_path = dirname(__FILE__);

if (file_exists($my_path . "/../../../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../../../configuration.php");
	require_once $my_path . "/../../../../configuration.php";
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

require_once JPATH_BASE . DS . 'includes' . DS . 'defines.php';
require_once JPATH_BASE . DS . 'includes' . DS . 'framework.php';

JDEBUG ? $_PROFILER->mark('afterLoad') : null;

/**
 * CREATE THE APPLICATION
 *
 * NOTE :
 */
$mainframe =& JFactory::getApplication('site');

/**
 * INITIALISE THE APPLICATION
 *
 * NOTE :
 */
// set the language
$mainframe->initialise();

define('JPATH_COMPONENT_SITE', JPATH_SITE . DS . 'components' . DS . 'com_redshop');

require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'redshop.cfg.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'configuration.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'product.php';
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

require_once JPATH_SITE . DS . 'plugins' . DS . 'system' . DS . 'redproductzoom' . DS . 'redproductzoom.php';
require_once JPATH_SITE . DS . 'plugins' . DS . 'system' . DS . 'redproductzoom' . DS . 'ajax' . DS . 'helper.php';
$zoomproducthelper = new zoomproducthelper;

#error_reporting(E_ALL | E_STRICT);
echo "<pre />";

$uri =& JURI::getInstance();
$url = $uri->toString(array('scheme', 'host', 'port'));
$path = explode("/", $uri->getPath());

if ($path[1] != "plugins") $url .= "/" . $path[1] . "/";

$get = JRequest::get('get');
$option = JRequest::getVar('option');

$property_id = urldecode($get['property_id']);
$subproperty_id = urldecode($get['subproperty_id']);

$product_id = $get['product_id'];
$accessory_id = $get['accessory_id'];
$relatedprd_id = $get['relatedprd_id'];
$main_imgwidth = PRODUCT_MAIN_IMAGE;
$main_imgheight = PRODUCT_MAIN_IMAGE_HEIGHT;
$redview = $get['redview'];
$redlayout = $get['redlayout'];

$dispatcher =& JDispatcher::getInstance();
JPluginHelper::importPlugin('redshop_product');
$pluginResults = $dispatcher->trigger('onBeforeImageLoad', array($get));

if (!empty($pluginResults))
{
	$mainImageResponse = $pluginResults[0]['mainImageResponse'];
	$result = $zoomproducthelper->displayAdditionalImage($product_id, $accessory_id, $relatedprd_id, $property_id, $subproperty_id);
}
else
{
	$result = $zoomproducthelper->displayAdditionalImage($product_id, $accessory_id, $relatedprd_id, $property_id, $subproperty_id, $main_imgwidth, $main_imgheight, $redview, $redlayout);
	$mainImageResponse = $result['mainImageResponse'];
}

$response = $result['response'];
$aHrefImageResponse = $result['aHrefImageResponse'];
$aTitleImageResponse = $result['aTitleImageResponse'];
$stockamountSrc = $result['stockamountSrc'];
$stockamountTooltip = $result['stockamountTooltip'];
$ProductAttributeDelivery = $result['ProductAttributeDelivery'];
$attrbimg = $result['attrbimg'];
$pr_number = $result['pr_number'];
$productinstock = $result['productinstock'];
$stock_status = $result['stock_status'];
$ImageName = $result['ImageName'];

$product_img = "";
//$view = $result['view'];
echo "`_`" . $response . "`_`" . $aHrefImageResponse . "`_`" . $aTitleImageResponse . "`_`" . $mainImageResponse . "`_`" . $stockamountSrc . "`_`" . $stockamountTooltip . "`_`" . $ProductAttributeDelivery . "`_`" . $product_img . "`_`" . $pr_number . "`_`" . $productinstock . "`_`" . $stock_status . "`_`" . $attrbimg;
exit;
?>