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
 * @copyright  Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
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

	$controller = JRequest::getVar('view', 'redshop');

	// Set the controller page
	if (!file_exists(JPATH_COMPONENT . '/controllers/' . $controller . '.php'))
	{
		$controller = 'redshop';
		JRequest::setVar('view', 'redshop');
	}

	JHtml::_('behavior.framework');
	$document = JFactory::getDocument();

	if (version_compare(JVERSION, '3.0', '>='))
	{
		JHtml::_('formbehavior.chosen', 'select');
		$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/j3ready.css');
	}

	$user        = JFactory::getUser();
	$task        = JRequest::getVar('task');
	$layout      = JRequest::getVar('layout', '');
	$showbuttons = JRequest::getVar('showbuttons', '0');
	$showall     = JRequest::getVar('showall', '0');

	$document->addStyleDeclaration('fieldset.adminform textarea {margin: 0px 0px 10px 0px !important;width: 100% !important;}');

	$document->addScriptDeclaration("
		var site_url = '" . JURI::root() . "';
		var REDCURRENCY_SYMBOL = '" . REDCURRENCY_SYMBOL . "';
		var PRICE_SEPERATOR = '" . PRICE_SEPERATOR . "';
		var CURRENCY_SYMBOL_POSITION = '" . CURRENCY_SYMBOL_POSITION . "';
		var PRICE_DECIMAL = '" . PRICE_DECIMAL . "';
		var IS_REQUIRED = '" . JText::_('COM_REDSHOP_IS_REQUIRED') . "';
		var THOUSAND_SEPERATOR = '" . THOUSAND_SEPERATOR . "';
		var VAT_RATE_AFTER_DISCOUNT = '" . VAT_RATE_AFTER_DISCOUNT . "';
	");

	$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/redshop.css');
	$format = $app->input->get('format', 'html');

	if ($controller != "search" && $controller != "order_detail" && $controller != "wizard" && $task != "getcurrencylist"
		&& $layout != "thumbs" && $controller != "catalog_detail" && $task != "clearsef" && $task != "removesubpropertyImage"
		&& $task != "removepropertyImage" && $controller != "product_price" && $task != "template" && $json_var == ''
		&& $task != 'gbasedownload' && $task != "export_data" && $showbuttons != "1" && $showall != 1
		&& $controller != "product_attribute_price" && $task != "ins_product" && $controller != "shipping_rate_detail"
		&& $controller != "accountgroup_detail" && $layout != "labellisting" && $task != "checkVirtualNumber" && $format == 'html')
	{
		if ($controller != "redshop" && $controller != "configuration" && $controller != "product_detail"
			&& $controller != "country_detail" && $controller != "state_detail" && $controller != "category_detail"
			&& $controller != "fields_detail" && $controller != "stockroom_detail"
			&& $controller != "shipping_detail" && $controller != "user_detail" && $controller != "template_detail"
			&& $controller != "voucher_detail" && $controller != "textlibrary_detail" && $controller != "manufacturer_detail"
			&& $controller != "rating_detail" && $controller != "newslettersubscr_detail" && $controller != "discount_detail"
			&& $controller != "mail_detail" && $controller != "newsletter_detail" && $controller != "media_detail"
			&& $controller != "shopper_group_detail" && $controller != "sample_detail" && $controller != "attributeprices"
			&& $controller != "attributeprices_detail" && $controller != "prices_detail" && $controller != "wrapper_detail"
			&& $controller != "tax_group_detail" && $controller != "addorder_detail" && $controller != "tax_detail"
			&& $controller != "coupon_detail" && $controller != "giftcard_detail" && $controller != "attribute_set_detail"
			&& $controller != 'shipping_box_detail' && $controller != 'quotation_detail'
			&& $controller != 'question_detail' && $controller != 'answer_detail'
			&& $controller != 'xmlimport_detail' && $controller != 'addquotation_detail'
			&& $controller != 'xmlexport_detail' && $task != 'element'  && $controller != 'stockimage_detail'
			&& $controller != 'mass_discount_detail' && $controller != 'supplier_detail'
			&& $controller != 'orderstatus_detail')
		{
			echo '<div style="float:left;width:19%; margin-right:1%;">';
			JLoader::load('RedshopHelperAdminMenu');
			$menu = new leftmenu;
			echo '</div>';

			// Set div for listing body
			echo '<div style="float:left;width:80%;">';
		}
	}

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
		JRequest::setVar('task', $controller . '.' . $command);
	}

	// Execute the task.
	$controller	= JControllerLegacy::getInstance('Redshop');

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
	echo '</div>';
