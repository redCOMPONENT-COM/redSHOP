<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<?php if (!empty($this->getinstalledmodule)): ?>
	<?php RedshopHelperModule::loadLanguages(); ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?php echo JText::_('COM_REDSHOP_CHECK'); ?></th>
            <th width="15%" style="text-align: center"><?php echo JText::_('COM_REDSHOP_RESULT'); ?></th>
            <th width="15%" style="text-align: center"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($this->getinstalledmodule as $module): ?>
            <tr>
                <td><?php echo JText::_(strtoupper($module->element)) ?></td>
                <td style="text-align: center">
					<?php if ($module->state == -1): ?>
                        <label class="label label-danger">
							<?php echo JText::_('COM_REDSHOP_NOT_INSTALLED') ?>
                        </label>
					<?php else: ?>
                        <label class="label label-success">
							<?php echo JText::_('COM_REDSHOP_INSTALLED') ?>
                        </label>
					<?php endif; ?>
                </td>
                <td style="text-align: center;">
					<?php if ($module->enabled): ?>
                        <span class="fa fa-check-circle text-success"></span>
					<?php else: ?>
                        <span class="fa fa-remove text-danger"></span>
					<?php endif; ?>
            </tr>
		<?php endforeach ?>
        </tbody>
    </table>
<?php endif; ?>
