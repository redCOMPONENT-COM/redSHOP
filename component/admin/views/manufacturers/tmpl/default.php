<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'm.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_redshop&task=manufacturers.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'manufacturerList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

?>
<form action="<?php JRoute::_('index.php?option=com_redshop&view=manufacturers'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else: ?>
			<table class="adminlist table table-striped" id="manufacturerList">
				<thead>
				<tr>
					<!-- No. -->
					<th width="5" class="hidden-phone">
						<?php
						echo JText::_('COM_REDSHOP_NUM');
						?>
					</th>
					<!-- Check all -->
					<th width="1%" class="center"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
					<!-- Manufacturer name -->
					<th class="title">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MANUFACTURER_NAME', 'manufacturer_name', $listDirn, $listOrder); ?>
					</th>
					<!-- Media box -->
					<th>
						<?php echo JText::_('COM_REDSHOP_MEDIA'); ?>
					</th>
					<!-- Description -->
					<th>
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MANUFACTURER_DESCRIPTION', 'manufacturer_desc', $listDirn, $listOrder); ?>
					</th>
					<!-- Ordering -->
					<th class="order" width="20%">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'm.ordering', $listDirn, $listOrder); ?>
					</th>
					<!-- Published -->
					<th width="5%" nowrap="nowrap">
						<?php
						echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'm.published', $listDirn, $listOrder);
						?>
					</th>
					<!-- ID -->
					<th width="5%" nowrap="nowrap">
						<?php
						echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'm.manufacturer_id', $listDirn, $listOrder);
						?>
					</th>
				</tr>
				</thead>
				<?php foreach ($this->items as $i => $item) : ?>
					<?php
					$item->max_ordering = 0;
					$ordering           = ($listOrder == 'm.ordering');
					$canCreate          = true;
					$canEdit            = true;
					$canCheckin         = true;
					$canEditOwn         = true;
					$canChange          = true;
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<!-- No. -->
						<td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<!-- Checkbox -->
						<td>
							<?php
							echo JHTML::_('grid.id', $i, $item->manufacturer_id);
							?>
						</td>
						<!-- Manufacturer name -->
						<td width="50%">
							<a href="<?php echo JText::_('index.php?option=com_redshop&view=manufacturer&id=' . $item->manufacturer_id); ?>"
							   title="<?php echo JText::_('COM_REDSHOP_EDIT_MANUFACTURER'); ?>">
								<?php echo trim($item->manufacturer_name); ?>
							</a>
						</td>
						<!-- Media box -->
						<td align="center">
							<?php $media_id = $this->getModel()->getMediaId($item->manufacturer_id); ?>
							<a class="modal"
							   href="index.php?tmpl=component&option=com_redshop&amp;view=media_detail&amp;cid[]=<?php echo $media_id; ?>&amp;section_id=<?php echo $item->manufacturer_id; ?>&amp;showbuttons=1&amp;media_section=manufacturer&amp;section_name=<?php echo $item->manufacturer_name; ?>"
							   rel="{handler: 'iframe', size: {x: 1050, y: 450}}" title=""><img
									src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
									alt="media">
							</a>
						</td>
						<!-- Description -->
						<td width="30%">
							<?php echo mb_substr(strip_tags($item->manufacturer_desc), 0, 50); ?>
						</td>
						<td class="order nowrap center hidden-phone">
							<?php
							$iconClass = '';
							if (!$canChange)
							{
								$iconClass = ' inactive';
							}
							elseif (!$saveOrder)
							{
								$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
							}
							?>
							<span class="sortable-handler<?php echo $iconClass ?>">
								<span class="icon-menu"></span>
							</span>
							<?php if ($canChange && $saveOrder) : ?>
								<input type="text" style="display:none" name="order[]" size="5"
								       value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
							<?php endif; ?>
						</td>
						<!-- Published -->
						<td align="center" width="5%">
							<?php $canChange = true; ?>
							<?php echo JHtml::_('jgrid.published', $item->published, $i, 'manufacturers.', $canChange, 'cb'); ?>
						</td>
						<td align="center" width="5%">
							<?php
							echo $item->manufacturer_id;
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>
	<fieldset>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
</form>
