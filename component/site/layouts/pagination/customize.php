<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

use Joomla\Registry\Registry;

$list = $displayData['list'];
$pages = $list['pages'];

$options = new Registry($displayData['options']);

$showLimitBox   = $options->get('showLimitBox', true);
$showPagesLinks = $options->get('showPagesLinks', true);
$showLimitStart = $options->get('showLimitStart', true);

// Calculate to display range of pages
$currentPage = 1;
$range = 1;
$step = 1;

if (!empty($pages['pages']))
{
	foreach ($pages['pages'] as $k => $page)
	{
		if (!$page['active'])
		{
			$currentPage = $k;
		}
	}
}

if ($currentPage >= $step)
{
	if ($currentPage % $step == 0)
	{
		$range = ceil($currentPage / $step) + 1;
	}
	else
	{
		$range = ceil($currentPage / $step);
	}
}
?>
	<?php if ($showLimitBox) : ?>
		<div class="limit pull-right">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM') . $list['limitfield']; ?>
		</div>
	<?php endif; ?>

	<?php if ($showPagesLinks && (!empty($pages))) : ?>
		<ul>
			<?php
				echo JLayoutHelper::render('pagination.customize_link', $pages['start']);
				echo JLayoutHelper::render('pagination.customize_link', $pages['previous']); ?>
			<?php foreach ($pages['pages'] as $k => $page) : ?>

				<?php $output = JLayoutHelper::render('pagination.customize_link', $page); ?>
				<?php if (in_array($k, range($range * $step - ($step + 1), $range * $step))) : ?>
					<?php if (($k % $step == 0 || $k == $range * $step - ($step + 1)) && $k != $currentPage && $k != $range * $step - $step) :?>
						<?php $output = preg_replace('#(<a.*?>).*?(</a>)#', '$1...$2', $output); ?>
					<?php endif; ?>
				<?php endif; ?>

				<?php echo $output; ?>
			<?php endforeach; ?>
			<?php
				echo JLayoutHelper::render('pagination.customize_link', $pages['next']);
				echo JLayoutHelper::render('pagination.customize_link', $pages['end']); ?>
		</ul>
	<?php endif; ?>
