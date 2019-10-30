<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

$app              = JFactory::getApplication();
$extraFieldHelper = extra_field::getInstance();
$productHelper    = productHelper::getInstance();

$model    = $this->getModel('product');
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn     = $this->escape($this->state->get('list.direction'));
$ordering = ($this->lists['order'] == 'x.ordering');
$allowOrder = ($listOrder == 'x.ordering' && strtolower($listDirn) == 'asc');

if ($allowOrder)
{
	$saveOrderingUrl = 'index.php?option=com_redshop&task=product.saveOrderAjax';
	JHtml::_('redshopsortable.sortable', 'adminForm', 'adminForm', 'asc', $saveOrderingUrl);
}

$category_id = $this->state->get('category_id', 0);

$user   = JFactory::getUser();
$userId = (int) $user->id;
JHtml::_('redshopjquery.framework');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitform = submitform = Joomla.submitbutton = submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'copy') || (pressbutton == 'saveorder') || (pressbutton == 'orderup') || (pressbutton == 'orderdown')) {
			form.view.value = "product_detail";
		}
		if ((pressbutton == 'assignCategory') || (pressbutton == 'removeCategory')) {
			form.view.value = "product_category";
		}

		if (pressbutton == 'remove') {
			if (confirm("<?php echo JText::_('COM_REDSHOP_PRODUCT_DELETE_CONFIRM') ?>") != true) {
				form.view.value = 'product';
				form.task.value = '';
				return false;
			}
		}


		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}

	function AssignTemplate() {
		var form = document.adminForm;
		if (form.boxchecked.value == 0) {
			jQuery('#product_template').val(0).trigger("liszt:updated");
			alert('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_PRODUCT');?>');
		} else {
			form.task.value = 'assignTemplate';
			if (confirm("<?php echo JText::_('COM_REDSHOP_SURE_WANT_TO_ASSIGN_TEMPLATE');?>")) {
				form.submit();
			} else {
				jQuery('#product_template').val(0).trigger("liszt:updated");
			}
		}

	}

	function resetFilter() {
		document.getElementById('keyword').value = '';
		document.getElementById('search_field').value = 'p.product_name';
		document.getElementById('category_id').value = 0;
		document.getElementById('manufacturer_id').value = 'all';
		document.getElementById('product_sort').value = 0;
	}

