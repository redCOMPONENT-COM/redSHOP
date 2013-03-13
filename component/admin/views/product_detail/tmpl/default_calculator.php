<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

$remove_format = JHtml::$formatOptions;
// calculation UNIT
$options = array();
$options[] = JHTML::_('select.option', '+', JText::_('COM_REDSHOP_PLUS'));
$options[] = JHTML::_('select.option', '-', JText::_('COM_REDSHOP_MINUS'));
$options[] = JHTML::_('select.option', '%', JText::_('COM_REDSHOP_PERCENTAGE'));
$lists['discount_calc_oprand'] = JHTML::_('select.genericlist', $options, 'pdc_oprand[]', 'class="inputbox" size="1" ', 'value', 'text', '+');
$lists['discount_calc_oprand'] = str_replace($remove_format['format.indent'], "", $lists['discount_calc_oprand']);
$lists['discount_calc_oprand'] = str_replace($remove_format['format.eol'], "", $lists['discount_calc_oprand']);

unset($options);

$model = $this->getModel();
$stockrooms = $model->StockRoomList();
?>
<script type="text/javascript">

	// create tr
	function addDiscountElement() {


		var getTABLE = document.getElementById('discount_calc_table');
		var newTR = document.createElement('tr');
		var newTD0 = document.createElement('td');
		var newTD1 = document.createElement('td');
		var newTD2 = document.createElement('td');
		var newTD3 = document.createElement('td');
		var newTD4 = document.createElement('td');

		newTD0.innerHTML = '<?php echo $this->lists['discount_calc_unit'];?>';
		newTD1.innerHTML = '<input type="text" name="area_start[]" id="area_start" value="" />';
		newTD2.innerHTML = '<input type="text" name="area_end[]" id="area_end" value="" />';
		newTD3.innerHTML = '<input type="text" name="area_price[]" id="area_price" value="" />';
		newTD4.innerHTML = '<input value="Delete" onclick="deleteDiscountElement(this)" class="button" type="button" /><input type="hidden" name="discount_calc_id[]" id="discount_calc_id" value="" />';

		newTR.appendChild(newTD0);
		newTR.appendChild(newTD1);
		newTR.appendChild(newTD2);
		newTR.appendChild(newTD3);
		newTR.appendChild(newTD4);
		getTABLE.appendChild(newTR);

	}

	function addDiscountElementExtra() {


		var getTABLE = document.getElementById('pdc_extra_table');
		var newTR = document.createElement('tr');
		var newTD0 = document.createElement('td');
		var newTD1 = document.createElement('td');
		var newTD2 = document.createElement('td');
		var newTD3 = document.createElement('td');

		newTD0.innerHTML = '<input type="text" name="pdc_option_name[]" id="pdc_option_name" value="" />';
		newTD1.innerHTML = '<?php echo $lists['discount_calc_oprand'];?>';
		newTD2.innerHTML = '<input type="text" name="pdc_price[]" id="pdc_price" value="" />';
		newTD3.innerHTML = '<input value="Delete" onclick="deleteDiscountElementExtra(this)" class="button" type="button" /><input type="hidden" name="pdcextra_id[]" id="pdcextra_id" value="" />';

		newTR.appendChild(newTD0);
		newTR.appendChild(newTD1);
		newTR.appendChild(newTD2);
		newTR.appendChild(newTD3);
		getTABLE.appendChild(newTR);

	}


	// delete tr
	function deleteDiscountElement(r) {

		var i = r.parentNode.parentNode.rowIndex;
		document.getElementById('discount_calc_table').deleteRow(i);
	}

	function deleteDiscountElementExtra(r) {

		var i = r.parentNode.parentNode.rowIndex;
		if (i == -1) {
			var g = r.parentNode.parentNode;
			document.getElementById('pdc_extra_table').removeChild(g);
		} else {
			document.getElementById('pdc_extra_table').deleteRow(i);
		}
	}

