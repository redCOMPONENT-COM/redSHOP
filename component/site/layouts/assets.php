<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Layouts
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('redshopjquery.framework');
JHtml::_('redshopjquery.select2', 'select:not(".disableBootstrapChosen")', array("width" => "auto", "dropdownAutoWidth" => "auto"));

$app = JFactory::getApplication();
$doc = new RedshopHelperDocument;

// We will apply our own searchtools styles
$doc->disableStylesheet('media/redcore/lib/jquery-searchtools/jquery.searchtools.css');
$doc->disableStylesheet('media/redcore/lib/jquery-searchtools/jquery.searchtools.min.css');
$doc->disableStylesheet('media/redcore/css/lib/chosen.min.css');
$doc->disableStylesheet('media/redcore/css/lib/chosen.css');

// Disable redCORE things
$doc->disableScript('media/redcore/js/lib/jquery.min.js');
$doc->disableScript('media/redcore/js/lib/jquery-migrate.min.js');
$doc->disableScript('media/redcore/js/lib/jquery-noconflict.js');
$doc->disableScript('media/redcore/js/lib/bootstrap.min.js');
$doc->disableScript('media/redcore/lib/bootstrap/js/bootstrap.min.js');
$doc->disableScript('media/redcore/lib/jquery.min.js');
$doc->disableScript('media/redcore/lib/jquery-migrate.min.js');
$doc->disableScript('media/redcore/lib/jquery-noconflict.js');
$doc->disableScript('media/redcore/lib/bootstrap.min.js');
$doc->disableScript('media/redcore/lib/bootstrap/js/bootstrap.min.js');

$doc->addScript(JURI::root() . 'media/com_redshop/js/redbox.js');
$doc->addScript(JURI::root() . 'media/com_redshop/js/attribute.js');
$doc->addScript(JURI::root() . 'media/com_redshop/js/common.js');

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
