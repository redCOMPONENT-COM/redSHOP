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
                        <a href="#home" aria-controls="home" role="tab" data-toggle="tab">System requirement</a>
                    </li>
                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Files detection</a>
                    </li>
                    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Extensions</a>
                    </li>
                    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">
	                    <?php echo $this->loadTemplate('requirements'); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile">
	                    <?php echo $this->loadTemplate('files'); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="messages">
	                    <?php echo $this->loadTemplate('extensions'); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="settings">...</div>
                </div>
            </div>
        </form>
    </div>
</div>
