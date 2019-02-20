<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
                var $version = $(this).data("version");
                var $loaderImg = $(this).parent().find("img.img");
                var $button = $(this);

                $table.find("button").prop("disabled", true).removeClass("false");
                $button.addClass("hidden");
                $loaderImg.removeClass("hidden");

                $.post(
                    "index.php?option=com_redshop&task=tool_update.ajaxMigrateFiles",
                    {
                        "<?php echo JSession::getFormToken() ?>": 1,
                        "version": $version
                    },
                    function (response) {
                        if (response.tasks.length > 0) {
                            doProcess($table, $loaderImg, $button);
                        } else {
                            $.redshopAlert(
                                "<?php echo JText::_('COM_REDSHOP_TOOL_RUN_TASK') ?>",
                                response.msg
                            );

                            $table.find("button").prop("disabled", false).removeClass("disabled");
                            $loaderImg.addClass("hidden");
                            $button.removeClass("hidden");
                        }
                    },
                    'JSON'
                );
            });

            $(".btn-run-db").click(function (event) {
                event.preventDefault();

                var $version = $(this).data("version");
                var $loaderImg = $(this).parent().find("img.img");
                var $button = $(this);

                $table.find("button").prop("disabled", true).removeClass("false");
                $button.addClass("hidden");
                $loaderImg.removeClass("hidden");

                $.post(
                    "index.php?option=com_redshop&task=tool_update.ajaxRunUpdateSql",
                    {
                        "<?php echo JSession::getFormToken() ?>": 1,
                        "version": $version
                    },
                    function (response) {
                        $.redshopAlert(
                            "<?php echo JText::_('COM_REDSHOP_TOOL_RUN_DATABASE') ?>",
                            response.msg
                        );

                        $table.find("button").prop("disabled", false).removeClass("disabled");
                        $loaderImg.addClass("hidden");
                        $button.removeClass("hidden");
                    },
                    'JSON'
                )
                    .fail(function(response) {
                        $.redshopAlert(
                            "<?php echo JText::_('COM_REDSHOP_TOOL_RUN_DATABASE') ?>",
                            response.responseText,
                            "danger"
                        )

                        $table.find("button").prop("disabled", false).removeClass("disabled");
                        $loaderImg.addClass("hidden");
                        $button.removeClass("hidden");
                    });
            });
        });
    })(jQuery);

    /**
     * Method for run migrate files and process
     * @param tableObj
     * @param loaderImg
     * @param buttonObj
     *
     * @return  void
     */
    function doProcess(tableObj, loaderImg, buttonObj) {
        $.post(
            "index.php?option=com_redshop&task=tool_update.ajaxProcess",
            {
                "<?php echo JSession::getFormToken() ?>": 1
            },
            function (response) {
                if (response.continue === 0) {
                    $.redshopAlert(
                        "<?php echo JText::_('COM_REDSHOP_TOOL_RUN_TASK') ?>",
                        response.msg
                    );

                    $(tableObj).find("button").prop("disabled", false).removeClass("disabled");
                    $(loaderImg).addClass("hidden");
                    $(buttonObj).removeClass("hidden");
                } else {
                    doProcess(tableObj, loaderImg, buttonObj);
                }
            },
            'JSON'
        )
            .fail(function(response) {
                $.redshopAlert(
                    "<?php echo JText::_('COM_REDSHOP_TOOL_RUN_TASK') ?>",
                    response.responseText,
                    "danger"
                );

                $(tableObj).find("button").prop("disabled", false).removeClass("disabled");
                $(loaderImg).addClass("hidden");
                $(buttonObj).removeClass("hidden");
            });
    }
</script>
<div class="row-fluid">
	<?php if (!empty($this->availableVersions)): ?>
        <div class="callout callout-default">
            <strong class="text-warning"><i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?>
            </strong>
            <p><?php echo JText::_('COM_REDSHOP_INSTALL_RUN_VERSION_TASKS_UPDATE_WARNING') ?></p>
        </div>
        <table class="table table-bordered table-hover" id="table-tasks">
            <thead>
            <tr>
                <th width="5%">
					<?php echo JText::_('COM_REDSHOP_TOOL_VERSION') ?>
                </th>
                <th width="10%"><?php echo JText::_('COM_REDSHOP_TOOL_TASKS') ?></th>
                <th width="30%"></th>
                <th width="10%">&nbsp;</th>
                <th width="30%">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($this->availableVersions as $availableVersion): ?>
                <tr>
                    <td class="text-center"><strong class="text-danger"><?php echo $availableVersion->version ?></strong></td>
                    <td>
                        <button class="btn btn-default btn-block text-center btn-run-task"
                                data-version="<?php echo $availableVersion->version ?>">
                            <i class="fa fa-file text-primary"></i> <?php echo JText::_('COM_REDSHOP_TOOL_RUN_TASK') ?>
                        </button>
                        <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH ?>/ajax-loader.gif" class="loader img hidden"
                             width="128px"
                             height="15px"/>
                    </td>
                    <td>
                        <ul>
			                <?php foreach ($availableVersion->tasks as $task): ?>
                                <li><?php echo $task['text'] ?></li>
			                <?php endforeach; ?>
                        </ul>
                    </td>
                    <td>
                        <button class="btn btn-default btn-block text-center btn-run-db"
                                data-version="<?php echo $availableVersion->version ?>">
                            <i class="fa fa-database text-danger"></i> <?php echo JText::_('COM_REDSHOP_TOOL_RUN_DATABASE') ?>
                        </button>
                        <img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH ?>/ajax-loader.gif" class="loader img hidden"
                             width="128px"
                             height="15px"/>
                    </td>
                    <td></td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
	<?php endif; ?>
</div>
