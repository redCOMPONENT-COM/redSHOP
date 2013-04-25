<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';
$extra_field = new extra_field();
JHTML::_('behavior.tooltip');
$editor = JFactory::getEditor();
$model = $this->getModel('template_detail');
$showbuttons = JRequest::getVar('showbuttons');
//echo $this->detail->template_section;
?>
<script language="javascript" type="text/javascript">

	function showclicktellbox() {
		if (document.adminForm.template_section.value == "clickatell") {
			document.getElementById('clicktellbox').style.display = 'block';
		} else {
			document.getElementById('clicktellbox').style.display = 'none';
		}
	}
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.template_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_TEMPLATE_MUST_HAVE_A_NAME', true ); ?>");
		} else if (form.template_section.value == "0") {
			alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_HAVE_A_SELECT_SECTION', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>
<?php
if (isset($showbuttons))
{
	?>
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('save');">
				<?php echo JText::_('COM_REDSHOP_SAVE'); ?>
			</button>
			<button type="button" onclick="window.parent.SqueezeBox.close();">
				<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
			</button>
		</div>
	</fieldset>
<?php
}

$style = $this->detail->template_section == 'clicktell_sms_message' ? "display: block;" : "display: none;";


?>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_TEMPLATE_NAME'); ?>:</td>
					<td><input class="text_area" type="text" name="template_name" id="template_name" size="32"
					           maxlength="250" value="<?php echo $this->detail->template_name; ?>"/></td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_TEMPLATE_SECTION'); ?>
						:
					</td>
					<td><?php echo $this->lists['section']; ?></td>
				</tr>
				<tr>
					<td colspan="2">

						<div id="clicktellbox" style="<?php echo $style; ?>">
							<table class="admintable">
								<tr>
									<td width="100" align="right" class="key">
										<?php echo JText::_('COM_REDSHOP_PAYMENT_METHOD'); ?>:
									</td>
									<td><?php echo $this->lists['payment_methods']; ?><?php echo $this->lists['shipping_methods']; ?>
									</td>
								</tr>

								<tr>
									<td valign="top" align="right"
									    class="key"><?php echo JText::_('COM_REDSHOP_ORDER_STATUS'); ?>:
									</td>
									<td><?php echo $this->lists['order_status']; ?></td>
								</tr>

							</table>
						</div>

					</td>
				</tr>

				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:</td>
					<td><?php echo $this->lists['published']; ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?></legend>
			<table class="admintable">
				<tr>
					<td><?php echo $editor->display("template_desc", $this->detail->template_desc, '$widthPx', '$heightPx', '100', '20'); ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<!-- Available Dynamic fields-->
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_AVAILABLE_DYNAMIC_FIELDS'); ?></legend>
			<?php echo $this->loadTemplate('dynamic_fields');?>
		</fieldset>
	</div>


	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_AVAILABLE_TEXTLIBRARY_ITEMS'); ?></legend>
			<?php echo $this->loadTemplate('library_items');?>
		</fieldset>
	</div>

	<div class="clr"></div>
	<input type="hidden" name="template_id" value="<?php echo $this->detail->template_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="template_detail"/>
</form>
