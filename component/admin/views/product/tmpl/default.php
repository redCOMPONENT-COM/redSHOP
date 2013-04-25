<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';
$app = JFactory::getApplication();
$extra_field = new extra_field();
JHTMLBehavior::modal();
$producthelper = new producthelper();
$option = JRequest::getVar('option', '', 'request', 'string');

$model = $this->getModel('product');
$ordering = ($this->lists['order'] == 'x.ordering');

$category_id = $app->getUserStateFromRequest('category_id', 'category_id', 0);

$user = JFactory::getUser();
$userId = (int) $user->id;

?>
<script language="javascript" type="text/javascript">


	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'copy') || (pressbutton == 'saveorder') || (pressbutton == 'orderup') || (pressbutton == 'orderdown')) {
			form.view.value = "product_detail";
		}
		if ((pressbutton == 'assignCategory') || (pressbutton == 'removeCategory')) {
			form.view.value = "product_category";
		}
		if (pressbutton == 'gbasefeed') {
			var x = confirm("Do you want to export unpublished products?");

			if (x == true) {
				document.adminForm.unpublished_data.value = 1;

			} else {
				document.adminForm.unpublished_data.value = 0;
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


		var templatevalue = document.getElementById('product_template').value;

		if (form.boxchecked.value == 0) {

			document.getElementById('product_template').value = 0;
			form.product_template.value = 0;
			alert('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_PRODUCT');?>');

		} else {

			form.task.value = 'assignTemplate';

			if (confirm("<?php echo JText::_('COM_REDSHOP_SURE_WANT_TO_ASSIGN_TEMPLATE');?>")) {

				form.product_template.value = templatevalue;
				form.submit();
			} else {

				document.getElementById('product_template').value = 0;
				form.product_template.value = 0;
				return false;
			}
		}

	}

</script>
<table border="0" cellpadding="2" cellspacing="2" width="100%">
	<tr>
		<td>
			<form action="<?php echo 'index.php?option=com_redshop&view=product'; ?>" method="post" name="adminForm2"
			      id="adminForm2">

				<input type="text" name="keyword" value="<?php echo $this->keyword; ?>"> <input type="submit"
				                                                                                value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
				<select name="search_field" onchange="javascript:document.adminForm2.submit();">
					<option
						value="p.product_name" <?php if ($this->search_field == 'p.product_name') echo "selected='selected'";?>>
						<?php echo JText::_("COM_REDSHOP_PRODUCT_NAME")?></option>
					<option
						value="c.category_name" <?php if ($this->search_field == 'c.category_name') echo "selected='selected'";?>>
						<?php echo JText::_("COM_REDSHOP_CATEGORY")?></option>
					<option
						value="p.product_number" <?php if ($this->search_field == 'p.product_number') echo "selected='selected'";?>
						><?php echo JText::_("COM_REDSHOP_PRODUCT_NUMBER")?></option>
					<option
						value="p.name_number" <?php if ($this->search_field == 'p.name_number') echo "selected='selected'";?>
						><?php echo JText::_("COM_REDSHOP_PRODUCT") . ' ' . JText::_("COM_REDSHOP_NAME_AND_NUMBER"); ?></option>
					<option
						value="pa.property_number" <?php if ($this->search_field == 'pa.property_number') echo "selected='selected'";?>>
						<?php echo JText::_("COM_REDSHOP_ATTRIBUTE_SKU")?></option>
				</select>
				<?php echo $this->lists['category'];
				echo $this->lists['product_sort'];
				?>
			</form>
		</td>
		<td align="right">
			<?php echo $this->lists['product_template'];?>
		</td>
	</tr>
</table>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
<div id="editcell">
<input type="hidden" name="unpublished_data" value="">
<table class="adminlist">
<thead>
<tr>
	<th width="5">
		<?php echo JText::_('COM_REDSHOP_NUM'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->products); ?>);"/>
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
	<?php

	for ($i = 0, $n = count($this->list_in_products); $i < $n; $i++)
	{
		?>
		<th nowrap="nowrap"><?php echo  JText::_($this->list_in_products[$i]->field_title); ?></th>
	<?php }    ?>
	<th>
		<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MEDIA', 'media', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>
	<th>
		<?php echo JText::_('COM_REDSHOP_WRAPPER'); ?>
	</th>
	<th>
		<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NUMBER_OF_VIEWS', 'p.visited', $this->lists['order_Dir'], $this->lists['order']); ?>
	</th>

	<th>
		<?php echo JText::_('COM_REDSHOP_CATEGORY'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_REDSHOP_MANUFACTURER'); ?>
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
	<?php if ($category_id > 0)
	{
		?>
		<th width="15%" nowrap="nowrap">
			<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'x.ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
			<?php
			if ($ordering)
			{
				echo JHTML::_('grid.order', $this->products);
			}
			?>
		</th>
	<?php } ?>
</tr>
</thead>
<?php
$k = 0;



for ($i = 0, $n = count($this->products); $i < $n; $i++)
{
	$row = & $this->products[$i];

	$row->id = $row->product_id;
	$link = JRoute::_('index.php?option=' . $option . '&view=product_detail&task=edit&cid[]=' . $row->product_id);

	//	$published 	= JHtml::_('jgrid.published', $row->published, $i,'',1);

	$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

	?>
	<tr class="<?php echo "row$k"; ?>">
		<td>
			<?php echo $this->pagination->getRowOffset($i); ?>
		</td>
		<td><?php echo @JHTML::_('grid.checkedout', $row, $i); ?></td>
		<td>
			<?php

			$checkedOut = ((int) $row->checked_out !== 0 && (int) $row->checked_out === $userId);
			if ($checkedOut)
			{
				if (isset($row->children))
				{
					?>
					<a href="<?php echo $link; ?>"
					   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?></a>
				<?php
				}
				else
				{
					if ($row->product_parent_id == 0)
					{
						?>
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?></a>
					<?php
					}
					else
					{
						$pro_array = $producthelper->getProductById($row->product_parent_id);

						?>
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?> </a>[child: <?php echo $pro_array->product_name; ?>]
					<?php
					}
				}
			}
			else
			{
				?>
				<?php
				if (isset($row->children))
				{
					?>
					<a href="<?php echo $link; ?>"
					   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?></a>
				<?php
				}
				else
				{
					if ($row->product_parent_id == 0)
					{
						?>
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?></a>
					<?php
					}
					else
					{
						$pro_array = $producthelper->getProductById($row->product_parent_id);

						?>
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?> </a>[child: <?php echo $pro_array->product_name; ?>]
					<?php
					}
				}
				?>
			<?php
			}
			?>
		</td>
		<td>
			<?php echo $row->product_number;?>
		</td>
		<td>
			<?php echo $producthelper->getProductFormattedPrice($row->product_price);?>
		</td>

		<?php    for ($j = 0, $k = count($this->list_in_products); $j < $k; $j++)
		{
			$field_arr = $extra_field->getSectionFieldDataList($this->list_in_products[$j]->field_id, 1, $row->product_id);
			$field_value = '';
			if (count($field_arr) > 0)
			{
				$field_value = $field_arr->data_txt;
			}    ?>
			<td><?php echo $field_value;  ?></td>
		<?php }    ?>


		<td align="center">
			<?php $mediadetail = $model->MediaDetail($row->product_id); ?>
			<a class="modal"
			   href="index.php?option=<?php echo $option; ?>&amp;view=media&amp;section_id=<?php echo $row->product_id; ?>&amp;showbuttons=1&amp;media_section=product&amp;section_name=<?php echo $row->product_name; ?>&amp;tmpl=component"
			   rel="{handler: 'iframe', size: {x: 1050, y: 450}}" title=""><img
					src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
					alt="media">(<?php  echo count($mediadetail);?>)</a>
		</td>
		<td align="center">
			<?php $wrapper = $producthelper->getWrapper($row->product_id, 0, 1);?>
			<a class="modal"
			   href="index.php?option=<?php echo $option; ?>&showall=1&view=wrapper&product_id=<?php echo $row->product_id; ?>&amp;tmpl=component"
			   rel="{handler: 'iframe', size: {x: 700, y: 450}}">
				<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>wrapper16.png" align="absmiddle"
				     alt="<?php echo JText::_('COM_REDSHOP_WRAPPER'); ?>"><?php echo "(" . count($wrapper) . ")";?></a>
		</td>
		<td align="center">
			<?php echo $row->visited;?>
		</td>

		<td>
			<?php $listedincats = $model->listedincats($row->product_id);
			for ($j = 0; $j < count($listedincats); $j++)
			{
				echo $cat = $listedincats[$j]->category_name . "<br />";
			}
			?>
		</td>
		<td>
			<?php echo $model->getmanufacturername($row->manufacturer_id); ?>
		</td>
		<td width="90">
			<a href="index.php?option=com_redshop&view=rating_detail&task=edit&cid[]=0&pid=<?php echo $row->product_id ?>"><?php echo JText::_('COM_REDSHOP_ADD_REVIEW'); ?></a>
		</td>
		<td align="center" width="8%">
			<?php echo $published;?>
		</td>
		<td align="center" width="5%">
			<?php echo $row->product_id; ?>
		</td>
		<?php if ($category_id > 0)
		{
			$disabled = $ordering ? '' : 'disabled="disabled"';

			?>
			<td class="order">
				<span><?php
					echo    $this->pagination->orderUpIcon($i, ($row->category_id == @$this->products[$i - 1]->category_id), 'orderup', JText::_('JLIB_HTML_MOVE_UP'), $ordering); ?></span>
				<span><?php
					echo $this->pagination->orderDownIcon($i, $n, ($row->category_id == @$this->products[$i + 1]->category_id), 'orderdown', JText::_('JLIB_HTML_MOVE_DOWN'), $ordering); ?></span>
				<input type="text" name="order[]" size="5" <?php echo $disabled; ?>
				       value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center"/>
				</td>
		<?php } ?>
	</tr>
	<?php
	$k = 1 - $k;
}
?>

<tfoot>
<td colspan="14">
	<?php echo $this->pagination->getListFooter(); ?>
</td>
</tfoot>
</table>
</div>

<input type="hidden" name="view" value="product"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="product_template" value=""/>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
