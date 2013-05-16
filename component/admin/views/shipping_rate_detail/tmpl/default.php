<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');

$editor = JFactory::getEditor();
$productHelper = new producthelper;
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.shipping_rate_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_NAME_MUST_HAVE_A_NAME', true); ?>");
		} else {
			if (document.getElementById('container_product')) {
				selectAll(document.getElementById('container_product'));
			}
			submitform(pressbutton);
		}
	}
</script>
<form action="<?php echo JRoute::_($this->request_url); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<input type="hidden" name="extension_id" value="<?php echo $this->shipping->extension_id; ?>"/>
<input type="hidden" name="shipping_class" value="<?php echo $this->shipping->element; ?>"/>
<input type="hidden" name="shipping_rate_id" id="shipping_rate_id" value="<?php echo $this->detail->shipping_rate_id; ?>"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="view" value="shipping_rate_detail"/>
<?php
if ($this->shipper_location)
{
	?>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<table class="admintable" width="100%">
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_SHIPPING_LOCATION'); ?>:
					</td>
					<td>
						<input class="text_area" type="text" name="shipping_rate_name" id="shipping_rate_name" size="32" maxlength="250" value="<?php echo $this->detail->shipping_rate_name; ?>"/>
					</td>
				</tr>
				<td class="key">
					<?php echo JText::_('COM_REDSHOP_SHIPPING_LOCATION_INFORMATION'); ?>:
				</td>
				<td>
					<?php echo $editor->display("shipping_location_info", $this->detail->shipping_location_info, '$widthPx', '$heightPx', '100', '20', '1'); ?>
				</td>
				</tr>
			</table>
		</fieldset>
	</div>
<?php
}
else
{
	// Get JPaneTabs instance
	$myTabs = JPane::getInstance('tabs', array('startOffset' => 0));
	$output = '';

	// Create Pane
	$output .= $myTabs->startPane('pane');

	// Create 1st Tab
	echo $output .= $myTabs->startPanel(JText::_('COM_REDSHOP_DETAILS'), 'tab1');    ?>
	<div class="col50">
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

	<table class="admintable" width="100%">

	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_NAME'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_name" id="shipping_rate_name" size="32"
			       maxlength="250" value="<?php echo $this->detail->shipping_rate_name; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_WEIGHT_START'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_weight_start" id="shipping_rate_weight_start"
			       size="32" maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_weight_start); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_WEIGHT_END'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_weight_end" id="shipping_rate_weight_end" size="32"
			       maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_weight_end); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_VOLUME_START'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_volume_start" id="shipping_rate_volume_start"
			       size="32" maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_volume_start); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_VOLUME_END'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_volume_end" id="shipping_rate_volume_end" size="32"
			       maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_volume_end); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_LENGTH_START'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_length_start" id="shipping_rate_length_start"
			       size="32" maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_length_start); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_LENGTH_END'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_length_end" id="shipping_rate_length_end" size="32"
			       maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_length_end); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_WIDTH_START'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_width_start" id="shipping_rate_width_start"
			       size="32" maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_width_start); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_WIDTH_END'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_width_end" id="shipping_rate_width_end" size="32"
			       maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_width_end); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_HEIGHT_START'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_height_start" id="shipping_rate_height_start"
			       size="32" maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_height_start); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_HEIGHT_END'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_height_end" id="shipping_rate_height_end" size="32"
			       maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_height_end); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_ORDERTOTAL_START'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_ordertotal_start"
			       id="shipping_rate_ordertotal_start" size="32" maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_ordertotal_start); ?>"/>
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_ORDERTOTAL_END'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_ordertotal_end" id="shipping_rate_ordertotal_end"
			       size="32" maxlength="250"
			       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_ordertotal_end); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_ZIP_START'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_zip_start" id="shipping_rate_zip_start" size="32"
			       maxlength="250" value="<?php echo $this->detail->shipping_rate_zip_start; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_ZIP_END'); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="shipping_rate_zip_end" id="shipping_rate_zip_end" size="32"
			       maxlength="250" value="<?php echo $this->detail->shipping_rate_zip_end; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:
			</label>
		</td>
		<td>
			<?php echo $this->lists['shipping_rate_country']; ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_STATE'); ?>:
			</label>
		</td>
		<td>
			<div id='changestate'>
				<?php echo $this->lists['shipping_rate_state']; ?>
			</div>
		</td>
	</tr>
	<table class="admintable">
		<tr width="100px">
			<td VALIGN="TOP" class="key" align="center">
				<?php echo JText::_('COM_REDSHOP_PRODUCT'); ?> <br/><br/>
				<input style="width: 250px" type="text" id="input" value=""/>

				<div style="display:none"><?php
					echo $this->lists['product_all'];
					?></div>
			</td>
			<TD align="center">
				<input type="button" value="-&gt;" onClick="moveRight(10);" title="MoveRight">
				<BR><BR>
				<input type="button" value="&lt;-" onClick="moveLeft();" title="MoveLeft">
			</TD>
			<TD VALIGN="TOP" align="right" class="key" style="width: 250px">
				<?php echo JText::_('COM_REDSHOP_SHIPPINGRATE_PRODUCT'); ?><br/><br/>
				<?php
				echo $this->lists['shipping_product'];?>
			</td>
		</tr>
	</table>
	<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_CATEGORY'); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['shipping_rate_on_category']; ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP'); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['shipping_rate_on_shopper_group']; ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_VALUE'); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="shipping_rate_value" id="shipping_rate_value" size="32"
				       maxlength="250"
				       value="<?php echo $productHelper->redpriceDecimal($this->detail->shipping_rate_value); ?>"/>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_PRIORITY'); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="shipping_rate_priority" id="shipping_rate_priority" size="32"
				       maxlength="250" value="<?php echo $this->detail->shipping_rate_priority; ?>"/>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_SHIPPING_RATE_FOR'); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['company_only']; ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_SHIPPING_VAT_GROUP_LBL'); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['shipping_tax_group_id']; ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_ADD_VAT'); ?>:
				</label>
			</td>
			<td>
				<?php

					$checked = '';

					if ($this->detail->apply_vat)
					{
						$checked = "checked='checked'";
					}

					echo "<input type='checkbox' value='1' name='apply_vat' $checked />";
				?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_CONSIGNOR_CARRIER_CODE'); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="consignor_carrier_code" id="consignor_carrier_code" size="32"
				       maxlength="250" value="<?php echo $this->detail->consignor_carrier_code; ?>"/>
			</td>
		</tr>
	<?php

		if ($this->detail->shipping_class === 'default_shipping')
		{
	?>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_DELIVER_TYPE'); ?>:
				</label>
			</td>
			<td>
				<div id='changestate'>
					<?php echo $this->lists['deliver_type'];?>
				</div>
			</td>
		</tr>
	<?php
		}
	?>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_ECONOMIC_DISPLAYNUMBER'); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="economic_displaynumber" id="economic_displaynumber" size="32" maxlength="250" value="<?php echo $this->detail->economic_displaynumber; ?>"/>
			</td>
		</tr>
	</table>

	</fieldset>
	</div>
	<?php

	echo $myTabs->endPanel();

// Create 2nd Tab

	if ($this->lists['extra_field'] != "")
	{
		echo $myTabs->startPanel(JText::_('COM_REDSHOP_EXTRA_FIELD'), 'tab2');
		?>
		<div class="col50">
		<?php
		echo $this->lists['extra_field'];
		?>
		</div><?php
	}
	else
	{
		echo '<input type="hidden" name="noextra_field" value="1">';
	}

// End Pane
	echo $myTabs->endPane();
}    ?>
<div class="clr"></div>
</form>

<script type="text/javascript">

	var options = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&alert=shipping&",
		varname: "input",
		json: true,
		shownoresults: true,

		callback: function (obj) {
			var selTo = document.adminForm.container_product;
			var chk_add = 1;
			for (var i = 0; i < selTo.options.length; i++) {
				if (selTo.options[i].value == obj.id) {
					chk_add = 0;
				}
			}
			if (chk_add == 1) {
				var newOption = new Option(obj.value, obj.id);
				selTo.options[selTo.options.length] = newOption;
			}
			document.adminForm.input.value = "";
		}
	};

	var as_json = new bsn.AutoSuggest('input', options);

</script>
