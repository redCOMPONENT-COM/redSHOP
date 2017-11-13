<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Display data
 * =======================
 * @var  array $displayData Available data.
 */
extract($displayData);
?>

<!-- Logo -->
<a href="<?php echo JRoute::_('index.php?option=com_redshop'); ?>" class="logo">
    <span class="logo-lg">
        <img src="<?php echo JUri::root() . 'media/com_redshop/images/redshop_white_logo.png' ?>" class="center-block">
    </span>
    <span class="logo-sm">
        <img src="<?php echo JUri::root() . 'media/com_redshop/images/redshop_white_logo_sm.png' ?>" class="center-block">
    </span>
</a>

<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top" role="navigation">
	<?php if (!$disableSidebar): ?>
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
	<?php endif; ?>
    <div class="navbar-custom-menu">
		<?php echo RedshopLayoutHelper::render('component.full.header.menu', $displayData); ?>
    </div>
</nav>