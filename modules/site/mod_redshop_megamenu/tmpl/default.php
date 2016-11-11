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
			showSpeed: ' . (int) $params->get('showSpeed', 300) . ', hideSpeed: ' . (int) $params->get('hideSpeed', 300) . ',
			showOverlay: ' . ($params->get('showOverlay', 1) ? 'true' : 'false') . '
		});
	});
})(jQuery);
');
?>
<div id="redshopbMegaMenu_<?php echo $module->id; ?>" class="navbar shopbMegaMenu">
	<ul class="nav shopbMegaMenu-menu menu<?php
	echo $class_sfx; ?>">
	<?php foreach ($categories as $key => $category): ?>
		<li class="item-<?php echo $category->category_id ?> level-item-1 current active deeper parent">
			<a href="#">
				<span class="menuLinkTitle"><?php echo $category->category_name ?></span>
			</a>
			<ul class="nav-child unstyled small dropdown">
				<?php foreach ($category->sub_cat as $sub_cat): ?>
					<li class="item-<?php echo $sub_cat->category_id ?> level-item-2">
						<a href="#">
							<span class="menuLinkTitle"><?php echo $sub_cat->category_name ?></span>
						</a>
						<img src="<?php echo JUri::root() . 'components/com_redshop/assets/images/category/' . $sub_cat->image; ?>" />
						<ul class="nav-child unstyled small dropdown">
							<?php foreach ($sub_cat->sub_cat as $child_cat): ?>
								<li class="item-<?php echo $child_cat->category_id ?> level-item-3">
									<a href="#">
										<span class="menuLinkTitle"><?php echo $child_cat->category_name ?></span>
									</a>
									<img src="<?php echo JUri::root() . 'components/com_redshop/assets/images/category/' . $child_cat->image; ?>" />
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endforeach; ?>
			</ul>
		</li>
	<?php endforeach; ?>
	</ul>
	<div class="clearfix"></div>
</div>
