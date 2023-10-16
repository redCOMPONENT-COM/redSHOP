<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Layouts
 *
 * @copyright   Copyright (C) 2008 - 2023 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

$app = Factory::getApplication();

HTMLHelper::_('redshopjquery.framework');
HTMLHelper::_(
    'redshopjquery.select2',
    'select:not(".disableBootstrapChosen")',
    array("width" => "auto", "dropdownAutoWidth" => "auto")
);

HTMLHelper::script('com_redshop/redshop.attribute.min.js', ['relative' => true]);
HTMLHelper::script('com_redshop/redshop.common.min.js', ['relative' => true]);
HTMLHelper::script('com_redshop/bootstrap.min.js', ['relative' => true]);

if (Redshop::getConfig()->getBool('LOAD_REDSHOP_STYLE', true)) {
    HTMLHelper::stylesheet('com_redshop/redshop.min.css', ['version' => 'auto', 'relative' => true]);
    HTMLHelper::stylesheet('com_redshop/bootstrap-grid.min.css', ['version' => 'auto', 'relative' => true]);
}

// Use different CSS for print layout
if ($app->input->getCmd('print', '')) {
    HTMLHelper::stylesheet('com_redshop/css/redshop.print.min.css', ['version' => 'auto', 'relative' => true]);
}