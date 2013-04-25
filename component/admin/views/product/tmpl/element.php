<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
JHTMLBehavior::modal();
$app = JFactory::getApplication();
$productobj = new producthelper();
$option = JRequest::getVar('option', '', 'request', 'string');

$model = $this->getModel('product');
$ordering = ($this->lists['order'] == 'ordering');

$category_id = $app->getUserStateFromRequest('category_id', 'category_id', 0);
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
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>
<?php

$kk = 0;
$tmpCats = array();
$n = count($this->products);
for ($i = $this->pagination->limitstart, $j = 0; $i < ($this->pagination->limitstart + $this->pagination->limit); $i++, $j++)
{
	$row = & $this->products[$j];
	if (!is_object($row))
	{
		break;
	}
	// change ordering
	$row->orderup = $this->pagination->orderUpIcon($j, ($row->product_parent_id == @$this->products[$j - 1]->product_parent_id), 'orderup', JText::_('JLIB_HTML_MOVE_UP'), $ordering);
	$row->orderdown = $this->pagination->orderDownIcon($j, $n, ($row->product_parent_id == @$this->products[$j + 1]->product_parent_id), 'orderdown', JText::_('JLIB_HTML_MOVE_DOWN'), $ordering);
	// end
	$tmpCats[$kk] = $row;
	$kk++;
}
if ($this->pagination->limit > 0)
	$this->products = $tmpCats;

?>
<table border="0" cellpadding="2" cellspacing="2" width="100%">
	<tr>
		<td>
			<form
				action="<?php echo 'index.php?option=com_redshop&view=product&amp;task=element&amp;tmpl=component&amp;object=' . JRequest::getVar('object'); ?>"
				method="post" name="adminForm2" id="adminForm2">

				<input type="text" name="keyword" value="<?php echo $this->keyword; ?>"> <input type="submit"
				                                                                                value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
				<select name="search_field" onchange="javascript:document.adminForm2.submit();">
					<option
						value="p.product_name" <?php if ($this->search_field == 'p.product_name') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_PRODUCT_NAME")?></option>
					<option
						value="c.category_name" <?php if ($this->search_field == 'c.category_name') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_CATEGORY")?></option>
					<option
						value="p.product_number" <?php if ($this->search_field == 'p.product_number') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_PRODUCT_NUMBER")?></option>
				</select>
				<?php echo $this->lists['category'];?>
			</form>
		</td>
	</tr>
</table>
<form
	action="<?php echo 'index.php?option=' . $option . '&amp;view=product&amp;task=element&amp;tmpl=component&amp;object=' . JRequest::getVar('object'); ?>"
	method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value=""
					       onclick="checkAll(<?php echo count($this->products); ?>);"/>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NUMBER', 'product_number', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NUMBER_OF_VIEWS', 'visited', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th>
					<?php echo JText::_('COM_REDSHOP_CATEGORY'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDSHOP_MANUFACTURER'); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'product_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
			</tr>
			</thead>
			<?php
			$k = 0;

			for ($i = 0, $n = count($this->products); $i < $n; $i++)
			{
				$row = & $this->products[$i];
				$row->id = $row->product_id;
				$link = JRoute::_('index.php?option=' . $option . '&view=product_detail&task=edit&cid[]=' . $row->product_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td>
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td>
						<a style="cursor: pointer;"
						   onclick="window.parent.jSelectProduct('<?php echo $row->product_id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""), $row->product_name); ?>', '<?php echo JRequest::getVar('object'); ?>');">
							<?php echo $row->product_name; ?></a>
					</td>
					<td>
						<?php echo $row->product_number;?>
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
					<td align="center" width="5%">
						<?php echo $row->product_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="13">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="product"/>
	<input type="hidden" name="task" value="element"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
