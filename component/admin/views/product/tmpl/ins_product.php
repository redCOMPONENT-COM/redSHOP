<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$model = $this->getModel('product');
$eName = JRequest::getVar('e_name');
$eName = preg_replace('#[^A-Z0-9\-\_\[\]]#i', '', $eName);
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {

		var form = document.adminForm;

		if (pressbutton) {

			form.task.value = pressbutton;

		}

		form.submit();
	}


	function insertProduct(pid) {

		//	var alt = document.getElementById("alt").value;

		var tag = "{redshop:" + pid + "}";

		window.parent.jInsertEditorText(tag, '<?php echo $eName; ?>');
		window.parent.SqueezeBox.close();
		return false;
	}
</script>


<form
	action="index.php?option=com_redshop&amp;view=product&amp;task=ins_product&amp;tmpl=component&amp;e_name=<?php echo $eName; ?>"
	method="post" name="adminForm" id="adminForm">
	<input type="text" name="keyword" value="<?php echo $this->keyword; ?>"> <input type="submit"
	                                                                                value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
	<select name="search_field" onchange="javascript:document.adminForm.submit();">
		<option
			value="p.product_name" <?php if ($this->search_field == 'p.product_name') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_PRODUCT_NAME")?></option>
		<option
			value="c.category_name" <?php if ($this->search_field == 'c.category_name') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_CATEGORY")?></option>
		<option
			value="p.product_number" <?php if ($this->search_field == 'p.product_number') echo "selected='selected'";?>><?php echo JText::_("COM_REDSHOP_PRODUCT_NUMBER")?></option>
	</select>

	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>

				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NUMBER', 'product_number', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->products); $i < $n; $i++)
			{
				$row = $this->products[$i];
				$row->id = $row->product_id;
				$link = JRoute::_('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>

					<td>
						<div style="cursor:pointer"
						     onclick="insertProduct(<?php echo $row->product_id; ?>);"><?php echo $row->product_name; ?></div>
					</td>
					<td>
						<div style="cursor:pointer"
						     onclick="insertProduct(<?php echo $row->product_id; ?>);"><?php echo $row->product_number;?></div>
					</td>

				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="6">
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
	<input type="hidden" name="task" value="ins_product"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
