<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$hacked    = 0;
$overrides = 0;
$missing   = 0;
?>
<table class="table table-bordered">
	<thead>
	<tr>
		<th>#</th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_FILE');?></th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_HACKING');?></th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_OVERRIDES');?></th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_MISSING');?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($this->list as $index => $item): ?>
		<?php if (is_array($item)): ?>
			<tr>
				<th scope="row"><?php echo $index++; ?></th>
				<td>
					<span class="text"><small><?php echo $item['original']; ?></small></span>
					<?php if (isset($item['overrides'])): ?>
						<?php foreach ($item['overrides'] as $override): ?>
							<span class="text-danger"><small><?php echo $override; ?></small></span>
						<?php endforeach; ?>
					<?php endif; ?>

				</td>
				<td class="center">
					<?php if ($item['hacking']): ?>
						<?php $hacked++; ?>
						<span class="label label-danger"><i class="fa fa-times" aria-hidden="true"></i></span>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php if (isset($item['overrides'])): ?>
						<?php $overrides++; ?>
						<span class="label label-danger"><i class="fa fa-times" aria-hidden="true"></i></span>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php if ($item['missing']): ?>
						<?php $missing++; ?>
						<span class="label label-danger"><i class="fa fa-times" aria-hidden="true"></i></span>
					<?php endif; ?>
				</td>
			</tr>
		<?php else: ?>
			<?php continue; ?>
		<?php endif; ?>
	<?php endforeach; ?>
	</tbody>
</table>
<div class="" style="margin-top:15px">
	<div class="well well-sm">
		<div class=""><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_HACKING');?>: <span class="badge"><?php echo $hacked; ?></span></div>
		<div class=""><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_OVERRIDES');?>: <span class="badge"><?php echo $overrides; ?></span></div>
		<div class=""><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_MISSING');?>: <span class="badge"><?php echo $missing; ?></span></div>
	</div>
</div>
