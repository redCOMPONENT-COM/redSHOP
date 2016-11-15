<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$editor = JEditor::getInstance();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if ((pressbutton != 'question.cancel') && (form.jform_product_id.value == 0))
		{
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_PRODUCT_NAME', true); ?>");
		}
		else
		{
			submitform(pressbutton);
		}
	}

	function deleteAnswer()
	{
		submitform('removeAnswer');
	}

	function sendAnswer()
	{
		submitform('sendAnswer');
	}
</script>
<form action="<?php echo JRoute::_($this->requestUrl) ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<div class="row-fluid">
		<div class="col-sm-12">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<?php echo $this->form->renderField('product_id') ?>
			<div class="control-group">
				<div class="control-label"><?php echo JText::_('COM_REDSHOP_USER_NAME'); ?>:</div>
				<div class="controls"><?php echo $this->detail->user_name; ?></div>
				<?php echo $this->form->renderField('user_name') ?>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo JText::_('COM_REDSHOP_USER_EMAIL'); ?>:</div>
				<div class="controls"><?php echo $this->detail->user_email; ?></div>
				<?php echo $this->form->renderField('user_email') ?>
			</div>
			<?php echo $this->form->renderField('telephone') ?>
			<?php echo $this->form->renderField('address') ?>
			<?php echo $this->form->renderField('published') ?>
		</div>
	</div>
	<div class="col-sm-12">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_QUESTION'); ?></legend>
				<?php echo $this->form->renderField('question') ?>
		</fieldset>
	</div>
	<?php
		$k = 0;
		$i = 0;
	?>
	<div class="col-sm-12" id='answerlists'>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_PREVIOUS_ANSWERS') ?></legend>
			<?php if (empty($this->answers)): ?>
			<div class="alert alert-info">
				<?php echo JText::_('COM_REDSHOP_QUESTION_NO_ANSWERS') ?>
			</div>
			<?php else: ?>
			<table class="adminlist table table-striped">
				<thead>
					<tr class="row<?php echo $k; ?>">
						<th class="title" width="1">#</th>
						<th class="title" width="1"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
						<th class="title"><?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></th>
						<th class="title"><?php echo JText::_('COM_REDSHOP_USER_NAME'); ?></th>
						<th class="title"><?php echo JText::_('COM_REDSHOP_USER_EMAIL'); ?></th>
						<th class="title"><?php echo JText::_('COM_REDSHOP_TIME') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->answers as $answer) : ?>
					<tr class="row<?php echo $k; ?>">
						<td align="center"><?php echo $i + 1; ?></td>
						<td class="order"><?php echo JHTML::_('grid.id', $i, $answer->id, false, 'aid'); ?></td>
						<td><?php echo $answer->question ?></td>
						<td><?php echo $answer->user_name ?></td>
						<td><?php echo $answer->user_email ?></td>
						<td align="center"><?php echo date("M d Y, h:i:s A", $answer->question_date); ?></td>
					</tr>
						<?php
						$i++;
						$k = 1 - $k;
						?>
					<?php endforeach; ?>
					<tr>
						<td colspan="6">
							<input type="button" class="btn btn-danger" name="btn_delete" id="btn_delete"
								   value="<?php echo JText::_('COM_REDSHOP_DELETE') ?>" onclick="deleteAnswer();"/>
							<input type="button" class="btn btn-info" name="btn_send" id="btn_send"
								   value="<?php echo JText::_('COM_REDSHOP_SEND') ?>" onclick="sendAnswer();"/></td>
					</tr>
				</tbody>
			</table>
			<?php endif; ?>
		</fieldset>
	</div>
	<div class="col-sm-12">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_ANSWERS') ?></legend>
			<?php echo $this->form->renderField('answer') ?>
		</fieldset>
	</div>
	<div class="clr"></div>
	<?php echo $this->form->renderField('id') ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="question" />
	<?php echo JHtml::_('form.token'); ?>
</form>
