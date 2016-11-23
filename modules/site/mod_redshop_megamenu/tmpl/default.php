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
		<?php $clsdeeper = !empty($category->sub_cat) ? 'deeper' : ''; ?>
		<li class="item-<?php echo $category->category_id ?> level-item-1 current parent <?php echo $clsdeeper;?> <?php echo $category->menu_anchor_css ?>">
			<a href="<?php echo $category->link; ?>">
				<span class="menuLinkTitle"><?php echo $category->category_name ?></span>
			</a>
			<?php if (!empty($category->sub_cat)) : ?>
				<?php ModRedshopMegaMenuHelper::displayLevel($category->sub_cat, $category); ?>
			<?php endif;?>
		</li>
		<?php endif;?>
	<?php endforeach; ?>
	</ul>
	<div class="clearfix"></div>
</div>
