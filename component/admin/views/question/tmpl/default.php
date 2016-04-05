<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$producthelper = producthelper::getInstance();
$config        = Redconfiguration::getInstance();

$lists         = $this->lists;
$ordering      = ($this->lists['order'] == 'ordering');

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'remove')
			|| (pressbutton == 'saveorder') || (pressbutton == 'orderup') || (pressbutton == 'orderdown')) {
			form.view.value = "question_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>

<form action="<?php echo JRoute::_($this->request_url); ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterItem">
			<div class="btn-wrapper input-append">
				<input type="text" name="filter" id="filter" value="<?php echo $this->state->get('filter'); ?>"
					   onchange="document.adminForm.submit();" placeholder="<?php echo JText::_('COM_REDSHOP_FILTER'); ?>">
				<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
				<button class="btn"
					onclick="document.getElementById('filter').value='';document.getElementById('product_id').value='0';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET'); ?></button>
			</div>
		</div>
		<div class="filterItem">
			<?php echo JText::_('COM_REDSHOP_PRODUCT_NAME') . ": " . $this->lists['product_id']; ?>
		</div>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="5%" class="title">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title" width="15%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'p.product_name', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th class="title" width="50%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUESTION', 'question', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th class="title" width="5%">
					<?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></th>
				<th class="title" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USER_NAME', 'user_name', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th class="title" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USER_EMAIL', 'user_email', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th class="order" width="10%">
					<?php  echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
					<?php
					if ($ordering)
					{
						echo JHTML::_('grid.order', $this->question);
					}
					?>
				</th>
				<th class="title" width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'question_id', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			</tr>
			</thead>
			<?php
			$k = 0;

			for ($i = 0, $n = count($this->question); $i < $n; $i++)
			{
				$row       = $this->question[$i];
				$row->id   = $row->question_id;
				$link      = JRoute::_('index.php?option=com_redshop&view=question_detail&task=edit&cid[]=' . $row->id);
				$anslink   = JRoute::_('index.php?option=com_redshop&view=question_detail&task=edit&cid[]=' . $row->id . '#answerlists');

				$answer    = $producthelper->getQuestionAnswer($row->id, 0, 1);
				$answer    = count($answer);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td align="center">
						<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REDSHOP_VIEW_QUESTION'); ?>"><?php echo $row->product_name; ?></a>
					</td>
					<td><?php
						if (strlen($row->question) > 50)
						{
							echo substr($row->question, 0, 50) . "...";
						}
						else
						{
							echo $row->question;
						}?></td>
					<td align="center"><a href="<?php echo $anslink; ?>">( <?php echo $answer; ?> )</a></td>
					<td><?php echo $row->user_name; ?></td>
					<td><?php echo $row->user_email; ?></td>
					<td class="order">
						<span><?php echo $this->pagination->orderUpIcon($i, true, 'orderup', JText::_('JLIB_HTML_MOVE_UP'), $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderdown', JText::_('JLIB_HTML_MOVE_DOWN'), $ordering); ?></span>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>"
						       class="text_area input-small" style="text-align: center" <?php if (!$ordering)
						{ ?> disabled="disabled"<?php }?> /></td>
					<td align="center"><?php echo $published;?></td>
					<td align="center"><?php echo $row->id; ?></td>
				</tr>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
			<tfoot>
				<td colspan="10">
					<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
						<div class="redShopLimitBox">
							<?php echo $this->pagination->getLimitBox(); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->pagination->getListFooter(); ?></td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="question"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
