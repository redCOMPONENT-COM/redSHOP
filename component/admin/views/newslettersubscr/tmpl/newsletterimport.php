<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$editor = JFactory::getEditor();

JHTML::_('behavior.tooltip');
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton) {
			form.task.value = pressbutton;

		}

		if ((pressbutton == 'importdata') || (pressbutton == 'back')) {
			form.view.value = "newslettersubscr";

		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">

	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_IMPORT_NEWSLETTER_SUBS'); ?></legend>

			<table class="admintable table">
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_SEPRATOR'); ?>:
					</td>
					<td>
						<input type="text" name="separator" size="1" value=","/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_NEWSLETTER_SELECT_NEWSLETTER'); ?>:
					</td>
					<td>
						<?php echo $this->lists['newsletters']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_EMAIL_IMPORT'); ?>:
						</label>
					</td>
					<td>
						<input type="file" name="file" size="53"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_BROWSE'), JText::_('COM_REDSHOP_NEWSLETTER_BROWSE'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="newslettersubscr"/>
</form>
