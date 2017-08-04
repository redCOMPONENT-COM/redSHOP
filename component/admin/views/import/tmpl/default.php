<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('redshopjquery.ui');
JHtml::script('com_redshop/jquery.iframe-transport.js', false, true);
JHtml::script('com_redshop/jquery.fileupload.js', false, true);

$allowFileTypes      = explode(',', Redshop::getConfig()->get('IMPORT_FILE_MIME', 'text/csv,application/vnd.ms-excel'));
$allowMaxFileSize    = (int) Redshop::getConfig()->get('IMPORT_MAX_FILE_SIZE', 2000000);
$allowMinFileSize    = (int) Redshop::getConfig()->get('IMPORT_MIN_FILE_SIZE', 1);
$allowFileExtensions = explode(',', Redshop::getConfig()->get('IMPORT_FILE_EXTENSION', '.csv'));
$encodings           = array();

// Defines encoding used in import
$characterSets = array(
	'ISO-8859-1'  => 'COM_REDSHOP_IMPORT_CHARS_ISO88591',
	'ISO-8859-5'  => 'COM_REDSHOP_IMPORT_CHARS_ISO88595',
	'ISO-8859-15' => 'COM_REDSHOP_IMPORT_CHARS_ISO885915',
	'UTF-8'       => 'COM_REDSHOP_IMPORT_CHARS_UTF8',
	'cp866'       => 'COM_REDSHOP_IMPORT_CHARS_CP866',
	'cp1251'      => 'COM_REDSHOP_IMPORT_CHARS_CP1251',
	'cp1252'      => 'COM_REDSHOP_IMPORT_CHARS_CP1252',
	'KOI8-R'      => 'COM_REDSHOP_IMPORT_CHARS_KOI8R',
	'BIG5'        => 'COM_REDSHOP_IMPORT_CHARS_BIG5',
	'GB2312'      => 'COM_REDSHOP_IMPORT_CHARS_GB2312',
	'BIG5-HKSCS'  => 'COM_REDSHOP_IMPORT_CHARS_BIG5HKSCS',
	'Shift_JIS'   => 'COM_REDSHOP_IMPORT_CHARS_SHIFTJIS',
	'EUC-JP'      => 'COM_REDSHOP_IMPORT_CHARS_EUCJP',
	'MacRoman'    => 'COM_REDSHOP_IMPORT_CHARS_MACROMAN'
);

// Creating JOption for JSelect box.
foreach ($characterSets as $char => $name)
{
	$title       = sprintf(JText::_($name), $char);
	$encodings[] = JHtml::_('select.option', $char, $title);
}
?>

<?php if (empty($this->imports)): ?>
    <div class="alert alert-warning">
        <span class="close" data-dismiss="alert">×</span>
        <h4 class="alert-heading">
            <i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?>
        </h4>
        <div>
            <p><?php echo JText::_('COM_REDSHOP_IMPORT_WARNING_MISSING_PLUGIN') ?></p>
        </div>
    </div>
