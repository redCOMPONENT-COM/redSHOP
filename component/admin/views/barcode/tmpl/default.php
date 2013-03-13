<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JHTMLBehavior::modal();
$option = JRequest::getVar('option');
$url = JUri::base();

$order_id = JRequest::getInt('order_id', 0);
$log = JRequest::getVar('log');


?>

	<script language=javascript>
		window.onload = function () {
			document.getElementById('barcode').focus();
		}
	</script>


	<form action="<?php echo 'index.php?option=' . $option; ?>&view=barcode" method="post" name="adminForm"
	      id="adminForm">
		<div id="editcell" style="background-color: ">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_REDSHOP_BARCODE'); ?></legend>
				<table class="adminlist">
					<tr>
						<td align="right" width="10%"><?php echo JText::_('COM_REDSHOP_BARCODE'); ?> :</td>
						<td width="20%"><input type="text" name="barcode" value="" id="barcode"></td>
						<td><span class="editlinktip hasTip"
						          title="<?php echo JText::_('COM_REDSHOP_BARCODE_TOOLTIP') ?>"><input type="submit"
						                                                                               name="searchbtn"
						                                                                               value="<?php echo JText::_('COM_REDSHOP_CHECK_BARCODE'); ?>">  </span>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
		<input type="hidden" name="view" value="barcode"/>
		<input type="hidden" name="task" value="getsearch"/>
	</form>

<?php if ($order_id != 0)
{ ?>
	<div>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_BARCODE_INFO'); ?></legend>
			<?php echo $this->loadTemplate('barcodeinfo');?>
		</fieldset>
	</div>
	<?php if ($log)
{
	?>
	<div>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_BARCODE_INFO_LOG'); ?></legend>
			<?php echo $this->loadTemplate('barcodeloginfo');?>
		</fieldset>
	</div>
<?php } ?>
	<div id="framediv">
		<?php $link = JUri::base() . 'index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $order_id . '&tmpl=component';?>

		<iframe src="<?php echo $link; ?>" width="900" height="800" style="border: 0px;"></iframe>
	</div>
<?php } ?>