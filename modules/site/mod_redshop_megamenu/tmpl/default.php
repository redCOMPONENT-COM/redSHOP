<?php
/**
 * @package     Redshopb.Site
 * @subpackage  mod_redshopb_megamenu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.loadCss', true);
$app = JFactory::getApplication();
$input = $app->input;

JFactory::getDocument()->addScriptDeclaration('
(function($){
	$(document).ready(function () {
		jQuery(\'#redshopbMegaMenu_' . $module->id . '\').shopbMegaMenu({
			effect: \'' . $params->get('effect', 'fade') . '\', animation: \'' . $params->get('animation', 'none') . '\',
			indicatorFirstLevel: \'' . $params->get('indicatorFirstLevel', '+') . '\', indicatorSecondLevel: \'' . $params->get('indicatorSecondLevel', '+') . '\',
			showSpeed: ' . (int) $params->get('showSpeed', 300) . ', hideSpeed: ' . (int) $params->get('hideSpeed', 100) . ',
			showOverlay: ' . ($params->get('showOverlay', 0) ? 'true' : 'false') . '
		});
	});
})(jQuery);
');
?>
<div id="redshopbMegaMenu_<?php echo $module->id; ?>" class="navbar shopbMegaMenu">
	<ul class="nav shopbMegaMenu-menu menu<?php
	echo $class_sfx; ?>">
	<?php foreach ($categories as $key => $category): ?>
		<?php if($category->published == 1):?>
			<?php $parent = !empty($category->sub_cat) ? ' parent ' : ''; ?>
			<?php $active = $category->menu_parent_id == $input->get('Itemid') ? ' active ' : ''; ?>
		<li class="item-<?php echo $category->category_id ?> level-item-1 <?php echo $parent . $active; ?> <?php echo $category->menu_anchor_css ?>">
			<a href="<?php echo $category->link; ?>">
				<span class="menuLinkTitle"><?php echo $category->category_name ?></span>
			</a>
			<?php if (!empty($category->sub_cat)) : ?>
				<ul class="nav-child unstyled megamenu">
					<li class="maxMegaMenuWidth">
						<div class="row-fluid">
							<?php ModRedshopMegaMenuHelper::displayLevel($category->sub_cat, $category); ?>
						</div>
					</li>
				</ul>
			<?php endif;?>
		</li>
		<?php endif;?>
	<?php endforeach; ?>
	</ul>
	<div class="clearfix"></div>
</div>
