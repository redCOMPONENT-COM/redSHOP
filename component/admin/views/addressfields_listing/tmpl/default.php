<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$jinput = JFactory::getApplication()->input;

$filter = $jinput->get('filter');

//Ordering allowed ?

$pagination = $this->pagination;
$ordering = ($this->lists['order'] == 'ordering');
$field_section_drop = $jinput->get('field_section_drop');
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'saveorder') || (pressbutton == 'orderup') || (pressbutton == 'orderdown')) {
			form.view.value = "addressfields_listing";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}

</script>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table width="100%">
			<tr>
				<td width="100%" valign="top" align="left" class="key" colspan="5">
					<?php echo JText::_('COM_REDSHOP_FIELD_SECTION') . " : " . $this->lists['addresssections']; ?>
				</td>
			</tr>
		</table>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title" width="20%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FIELD_TITLE', 'field_title', $this->lists['order_Dir'], $this->lists['order']); ?>

				</th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FIELD_SECTION', 'field_section', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="order" width="10%">
					<?php  echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
					<?php  if ($ordering) echo JHTML::_('grid.order', $this->fields);  ?>
				</th>


			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->fields); $i < $n; $i++)
			{
				$row = $this->fields[$i];
				$row->id = $row->id;
				$link = JRoute::_('index.php?option=com_redshop&view=fields_detail&task=edit&cid[]=' . $row->id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td>
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td width="30%">
						<?php echo $row->title; ?>
					</td>

					<td class="order" width="30%">
						<?php
						if ($row->section == 1) echo 'Product';
						elseif ($row->section == 2) echo 'Category';
						elseif ($row->section == 3) echo 'Form';
						elseif ($row->section == 4) echo 'Email';
						elseif ($row->section == 5) echo 'Confirmation';
						elseif ($row->section == 6) echo 'Userinformations';
						elseif ($row->section == 7) echo 'Customer Address';
						elseif ($row->section == 8) echo 'Company Address';
						elseif ($row->section == 9) echo 'Color sample';
						elseif ($row->section == 10) echo 'Manufacturer';
						elseif ($row->section == 11) echo 'Shipping';
						elseif ($row->section == 12) echo 'Product UserField';
						elseif ($row->section == 13) echo 'Giftcard UserField';
						elseif ($row->section == 14) echo 'Customer shipping Address';
						else  echo 'Company Shipping Address';

						?>


					<td class="order" width="30%">
						<span><?php echo $this->pagination->orderUpIcon($i, ($row->section == @$this->fields[$i - 1]->section), 'orderup', JText::_('JLIB_HTML_MOVE_UP'), $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $n, ($row->section == @$this->fields[$i + 1]->section), 'orderdown', JText::_('JLIB_HTML_MOVE_DOWN'), $ordering); ?></span>

						<?php $disabled = $ordering ? '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5"
						       value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area"
						       style="text-align: center"/>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="9">
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

	<input type="hidden" name="view" value="addressfields_listing"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
