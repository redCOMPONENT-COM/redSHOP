<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);

?>
<?php if(isset($items)) { ?>
<ul class="sidebar-menu">
	<?php foreach ($items as $group => $sections) : ?>
		<li class="treeview <?php echo ($active[0] == $group ? 'active': '') ?>">
			<a href="#">
				<i class="<?php echo strtolower($group)?>"></i>
				<span><?php echo JText::_('COM_REDSHOP_' . $group); ?></span>
			</a>

			<ul class="treeview-menu">
			<?php foreach ($sections as $sectionKey => $section) : ?>
				<li class="treeview <?php echo ($active[1] == $sectionKey ? 'active': '') ?>">
					<a href="#">
						<span><?php echo JText::_($section->title); ?></span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>

					<ul class="treeview-menu">
					<?php foreach ($section->items as $item) : ?>
						<li>
							<?php
							echo JLayoutHelper::render(
								'component.full.sidebar.link',
								array(
									'link'        => $item->link,
									'title'       => JText::_($item->title),
									'active' => JText::_($item->active)
								)
							);
							?>
						</li>
					<?php endforeach; ?>
					</ul>
				</li>
			<?php endforeach; ?>
			</ul>
		</li>
	<?php endforeach; ?>
</ul>
<?php } ?>