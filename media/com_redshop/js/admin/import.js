"use strict";

define(
	// modular name
	'import',
	// dependencies
	['ajax, log'],
	(function (w, $) {
		var redSHOP = w.redSHOP;

		/**
		 * redSHOP Administrator object
		 */
		if (typeof redSHOP.Admin === 'undefined') {
			redSHOP.Admin = {}
		}

		/**
		 * redSHOP Administrator import
		 */
		if (typeof redSHOP.Admin.import === 'undefined') {

			var redshopImport = {
				/**
				 *
				 * @param lock
				 */
				blockElements: function (lock) {
					var lockClass = 'disabled muted';
					var elements = '#import_plugins, #import_config, #import_btn_start, #fileupload'

					if (!lock) {
						$(elements).removeClass(lockClass);
						$(elements).prop("disabled", false);
					}
					else {
						$(elements).addClass(lockClass);
						$(elements).prop("disabled", true);
					}
				},

				/**
				 *
				 * @param lock
				 */
				reDraw: function (lock) {
					var cleanHtml = '#import_count, #import_config_body, #import_process_msg_body';
					var cleanProcessbar = '#import_upload_progress, #import_process_bar';

					$(cleanHtml).html('');
					$(cleanProcessbar).html('0%').css("width", "0%");

					this.blockElements(lock);
				},
				/**
				 *
				 * @param type
				 * @param message
				 */
				appendLog: function (type, message) {
					if (typeof redSHOP.Log[type] === 'function') {
						message = redSHOP.Log[type](message);

					}

					$("#import_process_msg_body").append(message);
				},

				/**
				 *
				 * @param total
				 * @param value
				 * @returns {number}
				 */
				updateProcessbar: function (total, value) {
					var $bar = $("#import_process_bar");

					// Get current percent
					var currentPercent = (value * 100) / total;

					if (currentPercent > 100) {
						currentPercent = 100;
					}

					$bar.css("width", currentPercent + "%");
					$bar.html(currentPercent.toFixed(2) + "%");

					return currentPercent;
				},

				/**
				 * Get selected plugin value
				 *
				 * @returns {*|jQuery}
				 */
				getSelectedPlugin: function () {
					return $("input[name='plugin_name']:checked").val();
				},

				/**
				 * @return  string
				 */
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
					// Alias to current object
					var $this = this;

					// Init default index
					if (typeof index === 'undefined') {
						index = 1;
					}

					var plugin = $this.getSelectedPlugin();

					// Generate URL for ajax
					var url = "index.php?option=com_ajax&plugin=" + plugin + "_import&group=redshop_import&format=raw";
					var data = this.getFormSerialize() + "&folder=" + folder + "&index=" + index;

					// Add overlay
					$this.appendLog('info', Joomla.JText._('COM_REDSHOP_IMPORT_IMPORTING'));

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

						.done(function (response, textStatus, jqXHR) {
							// Update status bar
							$this.updateProcessbar(total, index);

							// Update counting html
							$("#import_count").html(index + '/' + total);

							// Show log imported file
							$this.appendLog('info', Joomla.JText._('COM_REDSHOP_IMPORT_IMPORTED_FILE') + response.file);

							// Number if processed items / products
							if (response.data.length) {
								$.each(response.data, function (index, data) {
									if (data.status == 0) {
										// Show log imported item
										$this.appendLog('error', data.message);
									}
									else {
										// Show log imported item
										$this.appendLog('success', data.message);
									}
								})
							}

							// Go to next file
							index++;
							if (index <= total) {
								$this.executeImport(index, folder, total)
							}
							else {
								// Unblocking
								$this.blockElements(false);
								// Completed
								$this.appendLog('success', Joomla.JText._('COM_REDSHOP_IMPORT_SUCCESS'));
							}
						})

						.fail(function () {
							// Unblocking & reDraw
							$this.reDraw(false);
							$this.appendLog('error', Joomla.JText._('COM_REDSHOP_IMPORT_AJAX_FAILED'));
						});
				},

				init: function () {
					var $this = this;
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
							$this.appendLog('success', Joomla.JText._('COM_REDSHOP_IMPORT_FILE_UPLOAD_COMPLETED'));

							$uploadWrapper.hide();

							// Clean up logging
							$("#import_process_msg_body").empty();

							// File uploaded and success at service side
							if (data.result.status == 1) {

								// Message
								$this.appendLog('success', data.result.msg);

								// Show number of product(s) will import
								$("#import_count").html(data.result.files);

								// Show process bar
								$("#import_process_bar").parent().show();

								$this.appendLog('info', Joomla.JText._('COM_REDSHOP_IMPORT_INIT_IMPORT'));

								$this.appendLog(
									'info',
									Joomla.JText._('COM_REDSHOP_IMPORT_FOLDER') + '<span class="label label-default">' + data.result.folder + '</span>'
								);
								$this.appendLog(
									'info',
									Joomla.JText._('COM_REDSHOP_IMPORT_TOTAL_ROWS') + '<span class="label label-default">' + (data.result.rows - 1) + '</span>'
								);
								$this.appendLog(
									'info',
									Joomla.JText._('COM_REDSHOP_IMPORT_TOTAL_ROWS_PERCENT_FILE') + '<span class="label label-default">' + data.result.rows_per_file + '</span>'
								);
								$this.appendLog(
									'info',
									Joomla.JText._('COM_REDSHOP_IMPORT_TOTAL_FILES') + '<span class="label label-default">' + data.result.files + '</span>'
								);

								$('#import_process').show();

								// Execute import
								$this.executeImport(1, data.result.folder, data.result.files);
							} else {
								// Something wrong than we do release locking
								$this.reDraw(false);
								// Reset counting
								$("#import_count").empty();
								$('#import_process').hide();

								$this.appendLog('error', data.result.msg)
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
							var fileExt = data.files[0].name.split('.').pop();

							if (!allowFileExt.includes(fileExt)) {
								$this.lockElements(false);

								$this.appendLog('error', Joomla.JText._('COM_REDSHOP_IMPORT_ERROR_FILE_TYPE'));

								return false;
							}

							// Verify file size
							if (data.files[0].size > allowMaxFileSize) {
								$this.lockElements(false);
								$this.appendLog('error', Joomla.JText._('COM_REDSHOP_IMPORT_ERROR_FILE_MAX_SIZE'));

								return false;
							}

							// Verify file zie
							if (data.files[0].size < allowMinFileSize) {
								$this.lockElements(false);
								$this.appendLog('error', Joomla.JText._('COM_REDSHOP_IMPORT_ERROR_FILE_MIN_SIZE'));

								return false;
							}

							$this.appendLog('info', Joomla.JText._('COM_REDSHOP_IMPORT_FILE_UPLOADING'));

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
							$this.appendLog('error', error);
						}
					});

					/**
					 * Hook into select import to get config for each one
					 */
					$("#import_plugins input[type='radio']").change(function (e) {
						var plugin = $(this).val();

						// Redraw and lock elements
						$this.reDraw(true);

						// Load specific configuration of plugin
						var jqXHR = $.ajax({
							url: "index.php?option=com_ajax&plugin=" + plugin + "_config&group=redshop_import&format=raw",
							data: $("#adminForm").serialize(),
							method: "POST"
						})
							.done(function (response, textStatus, jqXHR) {
								// Redraw and unlock
								$this.reDraw(false);
								$("#import_config_body").html(response);
								$("select").select2({});

								$this.appendLog('success', Joomla.JText._('COM_REDSHOP_IMPORT_LOADED_CONFIGURATION') + '<span class="label label-debug">' + plugin + '</span>');
							})

					});

					/**
					 * Hook click on Upload button
					 */
					$("#import_btn_start").click(function (event) {
						$("#fileupload").click();
						// Lock elements
						$this.reDraw(true);
						event.preventDefault();
					});
				}
			}

			redSHOP.Admin.import = redshopImport;

			// Call init
			$(document).ready(function () {
				redSHOP.Admin.import.init();
			})
		}

	})(window, jQuery)
);


