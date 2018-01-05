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
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            var $table = $("#table-tasks");

            $(".btn-run-task").click(function (event) {
                event.preventDefault();
                var $version   = $(this).data("version");
                var $loaderImg = $(this).parent().find("img.img");
                var $button    = $(this);

                $table.find("button").prop("disabled", true).removeClass("false");
                $button.addClass("hidden");
                $loaderImg.removeClass("hidden");

                $.post(
                    "index.php?option=com_redshop&task=tool.ajaxMigrateFiles",
                    {
                        "<?php echo JSession::getFormToken() ?>": 1,
                        "version"                               : $version
                    },
                    function (response) {
                        console.log(response);
                    }
                ).always(function () {
                    $table.find("button").prop("disabled", false).removeClass("disabled");
                    $loaderImg.addClass("hidden");
                    $button.removeClass("hidden");
                });
            });

            $(".btn-run-db").click(function (event) {
                event.preventDefault();
            });
        });
    })(jQuery);
</script>
<div class="row-fluid">
	<?php if (!empty($this->availableVersions)): ?>
        <div class="callout callout-default">
            <strong class="text-warning"><i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?></strong>
            <p><?php echo JText::_('COM_REDSHOP_INSTALL_RUN_VERSION_TASKS_UPDATE_WARNING') ?></p>
        </div>
        <table class="table table-bordered" id="table-tasks">
            <thead>
            <tr>
                <th width="5%">
					<?php echo JText::_('COM_REDSHOP_TOOL_VERSION') ?>
                </th>
                <th width="10%">&nbsp;</th>
                <th width="10%">&nbsp;</th>
                <th>
					<?php echo JText::_('COM_REDSHOP_TOOL_TASKS') ?>
                </th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($this->availableVersions as $availableVersion): ?>
                <tr>
                    <td><span class="badge label-primary"><?php echo $availableVersion->version ?></span></td>
                    <td>
                        <button class="btn btn-default btn-block text-center btn-run-task" data-version="<?php echo $availableVersion->version ?>">
                            <i class="fa fa-file text-primary"></i> <?php echo JText::_('COM_REDSHOP_TOOL_RUN_TASK') ?>
                        </button>
                        <img src="components/com_redshop/assets/images/ajax-loader.gif" class="loader img hidden" width="128px"
                             height="15px"/>
                    </td>
                    <td>
                        <button class="btn btn-default btn-block text-center btn-run-db" data-version="<?php echo $availableVersion->version ?>">
                            <i class="fa fa-database text-danger"></i> <?php echo JText::_('COM_REDSHOP_TOOL_RUN_DATABASE') ?>
                        </button>
                        <img src="components/com_redshop/assets/images/ajax-loader.gif" class="loader img hidden" width="128px"
                             height="15px"/>
                    </td>
                    <td>
                        <ul>
							<?php foreach ($availableVersion->tasks as $task): ?>
                                <li><?php echo $task['text'] ?></li>
							<?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
	<?php endif; ?>
</div>
