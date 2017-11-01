<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$toolbar = JToolbar::getInstance('toolbar');

$view = JFactory::getApplication()->input->getCmd('view', 'redshop');

$classtoolbar = "";

if ($view == 'redshop')
{
	$classtoolbar = ' class="hidden-xs"';
}

?>

<div class="component-title">
	<?php echo JFactory::getApplication()->JComponentTitle; ?>
</div>

<div<?php echo $classtoolbar ?>>
	<?php echo $toolbar->render() ?>
</div>

<div class="row-fluid message-sys" id="message-sys"></div>
