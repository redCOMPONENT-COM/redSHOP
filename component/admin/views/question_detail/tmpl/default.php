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
JHTML::_('behavior.modal');
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';

$producthelper = new producthelper;
$editor        = JFactory::getEditor();

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

		if (form.product_id.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_PRODUCT_NAME', true); ?>");
		} else {
			submitform(pressbutton);
		}
	}

	function deleteanswer() {
		submitform('removeanswer');
	}

	function sendanswer() {
		submitform('sendanswer');
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?>:</td>
					<td><?php echo $this->lists['product_id']; ?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_NAME'); ?>:</td>
					<td><?php echo $this->detail->user_name; ?>
						<input type="hidden" name="user_name" id="user_name"
						       value="<?php echo $this->detail->user_name; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_EMAIL'); ?>:</td>
					<td><?php echo $this->detail->user_email; ?>
						<input type="hidden" name="user_email" id="user_email"
						       value="<?php echo $this->detail->user_email; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_PHONE_NO'); ?>:</td>
					<td><input type="text" name="telephone" id="telephone" value="<?php echo $this->detail->telephone; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_ADRESS'); ?>:</td>
					<td><input type="text" name="address" id="address" value="<?php echo $this->detail->address; ?>"/>
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
			<legend><?php echo JText::_('COM_REDSHOP_QUESTION'); ?></legend>
			<table class="admintable">
				<tr>
					<td><?php echo $editor->display("question", $this->detail->question, '$widthPx', '$heightPx', '100', '20', '1'); ?></td>
				</tr>
			</table>
		</fieldset>
	</div>

	<div class="col50" id='answerlists'>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_PREVIOUS_ANSWERS'); ?></legend>
			<table class="adminlist">
				<thead>
				<tr class="row<?php echo $k; ?>">
					<th class="title">#</th>
					<th class="title"><input type="checkbox" name="toggle" value=""
					                         onclick="checkAll(<?php echo count($this->answers); ?>);"/></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_USER_NAME'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_USER_EMAIL'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_TIME') ?></th>
				</tr>
				</thead>
				<?php if (count($this->answers) > 0)
{
	$k = 0;
	$i = 0;

	foreach ($this->answers as $answer)
	{
?>
						<tr class="row<?php echo $k; ?>">
							<td align="center"><?php echo $i + 1; ?></td>
							<td class="order"
							    width="5%"><?php echo JHTML::_('grid.id', $i, $answer->question_id, false, 'aid'); ?></td>
							<td><?php echo $answer->question; ?></td>
							<td><?php echo $answer->user_name; ?></td>
							<td><?php echo $answer->user_email; ?></td>
							<td align="center"><?php echo date("M d Y, h:i:s A", $answer->question_date); ?></td>
						</tr>
						<?php $i++;
		$k = 1 - $k;
	} ?>
					<tr>
						<td colspan="6">
							<input type="button" name="btn_delete" id="btn_delete"
							       value="<?php echo JText::_('COM_REDSHOP_DELETE') ?>" onclick="deleteanswer();"/>
							<input type="button" name="btn_send" id="btn_send"
							       value="<?php echo JText::_('COM_REDSHOP_SEND') ?>" onclick="sendanswer();"/></td>
					</tr>
				<?php
} ?>
			</table>
		</fieldset>
	</div>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></legend>
			<table class="admintable">
				<tr>
					<td>
						<?php
							echo $editor->display("answer", '', '$widthPx', '$heightPx', '100', '20', '1');
						?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->question_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="question_detail"/>
</form>
