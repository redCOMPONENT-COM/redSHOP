<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$formToken = JSession::getFormToken();
?>

<?php if (empty($this->exports)): ?>
    <div class="alert alert-warning">
        <span class="close" data-dismiss="alert">×</span>
        <h4 class="alert-heading">
            <i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?>
        </h4>
        <div>
            <p><?php echo JText::_('COM_REDSHOP_EXPORT_WARNING_MISSING_PLUGIN') ?></p>
        </div>
    </div>
<?php else: ?>
    <script type="text/javascript">
        var plugin = '';
        var total = 0;
        var itemRun = 100;

        (function ($) {
            $(document).ready(function () {
                $("#export_plugins input[type='radio']").change(function (e) {
                    plugin = $(this).val();
                    $("#export_config").addClass('disabled muted');
                    $("#export_process_msg").removeClass("alert-success").removeClass("alert-danger");
                    $("#export_process_msg_body").html("");
                    $("#export_process_bar").html('0%').css("width", "0%");

                    // Load specific configuration of plugin
                    $.post(
                        "index.php?option=com_ajax&plugin=" + plugin + "_config&group=redshop_export&format=raw",
                        $("#adminForm").serialize(),
                        function (response) {
                            $("#export_config_body").empty().html(response);
                            $("select").select2({});
                            $("#export_config").removeClass('disabled muted');
                        }
                    );
                });

                $("#export_btn_start").click(function (event) {
                    $("#export_plugins").addClass("disabled muted");
                    $("#export_config").addClass("disabled muted");

                    $("#export_process_msg").removeClass("alert-success").removeClass("alert-danger");
                    $("#export_process_msg_body").html("");

                    $("#export_process_bar").html('0%').css("width", "0%");

                    $.post(
                        "index.php?option=com_ajax&plugin=" + plugin + "_start&group=redshop_export&format=raw",
                        $("#adminForm").serialize(),
                        function (response) {
                            total = parseInt(response);
                            $("#export_process_title span").empty().html('(' + total + ')');

                            run_export(0);
                        }
                    );

                    event.preventDefault();
                });
            });
        })(jQuery);
    </script>

    <script type="text/javascript">
        function run_export(startIndex) {
            (function ($) {
                var url = "index.php?option=com_ajax&plugin=" + plugin + "_export&group=redshop_export&format=raw";
                var data = $("#adminForm").serialize();
                data += "&start=" + startIndex + "&limit=" + itemRun;

                $.post(
                    url,
                    data,
                    function (response) {
                        var success = startIndex + itemRun;
                        var percent = 0.0;
                        var $bar = $("#export_process_bar");

                        if (success > total) {
                            percent = 100;
                        } else {
                            percent = (success / total) * 100;
                        }

                        if (percent > 100) {
                            percent = 100;
                        }

                        if (response == 1) {
                            run_export(success);
                        }
                        else if (response == 0 || success > total) {
                            percent = 100;
                            total = 0;
                            $("#export_process_msg").addClass("alert-success").removeClass("alert-danger");
                            $("#export_process_msg_body").html("<?php echo JText::_('COM_REDSHOP_EXPORT_DONE') ?>");
                            $("#export_plugins").removeClass("disabled muted");
                            $("#export_config").removeClass("disabled muted");
                            $("#export_iframe")
                                .attr("src", "index.php?option=com_ajax&plugin=" + plugin + "_complete&group=redshop_export&format=raw");
                        }
                        else {
                            percent = 100;
                            total = 0;
                            $("#export_plugins").removeClass("disabled muted");
                            $("#export_config").removeClass("disabled muted");
                            $("#export_process_msg").removeClass("alert-success").addClass("alert-danger");
                            $("#export_process_msg_body").html(response);
                        }

                        $bar.css("width", percent + "%");
                        $bar.html(percent.toFixed(2) + "%");
                    }
                );
            })(jQuery);
        }
    </script>

    <form action="<?php echo 'index.php?option=com_redshop' ?>" method="post" name="adminForm" id="adminForm">
        <div class="row">
            <div class="col-md-6">
                <!-- Step 1. Choose plugin -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
							<?php echo JText::_('COM_REDSHOP_EXPORT_STEP_1') ?>
                        </h4>
                    </div>
                    <div class="panel-body" id="export_plugins">
						<?php foreach ($this->exports as $export): ?>
                            <label>
                                <input type="radio" value="<?php echo $export->name ?>"
                                       name="plugin_name"/> <?php echo JText::_('PLG_REDSHOP_EXPORT_' . strtoupper($export->name) . '_TITLE') ?>
                            </label>
						<?php endforeach; ?>
                    </div>
                </div>
                <!-- Step 1. End -->
            </div>
            <div class="col-md-6">
                <!-- Step 2. Config -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
							<?php echo JText::_('COM_REDSHOP_EXPORT_STEP_2') ?>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div id="export_config">
                            <fieldset class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">
                                        <?php echo JText::_('COM_REDSHOP_EXPORT_CONFIG_SEPARATOR') ?>
                                    </label>
                                    <div class="col-md-10">
                                        <input type="text" value="," class="form-control" maxlength="1" name="separator" />
                                    </div>
                                </div>
                                <div id="export_config_body"></div>
                            </fieldset>
                            <hr/>
                            <button class="btn btn-primary btn-large" id="export_btn_start" type="button">
								<?php echo JText::_('COM_REDSHOP_EXPORT_START') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Step 2. End -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Step 3. Process -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title" id="export_process_title">
							<?php echo JText::_('COM_REDSHOP_EXPORT_STEP_3') ?> <span class="small"></span>
                        </h4>
                    </div>
                    <div id="export_process_panel">
                        <div class="panel-body">
                            <div class="progress">
                                <div id="export_process_bar" class="progress-bar progress-bar-striped active" role="progressbar"
                                     aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                    0%
                                </div>
                            </div>
                            <div class="form-group">
                                <div id="export_process_msg" class="alert">
                                    <h4><?php echo JText::_('COM_REDSHOP_EXPORT_LOG') ?></h4>
                                    <div id="export_process_msg_body"></div>
                                </div>
                                <iframe id="export_iframe" src="" class="hidden"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Step 3. End -->
            </div>
        </div>

        <!-- Hidden field -->
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
		<?php echo JHtml::_('form.token') ?>
    </form>
<?php endif; ?>
