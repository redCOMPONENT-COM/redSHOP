<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$producthelper = productHelper::getInstance();
$config        = Redconfiguration::getInstance();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$ordering  = ($this->ordering == 'q.ordering');
?>
<form
	action="index.php?option=com_redshop&view=questions"
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
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%" class="title">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title" width="15%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'p.product_name', $listDirn, $listOrder); ?></th>
				<th class="title" width="50%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUESTION', 'q.question', $listDirn, $listOrder); ?></th>
				<th class="title" width="5%">
					<?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></th>
				<th class="title" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USER_NAME', 'q.user_name', $listDirn, $listOrder); ?></th>
				<th class="title" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USER_EMAIL', 'q.user_email', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'q.ordering', $listDirn, $listOrder); ?>
					<?php  if ($ordering) echo JHTML::_('grid.order', $this->items); ?>
				</th>
				<th class="title" width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'q.published', $listDirn, $listOrder); ?></th>
				<th width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'id', $listDirn, $listOrder); ?></th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->items); $i < $n; $i++)
			{
				$row       = $this->items[$i];
				$link      = JRoute::_('index.php?option=com_redshop&task=question.edit&id=' . $row->id);
				$anslink   = JRoute::_('index.php?option=com_redshop&task=question.edit&id=' . $row->id . '#answerlists');

				$answer    = $producthelper->getQuestionAnswer($row->id, 0, 1);
				$answer    = count($answer);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);
			?>
				<tr class="<?php echo "row$k"; ?>">
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
						<?php if ($ordering) :
							$orderDir = strtoupper($listDirn);
							?>
							<div class="input-prepend">
								<?php if ($orderDir == 'ASC' || $orderDir == '') : ?>
									<span class="add-on">
										<?php echo $this->pagination->orderUpIcon($i, ($row->parent_id == @$this->items[$i - 1]->parent_id), 'orderup'); ?>
									</span>
									<span class="add-on">
										<?php echo $this->pagination->orderDownIcon($i, $n, ($row->parent_id == @$this->items[$i + 1]->parent_id), 'orderdown'); ?>
									</span>
								<?php elseif ($orderDir == 'DESC') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, ($row->parent_id == @$this->items[$i - 1]->parent_id), 'orderdown'); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, ($row->parent_id == @$this->items[$i + 1]->parent_id), 'orderup'); ?></span>
								<?php endif; ?>
								<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="width-20 text-area-order" />
							</div>
						<?php else : ?>
							<?php echo $row->ordering; ?>
						<?php endif; ?>
					</td>
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
	<?php endif; ?>
	</div>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="view" value="questions"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
</form>
