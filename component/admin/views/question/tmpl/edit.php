<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$editor = JFactory::getEditor();
?>
<script language="javascript" type="text/javascript">
	function deleteanswer() {
		submitform('removeanswer');
	}

	function sendanswer() {
		submitform('sendanswer');
	}
</script>
<form action="<?php echo JRoute::_($this->requestUrl) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<table class="admintable table">
				<tr>
					<td>
						<?php echo $this->form->renderField('product_id') ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_NAME'); ?>:</td>
					<td><?php echo $this->detail->user_name; ?>
						<?php echo $this->form->renderField('user_name') ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_EMAIL'); ?>:</td>
					<td><?php echo $this->detail->user_email; ?>
						<?php echo $this->form->renderField('user_email') ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $this->form->renderField('telephone') ?></td>
				</tr>
				<tr>
					<td><?php echo $this->form->renderField('address') ?>
					</td>
				</tr>

				<tr>
					<td><?php echo $this->form->renderField('published') ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_QUESTION'); ?></legend>
			<table class="admintable table">
				<tr>
					<td><?php echo $this->form->renderField('question') ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php
		$k = 0;
		$i = 0;
	?>
	<div class="col50" id='answerlists'>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_PREVIOUS_ANSWERS'); ?></legend>
			<table class="adminlist table table-striped">
				<thead>
				<tr class="row<?php echo $k; ?>">
					<th class="title">#</th>
					<th class="title"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_USER_NAME'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_USER_EMAIL'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_TIME') ?></th>
				</tr>
				</thead>
				<?php if (count($this->answers) > 0) : ?>

			<?php foreach ($this->answers as $answer) : ?>
				<tr class="row<?php echo $k; ?>">
					<td align="center"><?php echo $i + 1; ?></td>
					<td class="order"
					    width="5%"><?php echo JHTML::_('grid.id', $i, $answer->id, false, 'aid'); ?></td>
					<td><?php echo $answer->question; ?></td>
					<td><?php echo $answer->user_name; ?></td>
					<td><?php echo $answer->user_email; ?></td>
					<td align="center"><?php echo date("M d Y, h:i:s A", $answer->question_date); ?></td>
				</tr>
				<?php
					$i++;
					$k = 1 - $k;
				?>
			<?php endforeach; ?>
				<tr>
					<td colspan="6">
						<input type="button" name="btn_delete" id="btn_delete"
						       value="<?php echo JText::_('COM_REDSHOP_DELETE') ?>" onclick="deleteanswer();"/>
						<input type="button" name="btn_send" id="btn_send"
						       value="<?php echo JText::_('COM_REDSHOP_SEND') ?>" onclick="sendanswer();"/></td>
				</tr>
			<?php endif; ?>
			</table>
		</fieldset>
	</div>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></legend>
			<table class="admintable table">
				<tr>
					<td>
						<?php echo $this->form->renderField('answer') ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="id" value="<?php echo $this->detail->id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="questions"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
