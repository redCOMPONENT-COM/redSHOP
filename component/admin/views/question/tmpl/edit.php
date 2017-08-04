<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

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
        Joomla.submitbutton('question.removeAnswer');
	}

	function sendAnswer()
	{
        Joomla.submitbutton('question.sendAnswer');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_redshop&task=question.edit&id=' . $this->detail->id) ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<div class="row">
		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DETAIL') ?></h3>
				</div>
				<div class="box-body">
					<?php echo $this->form->renderField('product_id') ?>
					<div class="form-group">
						<label class="control-label col-md-2"><?php echo JText::_('COM_REDSHOP_USER_NAME'); ?></label>
                        <div class="col-md-10">
                            <p><?php echo $this->detail->user_name ?></p>
						    <?php echo $this->form->getInput('user_name') ?>
                        </div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?php echo JText::_('COM_REDSHOP_USER_EMAIL'); ?></label>
						<div class="col-md-10"><?php echo $this->detail->user_email ?></div>
						<?php echo $this->form->getInput('user_email') ?>
					</div>
					<?php echo $this->form->renderField('telephone') ?>
					<?php echo $this->form->renderField('address') ?>
					<?php echo $this->form->renderField('published') ?>
					<?php echo $this->form->renderField('question') ?>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_PREVIOUS_ANSWERS') ?></h3>
				</div>
				<div class="box-body">
					<?php if (empty($this->answers)): ?>
						<div class="alert alert-info">
							<?php echo JText::_('COM_REDSHOP_QUESTION_NO_ANSWERS') ?>
						</div>
					<?php else: ?>
						<?php
						$k = 0;
						$i = 0;
						?>
						<div id="answerlists">
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
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_ANSWERS') ?></h3>
				</div>
				<div class="box-body">
					<?php echo $this->form->renderField('answer') ?>
				</div>
			</div>
		</div>
	</div>

    <input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('id') ?>
	<?php echo JHtml::_('form.token'); ?>
</form>
