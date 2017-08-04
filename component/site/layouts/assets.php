<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Layouts
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('redshopjquery.framework');
JHtml::_('redshopjquery.select2', 'select:not(".disableBootstrapChosen")', array("width" => "auto", "dropdownAutoWidth" => "auto"));

JHtml::script('com_redshop/attribute.js', false, true);
JHtml::script('com_redshop/common.js', false, true);
JHtml::script('com_redshop/redbox.js', false, true);

$app = JFactory::getApplication();
$doc = new RedshopHelperDocument;

$doc->addBottomStylesheet(JURI::root() . 'media/com_redshop/css/bootstrap-grid.css');

if (Redshop::getConfig()->get('LOAD_REDSHOP_STYLE'))
{
	$doc->addBottomStylesheet(JURI::root() . 'media/com_redshop/css/style.css');
}

// Use different CSS for print layout
if ($app->input->getCmd('print', ''))
{
	$doc->addBottomStylesheet(JURI::root() . 'media/com_redshop/css/print.css');
}
