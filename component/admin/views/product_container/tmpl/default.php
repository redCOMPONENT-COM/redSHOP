<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JHTMLBehavior::modal();

$option = JRequest::getVar('option', '', 'request', 'string');

$model = $this->getModel('product_container');
$lists = $this->lists;
$filter_container = $this->filter_container;
$filter_manufacturer = $this->filter_manufacturer;
$container = JRequest::getVar('container', '', 'request', 0);
$showbuttons = JRequest::getVar('showbuttons', '', 'request', 0);
$print_display = JRequest::getVar('print_display', '', 'request', 0);


$print_link = JRoute::_('index.php?tmpl=component&option=com_redshop&view=product_container&showbuttons=1&container=' . $container . '&filter_manufacturer=' . $filter_manufacturer . '&filter_container=' . $filter_container);

if ($showbuttons == 1 && $print_display != 1)
{
	echo '<div align="right"><br><br><input type="button" class="button" value="Print" onClick="window.print()"><br><br></div>';
}

?>
	<script language="javascript" type="text/javascript">

		Joomla.submitbutton = function (pressbutton) {
			submitbutton(pressbutton);
		}
		submitbutton = function (pressbutton) {
			var form = document.adminForm;

			if (pressbutton == "print_data") {
				window.open("<?php echo $print_link;?>", "Print", "status=1,toolbar=1");
				return false;
			}


			if (pressbutton) {
				form.task.value = pressbutton;
			}

			if ((pressbutton == 'addcontainer')) {

				form.view.value = "container_detail";
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
if ($showbuttons != 1 && $print_display != 1)
{
	?>
	<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
		<?php }
		?>
		<input type="hidden" name="container" value="<?php echo $container ?>"/>

		<div id="editcell">
			<?php if ($showbuttons != 1)
			{ ?>
				<table class="adminlist">
					<tr>
						<td valign="top" align="right" class="key">
							<?php echo $lists['filter_supplier']; ?>     <?php if ($container == 1)
							{
								echo $lists['filter_container'];
							} ?>
						</td>
					</tr>
				</table>
			<?php } ?>
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
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NUMBER', 'p.product_number', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
					<th class="title">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'p.product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>

					<th>
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_DESCRIPTION', 'p.product_description', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
					<th>
						<?php echo JText::_('COM_REDSHOP_CATEGORY'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_REDSHOP_CONTAINER_NO'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_REDSHOP_ORDER_NO'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_REDSHOP_QUANTITY'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_REDSHOP_VOLUME'); ?> (m3)
					</th>
					<th>
						<?php echo JText::_('COM_REDSHOP_SUPPLIER'); ?>
					</th>
					<th style="display:none;" width="5%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
					<th width="5%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'p.product_id', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>

				</tr>
				</thead>
				<?php
				$k = 0;
				$totvolume = 0;
				for ($i = 0, $n = count($this->products); $i < $n; $i++)
				{

					$row = & $this->products[$i];
					//var_dump($row);
					$row->id = $row->product_id;
					$link = JRoute::_('index.php?option=' . $option . '&view=product_detail&task=edit&cid[]=' . $row->product_id);

					$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

					?>
					<tr class="<?php echo "row$k"; ?>">
						<td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<td>
							<?php
							$row->id = $row->order_item_id;
							echo JHTML::_('grid.id', $i, $row->id); ?>
						</td>

						<td>
							<?php echo $row->product_number; ?>
						</td>
						<td>
							<a href="<?php echo $link; ?>"
							   title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->product_name; ?></a>
						</td>

						<td>
							<?php $shortdesc = substr($row->product_s_desc, 0, 50); echo $shortdesc; ?>
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
							<?php echo $row->ocontainer_id; ?>
						</td>
						<td>
							<?php echo $row->order_id; ?>
						</td>
						<td>
							<?php echo $row->product_quantity; ?>
						</td>
						<td>
							<?php echo $row->product_volume * $row->product_quantity; ?>
						</td>
						<td>
							<?php  echo $row->supplier_name; ?>
						</td>
						<td style="display:none;" align="center" width="8%">
							<?php echo $published;?>
						</td>
						<td align="center" width="5%">
							<?php echo $row->product_id; ?>
						</td>
					</tr>
					<input type="hidden" value="<?php echo $row->product_quantity; ?>" name="quantity[]">
					<input type="hidden" value="<?php echo $row->product_id; ?>" name="container_product[]">
					<?php
					$k = 1 - $k;


					//if($container == 1)
					{
						$totvolume = $totvolume + ($row->product_volume * $row->product_quantity);
					}
				}
				?>
				<tr>
					<td align="right" colspan="8">Total volume</td>
					<td><?php echo $totvolume; ?>
					</td>
				</tr>
				<?php if ($showbuttons != 1)
				{ ?>
					<tfoot>
					<td colspan="12">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
					</tfoot>
				<?php } ?>


			</table>
		</div>
		<?php if ($print_display != 1)
		{ ?>
			<input type="hidden" name="view" value="product_container"/>
			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
			<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
			<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
		<?php } ?>
		<?php
		if($showbuttons != 1 && $print_display != 1)
		{
		?>
	</form>
<?php
}
?>