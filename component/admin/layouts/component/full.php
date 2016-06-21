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


//print_r($displayData);
// Container CSS class definition
if (version_compare(JVERSION, '3.0', '<'))
{
	$redSHOPCSSContainerClass = ' isJ25';
}
else
{
	$redSHOPCSSContainerClass = ' isJ30';
}

?>
<div id="redSHOPAdminContainer" class="redSHOPAdminView<?php echo ucfirst($activeView) ?> <?php echo $redSHOPCSSContainerClass ?>">
	<div class="wrapper">
		<header class="main-header">
			<?php echo JLayoutHelper::render('component.full.header', array()); ?>
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