</script>
<table width="100%" cellpadding="2" border="0" cellspacing="2">
	<tr>
		<td>
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_REDSHOP_DISCOUNT_CALCULATOR'); ?></legend>
				<table class="admintable" border="0" width="100%">
					<tr>
						<td class="key"><?php echo JText::_('COM_REDSHOP_USE_DISCOUNT_CALCULATOR');?></td>
						<td><?php echo $this->lists['use_discount_calc'];?></td>
					</tr>
					<tr>
						<td class="key"><?php echo JText::_('COM_REDSHOP_DISCOUNT_CALCULATOR_METHOD');?></td>
						<td><?php echo $this->lists['discount_calc_method'];?></td>
					</tr>
					<tr>
						<td class="key"><?php echo JText::_('COM_REDSHOP_USE_RANGE');?></td>
						<td><?php echo $this->lists['use_range'];?></td>
					</tr>
				</table>
			</fieldset>
	<tr>
		<td colspan="2">
			<fieldset class="adminform">
				<table class="admintable" id="discount_calc_table" border="0"
				       width="100%">
					<tr>
						<td class="key"><?php echo JText::_('COM_REDSHOP_UNIT');?></td>
						<td class="key" align="center"><?php echo JText::_('COM_REDSHOP_RANGE_MIN');?></td>
						<td class="key" align="center"><?php echo JText::_('COM_REDSHOP_RANGE_MAX');?></td>
						<td class="key"><?php echo JText::_('COM_REDSHOP_PRICE');?></td>
						<td class="key"><a
								href="javascript:addDiscountElement();"><?php echo JText::_('COM_REDSHOP_ADD');?></a>
						</td>
					</tr>
					<?php

					$calc_data = $model->getDiscountCalcData();

					for ($i = 0; $i < count($calc_data); $i++)
					{

						// calculation UNIT
						$lists = array();
						$options = array();
						$options[] = JHTML::_('select.option', 'mm', JText::_('COM_REDSHOP_MILLIMETER'));
						$options[] = JHTML::_('select.option', 'cm', JText::_('COM_REDSHOP_CENTIMETER'));
						$options[] = JHTML::_('select.option', 'm', JText::_('COM_REDSHOP_METER'));
						$lists['discount_calc_unit'] = JHTML::_('select.genericlist', $options, 'discount_calc_unit[]', 'class="inputbox" size="1" ', 'value', 'text', $calc_data[$i]->discount_calc_unit);
						unset($options);
						?>
						<tr>
							<td><?php echo $lists['discount_calc_unit'];?></td>
							<td><input type="text" name="area_start[]" id="area_start"
							           value="<?php echo $calc_data[$i]->area_start; ?>"/></td>
							<td><input type="text" name="area_end[]" id="area_end"
							           value="<?php echo $calc_data[$i]->area_end; ?>"/></td>
							<td><input type="text" name="area_price[]" id="area_price"
							           value="<?php echo $calc_data[$i]->area_price; ?>"/></td>
							<td><input value="Delete"
							           onclick="deleteDiscountElement(this,'discount_calc_table')"
							           class="button" type="button"/><input type="hidden"
							                                                name="discount_calc_id[]"
							                                                id="discount_calc_id"
							                                                value="<?php echo $calc_data[$i]->id; ?>"/>
							</td>
						</tr>
					<?php
					}
					?>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<fieldset class="adminform">
				<table class="admintable" id="pdc_extra_table" border="0" width="100%">
					<tr>
						<td class="key"><?php echo JText::_('COM_REDSHOP_OPTION_NAME');?></td>
						<td class="key"><?php echo JText::_('COM_REDSHOP_OPRAND');?></td>
						<td class="key"><?php echo JText::_('COM_REDSHOP_PRICE');?></td>
						<td class="key"><a
								href="javascript:addDiscountElementExtra();"><?php echo JText::_('COM_REDSHOP_ADD');?></a>
						</td>
					</tr>
					<?php

					$calc_data = $model->getDiscountCalcDataExtra();

					for ($i = 0; $i < count($calc_data); $i++)
					{

						// calculation UNIT
						$options = array();
						$options[] = JHTML::_('select.option', '+', JText::_('COM_REDSHOP_PLUS'));
						$options[] = JHTML::_('select.option', '-', JText::_('COM_REDSHOP_MINUS'));
						$options[] = JHTML::_('select.option', '%', JText::_('COM_REDSHOP_PERCENTAGE'));
						$lists['discount_calc_oprand'] = JHTML::_('select.genericlist', $options, 'pdc_oprand[]', 'class="inputbox" size="1" ', 'value', 'text', $calc_data[$i]->oprand);

						unset($options);
						?>
						<tr>
							<td><input type="text" name="pdc_option_name[]" id="pdc_option_name"
							           value="<?php echo $calc_data[$i]->option_name; ?>"/></td>
							<td><?php echo $lists['discount_calc_oprand'];?></td>
							<td><input type="text" name="pdc_price[]" id="pdc_price"
							           value="<?php echo $calc_data[$i]->price; ?>"/></td>
							<td><input value="Delete" onclick="deleteDiscountElementExtra(this)"
							           class="button" type="button"/><input type="hidden"
							                                                name="pdcextra_id[]" id="pdcextra_id"
							                                                value="<?php echo $calc_data[$i]->pdcextra_id; ?>"/>
							</td>
						</tr>
					<?php
					}
					?>
				</table>
			</fieldset>
		</td>
	</tr>
	</td>
	</tr>
</table>
