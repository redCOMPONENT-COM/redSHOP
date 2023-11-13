<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$toolbar = JToolbar::getInstance('toolbar');

$view = JFactory::getApplication()->input->getCmd('view', 'redshop');

$classtoolbar = "";

if ($view == 'redshop') {
    $classtoolbar = ' class="hidden-xs"';
}

?>
<div id="subhead-container" class="subhead mb-3 <?php echo $classtoolbar ?>">
    <?php echo $toolbar->render() ?>
</div>

<div class="row-fluid message-sys" id="message-sys"></div>