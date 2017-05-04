<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$logoLgUrl = JUri::root() . 'media/com_redshop/images/redshop_white_logo.png';

?>
<?php if (!$disableSidebar): ?>
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
<?php endif; ?>

<!-- Logo -->
<a href="<?php echo JRoute::_('index.php?option=com_redshop'); ?>" class="logo">
  <span class="logo-lg">
	  <img src="<?php echo $logoLgUrl ?>" class="center-block">
  </span>
</a>

<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top" role="navigation">
    <div class="navbar-custom-menu">
		<?php echo RedshopLayoutHelper::render('component.full.header.menu', $displayData); ?>
    </div>
</nav>