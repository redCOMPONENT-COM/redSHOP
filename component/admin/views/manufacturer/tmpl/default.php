<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTMLBehavior::modal();


$model = $this->getModel('manufacturer');
$ordering = ($this->lists['order'] == 'm.ordering');
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'copy')) {
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
<form action="index.php?option=com_redshop"
      method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">
				<?php echo JText::_('COM_REDSHOP_USER_FILTER'); ?>:
					<div class="btn-wrapper input-append">
					<input type="text" name="filter" id="filter" value="<?php echo $this->filter; ?>"
					       onchange="document.adminForm.submit();">
					<button class="btn" onclick="this.form.submit();"><?php echo JText::_('COM_REDSHOP_GO'); ?></button>
					<button class="btn reset"
						onclick="document.getElementById('filter').value='';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET'); ?></button>
					</div>
			</div>
		</div>

		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5">
					<?php
					echo JText::_('COM_REDSHOP_NUM');
					?>
				</th>
				<th width="20"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
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
				$row = $this->manufacturer [$i];
				$row->id = $row->manufacturer_id;
				$link = JRoute::_('index.php?option=com_redshop&view=manufacturer_detail&task=edit&cid[]=' . $row->manufacturer_id);

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
						   href="index.php?tmpl=component&option=com_redshop&amp;view=media_detail&amp;cid[]=<?php echo $media_id; ?>&amp;section_id=<?php echo $row->manufacturer_id; ?>&amp;showbuttons=1&amp;media_section=manufacturer&amp;section_name=<?php echo $row->manufacturer_name; ?>"
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
					<td class="order">
						<?php if ($ordering) :
							$orderDir = strtoupper($this->lists['order_Dir']);
							?>
							<div class="input-prepend">
								<?php if ($orderDir == 'ASC' || $orderDir == '') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'orderup'); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderdown'); ?></span>
								<?php elseif ($orderDir == 'DESC') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'orderdown'); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderup'); ?></span>
								<?php endif; ?>
								<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="width-20 text-area-order" />
							</div>
						<?php else : ?>
							<?php echo $row->ordering; ?>
						<?php endif; ?>
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
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
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
