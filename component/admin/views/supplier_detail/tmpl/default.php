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
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.supplier_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_SUPPLIER_ITEM_MUST_HAVE_A_NAME', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<?php

	?>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_NAME'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="supplier_name" id="supplier_name" size="32"
						       maxlength="250" value="<?php echo $this->detail->supplier_name; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_SUPPLIER_EMAIL'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="supplier_email" id="supplier_email" size="32"
						       maxlength="250" value="<?php echo $this->detail->supplier_email; ?>"/>
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
						<?php echo $editor->display("supplier_desc", $this->detail->supplier_desc, '$widthPx', '$heightPx', '100', '20');    ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>


	<input type="hidden" name="cid[]" value="<?php echo $this->detail->supplier_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="supplier_detail"/>
</form>


