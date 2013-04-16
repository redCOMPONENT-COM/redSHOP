<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
JHTMLBehavior::modal();
$product = $this->products;


?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton) {

		var form = document.adminForm;

		if (document.adminForm.elements["category_id[]"]) {
			var filterstring = '';
			var faddcomma = '';
			var colopt = document.adminForm.elements["category_id[]"].options;
			var z;
			collen = colopt.length;
			var y = 0;
			for (z = 0; z < collen; ++z) {
				if (colopt[z].selected) {
					if (colopt[z].value != 0) {
						y++;
					}

				}
			}
			if (y == 0) {
				alert('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_CATEGORY');?>');
			}
			else {
				form.task.value = pressbutton;
				form.submit();
			}

		}

	}
</script>
<form name="adminForm" id="adminForm" method="post">
	<table class="adminlist">
		<tr>
			<th><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME');?></th>
			<th><?php echo JText::_('COM_REDSHOP_CATEGORY_NAME');?></th>
		</tr>
		<?php
		$row = & $product[0];
		echo "<tr>";
		echo "<td>" . $row->product_name . "</td>";
		echo "<td rowspan='" . count($product) . "'>" . $this->lists["category"] . "</td>";
		echo "<input type='hidden' name='cid[]' value='" . $row->product_id . "'>";
		echo "</tr>";

		for ($i = 1; $i < count($product); $i++)
		{
			$row = & $product[$i];
			echo "<tr>";
			echo "<td>" . $row->product_name;
			echo "<input type='hidden' name='cid[]' value='" . $row->product_id . "'>";
			echo "</td>";
			echo "</tr>";
		}
		?>
	</table>
	<input type="hidden" name="boxchecked" value=""/>
	<input type="hidden" name="view" value="product_category"/>
	<input type="hidden" name="task" value=""/>
</form>