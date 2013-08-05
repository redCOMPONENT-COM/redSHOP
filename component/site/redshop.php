<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  redSHOP
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

$app = JFactory::getApplication();
JLoader::import('joomla.html.parameter');

$option = JRequest::getCmd('option');
$view   = JRequest::getCmd('view');

// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/' . $option . '/helpers/redshop.cfg.php';
require_once JPATH_ADMINISTRATOR . '/components/' . $option . '/helpers/configuration.php';
require_once JPATH_ADMINISTRATOR . '/components/' . $option . '/helpers/template.php';
require_once JPATH_ADMINISTRATOR . '/components/' . $option . '/helpers/stockroom.php';
require_once JPATH_ADMINISTRATOR . '/components/' . $option . '/helpers/economic.php';

$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

JLoader::import('joomla.html.pagination');

require_once JPATH_COMPONENT . '/helpers/cron.php';
require_once JPATH_COMPONENT . '/helpers/statistic.php';
require_once JPATH_COMPONENT . '/helpers/pagination.php';
require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_COMPONENT . '/helpers/product.php';
require_once JPATH_COMPONENT . '/helpers/currency.php';

// Helper object
$helper = new redhelper;

// Include redCRM if required
$helper->isredCRM();

$print = JRequest::getCmd('print');

// Adding Redshop CSS
$doc = JFactory::getDocument();

// Use diffrent CSS for print layout
if (!$print)
	JHTML::Stylesheet('redshop.css', 'components/com_redshop/assets/css/');
else
	JHTML::Stylesheet('print.css', 'components/com_redshop/assets/css/');

JHTML::Stylesheet('style.css', 'components/com_redshop/assets/css/');
$Itemid = $helper->getCheckoutItemid();
$Itemid = JRequest::getInt('Itemid', $Itemid);
$Itemid = $helper->getCartItemid($Itemid);

// Include redshop js file.
require_once JPATH_COMPONENT . '/helpers/redshop.js.php';

$controller = JRequest::getCmd('view', 'category');

$task   = JRequest::getCmd('task');
$format = JRequest::getWord('format', '');
$layout = JRequest::getWord('layout', '');

$params = $app->getParams('com_redshop');

// Add product in cart from db
$helper->dbtocart();

$categoryid = JRequest::getInt('cid', $params->get('categoryid'));
$productid  = JRequest::getInt('pid', 0);

$sgportal = $helper->getShopperGroupPortal();
$portal   = 0;
if (count($sgportal) > 0)
	$portal = $sgportal->shopper_group_portal;

$user = JFactory::getUser();

if ($task != 'loadProducts' && $task != "downloadProduct" && $task != "discountCalculator" && $task != "ajaxupload" && $task != 'getShippingrate' && $task != 'addtocompare' && $task != 'ajaxsearch' && $task != "Download" && $task != 'addtowishlist')
{
	echo "<div id='redshopcomponent' class='redshop'>";

	if ($format != 'final' && $layout != 'receipt')
	{
		/*
		 * get redSHOP Google Analytics Plugin is Enable?
		 * If it is Disable than load Google Analytics From redSHOP
		 */
		$isredGoogleAnalytics = JPluginHelper::isEnabled('system', 'redgoogleanalytics');

		if (!$isredGoogleAnalytics && GOOGLE_ANA_TRACKER_KEY != "")
		{
			require_once JPATH_COMPONENT . '/helpers/google_analytics.php';

			$google_ana = new googleanalytics;

			$anacode = $google_ana->placeTrans();
		}
	}
}

if (PORTAL_SHOP == 1)
{
	if ($controller == 'product' && $productid > 0 && $user->id > 0)
	{
		$checkcid = $helper->getShopperGroupProductCategory($productid);

		if ($checkcid == true)
		{
			$controller = 'login';
			JRequest::setVar('view', 'login');
			JRequest::setVar('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}
	elseif ($controller == 'category' && $categoryid > 0 && $user->id > 0)
	{
		$checkcid = $helper->getShopperGroupCategory($categoryid);

		if ($checkcid == "")
		{
			$controller = 'login';
			JRequest::setVar('view', 'login');
			JRequest::setVar('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}
	else
	{
		$controller = 'login';
		JRequest::setVar('view', 'login');
		JRequest::setVar('layout', 'portal');
	}
}
else
{
	if ($controller == 'product' && $productid > 0 && $portal == 1)
	{
		$checkcid = $helper->getShopperGroupProductCategory($productid);

		if ($checkcid == true)
		{
			$controller = 'login';
			JRequest::setVar('view', 'login');
			JRequest::setVar('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}

	if ($controller == 'category' && $categoryid > 0 && $portal == 1)
	{
		$checkcid = $helper->getShopperGroupCategory($categoryid);

		if ($checkcid == "")
		{
			$controller = 'login';
			JRequest::setVar('view', 'login');
			JRequest::setVar('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}

	if ($controller == 'redshop')
	{
		$controller = 'category';
		JRequest::setVar('view', 'category');
	}
}


// Set the controller page
if (!file_exists(JPATH_COMPONENT . '/controllers/' . $controller . '.php'))
{
	$controller = 'category';
	JRequest::setVar('view', 'category');
}

require_once JPATH_COMPONENT . '/controllers/' . $controller . '.php';

// Set the controller page

$classname = $controller . 'controller';

// Create a new class of classname and set the default task:display

$controller = new $classname(array('default_task' => 'display'));

// Perform the Request task

$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
echo "</div>";
$controller->redirect();
