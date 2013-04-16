<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

$editor = JFactory::getEditor();
$uri = JURI::getInstance();
$url = $uri->root();
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {

		var form = document.adminForm;
		var field_type = document.getElementById('field_type').value;
		var field_section = document.getElementById('field_section').value;

		var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";

		for (var i = 0; i < form.field_name.value.length; i++) {
			if (iChars.indexOf(form.field_name.value.charAt(i)) != -1) {
				alert(" !@#$%^&*()+=-[]\\\';,./{}| \n Special characters are not allowed.\n Please remove them and try again.");
				return false;
			}
		}


		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.field_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_FIELDS_ITEM_MUST_HAVE_A_NAME', true ); ?>");
			form.field_name.focus();
			return false;
		} else if (form.field_title.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_FIELDS_ITEM_MUST_HAVE_A_TITLE', true ); ?>");
			form.field_title.focus();
			return false;
		} else if ((form.field_section.value == 13) && (form.field_type.value == 8 || form.field_type.value == 9 || form.field_type.value == 10)) {
			alert("<?php echo JText::_('COM_REDSHOP_ERROR_YOU_CAN_NOT_SELECT_THIS_SECTION_TYPE_UNDER_THIS_FIELD' , true);?>");
			return false;
		}
		if (field_type == 3 || field_type == 4 || field_type == 5 || field_type == 6 || field_type == 11 || field_type == 13) {
			var chks = document.getElementsByName('extra_value[]');//here extra_value[] is the name of the textbox

			for (var i = 0; i < chks.length; i++) {
				if (chks[i].value == "") {
					alert("Please fillup Option Value");
					chks[i].focus();
					return false;
				}
			}
		}

		document.getElementById('field_section').disabled = false;
		submitform(pressbutton);
	}

	function sectionValidation() {
		var field_type = document.getElementById('field_type').value;
		var field_section = document.getElementById('field_section').value;
		//field_section
		if ((field_section == 13) && (field_type == 8 || field_type == 9 || field_type == 10)) {
			alert("<?php echo JText::_('COM_REDSHOP_ERROR_YOU_CAN_NOT_SELECT_THIS_SECTION_TYPE_UNDER_THIS_FIELD' );?>");
			return false;
		}

		if ((field_section == 1) || (field_section == 17)) {
			document.getElementById('showdiv').style.display = 'block';
			document.getElementById('showdivcheckout').style.display = 'block';
		} else {
			document.getElementById('showdiv').style.display = 'none';
			document.getElementById('showdivcheckout').style.display = 'none';
		}
	}

	function isAlphabet(elem, helperMsg) {
		var alphaExp = /^[a-zA-Z]+$/;
		if (elem.value.match(alphaExp)) {
			return true;
		} else {
			alert(helperMsg);
			elem.focus();
			return false;
		}
	}


