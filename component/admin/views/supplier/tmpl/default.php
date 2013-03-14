<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

$option = JRequest::getVar('option', '', 'request', 'string');

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
			|| (pressbutton == 'remove') || (pressbutton == 'copy')) {
			form.view.value = "supplier_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}

</script>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5%">
					<?php
					echo JText::_('COM_REDSHOP_NUM');
					?>
				</th>
				<th width="5%"><input type="checkbox" name="toggle" value=""
				                      onclick="checkAll(<?php
				                      echo count($this->supplier);
				                      ?>);"/></th>
				<th class="title">
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_SUPPLIER_NAME', 'supplier_name', $this->lists ['order_Dir'], $this->lists ['order']);
					?>

				</th>
				<th width="20%">
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_SUPPLIER_EMAIL', 'supplier_email', $this->lists ['order_Dir'], $this->lists ['order']);
					?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists ['order_Dir'], $this->lists ['order']);
					?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'supplier_id', $this->lists ['order_Dir'], $this->lists ['order']);
					?>
				</th>

			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->supplier); $i < $n; $i++)
			{

				$row = & $this->supplier[$i];

				$row->id = $row->supplier_id;

				$link = JRoute::_('index.php?option=' . $option . '&view=supplier_detail&task=edit&cid[]=' . $row->supplier_id);

				$published = JHTML::_('grid.published', $row, $i);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php
						echo $this->pagination->getRowOffset($i);
						?>
					</td>
					<td align="center">
						<?php
						echo JHTML::_('grid.id', $i, $row->id);
						?>
					</td>
					<td><a href="<?php
						echo $link;
						?>"
					       title="<?php
					       echo JText::_('COM_REDSHOP_EDIT_SUPPLIER');
					       ?>"><?php
							echo $row->supplier_name;
							?></a></td>
					<td>
						<?php
						echo $row->supplier_email;
						//				$desctext = strip_tags($row->supplier_desc);
						//				echo substr ( $desctext, 0, 50 );
						?>
					</td>
					<td align="center">
						<?php
						echo $published;
						?>
					</td>
					<td align="center">
						<?php
						echo $row->supplier_id;
						?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="9">
				<?php
				echo $this->pagination->getListFooter();
				?>
			</td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="supplier"/> <input
		type="hidden" name="task" value=""/> <input type="hidden"
	                                                name="boxchecked" value="0"/> <input type="hidden"
	                                                                                     name="filter_order"
	                                                                                     value="<?php
	                                                                                     echo $this->lists ['order'];
	                                                                                     ?>"/> <input type="hidden"
	                                                                                                  name="filter_order_Dir"
	                                                                                                  value="<?php
	                                                                                                  echo $this->lists ['order_Dir'];
	                                                                                                  ?>"/></form>
