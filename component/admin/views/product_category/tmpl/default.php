<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {

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
	<div class="filterItem">
		<?php echo JText::_("COM_REDSHOP_SELECT_CATEGORY_LBL")?><br /><?php echo $this->lists["category"]; ?>
	</div>
	<table class="adminlist table table-striped">
		<thead>
		<tr>
			<th><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME');?></th>
			<th><?php echo JText::_('COM_REDSHOP_PRODUCT_CATEGORY');?></th>
		</tr>
		</thead>
		<?php foreach ($this->products as $row): ?>
			<tr>
				<td>
					<?php echo $row->product_name; ?>
					<input type="hidden" name="cid[]" value="<?php echo $row->product_id; ?>">
				</td>
				<td>
				<?php
				if (isset($row->categories) && count($row->categories))
				{
					foreach ($row->categories as $category)
					{
						echo $category . '<br />';
					}
				}
			?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<input type="hidden" name="boxchecked" value=""/>
	<input type="hidden" name="view" value="product_category"/>
	<input type="hidden" name="task" value=""/>
</form>
