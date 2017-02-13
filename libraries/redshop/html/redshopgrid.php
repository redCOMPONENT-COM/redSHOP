<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Utility class for creating HTML Grids
 *
 * @package     RedSHOP.Library
 * @subpackage  HTML
 * @since       1.5
 */
abstract class JHtmlRedshopGrid
{
	/**
	 * Method to check all checkboxes in a grid
	 *
	 * @param   string  $name    The name of the form element
	 * @param   string  $tip     The text shown as tooltip title instead of $tip
	 * @param   string  $action  The action to perform on clicking the checkbox
	 *
	 * @return  string
	 *
	 * @since   3.1.2
	 */
	public static function checkall($name = 'checkall-toggle', $tip = 'JGLOBAL_CHECK_ALL', $action = 'Joomla.checkAll(this)')
	{
		if (version_compare(JVERSION, '3.0', '>='))
		{
			JHtml::_('bootstrap.tooltip');

			return '<input type="checkbox" name="' . $name . '" value="" class="hasTooltip" title="'
				. JHtml::tooltipText($tip) . '" onclick="' . $action . '" />';
		}
		else
		{
			return '<input type="checkbox" name="' . $name . '" value="" title="' . JText::_($tip) . '" onclick="' . $action . '" />';
		}
	}

	/**
	 * Method for render HTML of inline edit field.
	 *
	 * @param   string  $name   DOM name of field
	 * @param   string  $value  Value of field
	 * @param   int     $id     DOM ID of field
	 * @param   string  $type   Field type (text)
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function inline($name = '', $value = '', $id = 0, $type = 'text')
	{
		if ($type != 'text')
		{
			return $value;
		}

		JFactory::getDocument()->addScriptDeclaration('
			(function($){
				$(document).ready(function(){
					$("#' . $name . '-' . $id . '").click(function(event){
						event.preventDefault();
						
						var $label = $(this);
						var $input = $("#" + $(this).data("target"));
						
						$label.hide("fast", function(){
							$input.show("fast", function(){$input.focus().select();})
								.on("blur", function(event) {
									$input.hide("fast", function(){$label.show("fast");});
								})
								.on("keypress", function(event) {
									var keyCode = event.keyCode || event.which;
								
									if (keyCode == 13) {
										event.preventDefault();
										// Enter key
										document.adminForm.task.value = "ajaxInlineEdit";
										formData = $("#adminForm").serialize();
										formData += "&id=' . $id . '";
										
										$.ajax({
											url: document.adminForm.action,
											type: "POST",
											data: formData,
											dataType: "JSON",
											beforeSend: function(jqXHR, settings) {
												$input.prop("disabled", true).addClass("disabled"); 
											},
											complete: function() {
												$input.prop("disabled", false).removeClass("disabled"); 
											}
										})
											.done(function(response){
												if (response == 1) {
													$label.text($input.val());
													$.redshopAlert(
														"' . JText::_('COM_REDSHOP_SUCCESS') . '",
														"' . JText::_('COM_REDSHOP_DATA_UPDATE_SUCCESS') . '"
													);
												} else {
													$.redshopAlert(
														"' . JText::_('COM_REDSHOP_FAIL') . '",
														"' . JText::_('COM_REDSHOP_DATA_UPDATE_FAIL') . '",
														"error"
													);
												}
											
												$input.hide("fast", function(){
													$label.show("fast");
												});
											});
									} else if (keyCode == 27) {
										// Escape key
										$input.val("' . $value . '");
										$input.hide("fast", function(){$label.show("fast");});
									}
								});
						});
					});
				});
			})(jQuery);
		');

		$html = '<input type="text" id="' . $name . '-' . $id . '-edit-inline" value="' . $value . '"'
			. 'name="jform_inline[' . $id . '][' . $name . ']" class="form-control edit-inline" style="display: none;" />';
		$html .= '<label id="' . $name . '-' . $id . '" data-target="' . $name . '-' . $id . '-edit-inline">' . $value . '</label>';

		return $html;
	}
}
