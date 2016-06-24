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

JHtml::_('jquery.framework');
JHtmlBehavior::core();

JFactory::getDocument()->addScriptDeclaration('
	jQuery(document).ready(function($)
	{
		if (window.toggleSidebar)
		{
			toggleSidebar(true);
		}
		else
		{
			$("#j-toggle-sidebar-header").css("display", "none");
			$("#j-toggle-button-wrapper").css("display", "none");
		}
	});
');
?>

<div id="j-toggle-sidebar-wrapper">
	<div id="j-toggle-button-wrapper" class="j-toggle-button-wrapper">
		<?php echo JLayoutHelper::render('joomla.sidebars.toggle'); ?>
	</div>
	<div id="sidebar" class="sidebar">
		<div class="sidebar-nav">

		<?php
		echo JHtml::_('bootstrap.startAccordion', 'redshop-main-menu', array('active' => $active, 'parent' => true));
		?>
		<h3 id="dashboard-item">
			<a href="index.php?option=com_redshop" title="<?php echo JText::_('COM_REDSHOP_DASHBOARD'); ?>">
				<?php echo JText::_('COM_REDSHOP_DASHBOARD'); ?>
			</a>
		</h3>
		<?php foreach ($items as $group => $sections) : ?>
			<div class="nav-header"><?php echo JText::_($group); ?></div>

			<?php foreach ($sections as $sectionKey => $section) : ?>
			<?php $title =  '<i class="icon-16-redshop_' . $sectionKey . '"></i>' . JText::_($section->title); ?>
			<?php echo JHtml::_('bootstrap.addSlide', 'redshop-main-menu', $title, $sectionKey); ?>
				<ul class="nav nav-list">
				<?php foreach ($section->items as $items) : ?>
					<li>
						<?php
							echo JLayoutHelper::render(
								'menu.link',
								array(
									'link'        => $items->link,
									'title'       => JText::_($items->title),
									'description' => JText::_($items->description)
								)
							);
						?>
					</li>
				<?php endforeach; ?>
				</ul>
			<?php echo JHtml::_('bootstrap.endSlide'); ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
		<?php echo JHtml::_('bootstrap.endAccordion'); ?>

		</div>
	</div>
	<div id="j-toggle-sidebar"></div>
</div>

