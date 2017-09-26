<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$shippinghelper = shipping::getInstance();
$app      = JFactory::getApplication();
$jinput   = $app->input;
$download = $jinput->get('download');

if ($download)
{
	$oid = $jinput->getInt('oid');
	$baseURL = JURI::root();

	$name = 'label_' . $oid . '.pdf';
	$tmp_name = JPATH_COMPONENT_ADMINISTRATOR . '/assets/lables/' . $name;

	$tmp_type = strtolower(JFile::getExt($name));

	switch ($tmp_type)
	{
		case "pdf":
			$ctype = "application/pdf";
			break;
		default:
			$ctype = "application/force-download";
	}

	header("Pragma: public");
	header('Expires: 0');
	header("Content-Type: $ctype");
	header('Content-Length: ' . filesize($tmp_name));
	header('Content-Disposition: attachment; filename=' . basename($name));

	ob_clean();
	flush();
	readfile($tmp_name);
	$app->close();
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=order'); ?>" method="post" name="adminForm"
      id="adminForm">
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th class="title" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_ID', 'order_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="title" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_NUMBER', 'order_number', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="10%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_DATE', 'cdate', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="10%"></th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->orders); $i < $n; $i++)
			{
				$row = $this->orders[$i];
				$row->id = $row->order_id;
				$link = JRoute::_('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $row->order_id);
				$dlink = JRoute::_('index.php?option=com_redshop&view=order&layout=labellisting&download=1&oid=' . $row->order_id);
				$plink = JURI::base() . 'components/com_redshop/assets/lables/label_' . $row->order_id . '.pdf';
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center" class="order" width="5%">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDER'); ?>"><?php echo $row->order_id; ?></a>
					</td>
					<td align="center"><?php echo $row->order_number; ?></td>
					<td align="center"><?php echo $row->cdate; ?></td>
					<td align="center"><?php
						$details = RedshopShippingRate::decrypt($row->ship_method_id);
						if (strstr($details[0], 'default_shipping'))
						{
							if (file_exists(JPATH_SITE . '/administrator/components/com_redshop/assets/lables/label_' . $row->order_id . '.pdf'))
							{
								?>
								<a href="<?php echo $dlink; ?>"><?php echo JText::_('COM_REDSHOP_DOWNLOAD');?></a>
								<a href="<?php echo $plink; ?>"
								   target="_blank"><?php echo JText::_("COM_REDSHOP_OPEN_AND_PRINT");?></a>
							<?php
							}
						}    ?></td>
				</tr>
				<?php    $k = 1 - $k;
			}?>
			<tfoot>
			<td colspan="9">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php  echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>


	<input type="hidden" name="return" value="order"/>
	<input type="hidden" name="layout" value="labellisting"/>
	<input type="hidden" name="view" value="order"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
