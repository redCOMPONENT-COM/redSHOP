<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form
	action="<?php echo JRoute::_('index.php?option=com_redshop&view=attributes'); ?>"
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
					'limitFieldSelector' => '#list_users_limit',
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
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="1%" style="min-width:55px" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.attribute_published', $listDirn, $listOrder); ?>
					</th>
					<th width="20%">
						<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_ATTRIBUTE_NAME', 'a.attribute_name', $listDirn, $listOrder); ?>
					</th>
					<th width="20%">
						<?php echo JHtml::_('grid.sort',  'COM_REDSHOP_PRODUCT_NAME_LBL', 'p.product_name', $listDirn, $listOrder); ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_ATTRIBUTE_DISPLAY_TYPE', 'a.display_type', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'attribute_id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $item->attribute_id); ?>
						</td>
						<td class="center">
							<div class="btn-group">
								<?php echo JHtml::_('jgrid.published', $item->attribute_published, $i, 'attributes.', true, 'cb'); ?>
							</div>
						</td>
						<td class="has-context">
							<div class="pull-left break-word">
								<a
									class="hasTooltip"
									href="<?php echo JRoute::_('index.php?option=com_redshop&task=attribute.edit&attribute_id=' . $item->attribute_id); ?>"
									title="<?php echo JText::_('JACTION_EDIT'); ?>"
								>
								<?php echo $this->escape($item->attribute_name); ?>
								</a>

							</div>
						</td>
						<td>
							<?php echo $item->product_name;?>
						</td>
						<td>
							<?php echo $item->display_type;?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->attribute_id; ?>
						</td>
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
	<?php echo JHtml::_('form.token'); ?>
</form>
