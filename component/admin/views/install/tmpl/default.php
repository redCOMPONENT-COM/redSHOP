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
    var runTasks = 0;

    (function ($) {
        $(document).ready(function () {
            var $first = $($("#table-install").find("tr")[0]);
            window.setTimeout(function () {
                doProgress($first);
            }, 1000);
        });
    })(jQuery);
</script>
<script type="text/javascript">
    function doProgress(row) {
        (function ($) {
            runTasks++;
            var $row = $(row);
            $row.removeClass("hidden");
            $row.find('img.loader').removeClass("hidden");
            $row.find('.text-result').addClass("hidden");

            var percent = runTasks / <?php echo count($this->steps) ?> * 100;
            $("#slider-install").css("width", percent + "%");
            $("#slider-install").html(percent.toFixed(2) + "%");

            $.post(
                "index.php?option=com_redshop&task=install.ajaxProcess",
                {
                    "<?php echo JSession::getFormToken() ?>": 1
                },
                function (response) {
                    $row.find('.text-result').text(response)
                        .removeClass("text-muted hidden").addClass("text-success");
                    $row.find('.status-icon').removeClass("fa-tasks").addClass("fa-check text-success");
                    $row.find('.task-name').removeClass("text-muted").addClass("text-success");
                    $row.addClass("hidden");
                }
            )
                .always(function () {

                    $row.find('img.loader').addClass("hidden");

                    var $next = $row.next("tr");

                    // Still have next progress
                    if ($next.length) {
                        doProgress($next);

                        return;
                    }

                    // This is final step
                    window.setTimeout(function () {
                        $("#slider-install").parent().fadeOut('slow', function () {
                            $("#install-desc").fadeIn('slow');
                            $("#system-message-container").removeClass("hidden");
							<?php if ($this->installType == 'update'): ?>
                            $("#update_versions").fadeIn('slow');
							<?php endif; ?>
                        });
                    }, 500);
                })
                .fail(function (response) {
                    $row.find('.text-result').text(response.responseText)
                        .removeClass("text-muted hidden").addClass("text-danger");
                    $row.find('.status-icon').removeClass("fa-tasks").addClass("fa-remove text-danger");
                    $row.find('.task-name').removeClass("text-muted").addClass("text-danger");
                });
        })(jQuery);
    }
</script>
<div class="container">
    <div id="install-desc" style="display: none;">
        <div class="row">
            <div id="system-message-container" class="hidden">
                <div id="system-message">
                    <div class="alert alert-success">
						<?php echo JText::_('COM_REDSHOP_INSTALL_SUCCESS') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <img src="<?php echo JURI::root(); ?>administrator/components/com_redshop/assets/images/261-x-88.png"
                     width="261" height="88"
                     alt="redSHOP Logo" align="left" class="img"/>
            </div>
            <div class="col-md-8">
                <h3><?php echo JText::_('COM_REDSHOP_COMPONENT_NAME'); ?></h3>
                <p><?php echo JText::_('COM_REDSHOP_BY_LINK') ?></p>
                <p><?php echo JText::_('COM_REDSHOP_TERMS_AND_CONDITION') ?></p>
                <p><?php echo JText::_('COM_REDSHOP_CHECK_UPDATES'); ?>:
                    <a href="http://redcomponent.com/" target="_new"><img
                                src="http://images.redcomponent.com/redcomponent.jpg" alt=""/></a>
                </p>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-12 center panel-body">
				<?php if ($this->installType != 'update'): ?>
                    <button type="button" class="btn btn-large btn-primary" name="save"
                            onclick="location.href='index.php?option=com_redshop&wizard=1'">
                        <icon class="fa fa-cog"></icon>&nbsp;&nbsp;<?php echo JText::_('COM_REDSHOP_WIZARD') ?>
                    </button>
                    <button type="button" class="btn btn-large btn-warning" name="content" id="btn-demo-content"
                            onclick="location.href='index.php?option=com_redshop&wizard=0&task=demoContentInsert'">
                        <icon class="fa fa-laptop"></icon>&nbsp;&nbsp;<?php echo JText::_('COM_REDSHOP_INSTALL_DEMO_CONTENT') ?>
                    </button>
				<?php endif; ?>
                <button type="button" class="btn btn-large btn-info" name="cancel"
                        onclick="location.href='index.php?option=com_redshop&wizard=0'">
                    <icon class="fa fa-bar-chart"></icon>&nbsp;&nbsp;<?php echo JText::_('COM_REDSHOP_DASHBOARD') ?>
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="progress">
            <div id="slider-install" class="progress-bar progress-bar-striped active" role="progressbar"
                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%
            </div>
        </div>
    </div>
    <table class="table" id="table-install">
        <tbody>
		<?php foreach ($this->steps as $i => $step): ?>
            <tr id="row-<?php echo preg_replace("/[^A-Za-z0-9?!]/", '', $step['func']) ?>">
                <td width="1">
                    <i class="fa fa-tasks status-icon"></i>
                </td>
                <td>
                    <span class="text-muted task-name"><?php echo $step['text'] ?></span>
                </td>
                <td width="20%" style="text-align: right;">
                    <strong class="text-result text-muted">Pending</strong>
                    <img src="components/com_redshop/assets/images/ajax-loader.gif" class="loader img" width="128px"
                         height="15px"/>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
	<?php if ($this->installType == 'update' && !empty($this->availableVersions)): ?>
        <div class="row" id="update_versions" style="display: none;">
            <hr/>
            <div class="col-md-12">
                <a class="btn btn-warning" role="button" data-toggle="collapse" href="#collapseLastVersion"
                   aria-expanded="false" aria-controls="collapseLastVersion">
					<?php echo JText::_('COM_REDSHOP_INSTALL_RUN_VERSION_TASKS_UPDATE') ?>
                </a>
                <p></p>
                <div class="collapse" id="collapseLastVersion">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="callout callout-default">
                                <strong class="text-warning"><i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?></strong>
                                <p><?php echo JText::_('COM_REDSHOP_INSTALL_RUN_VERSION_TASKS_UPDATE_WARNING') ?></p>
                            </div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th width="10%">
                                        Version
                                    </th>
                                    <th>
                                        Tasks
                                    </th>
                                    <th width="10%">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php foreach ($this->availableVersions as $availableVersion): ?>
                                    <tr>
                                        <td><?php echo $availableVersion->version ?></td>
                                        <td>
                                            <ul>
												<?php foreach ($availableVersion->tasks as $task): ?>
                                                    <li><?php echo $task; ?></li>
												<?php endforeach; ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-block text-center"
                                               href="index.php?option=com_redshop&view=install&install_type=update&version=<?php echo $availableVersion->version ?>">
                                                <i class="fa fa-play"></i> <?php echo JText::_('COM_REDSHOP_INSTALL_RUN') ?>
                                            </a>
                                        </td>
                                    </tr>
								<?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php endif; ?>
</div>
