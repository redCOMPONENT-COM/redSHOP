<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$model        = $this->getModel('categories');
$user         = JFactory::getUser();
$userId       = $user->id;
$saveOrderUrl = 'index.php?option=com_redshop&task=categories.saveOrderAjax&tmpl=component';
$listOrder    = $this->state->get('list.ordering');
$listDirn     = $this->state->get('list.direction');
$saveOrder    = ($listOrder == 'c.lft' && strtolower($listDirn) == 'asc');
$search       = $this->state->get('filter.search');

if (($saveOrder) && ($this->canEditState))
{
	JHTML::_('sortablelist.sortable', 'table-categories', 'adminForm', strtolower($listDirn), $saveOrderUrl, false, true);
}
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
		<table class="table table-striped"  id="table-categories">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="20">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<?php if (($search == '') && ($this->canEditState)) : ?>
				<th width="40" class="center">
					<?php echo JHtml::_('grid.sort', '', 'c.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
				</th>
				<?php endif; ?>
				<th width="1%" style="min-width:55px" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'c.published', $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CATEGORY_NAME', 'c.name', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CATEGORY_DESCRIPTION', 'c.description', $listDirn, $listOrder); ?>
				</th>
				<th><?php echo JText::_('COM_REDSHOP_PRODUCTS'); ?></th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'c.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>
			<tbody>
				<?php $n = count($this->items); ?>
				<?php foreach ($this->items as $i => $item) : ?>
					<?php $orderkey = array_search($item->id, $this->ordering[$item->parent_id]); ?>
					<?php if ($item->level > 1) : ?>
						<?php
						$parentsStr = '';
						$_currentParentId = $item->parent_id;
						$parentsStr = ' ' . $_currentParentId;
						?>
						<?php for ($i2 = 0; $i2 < $item->level; $i2++) : ?>
							<?php foreach ($this->ordering as $k => $v) : ?>
								<?php
								$v = implode('-', $v);
								$v = '-' . $v . '-';
								?>
								<?php if (strpos($v, '-' . $_currentParentId . '-') !== false) : ?>
									<?php
									$parentsStr .= ' ' . $k;
									$_currentParentId = $k;
									break;
									?>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endfor; ?>
					<?php else : ?>
						<?php $parentsStr = 0; ?>
					<?php endif; ?>
					<tr sortable-group-id="<?php echo $item->parent_id;?>" item-id="<?php echo $item->id?>" parents="<?php echo $parentsStr?>" level="<?php echo $item->level?>">
						<td class="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<?php if (($search == '') && ($this->canEditState)) : ?>
						<td class="order nowrap center">
							<span class="sortable-handler hasTooltip <?php echo ($saveOrder) ? '' : 'inactive'; ?>">
								<i class="icon-move"></i>
							</span>
							<input type="text" style="display:none" name="order[]" value="<?php echo $orderkey + 1;?>" class="text-area-order" />
						</td>
						<?php endif; ?>
						<td class="center">
							<?php echo JHtml::_('jgrid.published', $item->published, $i, 'categories.', true, 'cb'); ?>
						</td>
						<td>
						<?php if ($item->checked_out): ?>
							<?php
							$author = JFactory::getUser($item->checked_out);
							$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
							echo JHtml::_('jgrid.checkedout', $i, $item->checked_out, $item->checked_out_time, 'categories.', $canCheckin);
							?>
						<?php endif; ?>
						<?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level - 1) ?>
						<?php if ($item->checked_out && $userId != $item->checked_out): ?>
							<?php echo $this->escape($item->title); ?>
						<?php else : ?>
							<?php echo JHtml::_('link', 'index.php?option=com_redshop&task=category.edit&id=' . $item->id, $this->escape($item->title)); ?>
						<?php endif; ?>
					</td>
					<td><?php $shortDesc = substr(strip_tags($item->description), 0, 50); echo $shortDesc; ?></td>
					<td align="center"><?php echo $model->getProducts($item->id); ?></td>
					<td align="center" width="5%"><?php echo $item->id; ?></td>
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