</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key">
					<label for="name">
						<?php echo JText::_('COM_REDSHOP_TYPE'); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['type']; ?>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TYPE'), JText::_('COM_REDSHOP_TYPE'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="volume">
						<?php echo JText::_('COM_REDSHOP_SECTION'); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['section']; ?>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SECTION'), JText::_('COM_REDSHOP_SECTION'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<label for="name">
						<?php echo JText::_('COM_REDSHOP_NAME'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="field_name" id="field_name" size="32" maxlength="250"
					       value="<?php echo str_replace('-', '_', $this->detail->field_name); ?>"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_FIELD_NAME'), JText::_('COM_REDSHOP_FIELD_NAME'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<label for="name">
						<?php echo JText::_('COM_REDSHOP_FIELD_TITLE'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="field_title" id="field_title" size="32" maxlength="250"
					       value="<?php echo $this->detail->field_title; ?>"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_FIELD_TITLE'), JText::_('COM_REDSHOP_FIELD_TITLE'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_('COM_REDSHOP_CLASS'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="field_class" id="field_class"
					       value="<?php echo $this->detail->field_class; ?>" size="32" maxlength="250"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CLASS'), JText::_('COM_REDSHOP_CLASS'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_('COM_REDSHOP_MAX_LENGTH'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="field_maxlength" id="field_maxlength"
					       value="<?php echo $this->detail->field_maxlength; ?>" size="32" maxlength="250"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAX_LENGTH'), JText::_('COM_REDSHOP_MAX_LENGTH'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_('COM_REDSHOP_SIZE'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="field_size" id="field_size"
					       value="<?php echo $this->detail->field_size; ?>" size="32" maxlength="250"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SIZE'), JText::_('COM_REDSHOP_SIZE'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_('COM_REDSHOP_COLS'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="field_cols" id="field_cols"
					       value="<?php echo $this->detail->field_cols; ?>" size="32" maxlength="250"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_COLS'), JText::_('COM_REDSHOP_COLS'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_('COM_REDSHOP_ROWS'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="field_rows" id="field_rows"
					       value="<?php echo $this->detail->field_rows; ?>" size="32" maxlength="250"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_ROWS'), JText::_('COM_REDSHOP_ROWS'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>

			<?php
			if ($this->detail->field_section == 1 || $this->detail->field_section == 17)
			{
				$display = 'style="display:block;"';
			}
			else
			{
				$display = 'style="display:none;"';
			}
			?>
			<tr>
				<td colspan="2">
					<div id="showdiv" <?php echo $display;?> >
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr>
								<td valign="top" align="right" class="key">
									<?php echo JText::_('COM_REDSHOP_DISPLAY_IN_PRODUCT_LIST'); ?>:
								</td>
								<td>
									<?php echo $this->lists['display_in_product']; ?>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="showdivcheckout" <?php echo $display;?> >
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr>
								<td valign="top" align="right" class="key">
									<?php echo JText::_('COM_REDSHOP_DISPLAY_IN_CHECKOUT'); ?>:
								</td>
								<td>
									<?php echo $this->lists['display_in_checkout']; ?>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_REDSHOP_SHOW_AT_FRONT'); ?>:
				</td>
				<td>
					<?php echo $this->lists['show_in_front']; ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_REDSHOP_IS_REQUIRED'); ?>:
				</td>
				<td>
					<?php echo $this->lists['required']; ?>

				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
				</td>
				<td>
					<?php echo $this->lists['published']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="col50">

</div>

<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?></legend>

		<table class="admintable">
			<tr>
				<td>
					<?php echo $editor->display("field_desc", $this->detail->field_desc, '$widthPx', '$heightPx', '100', '20');    ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<?php
// print_r($this->detail);
//(count($this->lists['extra_data']) <=0 ) ||
if ($this->detail->field_type == 7 || $this->detail->field_type == 8 || $this->detail->field_type == 1 || $this->detail->field_type == 2)
	$style = "display:none;";
else
	$style = "display:block;";
//echo $style;
if ($this->detail->field_type == 11 || $this->detail->field_type == 13)
{
	$style1 = "display:none;";
	$style2 = "display:block;";
}
else
{
	$style1 = "display:block;";
	$style2 = "display:none;";
}

?>
<div class="col50" id="field_data" style="<?php echo $style; ?>">

	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_VALUE'); ?></legend>
		<?php echo JText::_('COM_REDSHOP_USE_THE_TABLE_BELOW_TO_ADD_NEW_VALUES'); ?>

		<input type="button" name="addvalue" id="addvalue" class="button"
		       Value="<?php echo JText::_('COM_REDSHOP_ADD_VALUE'); ?>" onclick="addNewRow('extra_table');"/>

		<table cellpadding="0" cellspacing="5" border="0" id="extra_table" width="95%">
			<tr>
				<th width="20%"><?php echo JText::_('COM_REDSHOP_OPTION_NAME'); ?></th>
				<th><?php echo JText::_('COM_REDSHOP_OPTION_VALUE'); ?></th>
				<th>&nbsp;</th>
			</tr>
			<?php    if (count($this->lists['extra_data']) > 0)
			{
				for ($k = 0; $k < count($this->lists['extra_data']); $k++)
				{
					?>
					<tr>
						<td>
							<div id="divfieldText" style="<?php echo $style1; ?>"><input type="text" name="extra_name[]"
							                                                             value="<?php echo htmlentities($this->lists['extra_data'][$k]->field_name); ?>"
							                                                             id="extra_name[]"></div>
							<div id="divfieldFile" style="<?php echo $style2; ?>"><input type="file"
							                                                             name="extra_name_file[]"
							                                                             id="extra_name_file[]"></div>
						</td>
						<td><input type="text" name="extra_value[]"
						           value="<?php echo $this->lists['extra_data'][$k]->field_value; ?>"
						           id="extra_value[]">
							<input type="hidden"
							       value="<?php echo htmlentities($this->lists['extra_data'][$k]->value_id); ?>"
							       name="value_id[]" id="value_id[]">
							<input value="Delete" onclick="deleteRow(this)" class="button" type="button"/></td>
						<td>
							<?php    if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'extrafield/' . $this->lists['extra_data'][$k]->field_name) && $this->lists['extra_data'][$k]->field_name != '')
							{ ?>
								<img
									src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $this->lists['extra_data'][$k]->field_name; ?>"/>
							<?php }?>
						</td>
					</tr>
				<?php
				}
			}
			else
			{
				$k = 1;    ?>
				<tr>
					<td>
						<div id="divfieldText" style="<?php echo $style1; ?>"><input type="text" name="extra_name[]"
						                                                             value="field_temp_opt_1"
						                                                             id="extra_name[]"></div>
						<div id="divfieldFile" style="<?php echo $style2; ?>"><input type="file"
						                                                             name="extra_name_file[]"
						                                                             id="extra_name_file[]"></div>
					</td>
					<td><input type="text" name="extra_value[]" id="extra_value[]"><input type="hidden"
					                                                                      name="value_id[]"
					                                                                      id="value_id[]"></td>
					<td>&nbsp;</td>
				</tr>
			<?php }    ?>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" value="<?php echo $k; ?>" name="total_extra" id="total_extra">
<input type="hidden" name="cid[]" value="<?php echo $this->detail->field_id; ?>"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="view" value="fields_detail"/>
</form>
