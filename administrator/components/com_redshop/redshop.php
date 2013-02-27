<?php
/**
 * @package     redSHOP
 * @subpackage  Backend
 *
 * @todo        move this code in the front controller...
 * @todo        make the left menu a template
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'configuration.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'template.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'stockroom.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'economic.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'access_level.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'helper.php');

$input = JFactory::getApplication()->input;

$view = $input->get('view');
$task = $input->get('task');

$user = JFactory::getUser();

// reddesign
if ($task == 'downloaddesign')
{
    error_reporting(0);
}

$configpath = JPATH_COMPONENT . DS . 'helpers' . DS . 'redshop.cfg.php';

if (!file_exists($configpath))
{
    error_reporting(0);
    $input->set('view', 'redshop');
    $input->set('layout', 'noconfig');
}
else
{
    require_once($configpath);
}

$redhelper = new redhelper();
$redhelper->removeShippingRate();
$Redconfiguration = new Redconfiguration();
$Redconfiguration->defineDynamicVars();

$usertype       = array_keys($user->groups);
$user->usertype = $usertype[0];
$user->gid      = $user->groups[$user->usertype];

if (ENABLE_BACKENDACCESS && $user->gid != 8)
{
    $access_rslt = new Redaccesslevel();
    $access_rslt->checkaccessofuser($user->gid);
}

if (ENABLE_BACKENDACCESS):
    if ($user->gid != 8 && $view != ''):
        $redaccesslevel = new Redaccesslevel();
        $redaccesslevel->checkgroup_access($view, $task, $user->gid);
    endif;
endif;

$isWizard = $input->getInt('wizard', 0);
$step     = $input->get('step', '');

# initialize wizard
if ($isWizard || $step != '')
{

    if (ENABLE_BACKENDACCESS):
        if ($user->gid != 8):
            $redaccesslevel = new Redaccesslevel();
            $redaccesslevel->checkgroup_access('wizard', '', $user->gid);
        endif;
    endif;

    require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'wizard' . DS . 'wizard.php');
    $redSHOPWizard = new redSHOPWizard();
    $redSHOPWizard->initialize();
    return true;
}
# End
$layout      = $input->get('layout', '');
$showbuttons = $input->get('showbuttons', '0');
$showall     = $input->get('showall', '0');
$json_var    = $input->get('json');

$document = JFactory::getDocument();
$document->addStyleDeclaration('fieldset.adminform textarea {margin: 0px 0px 10px 0px !important;width: 100% !important;}');

$document->addScriptDeclaration("
	var site_url = '" . JURI::root() . "';
	var REDCURRENCY_SYMBOL = '" . REDCURRENCY_SYMBOL . "';
	var PRICE_SEPERATOR = '" . PRICE_SEPERATOR . "';
	var CURRENCY_SYMBOL_POSITION = '" . CURRENCY_SYMBOL_POSITION . "';
	var PRICE_DECIMAL = '" . PRICE_DECIMAL . "';
	var IS_REQUIRED = '" . JText::_('COM_REDSHOP_IS_REQUIRED') . "';
	var THOUSAND_SEPERATOR = '" . THOUSAND_SEPERATOR . "';

");

$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/redshop.css');
if ($view != '' && $view != "search" && $view != "order_detail" && $view != "wizard" && $task != "getcurrencylist" && $layout != "thumbs" && $view != "catalog_detail" && $task != "clearsef" && $task != "removesubpropertyImage" && $task != "removepropertyImage" && $view != "product_price" && $task != "template" && $json_var == '' && $task != 'gbasedownload' && $task != "export_data" && $showbuttons != "1" && $showall != 1 && $view != "product_attribute_price" && $task != "ins_product" && $view != "shipping_rate_detail" && $view != "accountgroup_detail" && $layout != "labellisting" && $task != "checkVirtualNumber")
{
    echo '<div style="width:100%;">';
    if ($view != "redshop" && $view != "configuration" && $view != "product_detail" && $view != "country_detail" && $view != "state_detail" && $view != "category_detail" && $view != "fields_detail" && $view != "container_detail" && $view != "stockroom_detail" && $view != "shipping_detail" && $view != "user_detail" && $view != "template_detail" && $view != "voucher_detail" && $view != "textlibrary_detail" && $view != "manufacturer_detail" && $view != "rating_detail" && $view != "newslettersubscr_detail" && $view != "discount_detail" && $view != "mail_detail" && $view != "newsletter_detail" && $view != "media_detail" && $view != "shopper_group_detail" && $view != "sample_detail" && $view != "attributeprices" && $view != "attributeprices_detail" && $view != "prices_detail" && $view != "wrapper_detail" && $view != "tax_group_detail" && $view != "addorder_detail" && $view != "tax_detail" && $view != "coupon_detail" && $view != "giftcard_detail" && $view != "attribute_set_detail" && $view != 'shipping_box_detail' && $view != 'quotation_detail' && $view != 'question_detail' && $view != 'answer_detail' && $view != 'xmlimport_detail' && $view != 'addquotation_detail' && $view != 'xmlexport_detail' && $task != 'element' && $view != 'stockimage_detail' && $view != 'mass_discount_detail' && $view != 'supplier_detail' && $view != 'orderstatus_detail')
    {
        echo '<div style="float:left;width:19%; margin-right:1%;">';
        require_once (JPATH_COMPONENT . DS . 'helpers/menu.php');
        $menu = new leftmenu();
        echo '</div>';
        echo '<div style="float:left;width:80%;">';
    }
}

// Get the front controller.
$controller = JControllerLegacy::getInstance('Redshop');

// Perform the Request task
$controller->execute($input->get('task'));

// Redirect if set by the controller
$controller->redirect();

echo "</div>";
echo "</div>";
echo "<div>";
