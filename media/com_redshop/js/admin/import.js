"use strict";

/**
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
(function (w, $) {
	define(
		// dependencies
		['lib/log', 'lib/ajax', 'admin/admin'],
		function (redLog, redAjax, redAdmin) {
			// Declare import
			var redshopImport = {
				/**
				 * Elements object
				 */
				_elements: {
					blocks: '#import_plugins, #import_config, #import_btn_start, #fileupload',
					logArea: '#import_process_msg_body',
					progressBar: '#import_upload_progress, #import_process_bar',
					//
					html: '#import_count, #import_config_body, #import_process_msg_body'
				},

				blockElements: function (block) {
					redAdmin.blockElements(this._elements.blocks, block);
				},

				/**
				 *
				 * @param lock
				 */
				reDraw: function (block) {
					redAdmin.clean(this._elements.html);
					redAdmin.cleanProgress(this._elements.progressBar);

					this.blockElements(block);
				},

				/**
				 *
				 * @param type
				 * @param message
				 */
				log: function (type, message) {
					redAdmin.appendLog(this._elements.logArea, type, message);
				},

				/**
				 *
				 * @param value
				 * @param total
				 * @returns {number}
				 */
				updateProgressBar: function (value, total) {
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
				 *
				 * @param index
				 * @param folder
				 * @param total
				 */
				importing: function (index, folder, total) {
					// Alias to current object
					var $this = this;

					// Init default index
					if (typeof index === 'undefined') {
						index = 1;
					}

					var plugin = $this.getSelectedPlugin();

					// Generate URL for ajax
					var url = "index.php?option=com_ajax&plugin=" + plugin + "_import&group=redshop_import&format=raw";
					var data = redAdmin.getAdminFormSerialize() + "&folder=" + folder + "&index=" + index;

					$this.log('info', Joomla.JText._('COM_REDSHOP_IMPORT_IMPORTING'));

					// Ajax request for importing
					redAjax.execute(
						{
							url: url,
							data: data
						},
						// Done callback
						function (response, textStatus, jqXHR) {
							// Update status bar
							$this.updateProgressBar(index, total);

							// Update counting html
							$("#import_count").html(index + '/' + total);

							// Show log imported file
							$this.log(
								'info',
								Joomla.JText._('COM_REDSHOP_IMPORT_IMPORTED_FILE') + response.file
							);

							// Number if processed items / products
							if (response.data.length) {
								$.each(response.data, function (index, data) {
									if (data.status == 0) {
										// Show log imported item
										$this.log('error', data.message);
									}
									else {
										// Show log imported item
										$this.log('success', data.message);
									}
								})
							}

							// Go to next file
							index++;
							if (index <= total) {
								$this.importing(index, folder, total)
							}
							else {
								// Unblocking
								$this.blockElements(false)
								// Completed
								$this.log('success', Joomla.JText._('COM_REDSHOP_IMPORT_SUCCESS'));
							}
						}
					)
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

						.done()

						.fail(function () {
							// Unblocking & reDraw
							$this.reDraw(false);
							$this.log('error', Joomla.JText._('COM_REDSHOP_IMPORT_AJAX_FAILED'));
						});
				},

				/**
				 * Hooking upload request
				 */
				hookUpload: function () {
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
							$uploadWrapper.hide();

							$this.log('success', Joomla.JText._('COM_REDSHOP_IMPORT_FILE_UPLOAD_COMPLETED'));

							// Clean up logging
							redAdmin.clean("#import_process_msg_body")

							// File uploaded and success at service side
							if (data.result.status == 1) {

								// Message
								$this.log('success', data.result.msg);

								// Show number of product(s) will import
								$("#import_count").html(data.result.files);

								// Show process bar
								$("#import_process_bar").parent().show();

								$this.log(
									'info',
									Joomla.JText._('COM_REDSHOP_IMPORT_FOLDER') + '<span class="label label-default">' + data.result.folder + '</span>'
								);
								$this.log(
									'info',
									Joomla.JText._('COM_REDSHOP_IMPORT_TOTAL_ROWS') + '<span class="label label-default">' + (data.result.rows - 1) + '</span>'
								);
								$this.log(
									'info',
									Joomla.JText._('COM_REDSHOP_IMPORT_TOTAL_ROWS_PERCENT_FILE') + '<span class="label label-default">' + data.result.rows_per_file + '</span>'
								);
								$this.log(
									'info',
									Joomla.JText._('COM_REDSHOP_IMPORT_TOTAL_FILES') + '<span class="label label-default">' + data.result.files + '</span>'
								);

								$this.log(
									'info',
									Joomla.JText._('COM_REDSHOP_IMPORT_INIT_IMPORT')
								);

								$('#import_process').show();

								// Execute import
								$this.importing(1, data.result.folder, data.result.files);
							} else {
								// Something wrong than we do release locking
								$this.reDraw(false);

								// Reset counting
								redAdmin.clean("#import_count");

								$('#import_process').hide();

								$this.appendLog(
									'error',
									data.result.msg
								)
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
								$this.log('error', Joomla.JText._('COM_REDSHOP_IMPORT_ERROR_FILE_TYPE'));
								$this.blockElements(false);

								return false;
							}

							// Verify file size
							if (data.files[0].size > allowMaxFileSize) {
								$this.log('error', Joomla.JText._('COM_REDSHOP_IMPORT_ERROR_FILE_MAX_SIZE'));
								$this.blockElements(false);

								return false;
							}

							// Verify file zie
							if (data.files[0].size < allowMinFileSize) {
								$this.log('error', Joomla.JText._('COM_REDSHOP_IMPORT_ERROR_FILE_MIN_SIZE'));
								$this.blockElements(false);

								return false;
							}

							$this.log(
								'info',
								Joomla.JText._('COM_REDSHOP_IMPORT_FILE_UPLOADING')
							);

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
							$this.blockElements(false);
							$this.log('error', error);
						}
					});
				},

				/**
				 * Hook on changing plugin to get configuration
				 */
				hookChangePlugin: function () {
					var $this = this;

					/**
					 * Hook into select import to get config for each one
					 */
					$("#import_plugins input[type='radio']").change(function (e) {
						var plugin = $(this).val();

						// Redraw and lock elements
						$this.reDraw(true);

						redAjax.execute(
							{
								url: "index.php?option=com_ajax&plugin=" + plugin + "_config&group=redshop_import&format=raw",
								data: redAdmin.getAdminFormSerialize()
							},
							// Done callback
							function (response, textStatus, jqXHR) {
								// Redraw and unlock
								$this.reDraw(false);

								if (response.status) {
									if (response.data[0].dataContent != '') {
										$("#import_config_body").html(response.data[0].dataContent);
									}
								}

								$("select").select2({});

								$this.log(
									'success',
									Joomla.JText._('COM_REDSHOP_IMPORT_LOADED_CONFIGURATION') + '<span class="label label-debug">' + plugin + '</span>'
								);
							},
							// Fail callback
							function (response, textStatus, jqXHR) {
								// Redraw and unlock
								$this.reDraw(false);
								$this.log(
									'error',
									Joomla.JText._('COM_REDSHOP_IMPORT_AJAX_FAILED')
								);
							}
						)
					});
				},

				hookStart: function () {
					var $this = this;
					/**
					 * Hook click on Upload button
					 */
					$("#import_btn_start").click(function (event) {
						$("#fileupload").click();
						// Lock elements
						$this.reDraw(true);

						event.preventDefault();
					});
				},

				init: function () {
					this.hookChangePlugin();
					this.hookUpload();
					this.hookStart();
				}
			}

			w.redSHOP.Admin.import = redshopImport;

			// Call init
			$(document).ready(function () {
				w.redSHOP.Admin.import.init();
			})

			return w.redSHOP.Admin.import;

		}
	)
})(window, jQuery)


