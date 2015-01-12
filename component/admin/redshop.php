<?php
/**
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package    RedSHOP.Backend
 *
 * @copyright  Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Load redSHOP Library
JLoader::import('redshop.library');

$configpath = JPATH_COMPONENT . '/helpers/redshop.cfg.php';

if (!file_exists($configpath))
{
	error_reporting(0);
	$controller = 'redshop';
	JRequest::setVar('view', 'redshop');
	JRequest::setVar('layout', 'noconfig');
}
else
{
	require_once $configpath;
}

JLoader::load('RedshopHelperAdminProduct');
JLoader::load('RedshopHelperAdminConfiguration');
JLoader::load('RedshopHelperAdminTemplate');
JLoader::load('RedshopHelperAdminStockroom');
JLoader::load('RedshopHelperAdminEconomic');
JLoader::load('RedshopHelperAdminAccess_level');
JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperAdminImages');
JLoader::load('RedshopHelperAdminCategory');

$redhelper = new redhelper;
$redhelper->removeShippingRate();
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();
$json_var = JRequest::getVar('json');

$view = JRequest::getVar('view');
$user = JFactory::getUser();
$usertype = array_keys($user->groups);
$user->usertype = $usertype[0];
$user->gid = $user->groups[$user->usertype];

if (ENABLE_BACKENDACCESS && $user->gid != 8 && !$json_var)
{
	$access_rslt = new Redaccesslevel;
	$access_rslt->checkaccessofuser($user->gid);
}

if (ENABLE_BACKENDACCESS)
{
	if ($user->gid != 8 && $view != '' && !$json_var)
	{
		$task = JRequest::getVar('task');
		$redaccesslevel = new Redaccesslevel;
		$redaccesslevel->checkgroup_access($view, $task, $user->gid);
	}
}

$isWizard = JRequest::getInt('wizard', 0);
$step     = JRequest::getVar('step', '');

// Initialize wizard
if ($isWizard || $step != '')
{
	if (ENABLE_BACKENDACCESS)
	{
		if ($user->gid != 8)
		{
			$redaccesslevel = new Redaccesslevel;
			$redaccesslevel->checkgroup_access('wizard', '', $user->gid);
		}
	}

	JRequest::setVar('view', 'wizard');

	require_once JPATH_COMPONENT . '/helpers/wizard/wizard.php';
	$redSHOPWizard = new redSHOPWizard;
	$redSHOPWizard->initialize();

	return true;
}

$view = $app->input->get('view', 'redshop');

JHtml::_('behavior.framework');
JHtml::_('redshopjquery.framework');
$document = JFactory::getDocument();

if (version_compare(JVERSION, '3.0', '>='))
{
	JHtml::_('formbehavior.chosen', 'select');
	$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/j3ready.css');
}

$user        = JFactory::getUser();
$task        = $app->input->get('task', '');
$layout      = JRequest::getVar('layout', '');
$showbuttons = JRequest::getVar('showbuttons', '0');
$showall     = JRequest::getVar('showall', '0');

// Check for array format.
$filter = JFilterInput::getInstance();

if (is_array($task))
{
	$command = $filter->clean(array_pop(array_keys($task)), 'cmd');
}
else
{
	$command = $filter->clean($task, 'cmd');
}

// Check for a not controller.task command.
if ($command != '' && strpos($command, '.') === false)
{
	JRequest::setVar('task', $view . '.' . $command);
	$task = $command;
}
elseif ($command != '' && strpos($command, '.') !== false)
{
	$commands = explode('.', $command);
	$view = $commands[0];
	$task = $commands[1];
}

// Set the controller page
if (!file_exists(JPATH_COMPONENT . '/controllers/' . $view . '.php'))
{
	$view = 'redshop';
	JRequest::setVar('view', $view);
}

$document->addStyleDeclaration('fieldset.adminform textarea {margin: 0px 0px 10px 0px !important;width: 100% !important;}');

RedshopConfig::script('SITE_URL', JURI::root());
RedshopConfig::script('REDCURRENCY_SYMBOL', REDCURRENCY_SYMBOL);
RedshopConfig::script('PRICE_SEPERATOR', PRICE_SEPERATOR);
RedshopConfig::script('CURRENCY_SYMBOL_POSITION', CURRENCY_SYMBOL_POSITION);
RedshopConfig::script('PRICE_DECIMAL', PRICE_DECIMAL);
RedshopConfig::script('THOUSAND_SEPERATOR', THOUSAND_SEPERATOR);
RedshopConfig::script('VAT_RATE_AFTER_DISCOUNT', VAT_RATE_AFTER_DISCOUNT);
RedshopConfig::script('IS_REQUIRED', IS_REQUIRED);

$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/redshop.css');
$format = $app->input->get('format', 'html');

if ($view != "search" && $view != "order_detail" && $view != "wizard" && $task != "getcurrencylist"
	&& $layout != "thumbs" && $view != "catalog_detail" && $task != "clearsef" && $task != "removesubpropertyImage"
	&& $task != "removepropertyImage" && $view != "product_price" && $task != "template" && $json_var == ''
	&& $task != 'gbasedownload' && $task != "export_data" && $showbuttons != "1" && $showall != 1
	&& $view != "product_attribute_price" && $task != "ins_product" && $view != "shipping_rate_detail"
	&& $view != "accountgroup_detail" && $layout != "labellisting" && $task != "checkVirtualNumber" && $view != "update"
	&& $format == 'html')
{
	// Container CSS class definition
	if (version_compare(JVERSION, '3.0', '<'))
	{
		$redSHOPCSSContainerClass = ' isJ25';
	}
	else
	{
		$redSHOPCSSContainerClass = ' isJ30';
	}

	echo '<div id="redSHOPAdminContainer" class="redSHOPAdminView' . ucfirst($view) . $redSHOPCSSContainerClass . '">';

	if ($view != "redshop" && $view != "configuration" && $view != "product_detail"
		&& $view != "country_detail" && $view != "state_detail" && $view != "category_detail"
		&& $view != "fields_detail" && $view != "stockroom_detail"
		&& $view != "shipping_detail" && $view != "user_detail" && $view != "template_detail"
		&& $view != "voucher_detail" && $view != "textlibrary_detail" && $view != "manufacturer_detail"
		&& $view != "rating_detail" && $view != "newslettersubscr_detail" && $view != "discount_detail"
		&& $view != "mail_detail" && $view != "newsletter_detail" && $view != "media_detail"
		&& $view != "shopper_group_detail" && $view != "sample_detail" && $view != "attributeprices"
		&& $view != "attributeprices_detail" && $view != "prices_detail" && $view != "wrapper_detail"
		&& $view != "tax_group_detail" && $view != "addorder_detail" && $view != "tax_detail"
		&& $view != "coupon_detail" && $view != "giftcard_detail" && $view != "attribute_set_detail"
		&& $view != 'shipping_box_detail' && $view != 'quotation_detail'
		&& $view != 'question_detail' && $view != 'answer_detail'
		&& $view != 'xmlimport_detail' && $view != 'addquotation_detail'
		&& $view != 'xmlexport_detail' && $task != 'element'  && $view != 'stockimage_detail'
		&& $view != 'mass_discount_detail' && $view != 'supplier_detail'
		&& $view != 'orderstatus_detail')
	{
		echo '<div style="float:left;width:19%; margin-right:1%;">';
		JLoader::load('RedshopHelperAdminMenu');
		$menu = new leftmenu;
		echo '</div>';

		// Set div for listing body
		echo '<div style="float:left;width:80%;">';
	}
}

// Execute the task.
$controller = JControllerLegacy::getInstance('Redshop');

if (version_compare(JVERSION, '3.0', '<'))
{
	$task = JRequest::getCmd('task');
}
else
{
	$task = $app->input->get('task', '');
}

$controller->execute($task);
$controller->redirect();

// End div here
echo '</div></div>';

// Set redshop config javascript header
RedshopConfig::scriptDeclaration();
