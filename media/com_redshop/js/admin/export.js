"use strict";

/**
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// redSHOP Admin export

(function (w, $) {
	define(
		// Dependencies
		['lib/log', 'lib/ajax', 'admin/admin'],
		// Export class
		function (redLog, redAjax, redAdmin) {

			// Declare export
			var redshopExport = {
				/**
				 * Elements object
				 */
				_elements: {
					blocks: '#export_plugins, #export_config, #export_btn_start',
					logArea: '#export_process_msg_body',
					progressBar: '#export_process_bar',
					//
					html: '#export_count, #export_config_body, #export_process_msg_body'
				},
				/**
				 *
				 * @param block
				 */
				blockElements: function (block) {
					redAdmin.blockElements(this._elements.blocks, block);
				},

				/**
				 *
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
				 * @param total
				 * @param value
				 * @returns {number}
				 */
				updateProgressBar: function (value, total) {
					var $bar = $("#export_process_bar");

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
				 * @param total
				 */
				exporting: function (index, total) {
					var $this = this;

					// Init default index
					if (typeof index === 'undefined') {
						index = 0;
					}

					var plugin = $this.getSelectedPlugin();
					var url = "index.php?option=com_ajax&plugin=" + plugin + "_export&group=redshop_export&format=raw";
					var data = $("#adminForm").serialize() + "&from=" + index;

					// Execute ajax for exporting
					redAjax.execute(
						{
							url: url,
							data: data
						},
						// Done callback
						function (response, textStatus, jqXHR) {
							// Update status bar
							$this.updateProgressBar(index, total);

							// Go to next file
							index++;
							if (index <= total) {
								$this.exporting(index, total)
							}
							else {
								// Unblocking
								$this.blockElements(false);
								// Completed
								$this.log('success', Joomla.JText._('COM_REDSHOP_EXPORT_SUCCESS'));

								redAjax.execute(
									{
										url: "index.php?option=com_ajax&plugin=" + plugin + "_complete&group=redshop_export&format=raw",
										data: data
									},
									// Done callback
									function (response, textStatus, jqXHR) {
										// Generate download file
										// @TODO Move this to js lib
										var url = 'index.php?option=com_redshop&view=export&task=download&file_path=' + response.data.filePath;
										$('#export_iframe').attr('src', url);
									}
								)
							}
						}
					)
				},

				hookChangePlugin: function () {
					var $this = this;

					/**
					 * Switch plugin
					 */
					$("#export_plugins input[type='radio']").change(function (e) {
						var plugin = $this.getSelectedPlugin();

						// Load specific configuration of plugin
						redAjax.execute(
							{
								url: "index.php?option=com_ajax&plugin=" + plugin + "_config&group=redshop_export&format=raw",
								data: redAdmin.getAdminFormSerialize()
							},
							// Done callback
							function (response, textStatus, jqXHR) {

								// Redraw and unlock
								$this.reDraw(false);

								if (response.status) {
									if (response.data[0].dataContent != '') {
										$("#export_config_body").html(response.data[0].dataContent);
									}
								}

								//
								$("select").select2({});

								$this.log(
									'success',
									Joomla.JText._('COM_REDSHOP_EXPORT_LOADED_CONFIGURATION') + '<span class="label label-debug">' + plugin + '</span>'
								);
							}
						)
					});
				},

				hookStart: function () {
					var $this = this;

					/**
					 * Start
					 */
					$("#export_btn_start").click(function (event) {
						var plugin = $this.getSelectedPlugin();

						// Redraw
						//$this.reDraw(true);
						$this.blockElements(true);

						redAjax.execute(
							{
								url: "index.php?option=com_ajax&plugin=" + plugin + "_start&group=redshop_export&format=raw",
								data: redAdmin.getAdminFormSerialize()
							},
							// Done callback
							function (response, textStatus, jqXHR) {
								if (response.status) {
									// First execute;
									$this.exporting(0, response.data.total);
								}
							}
						)
						event.preventDefault();
					});
				},

				init: function () {

					this.hookChangePlugin();
					this.hookStart();
				}
			}

			redAdmin.export = redshopExport;

			// Call init
			$(document).ready(function () {
				redAdmin.export.init();
			})
		}
	)
})(window, jQuery)
