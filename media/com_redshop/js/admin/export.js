"use strict";

define(
	// modular name
	'export',
	// dependencies
	['ajax', 'log'],
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
		if (typeof redSHOP.Admin.export === 'undefined') {

			var redshopExport = {
				/**
				 *
				 * @param lock
				 */
				blockElements: function (lock) {
					var lockClass = 'disabled muted';
					var elements = '#export_plugins, #export_config, #export_btn_start'

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
				 */
				reDraw: function (lock) {
					var cleanHtml = '#export_count, #export_process_msg_body';
					var cleanProcessbar = '#export_process_bar';

					// Clean up
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

					$("#export_process_msg_body").append(message);
				},

				/**
				 *
				 * @param total
				 * @param value
				 * @returns {number}
				 */
				updateProcessbar: function (total, value) {
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
				 * @return  string
				 */
				getFormSerialize: function () {
					return $("#adminForm").serialize();
				},

				executeExport: function (index, total) {
					var $this = this;

					// Init default index
					if (typeof index === 'undefined') {
						index = 0;
					}

					var plugin = $this.getSelectedPlugin();
					var url = "index.php?option=com_ajax&plugin=" + plugin + "_export&group=redshop_export&format=raw";
					var data = $("#adminForm").serialize() + "&from=" + index;
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

							// Go to next file
							index++;
							if (index <= total) {
								$this.executeExport(index, total)
							}
							else {
								// Unblocking
								$this.blockElements(false);
								// Completed
								$this.appendLog('success', Joomla.JText._('COM_REDSHOP_EXPORT_SUCCESS'));

								// Generate download file
								var jqXHRComplete = $.ajax({
									url: "index.php?option=com_ajax&plugin=" + plugin + "_complete&group=redshop_export&format=raw",
									data: data,
									dataType: "json",
									method: "POST"
								})
									.done(function (response, textStatus, jqXHR) {
										var url = 'index.php?option=com_redshop&view=export&task=download&file_path=' + response.data.filePath + '&format=raw';
										$('#export_iframe').attr('src', url);
									})
							}

						})
				},

				init: function () {
					var $this = this;

					/**
					 * Switch plugin
					 */
					$("#export_plugins input[type='radio']").change(function (e) {
						var plugin = $this.getSelectedPlugin();

						// Load specific configuration of plugin
						// @TODO Should work with json instead
						var jqXHR = $.ajax({
							url: "index.php?option=com_ajax&plugin=" + plugin + "_config&group=redshop_export&format=raw",
							data: $("#adminForm").serialize(),
							method: "POST"
						})
							.done(function (response, textStatus, jqXHR) {
								$this.reDraw(false);
								$("#export_config_body").html(response);
								$("select").select2({});
							})
					});

					/**
					 * Start
					 */
					$("#export_btn_start").click(function (event) {
						var plugin = $this.getSelectedPlugin();

						// Redraw
						$this.reDraw(true);

						var jqXHR = $.ajax({
							url: "index.php?option=com_ajax&plugin=" + plugin + "_start&group=redshop_export&format=raw",
							data: $("#adminForm").serialize(),
							dataType: "json",
							method: "POST"
						})
							.done(function (response, textStatus, jqXHR) {
								if (response.status) {
									// First execute;
									$this.executeExport(0, response.data.total);
								}

							})

						event.preventDefault();
					});
				}
			}

			redSHOP.Admin.export = redshopExport;

			// Call init
			$(document).ready(function () {
				redSHOP.Admin.export.init();
			})
		}

	})(window, jQuery)
);


