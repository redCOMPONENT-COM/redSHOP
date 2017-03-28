<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);
?>
<?php if (isset($items)): ?>
<ul class="sidebar-menu">
	<li>
		<a href="index.php?option=com_redshop">
			<i class="fa fa-bar-chart"></i>
			<span><?php echo JText::_('COM_REDSHOP_DASHBOARD') ?></span>
		</a>
	</li>
	<?php foreach ($items as $group => $sections): ?>
		<?php if (is_object($sections)): ?>
			<?php if ($sections->disable === false): ?>
				<li class="<?php echo ($sections->active) ? 'active' : '' ?>">
				<?php echo JLayoutHelper::render(
					'component.full.sidebar.link',
					array(
						'link'   => $sections->link,
						'title'  => JText::_($sections->title),
						'active' => JText::_($sections->active),
						'icon'   => isset($sections->icon) ? $sections->icon : ''
					)
				); ?>
				</li>
			<?php endif; ?>
			<?php continue; ?>
		<?php endif; ?>
		<?php if ($sections['disable'] === false && count($sections['items']) > 0) : ?>
			<?php if ($sections['style'] == 'header'): ?>
				<?php foreach ($sections['items'] as $section): ?>
					<?php foreach ($section->items as $item): ?>
						<?php if ($item->disable === false): ?>
							<li class="<?php echo ($item->active) ? 'active' : '' ?>">
							<?php
							echo JLayoutHelper::render(
								'component.full.sidebar.link',
								array(
									'link'   => $item->link,
									'title'  => JText::_($item->title),
									'active' => JText::_($item->active),
									'icon'   => isset($item->icon) ? $item->icon : ''
								)
							);
							?>
						</li>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php else: ?>
			<li class="treeview <?php echo ($active[0] == $group ? 'active': '') ?>">
				<a href="#">
					<?php if (strtolower($group) == 'stockroom'): ?>
						<i class="<?php echo (strtolower($group) == 'stockroom') ? 'fa fa-archive' : strtolower($group) ?>"></i>
					<?php elseif (strtolower($group) == 'product_listing'): ?>
						<i class="fa fa-briefcase"></i>
					<?php else: ?>
						<i class="<?php echo strtolower($group) ?>"></i>
					<?php endif; ?>
					<span><?php echo JText::_('COM_REDSHOP_' . $group); ?></span>
					<i class="fa fa-angle-right pull-right" style="margin-right: 0px;"></i>
				</a>
				<ul class="treeview-menu">
			<?php if(count($sections['items']) == 1) : ?>
			<?php $curSection = reset($sections['items']); ?>
			<?php foreach ($curSection->items as $item) : ?>
					<?php if ($item->disable === false): ?>
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
					<?php endif; ?>
				<?php endforeach; ?>
			<?php else : ?>
				<?php foreach ($sections['items'] as $sectionKey => $section) : ?>
					<?php if(count($section->items) == 1) : ?>
					<?php $item = reset($section->items); ?>
						<?php if ($item->disable === false): ?>
					<li>
						<?php
						echo JLayoutHelper::render(
							'component.full.sidebar.link',
							array(
								'link'   => $item->link,
								'title'  => JText::_($item->title),
								'active' => JText::_($item->active),
								'icon'   => isset($item->icon) ? $item->icon : ''
							)
						);
						?>
					</li>
							<?php endif; ?>
					<?php elseif(count($section->items) > 1) : ?>
					<li class="treeview <?php echo ($active[1] == $sectionKey || count($sections) == 1 ? 'active': '') ?>">
						<a href="#">
							<span><?php echo JText::_($section->title); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu" <?php echo (count($sections) == 1 ? 'style="display: block;"': '') ?>>
							<?php foreach ($section->items as $item) : ?>
								<?php if ($item->disable === false): ?>
							<li>
								<?php
								echo JLayoutHelper::render(
									'component.full.sidebar.link',
									array(
										'link'   => $item->link,
										'title'  => JText::_($item->title),
										'active' => JText::_($item->active),
										'icon'   => isset($item->icon) ? $item->icon : ''
									)
								);
								?>
							</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</li>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</ul>
			</li>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
