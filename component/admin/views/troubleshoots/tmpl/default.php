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

<div class="row">
    <div class="col-md-12">
        <form class="" method="post" name="adminForm" id="adminForm">
			<?php if (JPluginHelper::isEnabled('system', 'mvcoverride')): ?>
                <span class="label label-danger"><?php JText::_('COM_REDSHOP_MVCOVERRIDE_PLUGIN_IS_ENABLED'); ?></span>
			<?php endif; ?>
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
				<?php if ($this->list): ?>
					<?php foreach ($this->list as $index => $item): ?>
						<?php if (is_object($item)): ?>
                            <tr class="<?php echo $item->getMissingClass(); ?>"
                                data-hacking="<?php echo (int) $item->isModified(); ?>"
                                data-override="<?php echo (int) $item->isOverrided(); ?>"
                                data-missing="<?php echo (int) $item->isMissing(); ?>">
                                <td scope="row">
                                    <?php echo $index++; ?>
                                </td>
                                <td>
                                    <span class="text"><?php echo $item->getOriginalFile() ?></span>
                                    <!-- Modified time -->
									<?php if ($item->isModified()): ?>
                                        <br/>
                                        <span class="label label-default">
									<small>
										<?php echo $item->getModifiedTime(); ?>
									</small>
								</span>
									<?php endif; ?>
                                </td>
                                <td class="center">
									<?php if ($item->isModified()): ?>
										<?php $hacked++; ?>
										<?php echo $item->renderCheckmark(); ?>
									<?php endif; ?>
                                </td>
                                <td class="center">
									<?php if ($item->isOverrided()): ?>
										<?php $overrides++; ?>
                                        <<?php echo $item->renderCheckmark(); ?>
									<?php endif; ?>
                                </td>
                                <td class="center">
									<?php if ($item->isMissing()): ?>
										<?php $missing++; ?>
										<?php echo $item->renderCheckmark(); ?>
									<?php endif; ?>
                                </td>
                            </tr>
						<?php else: ?>
							<?php continue; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
                </tbody>
            </table>
            <div class="" style="margin-top:15px">
                <div class="well well-sm">
                    <div class=""><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_HACKING'); ?>:
                        <span class="badge"><?php echo $hacked; ?></span>
                    </div>
                    <div class=""><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_OVERRIDES'); ?>:
                        <span class="badge"><?php echo $overrides; ?></span>
                    </div>
                    <div class=""><?php echo JText::_('COM_REDSHOP_TROUBLESHOOT_HEADING_MISSING'); ?>:
                        <span class="badge"><?php echo $missing; ?></span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
