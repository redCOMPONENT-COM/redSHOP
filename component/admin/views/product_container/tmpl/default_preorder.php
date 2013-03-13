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

?>
<table id="tbl_preorder" class="adminlist" cellpadding="1" cellspacing="1">

	<?php
	$k = 0;
	$totvolume = 0;
	for ($i = 0, $n = count($this->products); $i < $n; $i++)
	{

		$row = & $this->products[$i];
		//var_dump($row);
		$row->id = $row->product_id;

		$link = JRoute::_('index.php?option=' . $option . '&view=product_detail&task=edit&cid[]=' . $row->product_id);

		//$published 	= JHtml::_('jgrid.published', $row->published, $i,'',1);		

		?>
		<tr>
			<input onclick="calculateVolume();" name="container_product[]" id="container_product" type="hidden"
			       value="<?php echo $row->product_id; ?>">
			<td width="200"><?php echo $row->product_name; ?><input name="product_name[]" id="product_name"
			                                                        type="hidden"
			                                                        value="<?php echo $row->product_name; ?>"></td>
			<td width="20"><input size="5" class="text_area" value="<?php echo $row->product_quantity ?>"
			                      onchange="changeM3(<?php echo $row->product_id; ?>,this.value,<?php echo $row->product_volume ?>);"
			                      name="quantity[]" id="quantity" type="text">
				<input value="<?php echo $row->product_volume; ?>" name="product_volume[]" id="product_volume"
				       type="hidden">
			</td>
			<td align="center" width="20"><input size="5" id="volume<?php echo $row->product_id; ?>"
			                                     value="<?php echo $row->product_volume * $row->product_quantity ?>"
			                                     readonly="readonly" type="text"></td>
			<td width="50">
				<input size="5" class="text_area" value="<?php if ($row->show_qty) echo $row->product_quantity ?>"
				       id="quantity2" name="quantity2[]" type="text">
				<!--			<input value="X" onclick="deleteRow_property(this,'tbl_preorder');" class="button" type="button">-->
			</td>
		</tr>

		<?php
		$k = 1 - $k;

		//if($container == 1)
		{
			$totvolume = $totvolume + ($row->product_volume * $row->product_quantity);
		}
	}
	?>
</table>