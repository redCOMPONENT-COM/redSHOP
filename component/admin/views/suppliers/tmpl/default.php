<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form 
	action="index.php?option=com_redshop&view=suppliers" 
	class="admin" 
	id="adminForm" 
	method="post" 
	name="adminForm">
	<div class="filterTool">
		<?php
		echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view' => $this,
				'options' => array(
					'searchField' => 'search',
					'filtersHidden' => false,
					'filterButton' => false,
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector' => '#list_users_limit',
					'activeOrder' => $listOrder,
					'activeDirection' => $listDirn,
					'showFilter' => false,
					'showListNumber' => false
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
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%">
					<?php
					echo JText::_('COM_REDSHOP_NUM');
					?>
				</th>
				<th width="5%"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
				<th class="title">
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_SUPPLIER_NAME', 'supplier_name', $listOrder, $listDirn);
					?>

				</th>
				<th width="20%">
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_SUPPLIER_EMAIL', 'supplier_email', $listOrder, $listDirn);
					?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'id', $listOrder, $listDirn);
					?>
				</th>

			</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;

			for ($i = 0, $n = count($this->items); $i < $n; $i++)
			{
				$row = $this->items[$i];

				$row->id = $row->id;

				$link = JRoute::_('index.php?option=com_redshop&task=supplier.edit&id=' . $row->id);

				$published = JHTML::_('grid.published', $row, $i);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php
						echo $this->pagination->getRowOffset($i);
						?>
					</td>
					<td align="center">
						<?php
						echo JHTML::_('grid.id', $i, $row->id);
						?>
					</td>
					<td><a href="<?php
						echo $link;
						?>"
					       title="<?php
					       echo JText::_('COM_REDSHOP_EDIT_SUPPLIER');
					       ?>"><?php
							echo $row->supplier_name;
							?></a></td>
					<td>
						<?php
						echo $row->supplier_email;
						?>
					</td>
					<td align="center">
						<?php
						echo $published;
						?>
					</td>
					<td align="center">
						<?php
						echo $row->id;
						?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
			<tfoot>
			<td colspan="9">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php
				echo $this->pagination->getListFooter();
				?>
			</td>
			</tfoot>
		</table>
	</div>
	<?php endif; ?>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="view" value="suppliers"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
</form>
