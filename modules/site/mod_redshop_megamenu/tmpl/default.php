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
		<li class="item-<?php echo $category->category_id ?> level-item-1 current parent <?php echo $clsdeeper;?>">
			<a href="<?php echo $category->link; ?>">
				<span class="menuLinkTitle"><?php echo $category->category_name ?></span>
			</a>
			<?php if (!empty($category->sub_cat)) : ?>
				<div class="dropdown lv1">
					<ul class="nav-child unstyled small container lv1">
						<?php if(!empty($category->image)):?>
							<div class="left-image row">
						<?php else:?>
							<div class="left-image-relative row">
						<?php endif;?>
						<?php foreach ($category->sub_cat as $sub_cat): ?>
							<li class="item-<?php echo $sub_cat->category_id ?> level-item-2 col-sm-3">
								<a href="<?php echo $sub_cat->link; ?>">
									<span class="menuLinkTitle"><?php echo $sub_cat->category_name ?></span>
									<?php if(!empty($sub_cat->image)):?>
										<img src="<?php echo JUri::root() . 'components/com_redshop/assets/images/category/' . $sub_cat->image; ?>" />
									<?php endif;?>
								</a>
								<?php if (!empty($sub_cat->sub_cat)) : ?>
									<div class="dropdown lv2">
										<ul class="nav-child unstyled small lv2">
											<?php foreach ($sub_cat->sub_cat as $child_cat): ?>
												<li class="item-<?php echo $child_cat->category_id ?> level-item-3">
													<a href="<?php $child_cat->link; ?>">
														<span class="menuLinkTitle"><?php echo $child_cat->category_name ?></span>
														<?php if(!empty($child_cat->image)):?>
															<img src="<?php echo JUri::root() . 'components/com_redshop/assets/images/category/' . $child_cat->image; ?>" />
														<?php endif;?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif;?>
							</li>
						<?php endforeach; ?>
					</div>
					</ul>
					<div class="container" style="display:none;">
						<?php if(!empty($category->image)):?>
							<img src="<?php echo JUri::root() . 'components/com_redshop/assets/images/category/' . $category->image; ?>" />
						<?php endif;?>
					</div>
				</div>
			<?php endif;?>
		</li>
		<?php endif;?>
	<?php endforeach; ?>
	</ul>
	<div class="clearfix"></div>
</div>
