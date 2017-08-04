<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

// Create product Helper object
$productHelper = productHelper::getInstance();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

?>
<form
	action="<?php echo JRoute::_('index.php?option=com_redshop&view=giftcards'); ?>"
	method="post"
	name="adminForm"
	id="adminForm"
>
	<div class="filterTool">
		<div class="filterItem">
			<div class="btn-wrapper input-append">
				<input
						type="text"
						name="filter_search"
						id="filter_search"
						placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
						value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
						class="hasTooltip"
						title="<?php echo JHtml::tooltipText('COM_REDSHOP_GIFTCARD_SEARCH_IN_NAME'); ?>"
					/>
				<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
				<input type="button" class="btn reset" onclick="document.getElementById('filter_search').value='';this.form.submit();" value="<?php echo JText::_('COM_REDSHOP_RESET');?>"/>
			</div>
		</div>
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
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_GIFTCARD_NAME', 'giftcard_name', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="hidden-phone">
						<?php echo JText::_('COM_REDSHOP_GIFTCARD_IMAGE'); ?>
					</th>
					<th width="15%" class="hidden-phone">
						<?php echo JText::_('COM_REDSHOP_GIFTCARD_BGIMAGE'); ?>
					</th>
					<th width="10%" class="center">
						<?php echo JHtml::_('grid.sort',  'COM_REDSHOP_GIFTCARD_PRICE', 'giftcard_price', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="center">
						<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_GIFTCARD_VALUE', 'giftcard_value', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_GIFTCARD_VALIDITY', 'giftcard_validity', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'giftcard_id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $item->giftcard_id); ?>
						</td>
						<td class="center">
							<div class="btn-group">
								<?php echo JHtml::_('jgrid.published', $item->published, $i, 'giftcards.', true, 'cb'); ?>
							</div>
						</td>
						<td class="has-context">
							<div class="pull-left break-word">
								<a
									class="hasTooltip"
									href="<?php echo JRoute::_('index.php?option=com_redshop&task=giftcard.edit&giftcard_id=' . $item->giftcard_id); ?>"
									title="<?php echo JText::_('JACTION_EDIT'); ?>"
								>
								<?php echo $this->escape($item->giftcard_name); ?>
								</a>

							</div>
						</td>
						<td class="small hidden-phone">
							<?php $giftCardPath = 'giftcard/' . $item->giftcard_image; ?>

							<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $giftCardPath)) : ?>
								<?php
									$giftCardImagePath = RedShopHelperImages::getImagePath(
										$item->giftcard_image,
										'',
										'thumb',
										'giftcard',
										150,
										150,
										Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
									);
								?>
								<a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $giftCardPath; ?>"
									title="<?php echo JText::_('COM_REDSHOP_VIEW_IMAGE'); ?>" rel="{handler: 'image', size: {}}">
									<img src="<?php echo $giftCardImagePath;?>" class="img-polaroid">
								</a>
							<?php endif; ?>
						</td>
						<td class="small hidden-phone">
							<?php $giftCardPath = 'giftcard/' . $item->giftcard_bgimage; ?>

							<?php if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $giftCardPath)) : ?>
								<?php
									$giftCardImagePath = RedShopHelperImages::getImagePath(
										$item->giftcard_bgimage,
										'',
										'thumb',
										'giftcard',
										150,
										150,
										Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
									);
								?>
								<a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $giftCardPath; ?>"
									title="<?php echo JText::_('COM_REDSHOP_VIEW_IMAGE'); ?>" rel="{handler: 'image', size: {}}">
									<img src="<?php echo $giftCardImagePath;?>" class="img-polaroid">
								</a>
							<?php endif; ?>
						</td>

						<td class="center">
							<?php echo $productHelper->getProductFormattedPrice($item->giftcard_price);?>
						</td>
						<td class="center">
							<?php echo $productHelper->getProductFormattedPrice($item->giftcard_value);?>
						</td>
						<td class="center hidden-phone">
							<?php echo $item->giftcard_validity;?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->giftcard_id; ?>
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

	<?php echo $this->pagination->getListFooter(); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
