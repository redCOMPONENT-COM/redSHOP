<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Layouts
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('redshopjquery.framework');
JHtml::_('behavior.framework', true);
JHtml::_('redshopjquery.select2', 'select:not(".disableBootstrapChosen")', array("width" => "auto", "dropdownAutoWidth" => "auto"));

/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/redshop.attribute.min.js', false, true);
/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/redshop.common.min.js', false, true);
/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/redshop.redbox.min.js', false, true);
/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/bootstrap.min.js', false, true);

$app = JFactory::getApplication();
$doc = new RedshopHelperDocument;

$doc->addBottomStylesheet(JURI::root() . 'media/com_redshop/css/bootstrap-grid.min.css');

if (Redshop::getConfig()->getBool('LOAD_REDSHOP_STYLE', true))
{
	$doc->addBottomStylesheet(JURI::root() . 'media/com_redshop/css/redshop.min.css');
}

// Use different CSS for print layout
if ($app->input->getCmd('print', ''))
{
	$doc->addBottomStylesheet(JURI::root() . 'media/com_redshop/css/redshop.print.min.css');
}
