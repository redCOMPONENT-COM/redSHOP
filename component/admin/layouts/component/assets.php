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
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('redshopjquery.framework');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_(
    'redshopjquery.select2',
    'select:not(".disableBootstrapChosen")',
    array("width" => "auto", "dropdownAutoWidth" => "auto")
);
HTMLHelper::_('redshopjquery.popover', '.hasPopover', ['placement' => 'top']);

$app = Factory::getApplication();
$doc = new RedshopHelperDocument;

$doc->addTopScript(JURI::root() . 'media/com_redshop/js/redshop.admin.min.js');
$doc->addTopScript(JURI::root() . 'media/com_redshop/js/redshop.validation.min.js');
$doc->addTopScript(JURI::root() . 'media/com_redshop/js/redshop.alert.min.js');
HTMLHelper::stylesheet('com_redshop/redshop.admin.min.css', ['version' => 'auto', 'relative' => true]);
HTMLHelper::stylesheet('com_redshop/font-awesome.min.css', ['version' => 'auto', 'relative' => true]);

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