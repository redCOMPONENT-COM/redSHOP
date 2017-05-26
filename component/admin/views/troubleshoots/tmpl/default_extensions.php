<table class="table table-bordered">
	<thead>
	<tr>
		<th>#</th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_EXTENSION_NAME'); ?></th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_EXTENSION_TYPE'); ?></th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_EXTENSION_GROUP'); ?></th>
		<th><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_EXTENSION_INSTALLED'); ?></th>
	</tr>
	</thead>
	<tbody>
        <?php $index = 0; ?>
		<?php foreach ($this->list['extensions'] as $type => $extensions): ?>
            <?php foreach ($extensions as $extension): ?>
                <tr class="i">
                    <th scope="row"><?php echo $index++; ?></th>
                    <td>
                        <?php echo $extension->getName(); ?>
                    </td>
                    <td class="">
	                    <?php echo $extension->getOriginal('type'); ?>
                    </td>
                    <td class="">
	                    <?php echo $extension->getOriginal('plugin'); ?>
                    </td>
                    <td class="center">
                        <?php if ($type == 'plugin'): ?>
                            <?php echo $extension->isInstalledPlugin(); ?>
                        <?php endif; ?>
                        <?php if ($type == 'module'): ?>
	                        <?php echo $extension->isInstalledModule(); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
		<?php endforeach; ?>
	</tbody>
</table>