</script>
<form action="index.php?option=com_redshop&view=product" method="post" name="adminForm" id="adminForm">

	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">
				<div class="btn-wrapper input-append">
					<input type="text" name="keyword" id="keyword" value="<?php echo $this->keyword; ?>"
						   placeholder="<?php echo JText::_("COM_REDSHOP_USER_FILTER") ?>">
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="button" class="btn reset" onclick="resetFilter();this.form.submit();"
						   value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"/>
				</div>
			</div>
			<div class="filterItem">
				<select id="search_field" name="search_field" onchange="javascript:document.adminForm.submit();">
					<option
							value="p.product_name" <?php if ($this->search_field == 'p.product_name') echo "selected='selected'"; ?>>
						<?php echo JText::_("COM_REDSHOP_PRODUCT_NAME") ?></option>
					<option
							value="c.name" <?php if ($this->search_field == 'c.category_name') echo "selected='selected'"; ?>>
						<?php echo JText::_("COM_REDSHOP_CATEGORY") ?></option>
					<option
							value="p.product_number" <?php if ($this->search_field == 'p.product_number') echo "selected='selected'"; ?>
					><?php echo JText::_("COM_REDSHOP_PRODUCT_NUMBER") ?></option>
					<option
							value="p.name_number" <?php if ($this->search_field == 'p.name_number') echo "selected='selected'"; ?>
					><?php echo JText::_("COM_REDSHOP_PRODUCT") . ' ' . JText::_("COM_REDSHOP_NAME_AND_NUMBER"); ?></option>
					<option
							value="pa.property_number" <?php if ($this->search_field == 'pa.property_number') echo "selected='selected'"; ?>>
						<?php echo JText::_("COM_REDSHOP_ATTRIBUTE_SKU") ?></option>
				</select>
			</div>
			<div class="filterItem">
				<?php echo $this->lists['category']; ?>
			</div>
			<div class="filterItem">
				<?php echo $this->lists['manufacturer']; ?>
			</div>
			<div class="filterItem">
				<?php echo $this->lists['product_sort']; ?>
			</div>
		</div>
		<input type="hidden" name="unpublished_data" value="">
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<?php if ($category_id < 0) : ?>
				<th width="5">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<?php endif?>
				<?php if ($category_id > 0): ?>
					<th width="1" class="nowrap center hidden-phone">
						<a href="#" onclick="Joomla.tableOrdering('x.ordering','asc','');return false;"
						   data-order="X.ordering" data-direction="asc">
							<span class="fa fa-sort-alpha-asc"></span>
						</a>
					</th>
				<?php endif; ?>
				<th width="20">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'p.product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NUMBER', 'p.product_number', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_PRICE', 'p.product_price', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<?php foreach ($this->list_in_products as $listInProduct): ?>
					<th nowrap="nowrap"><?php echo JText::_($listInProduct->title); ?></th>
				<?php endforeach; ?>
				<th>
					<?php echo JText::_('COM_REDSHOP_MEDIA'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDSHOP_WRAPPER'); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NUMBER_OF_VIEWS', 'p.visited', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CATEGORY', 'category_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MANUFACTURER', 'm.name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDSHOP_CUSTOMER_REVIEWS'); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'p.published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'p.product_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
			</tr>
			</thead>
			<?php $k = 0; ?>
			<?php foreach ($this->products as $index => $product): ?>
				<?php
				$product->id = $product->product_id;
				$link        = JRoute::_('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $product->product_id);
				$published   = JHtml::_('jgrid.published', $product->published, $index, '', 1);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<?php if ($category_id < 0) : ?>
					<td>
						<?php echo $this->pagination->getRowOffset($index); ?>
					</td>
					<?php endif ?>
					<?php if ($category_id > 0)
					{
						?>
						<td class="order nowrap center hidden-phone">
						<span class="sortable-handler <?php echo ($allowOrder) ? '' : 'inactive' ?>">
							<span class="icon-move"></span>
						</span>
							<input type="text" style="display:none" name="order[]" value="<?php echo $product->ordering; ?>" />
						</td>
					<?php } ?>
					<td>
						<?php echo @JHTML::_('grid.checkedout', $product, $index); ?>
					</td>
					<td>
						<?php

						$canCheckin = $user->authorise('core.manage', 'com_checkin') || $product->checked_out == $userId || $product->checked_out == 0;
						?>
						<?php if ($product->checked_out) : ?>
							<?php $checkedOut = JFactory::getUser($product->checked_out); ?>
							<?php echo JHtml::_('jgrid.checkedout', $index, $checkedOut->name, $product->checked_out_time, 'product.', $canCheckin); ?>
						<?php endif; ?>
						<?php
						if ($canCheckin)
						{
							if (isset($product->children))
							{
								?>
								<a href="<?php echo $link; ?>"
								   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $product->treename; ?></a>
								<?php
							}
							else
							{
								if ($product->product_parent_id == 0)
								{
									?>
									<a href="<?php echo $link; ?>"
									   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $product->treename; ?></a>
									<?php
								}
								else
								{
									$pro_array = Redshop::product((int) $product->product_parent_id);

									?>
									<a href="<?php echo $link; ?>"
									   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $product->treename; ?> </a>[child: <?php echo $pro_array->product_name; ?>]
									<?php
								}
							}
						}
						else
						{
							?>
							<?php
							if (isset($product->children))
							{
								?>
								<a href="<?php echo $link; ?>"
								   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $product->treename; ?></a>
								<?php
							}
							else
							{
								if ($product->product_parent_id == 0)
								{
									?>
									<a href="<?php echo $link; ?>"
									   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $product->treename; ?></a>
									<?php
								}
								else
								{
									$pro_array = Redshop::product((int) $product->product_parent_id);

									?>
									<a href="<?php echo $link; ?>"
									   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $product->treename; ?> </a>[child: <?php echo $pro_array->product_name; ?>]
									<?php
								}
							}
							?>
							<?php
						}
						?>
					</td>
					<td>
						<?php echo $product->product_number; ?>
					</td>
					<td class="nowrap">
						<?php echo $productHelper->getProductFormattedPrice($product->product_price); ?>
					</td>

					<?php foreach ($this->list_in_products as $list_in_product) : ?>
						<?php
						$fieldArray = RedshopHelperExtrafields::getSectionFieldDataList($list_in_product->id, 1, $product->product_id);
						$fieldValue = '';
						if (!empty($fieldArray))
						{
							$fieldValue = $fieldArray->data_txt;
						}
						?>
						<td><?php echo $fieldValue; ?></td>
					<?php endforeach; ?>

					<td align="center">
						<a class="joom-box"
						   href="index.php?option=com_redshop&view=media&section_id=<?php echo $product->product_id; ?>&showbuttons=1&media_section=product&section_name=<?php echo $product->product_name; ?>&tmpl=component"
						   rel="{handler: 'iframe', size: {x: 1050, y: 450}}" title=""> <img
									src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
									alt="media"> (<?php echo count($model->MediaDetail($product->product_id)); ?>)</a>
					</td>
					<td align="center">
						<?php $wrapper = $productHelper->getWrapper($product->product_id, 0, 1); ?>
						<a class="joom-box"
						   href="index.php?option=com_redshop&showall=1&view=wrapper&product_id=<?php echo $product->product_id; ?>&tmpl=component"
						   rel="{handler: 'iframe', size: {x: 700, y: 450}}">
							<img src="<?php echo REDSHOP_MEDIA_IMAGES_ABSPATH; ?>wrapper16.png" align="absmiddle"
								 alt="<?php echo JText::_('COM_REDSHOP_WRAPPER'); ?>"> <?php echo "(" . count($wrapper) . ")"; ?>
						</a>
					</td>
					<td align="center">
						<?php echo $product->visited; ?>
					</td>

					<td>
						<?php $listedincats = $model->listedincats($product->product_id); ?>
						<?php foreach ($listedincats as $listedincat) : ?>
							<?php echo $cat = $listedincat->name . "<br />"; ?>
						<?php endforeach; ?>
					</td>
					<td>
						<?php echo RedshopEntityManufacturer::getInstance($product->manufacturer_id)->get('name', ''); ?>
					</td>
					<td>
						<a href="index.php?option=com_redshop&view=rating_detail&task=edit&cid[]=0&pid=<?php echo $product->product_id ?>"><?php echo JText::_('COM_REDSHOP_ADD_REVIEW'); ?></a>
					</td>
					<td align="center" width="8%">
						<?php echo $published; ?>
					</td>
					<td align="center" width="5%">
						<?php echo $product->product_id; ?>
					</td>
				</tr>
				<?php $k = 1 - $k; ?>
			<?php endforeach; ?>
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
	</div>

	<input type="hidden" name="view" value="product"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
