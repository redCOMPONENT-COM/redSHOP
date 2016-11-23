<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


$model = $this->getModel('category');
$category_main_filter = JRequest::getVar('category_main_filter');
$ordering = ($this->lists['order'] == 'c.ordering');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitform = submitform = Joomla.submitbutton = submitbutton = function (pressbutton) {
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

	function resetFilter() {
		document.getElementById('category_main_filter').value = '';
		document.getElementById('category_template').value = 0;
		document.getElementById('category_id').value = 0;
	}

</script>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">
				<div class="btn-wrapper input-append">
					<input type="text" name="category_main_filter" id="category_main_filter" placeholder="<?php echo JText::_("COM_REDSHOP_CATEGORY_FILTER") ?>"
						   value="<?php echo $category_main_filter; ?>" onchange="document.adminForm.submit();">

					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="button" class="btn reset" onclick="resetFilter();this.form.submit();" value="<?php echo JText::_('COM_REDSHOP_RESET');?>"/>
				</div>
			</div>
			<div class="filterItem">
				<?php echo $this->lists['category_template'];?>
			</div>
			<div class="filterItem">
				<?php echo $this->lists['category']; ?>
			</div>
		</div>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="20">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
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
				$row = $this->categories[$i];
				if (!is_object($row))
				{
					break;
				}
				$row->id = $row->category_id;
				$link = JRoute::_('index.php?option=com_redshop&view=category_detail&task=edit&cid[]=' . $row->category_id);
				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);
				?>
				<tr class="<?php echo "row$k"; ?> ">
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td>
						<?php if (property_exists($row, 'treename') && $row->treename != ""): ?>
							<?php echo $row->indent ?>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_CATEGORY'); ?>">
								<?php echo $row->category_name ?>
							</a>
						<?php else: ?>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_CATEGORY'); ?>">
								<?php echo $row->category_name; ?>
							</a>
						<?php endif; ?>
					</td>
					<td>
						<?php echo substr(strip_tags($row->category_description), 0, 50); ?>
					</td>
					<td align="center" width="5%">
						<?php echo $model->getProducts($row->category_id); ?>
					</td>
					<td class="order">
						<?php if ($ordering) :
							$orderDir = strtoupper($this->lists['order_Dir']);
							?>
							<div class="input-prepend">
								<?php if ($orderDir == 'ASC' || $orderDir == '') : ?>
									<span class="add-on">
										<?php echo $this->pagination->orderUpIcon($i, ($row->category_parent_id == @$this->categories[$i - 1]->category_parent_id), 'orderup'); ?>
									</span>
									<span class="add-on">
										<?php echo $this->pagination->orderDownIcon($i, $n, ($row->category_parent_id == @$this->categories[$i + 1]->category_parent_id), 'orderdown'); ?>
									</span>
								<?php elseif ($orderDir == 'DESC') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, ($row->category_parent_id == @$this->categories[$i - 1]->category_parent_id), 'orderdown'); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, ($row->category_parent_id == @$this->categories[$i + 1]->category_parent_id), 'orderup'); ?></span>
								<?php endif; ?>
								<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="width-20 text-area-order" />
							</div>
						<?php else : ?>
							<?php echo $row->ordering; ?>
						<?php endif; ?>
					</td>
					<td align="center" width="8%"><?php echo $published;?></td>
					<td align="center" width="5%"><?php echo $row->category_id; ?></td>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
			<tfoot>
			<td colspan="9">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->pagination->getListFooter(); ?></td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="category"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
