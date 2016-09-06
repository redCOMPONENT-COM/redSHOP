<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$activeView = $view->getName();

?>
<div id="redSHOPAdminContainer" class="redSHOPAdminView<?php echo ucfirst($activeView) ?>">
	<div class="wrapper">
		<header class="main-header">
			<?php echo JLayoutHelper::render('component.full.header', $displayData); ?>
		</header>
		<aside class="main-sidebar">
			<?php echo JLayoutHelper::render('component.full.sidebar', array()); ?>
		</aside>
		<div class="content-wrapper">
			<section class="content-header clearfix">
				<?php echo JLayoutHelper::render('component.full.content.header', $displayData); ?>
			</section>
			<section class="content">
				<?php echo JLayoutHelper::render('component.full.content.body', $displayData); ?>
			</section>
		</div>
		<footer class="main-footer">
			<?php echo JLayoutHelper::render('component.full.content.footer', $displayData); ?>
		</footer>
	</div>
</div>
