<table class="table table-bordered">
	<thead>
	<tr>
		<th>#</th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_FILE'); ?></th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_HACKING'); ?></th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_OVERRIDES'); ?></th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_MISSING'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php if ($this->list['items']): ?>
		<?php foreach ($this->list['items'] as $index => $item): ?>
			<?php echo $item->render($index); ?>
		<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
</table>