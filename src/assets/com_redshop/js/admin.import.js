(function (w, $) {

    if (typeof redSHOP === 'undefined') {
        var redSHOP = {};
        w.redSHOP = redSHOP;
    }

    if (typeof redSHOP.Admin === 'undefined') {
        redSHOP.Admin = {}
    }

    if (typeof redSHOP.Admin.import === 'undefined') {
        var importObj = {

            countSuccessed: 0,
            countFailed: 0,

            /**
             *
             * @param lock
             */
            lockElements: function (lock) {
                if (!lock) {
                    $("#import_plugins").removeClass("disabled muted");
                    $("#import_config").removeClass("disabled muted");
                    $("#import_btn_start").removeClass("disabled muted");
                    $("#fileupload").removeClass("disabled muted");
                }
                else {
                    $("#import_plugins").addClass("disabled muted");
                    $("#import_config").addClass("disabled muted");
                    $("#import_btn_start").addClass("disabled muted");
                    $("#fileupload").addClass("disabled muted");
                }
            },

            /**
             *
             * @param classString
             * @param message
             */
            appendLog: function (classString, message) {
                $('<p>').addClass(classString).html(message).appendTo($("#import_process_msg_body"));
            },

            /**
             *
             * @param total
             * @param value
             * @returns {number}
             */
            updateProcessbar: function (total, value) {
                var $bar = $("#import_process_bar");
                var currentPercent = (value * 100) / total;

                if (currentPercent > 100) {
                    currentPercent = 100;
                }

                $bar.css("width", currentPercent + "%");
                $bar.html(currentPercent.toFixed(2) + "%");

                return currentPercent;
            },

            /**
             *
             * @returns {*|jQuery}
             */
            getSelectedPlugin: function () {
                return $("input[name='plugin_name']:checked").val();
            },

            getFormSerialize: function () {
                return $("#adminForm").serialize();
            },

            /**
             *
             * @param index
             * @param folder
             * @param total
             */
            executeImport: function (index, folder, total) {
                var $this = this;

                if (typeof index === 'undefined') {
                    index = 1;
                }

                // Add overlay
                $this.appendLog('text-info', Joomla.JText._('COM_REDSHOP_IMPORT_IMPORTING'));

                var plugin = $this.getSelectedPlugin();

                // Generate URL for ajax
                var url = "index.php?option=com_ajax&plugin=" + plugin + "_import&group=redshop_import&format=raw";
                var data = this.getFormSerialize() + "&folder=" + folder + "&index=" + index;

                /**
                 * Post ajax request
                 */
                var jqXHR = $.ajax({
                    url: url,
                    // Force to wait
                    async: true,
                    // Before request ajax for importing
                    beforeSend: function (xhr) {
                    },
                    data: data,
                    dataType: "json",
                    method: "POST"
                })
                /**
                 * Ajax request success
                 */
                    .done(function (response, textStatus, jqXHR) {
                        // Update status bar
                        $this.updateProcessbar(total, index);

                        // Update counting html
                        $("#import_count").html(index + '/' + total);

                        // Show log imported file
                        $this.appendLog('text-info', Joomla.JText._('COM_REDSHOP_IMPORT_IMPORTED_FILE') + response.file);

                        // Number if processed items / products
                        if (response.data.length) {
                            $.each(response.data, function (index, data) {
                                var textClass = "text-success";

                                if (data.status == 0) {
                                    textClass = "text-danger";
                                    $this.countFailed = $this.countFailed + 1;
                                }
                                else
                                {
                                    $this.countSuccessed = $this.countSuccessed + 1;
                                }

                                // Show log imported item
                                $this.appendLog(textClass, data.message);
                            })
                        }

                        // Go to next file
                        index++;
                        if (index <= total) {
                            $this.executeImport(index, folder, total)
                        }
                        else {
                            // Completed
                            $this.appendLog('text-info', Joomla.JText._('COM_REDSHOP_IMPORT_SUCCESS'));
                            // Unblocking
                            $this.lockElements(false);
                        }
                    })
                    /**
                     * Ajax failed
                     * @TODO Should we keep execute next ajax ?
                     */
                    .fail(function () {
                        $this.lockElements(false);
                        // Alert
                    });
            },

            init: function () {
                var $fileUpload = $('#fileupload');
                var $uploadWrapper = $("#import_upload_progress_wrapper");
                var $uploadProgress = $("#import_upload_progress");
                var $this = this;

                /**
                 * Submit file upload
                 */
                $fileUpload.fileupload({
                    dataType: "json",
                    singleFileUploads: true,
                    /**
                     * Uploaded
                     *
                     * @param e
                     * @param data
                     */
                    done: function (e, data) {
                        $this.appendLog('text-success', Joomla.JText._('COM_REDSHOP_IMPORT_FILE_UPLOAD_COMPLETED'));
                        $uploadWrapper.hide();

                        // Clean up logging
                        $("#import_process_msg_body").empty();

                        // File uploaded and success at service side
                        if (data.result.status == 1) {

                            // Message
                            $this.appendLog('text-success', data.result.msg);

                            // Show number of product(s) will import
                            $("#import_count").html(data.result.files);

                            // Show process bar
                            $("#import_process_bar").parent().show();

                            $this.appendLog('text-info', Joomla.JText._('COM_REDSHOP_IMPORT_INIT_IMPORT'));

                            $this.appendLog(
                                'text-primary',
                                Joomla.JText._('COM_REDSHOP_IMPORT_FOLDER') + '<span class="label label-default">' + data.result.folder + '</span>'
                            );
                            $this.appendLog(
                                'text-primary',
                                Joomla.JText._('COM_REDSHOP_IMPORT_TOTAL_ROWS') + '<span class="label label-default">' + (data.result.rows - 1) + '</span>'
                            );
                            $this.appendLog(
                                'text-primary',
                                Joomla.JText._('COM_REDSHOP_IMPORT_TOTAL_ROWS_PERCENT_FILE') + '<span class="label label-default">' + data.result.rows_per_file + '</span>'
                            );
                            $this.appendLog(
                                'text-primary',
                                Joomla.JText._('COM_REDSHOP_IMPORT_TOTAL_FILES') + '<span class="label label-default">' + data.result.files + '</span>'
                            );

                            $('#import_process').show();

                            // Execute import
                            $this.executeImport(1, data.result.folder, data.result.files);
                        } else {
                            // Something wrong than we do release locking
                            $this.lockElements(false);
                            // Reset counting
                            $("#import_count").empty();
                            $('#import_process').hide();
                            $this.appendLog('text-danger', data.result.msg)
                        }
                    },
                    /**
                     * Add new file
                     *
                     * @param e
                     * @param data
                     */
                    add: function (e, data) {
                        // Verify file type
                        if (allowFileType.indexOf(data.files[0].type) == -1
                            && data.files[0].name.indexOf(allowFileExt) == -1) {
                            $this.lockElements(false);
                            $this.appendLog('text-danger', Joomla.JText._('COM_REDSHOP_IMPORT_ERROR_FILE_TYPE'));

                            return false;
                        }

                        // Verify file size
                        if (data.files[0].size > allowMaxFileSize) {
                            $this.lockElements(false);
                            $this.appendLog('text-danger', Joomla.JText._('COM_REDSHOP_IMPORT_ERROR_FILE_MAX_SIZE'));

                            return false;
                        }

                        // Verify file zie
                        if (data.files[0].size < allowMinFileSize) {
                            $this.lockElements(false);
                            $this.appendLog('text-danger', Joomla.JText._('COM_REDSHOP_IMPORT_ERROR_FILE_MIN_SIZE'));

                            return false;
                        }

                        $this.appendLog('text-info', Joomla.JText._('COM_REDSHOP_IMPORT_FILE_UPLOADING'));
                        $uploadWrapper.show();
                        data.submit();
                    },
                    /**
                     *
                     * @param e
                     * @param data
                     */
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $uploadProgress.html(progress + "%").css("width", progress + "%");
                    },
                    /**
                     * Error case
                     *
                     * @param e
                     * @param text
                     * @param error
                     */
                    error: function (e, text, error) {
                        $uploadWrapper.hide();
                        $this.lockElements(false);
                        $this.appendLog('text-danger', error);
                    }
                });

                /**
                 * Hook into select import to get config for each one
                 */
                $("#import_plugins input[type='radio']").change(function (e) {
                    var plugin = $(this).val();

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

                            $this.appendLog('text-success', Joomla.JText._('COM_REDSHOP_IMPORT_LOADED_CONFIGURATION') + '<span class="label label-default">' + plugin + '</span>');
                        }
                    );
                });

                /**
                 * Hook click on Upload button
                 */
                $("#import_btn_start")
                    .addClass("disabled")
                    .prop("disabled", true)
                    .click(function (event) {
                        // Lock elements
                        $this.lockElements(true);

                        $("#import_process_msg").removeClass("alert-success").removeClass("alert-danger");
                        $("#import_process_msg_body").html("");

                        $("#import_process_bar").html('0%').css("width", "0%");
                        $("#fileupload").click();

                        event.preventDefault();
                    });
            }
        }

        redSHOP.Admin.import = importObj;

        // Call init
        $(document).ready(function () {
            redSHOP.Admin.import.init();
        })
    }



})(window, jQuery)
