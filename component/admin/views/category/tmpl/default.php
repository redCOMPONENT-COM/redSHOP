<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$option = JRequest::getVar('option', '', 'request', 'string');
$model = $this->getModel('category');
$category_main_filter = JRequest::getVar('category_main_filter');
$ordering = ($this->lists['order'] == 'c.ordering');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'saveorder') || (pressbutton == 'orderup') || (pressbutton == 'orderdown') || (pressbutton == 'copy')) {
			form.view.value = "category_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		if (pressbutton == 'remove') {
			var r = confirm('<?php echo JText::_("COM_REDSHOP_DELETE_CATEGORY")?>');
			if (r == true)    form.submit();
			else return false;
		}
		form.submit();
	}

	function AssignTemplate() {

		var form = document.adminForm;


		var templatevalue = document.getElementById('category_template').value;

		if (form.boxchecked.value == 0) {

			document.getElementById('category_template').value = 0;
			form.category_template.value = 0;
			alert('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_CATEGORY');?>');

		} else {

			form.task.value = 'assignTemplate';

			if (confirm("<?php echo JText::_('COM_REDSHOP_SURE_WANT_TO_ASSIGN_TEMPLATE');?>")) {

				//form.product_template.value = templatevalue;
				form.submit();
			} else {

				document.getElementById('category_template').value = 0;
				form.category_template.value = 0;
				return false;
			}
		}

	}
</script>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
		<tr>
			<td valign="top" align="left" class="key" width="30%">
				<?php echo JText::_('COM_REDSHOP_CATEGORY_FILTER'); ?>:
				<input type="text" name="category_main_filter" id="category_main_filter"
				       value="<?php echo $category_main_filter; ?>" onchange="document.adminForm.submit();">
			</td>
			<td width="">
				<button onclick="document.adminForm.submit();"><?php echo JText::_('COM_REDSHOP_SEARCH'); ?></button>
			</td>
			<td align="right">
				<?php echo JText::_('COM_REDSHOP_ASSIGN_TEMPLATE'); ?>:
				<?php echo $this->lists['category_template'];?>
			</td>
			<td valign="top" align="right" width="250">
				<?php echo JText::_('COM_REDSHOP_CATEGORY'); ?>:
				<?php echo $this->lists['category']; ?>
			</td>
		</tr>
	</table>
	<div id="editcell">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="20">
					<input type="checkbox" name="toggle" value=""
					       onclick="checkAll(<?php echo count($this->categories); ?>);"/>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CATEGORY_NAME', 'category_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CATEGORY_DESCRIPTION', 'category_description', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th><?php echo JText::_('COM_REDSHOP_PRODUCTS'); ?></th>
				<th width="15%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'c.ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
					<?php  if ($ordering) echo JHTML::_('grid.order', $this->categories); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'category_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->categories); $i < $n; $i++)
			{
				$row = & $this->categories[$i];
				if (!is_object($row))
				{
					break;
				}
				$row->id = $row->category_id;
				$link = JRoute::_('index.php?option=' . $option . '&view=category_detail&task=edit&cid[]=' . $row->category_id);
				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td>
						<?php
						if (property_exists($row, 'treename') && $row->treename != "")
						{
							?>
							<a href="<?php echo $link; ?>"
							   title="<?php echo JText::_('COM_REDSHOP_EDIT_CATEGORY'); ?>"><?php echo $row->treename; ?></a>
						<?php
						}
						else
						{
							?>
							<a href="<?php echo $link; ?>"
							   title="<?php echo JText::_('COM_REDSHOP_EDIT_CATEGORY'); ?>"><?php echo $row->category_name; ?></a>
						<?php
						}
						?>
					</td>
					<td><?php    $shortdesc = substr(strip_tags($row->category_description), 0, 50);echo $shortdesc; ?></td>
					<td align="center" width="5%"><?php echo $model->getProducts($row->category_id); ?></td>
					<td class="order">
						<span><?php echo $row->orderup = $this->pagination->orderUpIcon($i, ($row->category_parent_id == @$this->categories[$i - 1]->category_parent_id), 'orderup', JText::_('JLIB_HTML_MOVE_UP'), 1); ?></span>
						<span><?php echo $row->orderdown = $this->pagination->orderDownIcon($i, $n, ($row->category_parent_id == @$this->categories[$i + 1]->category_parent_id), 'orderdown', JText::_('JLIB_HTML_MOVE_DOWN'), 1); ?></span>
						<?php $ordering ? $disable = '' : $disable = 'disabled="disabled"';    ?>
						<input type="text" name="order[]" size="5"
						       value="<?php echo $row->ordering; ?>"  <?php echo $disable;?> class="text_area"
						       style="text-align: center"/>
					</td>
					<td align="center" width="8%"><?php echo $published;?></td>
					<td align="center" width="5%"><?php echo $row->category_id; ?></td>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
			<tfoot>
			<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="category"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
