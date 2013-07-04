<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';

$producthelper = new producthelper;
$config        = new Redconfiguration;

$filter        = JRequest::getVar('filter');
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
			|| (pressbutton == 'publish') || (pressbutton == 'unpublish')
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
		<table class="adminlist" width="100%">
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_REDSHOP_FILTER'); ?>:
					<input type="text" name="filter" id="filter" value="<?php echo $filter; ?>"
					       onchange="document.adminForm.submit();">
					<?php echo JText::_('COM_REDSHOP_PRODUCT_NAME') . ": " . $this->lists['product_id']; ?>
					<button
						onclick="document.getElementById('filter').value='';document.getElementById('product_id').value='0';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET'); ?></button>
				</td>
			</tr>
		</table>
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="5%" class="title">
					<input type="checkbox" name="toggle" value=""
					       onclick="checkAll(<?php echo count($this->question); ?>);"/></th>
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
				$row       = & $this->question[$i];
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
						       class="text_area" style="text-align: center" <?php if (!$ordering)
						{ ?> disabled="disabled"<?php }?> /></td>
					<td align="center"><?php echo $published;?></td>
					<td align="center"><?php echo $row->id; ?></td>
				</tr>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
			<tr>
				<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
		</table>
	</div>

	<input type="hidden" name="view" value="question"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
