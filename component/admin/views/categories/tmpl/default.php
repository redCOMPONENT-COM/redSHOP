<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$model = $this->getModel('categories');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = ($listOrder == 'c.ordering' && strtolower($listDirn) == 'asc');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitform = submitform = Joomla.submitbutton = submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		if (pressbutton == 'categories.delete') {
			var r = confirm('<?php echo JText::_("COM_REDSHOP_DELETE_CATEGORY")?>');
			if (r == true)    form.submit();
			else return false;
		}

		form.submit();
	}

	function AssignTemplate() {
		var form = jQuery('#adminForm');
		var templatevalue = jQuery('select#filter_category_template').val();
		var boxchecked = jQuery('input[name="boxchecked"]').val();
		if (boxchecked == 0) {
			jQuery('select#filter_category_template').val(0);
			alert('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_CATEGORY');?>');

		} else {
			jQuery('input[name="task"]').val('assignTemplate');

			if (confirm("<?php echo JText::_('COM_REDSHOP_SURE_WANT_TO_ASSIGN_TEMPLATE');?>")) {
				form.submit();
			} else {
				jQuery('select#filter_category_template').val(0);
				return false;
			}
		}
	}
</script>
<form
	action="<?php echo JRoute::_('index.php?option=com_redshop&view=categories'); ?>"
	method="post"
	name="adminForm"
	id="adminForm"
>
	<div class="filterTool">
		<?php
		echo JLayoutHelper::render(
			'joomla.searchtools.default',
			array(
				'view' => $this,
				'options' => array(
					'searchField' => 'search',
					'filtersHidden' => false,
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector' => '#list_limit',
					'activeOrder' => $listOrder,
					'activeDirection' => $listDirn,
				)
			)
		);
		?>
	</div>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<table class="table table-striped" id="articleList">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="20">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th width="1%" style="min-width:55px" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'c.published', $listDirn, $listOrder); ?>
					</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CATEGORY_NAME', 'c.category_name', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CATEGORY_DESCRIPTION', 'c.category_description', $listDirn, $listOrder); ?>
				</th>
				<th><?php echo JText::_('COM_REDSHOP_PRODUCTS'); ?></th>
				<th width="15%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'c.ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder) echo JHTML::_('grid.order', $this->items); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'c.category_id', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>
			<tbody>
				<?php $n = count($this->items); ?>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $item->category_id); ?>
						</td>
						<td class="center">
							<div class="btn-group">
								<?php echo JHtml::_('jgrid.published', $item->published, $i, 'categories.', true, 'cb'); ?>
							</div>
						</td>
						<td>
						<?php if (property_exists($item, 'treename') && $item->treename != "") :?>
							<a href="<?php echo JRoute::_('index.php?option=com_redshop&task=category.edit&category_id=' . $item->category_id); ?>"
							   title="<?php echo JText::_('COM_REDSHOP_EDIT_CATEGORY'); ?>"><?php echo $item->treename; ?></a>
						<?php else: ?>
							<a href="<?php echo JRoute::_('index.php?option=com_redshop&task=category.edit&category_id=' . $item->category_id); ?>"
							   title="<?php echo JText::_('COM_REDSHOP_EDIT_CATEGORY'); ?>"><?php echo $item->category_name; ?></a>
						<?php endif; ?>
					</td>
					<td><?php $shortDesc = substr(strip_tags($item->category_description), 0, 50); echo $shortDesc; ?></td>
					<td align="center"><?php echo $model->getProducts($item->category_id); ?></td>
					<td class="order">
						<?php if ($saveOrder) : ?>
							<div class="input-prepend">
								<?php if ($listDirn == 'ASC' || $listDirn == '') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, ($item->category_parent_id == @$this->items[$i - 1]->category_parent_id), 'orderUp'); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, ($item->category_parent_id == @$this->items[$i + 1]->category_parent_id), 'orderDown'); ?></span>
								<?php elseif ($listDirn == 'DESC') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, ($item->category_parent_id == @$this->items[$i - 1]->category_parent_id), 'orderDown'); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, ($item->category_parent_id == @$this->items[$i + 1]->category_parent_id), 'orderUp'); ?></span>
								<?php endif; ?>
								<input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
							</div>
						<?php else : ?>
							<?php echo $item->ordering; ?>
						<?php endif; ?>
					</td>
					<td align="center" width="5%"><?php echo $item->category_id; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<td colspan="14">
					<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
						<div class="redShopLimitBox">
							<?php echo $this->pagination->getLimitBox(); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tfoot>
		</table>
	<?php endif; ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
