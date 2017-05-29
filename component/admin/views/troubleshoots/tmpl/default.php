<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>

<div class="row">
    <div class="col-md-12">
        <form class="" method="post" name="adminForm" id="adminForm">
			<?php if (JPluginHelper::isEnabled('system', 'mvcoverride')): ?>
                <div class="row">
                    <span class="label label-danger"><?php echo JText::_('COM_REDSHOP_MVCOVERRIDE_PLUGIN_IS_ENABLED'); ?></span>
                </div>
			<?php endif; ?>

            <div class="row">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#system-requirements" aria-controls="home" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_TROUBLESHOOTS_TAB_SYSTEM_REQUIREMENTS'); ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#files-detection" aria-controls="profile" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_TROUBLESHOOTS_TAB_FILES_DETECTION'); ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#extensions" aria-controls="messages" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_TROUBLESHOOTS_TAB_EXTENSIONS'); ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#logging" aria-controls="settings" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_TROUBLESHOOTS_TAB_LOGGING'); ?>
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="system-requirements">
						<?php echo $this->loadTemplate('requirements'); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="files-detection">
						<?php echo $this->loadTemplate('files'); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="extensions">
						<?php echo $this->loadTemplate('extensions'); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="logging">
						<?php echo $this->loadTemplate('logging'); ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
