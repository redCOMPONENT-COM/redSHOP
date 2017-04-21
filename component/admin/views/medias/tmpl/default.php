<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form
	action="index.php?option=com_redshop&view=countries"
	class="admin"
	id="adminForm"
	method="post"
	name="adminForm">
	<div class="filterTool">
		<?php echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view' => $this,
				'options' => array(
					'searchField' => 'search',
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector' => '#list_users_limit',
					'activeOrder' => $listOrder,
					'activeDirection' => $listDirn,
				)
			)
		) ?>
	</div>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
	<table class="adminlist table table-striped table-hover">
		<thead>
		<tr>
			<th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
			<th width="10">
				<?php echo JHtml::_('redshopgrid.checkall'); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_NAME', 'media_name', $listDirn, $listOrder); ?>
			<th><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_ALTERNATE_TEXT', 'media_alternate_text', $listDirn, $listOrder); ?>
			<th><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_SECTION', 'media_section', $listDirn, $listOrder); ?>
			<th><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_TYPE', 'media_TYPE', $listDirn, $listOrder); ?>
			<th width="1"><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_ID', 'id', $listDirn, $listOrder); ?>
		</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;

		for ($i = 0, $n = count($this->items); $i < $n; $i++)
		{
			$row  = $this->items[$i];
			$link = JRoute::_('index.php?option=com_redshop&task=media.edit&id=' . $row->id);

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $this->pagination->getRowOffset($i); ?></td>
				<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
				<td><a href="<?php echo $link; ?>"
					   title="<?php echo JText::_('COM_REDSHOP_EDIT_MEDIA'); ?>"><?php echo $row->name ?></a>
				</td>
				<td align="center" width="10%"><?php echo $row->alternate_text ?></td>
				<td align="center" width="10%"><?php echo $row->section ?></td>
				<td align="center" width="10%"><?php echo $row->type ?></td>
				<td align="right"><?php echo $row->id ?></td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</tbody>
		<tfoot>
		<td colspan="9">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
		</tfoot>
	</table>
	<?php endif; ?>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="view" value="medias"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>


