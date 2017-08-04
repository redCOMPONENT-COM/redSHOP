<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

$app = JFactory::getApplication();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.mail_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_MAIL_ITEM_MUST_HAVE_A_NAME', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}

	jQuery(document).ready(function($) {
		jQuery("input:radio[name='templateMode']").click(function(event) {
			Joomla.submitbutton('apply');
		});
	});
</script>

<form
	action="<?php echo JRoute::_($this->request_url) ?>"
	method="post"
	name="adminForm"
	id="adminForm"
    enctype="multipart/form-data"
>

	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<table class="admintable table">
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MAIL_NAME'); ?><span class="star text-danger"> *</span>:</td>
					<td><input class="text_area" type="text" name="mail_name" id="mail_name" size="32" maxlength="250"
					           value="<?php echo $this->detail->mail_name; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAIL_NAME'), JText::_('COM_REDSHOP_MAIL_NAME'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MAIL_SUBJECT'); ?>:</td>
					<td><input class="text_area" type="text" name="mail_subject" id="mail_subject" size="80" maxlength="255"
					           value="<?php echo $this->detail->mail_subject; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAIL_SUBJECT'), JText::_('COM_REDSHOP_MAIL_SUBJECT'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MAIL_SECTION'); ?>:</td>
					<td><?php echo $this->lists['type'];?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAIL_SECTION'), JText::_('COM_REDSHOP_MAIL_SECTION'), 'tooltip.png', '', '', false); ?>
						<input type="hidden" id="please"
						       value="<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_MAIL_SECTION'); ?>"></td>
				</tr>
				<tr id="order_state" <?php if ($this->detail->mail_section != 'order_status' || $this->detail->mail_section == "0")
				{ ?>    style="display: none;"    <?php }?>>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MAIL_ORDER_STATUS'); ?>:</td>
					<td>
						<div id="responce"></div>
						<div id="order_state_edit">
						<?php

							if (isset($this->lists['order_status']))
							{
								echo $this->lists['order_status'];
							}
						?>
						</div>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MAIL_BCC'); ?>:</td>
					<td><input class="text_area" type="text" name="mail_bcc" id="mail_bcc" size="80" maxlength="255"
					           value="<?php echo $this->detail->mail_bcc; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAIL_BCC'), JText::_('COM_REDSHOP_LBL_MAIL_BCC_TOOLTIP'), 'tooltip.png', '', '', false); ?>
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
			<legend><?php echo JText::_('COM_REDSHOP_BODY'); ?></legend>
			<div class="col-sm-12">
				<div class="col-sm-7">
					<p>
						<label class="text-info" for="templateMode">
							<?php echo JText::_('COM_REDSHOP_TEMPLATE_SWITCH_METHOD'); ?>
						</label>
						<?php
						$option = array();
						$option[] = JHTML::_('select.option', 'codemirror', JText::_('COM_REDSHOP_TEMPLATE_CODE_MIRRONR'));
						$option[] = JHTML::_('select.option', 'joomladefault', JText::_('COM_REDSHOP_TEMPLATE_JOOMLA_DEFAULT'));

						$templateMode = $app->input->getCmd('templateMode', 'codemirror');

						echo JHTML::_(
							'redshopselect.radiolist',
							$option,
							'templateMode',
							null,
							'value',
							'text',
							$templateMode
						);
						?>
					</p>
					<?php if ('codemirror' != $templateMode): ?>
						<?php $templateMode = null; ?>
					<?php endif; ?>
					<?php
					echo JFactory::getEditor($templateMode)
									->display(
										"mail_body",
										$this->detail->mail_body,
										'800px',
										'500px',
										'100',
										'20'
									);
				?>
				</div>
				<div class="col-sm-5">
					<?php echo $this->loadTemplate('hint');?>
				</div>
			</div>
		</fieldset>
	</div>

	<input type="hidden" name="cid[]" value="<?php echo $this->detail->mail_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="mail_detail"/>
</form>
