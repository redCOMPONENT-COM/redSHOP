<div class="panel panel-primary filterable">
    <table class="table table-bordered">
        <thead>
        <tr class="filters">
            <th>#</th>
            <th>
                <input type="text" class="form-control"
                       placeholder="<?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_FILE'); ?>"/>
            </th>
            <th>
                <input type="text" disabled=disabled class="form-control"
                       placeholder="<?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_HACKING'); ?>"/>
            </th>
            <th>
                <input type="text" disabled=disable class="form-control"
                       placeholder="<?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_OVERRIDES'); ?>"/>
            </th>
            <th>
                <input type="text" disabled=disable class="form-control"
                       placeholder="<?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_MISSING'); ?>"/>
            </th>
        </tr>
        </thead>
        <tbody>
		<?php if (isset($this->list['items'])): ?>
			<?php foreach ($this->list['items'] as $index => $item): ?>
				<?php echo $item->render($index); ?>
			<?php endforeach; ?>
		<?php endif; ?>
        </tbody>
    </table>
</div>