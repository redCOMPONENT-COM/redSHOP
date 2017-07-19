(function ($) {
    /**
     *
     * @param status
     */
    function importLockElements(status)
    {
        if (!status)
        {
            $("#import_plugins").removeClass("disabled muted");
            $("#import_config").removeClass("disabled muted");
            $("#import_btn_start").removeClass("disabled muted hide");
        }
        else
        {
            $("#import_plugins").addClass("disabled muted");
            $("#import_config").addClass("disabled muted");
            $("#import_btn_start").addClass("disabled muted hide");
        }
    }

    /**
     * Append logging
     *
     * @param classString
     * @param message
     */
    function importResultLogAppend(classString, message) {
        $('<p>').addClass(classString).html(message).appendTo($("#import_process_msg_body"));
    }

    /**
     * Render processing bar
     *
     * @param total
     * @param value
     */
    function importUpdateProcessbar(total, value) {
        var $bar = $("#import_process_bar");
        var currentPercent = (value * 100) / total;

        if (currentPercent > 100) {
            currentPercent = 100;
        }

        $bar.css("width", currentPercent + "%");
        $bar.html(currentPercent.toFixed(2) + "%");
    }

    /**
     * Main function to execute import
     *
     *
     * @param index
     * @param folder
     * @param total
     */
    function importExecuteImport(index, folder, total) {
        // Add overlay
        importResultLogAppend('text-info', 'Importing...');

        var plugin = $("input[name='plugin_name']:checked").val()

        var url = "index.php?option=com_ajax&plugin=" + plugin + "_import&group=redshop_import&format=raw";
        var data = $("#adminForm").serialize() + "&folder=" + folder + "&index=" + index;

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
                importUpdateProcessbar(total, index);

                // Update counting html
                $("#import_count").html(index + '/' + total);

                // Show log imported file
                importResultLogAppend('text-info', 'Imported file: ' + response.file);

                // Number if processed items / products
                if (response.data.length) {
                    // @TODO Use each instead
                    for (dataIndex = 0; dataIndex < response.data.length; dataIndex++) {
                        var textClass = "text-success";

                        if (response.data[dataIndex].status == 0) {
                            textClass = "text-danger";
                        }

                        // Show log imported item
                        importResultLogAppend(textClass, response.data[dataIndex].message);
                    }
                }

                // Go to next file
                index++;
                if (index <= total) {
                    importExecuteImport(index, folder, total)
                }
                else {
                    // Completed
                    importResultLogAppend('text-info', Joomla.JText._('COM_REDSHOP_IMPORT_SUCCESS'));
                    // Unblocking
                    importLockElements(false);
                }
            })
            /**
             * Ajax failed
             * @TODO Should we keep execute next ajax ?
             */
            .fail(function () {
                importLockElements(false);
                // Alert
            });
    }

    /**
     *
     */
    $(document).ready(function () {
        var $fileUpload = $('#fileupload');
        var $uploadWrapper = $("#import_upload_progress_wrapper");
        var $uploadProgress = $("#import_upload_progress");

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
                importResultLogAppend('text-success', 'File upload completed');

                $uploadWrapper.hide();
                $("#import_process_msg_body").empty();

                // File uploaded and success at service side
                if (data.result.status == 1) {

                    // Message
                    importResultLogAppend('text-success', data.result.msg);

                    // Show number of product(s) will import counted by number of lines without head line
                    total = data.result.rows - 1;

                    // Update folder var
                    folder = data.result.folder;

                    // Update number of splitted files
                    files = data.result.files;

                    // Show number of product(s) will import
                    $("#import_count").html(files);

                    // Show process bar
                    $("#import_process_bar").parent().show();

                    importResultLogAppend('text-info', 'Init import');
                    importResultLogAppend('text-primary', 'Folder: <span class="label label-default">' + data.result.folder + '</span>');
                    importResultLogAppend('text-primary', 'Total rows: <span class="label label-default">' + (data.result.rows - 1) + '</span>');
                    importResultLogAppend('text-primary', 'Total rows/ file: <span class="label label-default">' + data.result.rows_per_file + '</span>');
                    importResultLogAppend('text-primary', 'Total files: <span class="label label-default">' + data.result.files + '</span>');

                    // Execute import
                    index = 1;
                    importExecuteImport(index, folder, files);
                } else {
                    importLockElements(true);
                    $("#import_count").empty();
                    importResultLogAppend('text-danger', data.result.msg)
                }
            },
            /**
             * Add new file
             *
             * @param e
             * @param data
             */
            add: function (e, data) {
                importResultLogAppend('text-info', 'File uploading...');
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
                importLockElements(false);
                importResultLogAppend('text-danger', error);
            }
        });

        /**
         * Hook into select import to get config for each one
         */
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

                    importResultLogAppend('text-success', 'Loaded configuration: ' + plugin);
                }
            );
        });

        $("#import_btn_start")
            .addClass("disabled")
            .prop("disabled", true)
            .click(function (event) {
                importLockElements(true);

                $("#import_process_msg").removeClass("alert-success").removeClass("alert-danger");
                $("#import_process_msg_body").html("");

                $("#import_process_bar").html('0%').css("width", "0%");
                $("#fileupload").click();

                event.preventDefault();
            });
    })
})(jQuery)
