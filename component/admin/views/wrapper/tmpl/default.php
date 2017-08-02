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

$producthelper = productHelper::getInstance();

$showall = JFactory::getApplication()->input->get('showall', '0');
$tmpl = '';

$uri = JURI::getInstance();
$url = $uri->root(); ?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton) {
            form.task.value = pressbutton;
        }
        if ((pressbutton == 'add') || (pressbutton == 'edit')) {
            form.view.value = "wrapper_detail";
        }
        try {
            form.onsubmit();
        }
        catch (e) {
        }
        form.submit();
    }
</script>
<?php if ($showall)
{
	$tmpl = '&tmpl=component'; ?>
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="Joomla.submitbutton('add');">
				<?php echo JText::_('COM_REDSHOP_ADD'); ?>
			</button>
			<button type="button" onclick="Joomla.submitbutton('edit');">
				<?php echo JText::_('COM_REDSHOP_EDIT'); ?>
			</button>
			<button type="button" onclick="Joomla.submitbutton('remove');">
				<?php echo JText::_('COM_REDSHOP_DELETE'); ?>
			</button>
			<button type="button" onclick="window.parent.location.reload();">
				<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
			</button>
		</div>
		<div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_WRAPPER'); ?></div>
	</fieldset>
<?php } ?>
<form action="<?php echo 'index.php?option=com_redshop' . $tmpl; ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">
				<div class="btn-wrapper input-append">
					<input type="text" name="filter" id="filter" value="<?php echo $this->filter; ?>"
					       placeholder="<?php echo JText::_('COM_REDSHOP_WRAPPER_FILTER'); ?>">
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="reset" class="btn reset" name="reset" id="reset"
					       value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"
					       onclick="document.getElementById('filter').value='';this.form.submit();"/>
				</div>
			</div>
		</div>

		<table class="adminlist table table-striped" width="100%">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="5%"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
				<th width="20%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_WRAPPER_NAME', 'w.wrapper_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_WRAPPER_IMAGE'); ?></th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_WRAPPER_PRICE', 'w.wrapper_price', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USE_TO_ALL_PRODUCT', 'w.wrapper_use_to_all', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'w.published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'w.wrapper_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
			</tr>
			</thead>
			<?php $k = 0;
			for ($i = 0; $i < count($this->data); $i++)
			{
				$row            = $this->data[$i];
				$row->id        = $row->wrapper_id;
				$published      = JHtml::_('jgrid.published', $row->published, $i, '', 1);
				$row->published = $row->wrapper_use_to_all;
				$enable_default = JHTML::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'enable_default');

				$link = JRoute::_('index.php?option=com_redshop&view=wrapper_detail&task=edit&product_id=' . $this->product_id . '&cid[]=' . $row->wrapper_id . $tmpl . '&showall=' . $showall); ?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td><a href="<?php echo $link; ?>"
					       title="<?php echo JText::_('COM_REDSHOP_EDIT_WRAPPER'); ?>"><?php echo $row->wrapper_name; ?></a>
					</td>
					<td>
						<?php $wimage_path = 'wrapper/' . $row->wrapper_image;
						if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $wimage_path))
						{
							?>
							<a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $wimage_path; ?>"
							   title="<?php echo JText::_('COM_REDSHOP_VIEW_IMAGE'); ?>"
							   rel="{handler: 'image', size: {}}">
								<?php echo $row->wrapper_image; ?></a>
						<?php } ?>
					</td>
					<td align="center"><?php echo $producthelper->getProductFormattedPrice($row->wrapper_price);//CURRENCY_SYMBOL.number_format($row->wrapper_price,2,PRICE_SEPERATOR,THOUSAND_SEPERATOR);
						?></td>
					<td align="center"><?php echo $enable_default; ?></td>
					<td align="center"><?php echo $published; ?></td>
					<td align="center"><?php echo $row->id; ?></td>
				</tr>
				<?php $k = 1 - $k;
			} ?>
			<tfoot>
			<td colspan="8">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->pagination->getListFooter(); ?></td>
			</tfoot>
		</table>
	</div>
	<input type="hidden" name="view" value="wrapper"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="product_id" value="<?php echo $this->product_id; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="showall" value="<?php echo $showall; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
