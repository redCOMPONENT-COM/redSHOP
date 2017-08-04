<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$uri = JURI::getInstance();
$url = $uri->root();
?>


<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.country_code.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_COUNTRY_MUST_BE_SELECTED', true ); ?>");
		} else if (form.state_code.value == ""  && document.getElementById('state_code').length>0) {
			alert("<?php echo JText::_('COM_REDSHOP_STATE_MUST_BE_SELECTED', true ); ?>");
		} else if (form.city_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_CITY_MUST_HAVE_A_NAME', true ); ?>");
		} else if (form.zipcode.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_ZIPCODE_MUST_HAVE_A_CODE', true ); ?>");
		} else if (form.zipcode_to.value != "") {
			if (isNaN(form.zipcode.value)) {
				alert("<?php echo JText::_('COM_REDSHOP_ZIPCODE_MUST_HAVE_A_NUMERIC_VALUE', true ); ?>");
			}
			else if (isNaN(form.zipcode_to.value)) {
				alert("<?php echo JText::_('COM_REDSHOP_ZIPCODE_TO_MUST_HAVE_A_NUMERIC_VALUE', true ); ?>");
			}
			else if (form.zipcode_to.value < form.zipcode.value) {
				alert("<?php echo JText::_('COM_REDSHOP_ZIPCODE_TO_MUST_HAVE_A_GREATER_VALUE', true ); ?>");
			} else {
				submitform(pressbutton);
			}
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo "details" ?></legend>
		<table class="admintable table">
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_("COM_REDSHOP_COUNTRY_NAME"); ?>:</td>
				<td><?php echo $this->lists['country_code']; ?><?php //echo JHTML::tooltip( JText::_('COM_REDSHOP_CATEGORY_PARENT' ), JText::_('COM_REDSHOP_CATEGORY_PARENT' ), 'tooltip.png', '', '', false); ?></td>
			</tr>

			<tr>
				<td class="key"><?php echo JText::_("COM_REDSHOP_STATE_NAME"); ?></td>
				<td><?php echo $this->lists['state_code']; ?></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_("COM_REDSHOP_CITY_NAME"); ?>
				</td>
				<td>
					<input class="text_area" type="text" name="city_name" id="city_name" size="30" maxlength="100"
					       value="<?php echo $this->detail->city_name; ?>"/>
				</td>
			</tr>
			<tr>
				<td class="key" valign="top"><?php echo JText::_("COM_REDSHOP_ZIPCODE"); ?>:
				</td>
				<td>

					<?php
					if($this->detail->zipcode=="")
					{echo JText::_('COM_REDSHOP_FROM');
					?><input class="text_area" type="text" name="zipcode" id="zipcode" size="15" maxlength="10"
					         value="<?php echo $this->detail->zipcode; ?>"/>&nbsp; <?php echo JText::_('COM_REDSHOP_TO');?>
				<input class="text_area" type="text" name="zipcode_to" id="zipcode_to" size="15" maxlength="10"
				       value=""/><br></br><br></br>
					<b><?php echo JText::_('COM_REDSHOP_NOTE');?> </b> <?php echo JText::_('COM_REDSHOP_ZIPCODE_NOTE_DESC');?>
				<?php
					} else {
					?>
					<input class="text_area" type="text" name="zipcode" id="zipcode" size="15" maxlength="10"
					       value="<?php echo $this->detail->zipcode; ?>"/><input type="hidden" name="zipcode_to"
					                                                             id="zipcode_to" value=""/>
					<?php
					}
					?>
				</td>


		</table>
	</fieldset>


	<input type="hidden" name="cid[]" value="<?php echo $this->detail->zipcode_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="zipcode_detail"/>
</form>


