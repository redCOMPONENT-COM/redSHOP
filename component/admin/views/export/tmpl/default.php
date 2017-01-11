<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$formToken = JSession::getFormToken();
?>

<?php if (empty($this->exports)): ?>
    <div class="alert alert-info">
        <a class="close" data-dismiss="alert">×</a>
        <div>
            <p><?php echo JText::_('COM_REDSHOP_EXPORT_NO_AVAILABLE_EXPORT_FEATURES') ?></p>
        </div>
    </div>
<?php else: ?>
    <script type="text/javascript">
        var plugin = '';
        var total = 0;
        var itemRun = 10;

        (function ($) {
            $(document).ready(function () {
                $("#export_plugins input[type='radio']").change(function (e) {
                    plugin = $(this).val();

                    // Load specific configuration of plugin
                    $.post(
                        "index.php?option=com_ajax&plugin=" + plugin + "_config&group=redshop_export&format=raw",
                        {
                            "<?php echo JSession::getFormToken() ?>": 1
                        },
                        function (response) {
                            if (response == '')
                                response = '<p class="text-info"><?php echo JText::_('COM_REDSHOP_EXPORT_NO_CONFIG') ?></p>';

                            $("#export_config_body").empty().html(response);
                        }
                    );

                    $("#export_config").collapse('show');
                });

                $("#export_btn_start").click(function(event){
                    $("#export_config").hide('fast', function(){
                        $("#export_plugins").hide('fast');
                    });

                    $("#export_process_bar").html('0%').css("width", "0%");
                    $("#export_process_panel").collapse('show');

                    $.post(
                        "index.php?option=com_ajax&plugin=" + plugin + "_start&group=redshop_export&format=raw",
                        {
                            "<?php echo $formToken ?>": 1
                        },
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
        function run_export(startIndex)
        {
            (function($){
                var url = "index.php?option=com_ajax&plugin=" + plugin + "_export&group=redshop_export&format=raw";

                $.post(
                    url,
                    {
                        "start": startIndex,
                        "limit": itemRun,
                        "<?php echo $formToken ?>": 1
                    },
                    function (response) {
                        var success = startIndex + itemRun;
                        var percent = 0;
                        var $bar = $("#export_process_bar");

                        if (success > total) {
                            percent = 100;
                        } else {
                            percent = (success / total) * 100;
                        }

                        console.log(percent);

                        if (percent > 100) {
                            percent = 100;
                        }

                        $bar.css("width", percent + "%");
                        $bar.html(percent + "%");

                        if (response == 0 || success > total) {
                            total = 0;
                            $("#export_plugins").show('fast', function(){
                                $("#export_config").show('fast', function() {
                                    window.open("index.php?option=com_ajax&plugin=" + plugin + "_complete&group=redshop_export&format=raw");
                                });
                            });
                        } else {
                            run_export(success);
                        }
                    }
                );
            })(jQuery);
        }
    </script>

    <form action="<?php echo 'index.php?option=com_redshop' ?>" method="post" name="adminForm" id="adminForm">
        <div class="panel-group" id="export_step" role="tablist" aria-multiselectable="true">
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

            <!-- Step 2. Config -->
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="export_config_heading">
                    <h4 class="panel-title">
						<?php echo JText::_('COM_REDSHOP_EXPORT_STEP_2') ?>
                    </h4>
                </div>
                <div id="export_config" class="panel-collapse collapse" role="tabpanel" aria-labelledby="export_config_heading">
                    <div class="panel-body" id="">
                        <div id="export_config_body"></div>
                        <hr/>
                        <button class="btn btn-primary btn-large" id="export_btn_start" type="button">
                            <?php echo JText::_('COM_REDSHOP_EXPORT_START') ?>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Step 2. End -->

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title" id="export_process_title">
						<?php echo JText::_('COM_REDSHOP_EXPORT_STEP_3') ?> <span class="small"></span>
                    </h4>
                </div>
                <div id="export_process_panel" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="progress">
                            <div id="export_process_bar" class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                0%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hidden field -->
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
    </form>
<?php endif; ?>