<?php else: ?>
    <script type="text/javascript">
        var plugin = '';
        var total = 0;
        var folder = '';
        // Init import value for first time
        var importInit = 0;
        // Number of files after splitted. This one also value of ajax request will be execute
        var itemsCount = 0;
        // Number of item will be execute for each ajax request
        var itemRun = 1;
        // Number of failed import
        var failedCount = 0;
        var allowFileType = ["<?php echo implode('","', $allowFileTypes) ?>"];
        var allowFileExt = ["<?php echo implode('","', $allowFileExtensions) ?>"];
        var allowMaxFileSize = <?php echo $allowMaxFileSize ?>;
        var allowMinFileSize = <?php echo $allowMinFileSize ?>;

        (function ($) {
            $(document).ready(function () {
                var $uploadProgress = $("#import_upload_progress");
                var $uploadWrapper = $("#import_upload_progress_wrapper");

                // Get config
                $("#import_plugins input[type='radio']").change(function (e) {
                    plugin = $(this).val();
                    $("#import_config").addClass('disabled muted');
                    $("#import_process_msg_body").html("");
                    $("#import_process_bar").html('0%').css("width", "0%");
                    $uploadProgress.html('0%').css("width", "0%");

                    // Load specific configuration of plugin
                    $.post(
                        "index.php?option=com_ajax&plugin=" + plugin + "_config&group=redshop_import&format=raw",
                        $("#adminForm").serialize(),
                        function (response) {
                            $("#import_config_body").empty().html(response);
                            $("select").select2({});
                            $("#import_config").removeClass('disabled muted');
                            $("#import_btn_start").prop("disabled", false).removeClass("disabled");
                        }
                    );
                });

                /**
                 * Submit file upload
                 */
                $("#fileupload").fileupload({
                    dataType: "json",
                    singleFileUploads: true,
                    /**
                     * Uploaded
                     *
                     * @param e
                     * @param data
                     */
                    done: function (e, data) {
                        $uploadWrapper.hide();
                        $("#import_process_msg_body").empty();

                        // File uploaded and success at service side
                        if (data.result.status == 1) {
                            // Message
                            $("<p>").addClass("text-success").html(data.result.msg).appendTo($("#import_process_msg_body"));
                            // Show number of product(s) will import counted by number of lines without head line
                            total = data.result.lines - 1;
                            // Update folder var
                            folder = data.result.folder;
                            // Update number of splitted files
                            itemsCount = data.result.files;
                            // Show number of product(s) will import
                            $("#import_count").html(total);
                            // Show process bar
                            $("#import_process_bar").parent().show();
                            // Execute import
                            run_import(importInit);
                        } else {
                            $("#import_plugins").removeClass("disabled muted");
                            $("#import_config").removeClass("disabled muted");
                            $("<p>").addClass("text-danger").html(data.result.msg).appendTo($("#import_process_msg_body"));
                            $("#import_count").empty();
                        }
                    },
                    add: function (e, data) {
                        if (allowFileType.indexOf(data.files[0].type) == -1
                            && data.files[0].name.indexOf(allowFileExt) == -1) {
                            $("#import_plugins").removeClass("disabled muted");
                            $("#import_config").removeClass("disabled muted");
                            $("<p>").addClass("text-danger")
                                .html("<?php echo JText::_('COM_REDSHOP_IMPORT_ERROR_FILE_TYPE') ?>")
                                .appendTo($("#import_process_msg_body"));

                            return false;
                        }

                        if (data.files[0].size > allowMaxFileSize) {
                            $("#import_plugins").removeClass("disabled muted");
                            $("#import_config").removeClass("disabled muted");
                            $("<p>").addClass("text-danger")
                                .html("<?php echo JText::sprintf('COM_REDSHOP_IMPORT_ERROR_FILE_MAX_SIZE', $allowMaxFileSize) ?>")
                                .appendTo($("#import_process_msg_body"));

                            return false;
                        }

                        if (data.files[0].size < allowMinFileSize) {
                            $("#import_plugins").removeClass("disabled muted");
                            $("#import_config").removeClass("disabled muted");
                            $("<p>").addClass("text-danger")
                                .html("<?php echo JText::sprintf('COM_REDSHOP_IMPORT_ERROR_FILE_MIN_SIZE', $allowMinFileSize) ?>")
                                .appendTo($("#import_process_msg_body"));

                            return false;
                        }

                        $uploadWrapper.show();

                        data.submit();
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);

                        $uploadProgress.html(progress + "%").css("width", progress + "%");
                    },
                    error: function (e, text, error) {
                        $uploadWrapper.hide();
                        $("#import_plugins").removeClass("disabled muted");
                        $("#import_config").removeClass("disabled muted");
                        $("<p>").addClass("text-danger").html(error).appendTo($("#import_process_msg_body"));
                    }
                });

                $("#import_btn_start")
                    .addClass("disabled")
                    .prop("disabled", true)
                    .click(function (event) {
                        $("#import_plugins").addClass("disabled muted");
                        $("#import_config").addClass("disabled muted");

                        $("#import_process_msg").removeClass("alert-success").removeClass("alert-danger");
                        $("#import_process_msg_body").html("");

                        $("#import_process_bar").html('0%').css("width", "0%");
                        $("#fileupload").click();

                        event.preventDefault();
                    });
            });
        })(jQuery);
    </script>

    <script type="text/javascript">
        function run_import(startIndex) {
            (function ($) {
                var url = "index.php?option=com_ajax&plugin=" + plugin + "_import&group=redshop_import&format=raw";
                var data = $("#adminForm").serialize();
                data += "&folder=" + folder;

                /**
                 * Request ajax for importing
                 */
                $.post(
                    url,
                    data,
                    function (response) {
                        var $bar = $("#import_process_bar");
                        // Current process percent
                        var success = startIndex + itemRun;
                        var percent = 0.0;

                        if (success > total) {
                            percent = 100;
                        } else {
                            // itemsCount is number of files we'll need ajax request to process import
                            percent = (success * 100) / itemsCount;
                        }

                        if (percent > 100) {
                            percent = 100;
                        }

                        $bar.css("width", percent + "%");
                        $bar.html(percent.toFixed(2) + "%");

                        // Completed
                        if (percent == 100) {
                            // Render success message
                            $("<p>").addClass('text-info').html("<?php echo JText::_('COM_REDSHOP_IMPORT_SUCCESS'); ?>").appendTo($("#import_process_msg_body"));
                        }
                        else
                        {
                            // Number if processed items / products
                            if (response.data.length) {
                                for (i = 0; i < response.data.length; i++) {
                                    var textClass = "text-success";

                                    if (response.data[i].status == 0) {
                                        textClass = "text-danger";
                                    }

                                    $("<p>").addClass(textClass).html(response.data[i].message).appendTo($("#import_process_msg_body"));
                                }
                            }

                            // Execute next import
                            if (response.status == 1) {
                                run_import(success);
                            }
                            else if (response.status == 0 || success > total) {
                                total = 0;
                                $("#import_plugins").removeClass("disabled muted");
                                $("#import_config").removeClass("disabled muted");
                            }
                            else {
                                total = 0;
                                $("#import_plugins").removeClass("disabled muted");
                                $("#import_config").removeClass("disabled muted");
                                $("<p>").addClass("text-danger").html(response).appendTo($("#import_process_msg_body"));
                            }
                        }
                    },
                    "JSON"
                )
                    .fail(function () {
                        total = 0;
                        $("#import_count").html("");
                        $("#import_plugins").removeClass("disabled muted");
                        $("#import_config").removeClass("disabled muted");
                        $("<p>").addClass("text-danger")
                            .html("<?php echo JText::_('COM_REDSHOP_IMPORT_FAIL') ?>").appendTo($("#import_process_msg_body"));
                        $("#import_process_bar").parent().hide();
                    });
            })(jQuery);
        }
    </script>
    <form action="index.php?option=com_redshop&view=import" method="post" name="adminForm" id="adminForm">
        <div class="row">
            <div class="col-md-6">
                <!-- Step 1. Choose plugin -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
							<?php echo JText::_('COM_REDSHOP_IMPORT_STEP_1') ?>
                        </h4>
                    </div>
                    <div class="panel-body" id="import_plugins">
						<?php foreach ($this->imports as $import): ?>
                            <label>
                                <input type="radio" value="<?php echo $import->name ?>"
                                       name="plugin_name"/> <?php echo JText::_('PLG_REDSHOP_IMPORT_' . strtoupper($import->name) . '_TITLE') ?>
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
							<?php echo JText::_('COM_REDSHOP_IMPORT_STEP_2') ?>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div id="import_config">
                            <fieldset class="form-horizontal">
                                <p>
									<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MIN_FILE_SIZE') ?>:&nbsp;
                                    <span class="text-primary"><?php echo number_format($allowMinFileSize) ?>
                                        bytes</span>
                                </p>
                                <p>
									<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MAX_FILE_SIZE') ?>:&nbsp;
                                    <span class="text-primary"><?php echo number_format($allowMaxFileSize) ?>
                                        bytes</span>
                                </p>
                                <p>
									<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_MIME') ?>:&nbsp;
                                    <span class="text-primary"><?php echo implode(', ', $allowFileTypes) ?></span>
                                </p>
                                <p>
									<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_EXTENSION') ?>:&nbsp;
                                    <span class="text-primary"><?php echo implode(', ', $allowFileExtensions) ?></span>
                                </p>
                                <p class="help-block"><?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_HELP') ?></p>
                                <hr/>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">
										<?php echo JText::_('COM_REDSHOP_IMPORT_CONFIG_SEPARATOR') ?>
                                    </label>
                                    <div class="col-md-10">
                                        <input type="text" value="," class="form-control" maxlength="1"
                                               name="separator"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"><?php echo JText::_('COM_REDSHOP_IMPORT_ENCODING') ?></label>
                                    <div class="col-md-10">
										<?php echo JHtml::_('select.genericlist', $encodings, 'encoding', 'class="form-control"', 'value', 'text', 'UTF-8'); ?>
                                    </div>
                                </div>
                                <div id="import_config_body"></div>
                            </fieldset>
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
                        <h4 class="panel-title"
                            id="import_process_title"><?php echo JText::_('COM_REDSHOP_IMPORT_STEP_3') ?></h4>
                    </div>
                    <div id="import_process_panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-large" id="import_btn_start" type="button">
										<?php echo JText::_('COM_REDSHOP_IMPORT_SELECT_FILE') ?>&nbsp;&nbsp;<i class="fa fa-upload"></i>
                                    </button>
                                    <input id="fileupload" type="file" name="csv_file" class="hidden"
                                           data-url="index.php?option=com_redshop&task=import.uploadFile"/>
                                    <p></p>
                                    <div class="progress" id="import_upload_progress_wrapper" style="display: none;">
                                        <div id="import_upload_progress" class="progress-bar" role="progressbar"
                                             aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                             style="width: 0%;">0%
                                        </div>
                                    </div>
                                    <hr/>
                                    <h3><?php echo JText::_('COM_REDSHOP_IMPORT_DATA_IMPORT') ?>: <span id="import_count"></span></h3>
                                    <div class="progress" style="display: none;">
                                        <div id="import_process_bar" class="progress-bar progress-bar-success"
                                             role="progressbar"
                                             aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                             style="width: 0%;">
                                            0%
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <fieldset id="import_process_msg">
                                            <legend><?php echo JText::_('COM_REDSHOP_IMPORT_LOG') ?></legend>
                                            <div id="import_process_msg_body" style="max-height: 300px; overflow-x: hidden;"></div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Step 3. End -->
            </div>
        </div>

        <!-- Hidden field -->
		<?php echo JHtml::_('form.token') ?>
    </form>
<?php endif; ?>
