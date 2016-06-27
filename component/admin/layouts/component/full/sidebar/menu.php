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
		<li class="treeview">
			<a href="#">
				<i class="product_management"></i>
				<span><?php echo JText::_($group); ?></span>
			</a>

			<ul class="treeview-menu">
			<?php foreach ($sections as $sectionKey => $section) : ?>
				<li class="treeview">
					<a href="#">
						<span><?php echo JText::_($section->title); ?></span>
						<i class="fa fa-caret-down pull-right"></i>
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
									'description' => JText::_($item->description)
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