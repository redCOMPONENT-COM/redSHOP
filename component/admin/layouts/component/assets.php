<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layouts
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

JHtml::_('redshopjquery.framework');
JHtml::_('bootstrap.tooltip');
JHtml::_(
    'redshopjquery.select2',
    'select:not(".disableBootstrapChosen")',
    array("width" => "auto", "dropdownAutoWidth" => "auto")
);
JHtml::_('redshopjquery.popover', '.hasPopover', array('placement' => 'top'));

$app = Factory::getApplication();
$doc = new RedshopHelperDocument;

$doc->addTopScript(JURI::root() . 'media/com_redshop/js/redshop.admin.min.js');
$doc->addTopScript(JURI::root() . 'media/com_redshop/js/redshop.validation.min.js');
$doc->addTopScript(JURI::root() . 'media/com_redshop/js/redshop.alert.min.js');
$doc->addTopStylesheet(JURI::root() . 'media/com_redshop/css/redshop.admin.min.css');
$doc->addTopStylesheet(JURI::root() . 'media/com_redshop/css/font-awesome.min.css');

// Disable default template files
$doc->disableStylesheet('media/templates/administrator/atum/css/template.css');
$doc->disableStylesheet('media/templates/administrator/atum/css/vendor/joomla-custom-elements/joomla-tab.css');

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

// Disable core things
$doc->disableScript('media/jui/js/jquery.min.js');
$doc->disableScript('media/jui/js/jquery-noconflict.js');
$doc->disableScript('media/jui/js/jquery-migrate.min.js');
