<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

JHTMLBehavior::modal();

$option = JRequest::getVar('option', '', 'request', 'string');
$filter = JRequest::getVar('filter');
$model = $this->getModel('manufacturer');
$ordering = ($this->lists['order'] == 'm.ordering');
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
			form.view.value = "manufacturer_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}

</script>
<form action="<?php
echo 'index.php?option=' . $option;
?>"
      method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table width="100%">
			<tr>
				<td valign="top" align="left" class="key">
					<?php echo JText::_('COM_REDSHOP_USER_FILTER'); ?>:
					<input type="text" name="filter" id="filter" value="<?php echo $filter; ?>"
					       onchange="document.adminForm.submit();">
					<button onclick="this.form.submit();"><?php echo JText::_('COM_REDSHOP_GO'); ?></button>
					<button
						onclick="document.getElementById('filter').value='';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET'); ?></button>
				</td>
			</tr>
		</table>
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5">
					<?php
					echo JText::_('COM_REDSHOP_NUM');
					?>
				</th>
				<th width="20"><input type="checkbox" name="toggle" value=""
				                      onclick="checkAll(<?php
				                      echo count($this->manufacturer);
				                      ?>);"/></th>
				<th class="title">
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_MANUFACTURER_NAME', 'manufacturer_name', $this->lists ['order_Dir'], $this->lists ['order']);
					?>

				</th>
				<th>
					<?php echo JText::_('COM_REDSHOP_MEDIA'); ?>
				</th>
				<th>
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_MANUFACTURER_DESCRIPTION', 'manufacturer_desc', $this->lists ['order_Dir'], $this->lists ['order']);
					?>
				</th>
				<th class="order" width="20%">
					<?php  echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'm.ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
					<?php  if ($ordering) echo JHTML::_('grid.order', $this->manufacturer);  ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists ['order_Dir'], $this->lists ['order']);
					?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php
					echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'manufacturer_id', $this->lists ['order_Dir'], $this->lists ['order']);
					?>
				</th>

			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->manufacturer); $i < $n; $i++)
			{
				$row = & $this->manufacturer [$i];
				$row->id = $row->manufacturer_id;
				$link = JRoute::_('index.php?option=' . $option . '&view=manufacturer_detail&task=edit&cid[]=' . $row->manufacturer_id);

				$published = JHTML::_('grid.published', $row, $i);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php
						echo $this->pagination->getRowOffset($i);
						?>
					</td>
					<td>
						<?php
						echo JHTML::_('grid.id', $i, $row->id);
						?>
					</td>
					<td width="50%"><a href="<?php
						echo $link;
						?>"
					                   title="<?php
					                   echo JText::_('COM_REDSHOP_EDIT_MANUFACTURER');
					                   ?>"><?php
							echo $row->manufacturer_name;
							?></a>
					</td>
					<td align="center">
						<?php $media_id = $model->getMediaId($row->manufacturer_id);?>
						<a class="modal"
						   href="index.php?tmpl=component&option=<?php echo $option; ?>&amp;view=media_detail&amp;cid[]=<?php echo $media_id; ?>&amp;section_id=<?php echo $row->manufacturer_id; ?>&amp;showbuttons=1&amp;media_section=manufacturer&amp;section_name=<?php echo $row->manufacturer_name; ?>"
						   rel="{handler: 'iframe', size: {x: 1050, y: 450}}" title=""><img
								src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
								alt="media"></a>
					</td>
					<td width="30%">
						<?php
						$desctext = strip_tags($row->manufacturer_desc);
						echo substr($desctext, 0, 50);
						?>
					</td>
					<td class="order" width="30%">
						<span><?php echo $this->pagination->orderUpIcon($i, true, 'orderup', JText::_('JLIB_HTML_MOVE_UP'), $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderdown', JText::_('JLIB_HTML_MOVE_DOWN'), $ordering); ?></span>
						<?php $disabled = $ordering ? '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5"
						       value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area"
						       style="text-align: center"/>
					</td>
					<td align="center" width="5%">
						<?php
						echo $published;
						?>
					</td>
					<td align="center" width="5%">
						<?php
						echo $row->manufacturer_id;
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

	<input type="hidden" name="view" value="manufacturer"/> <input
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
