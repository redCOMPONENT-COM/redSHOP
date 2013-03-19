<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTMLBehavior::modal();

$option = JRequest::getVar('option', '', 'request', 'string');


$lists = $this->lists;


$container_product = $this->lists['container_product'];

?>
<div class="key">
	<br><br>

	<h2><?php echo $this->detail->container_name;?></h2>

	<h2>Selected Products</h2>
	<br>
</div>
<table id="tbl_selected" class="adminlist" border="0">

	<thead>
	<tr>
		<!--<td>&nbsp;</td>-->
		<th class="title" width="200"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
		<th width="20"><?php echo JText::_('COM_REDSHOP_PRODUCT_QUANTITY'); ?></th>
		<th width="20"><?php echo JText::_('COM_REDSHOP_PRODUCT_VOLUME_UNIT'); ?></th>
		<th width="50"><?php echo JText::_('COM_REDSHOP_DELETE'); ?></th>


	</tr>
	</thead>
	<?php
	$k = 0;
	$totvolume = 0;
	$totqty = 0;
	for ($i = 0, $n = count($container_product); $i < $n; $i++)
	{

		$row = $container_product[$i];




		?>
		<tr>
			<input onclick="calculateVolume();" name="container_product_2[]" id="container_product_2" type="hidden"
			       value="<?php echo $row->product_id; ?>">
			<td width="200"><?php echo $row->product_name; ?></td>
			<td width="20"><input size="5" class="text_area" value="<?php echo $row->quantity ?>"
			                      onchange="changeM3(<?php echo $row->product_id; ?>,this.value,<?php echo $row->product_volume ?>);calculateVolume();"
			                      name="quantity_<?php echo $row->product_id; ?>" type="text">
				<input value="<?php echo $row->product_id; ?>" name="container_product_<?php echo $row->product_id; ?>"
				       type="hidden">
				<input value="0" name="container_porder[]" type="hidden"></td>
			<td align="center" width="20"><input size="5" name="volume[]" id="volume<?php echo $row->product_id; ?>"
			                                     value="<?php echo $row->product_volume * $row->quantity ?>"
			                                     readonly="readonly" type="text"></td>
			<td width="50">
				<input value="X"
				       onclick="deleteProduct('<?php echo $row->product_id; ?>','<?php echo $row->container_id; ?>');"
				       class="button" type="button">
			</td>
		</tr>

		<?php
		$k = 1 - $k;

		$totvolume += $row->product_volume * $row->quantity;
		$totqty += $row->quantity;
	}
	$remainvol = $this->detail->container_volume - $totvolume;
	?>
	<tr>
		<td><b><?php echo JText::_('COM_REDSHOP_TOTAL_AMOUNT'); ?></b></td>
		<td><input size="5" class="text_area" value="<?php echo $totqty ?>" type="text"></td>
		<td><input size="5" class="text_area" value="<?php echo $totvolume ?>" type="text"></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('COM_REDSHOP_TOTAL_M3_FILLED'); ?></b></td>
		<td>&nbsp;</td>
		<td><input size="5" class="text_area" value="<?php echo $remainvol ?>" type="text"></td>
		<td>&nbsp;</td>
	</tr>
</table>
<?php exit; ?>
