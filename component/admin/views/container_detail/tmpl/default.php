<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$editor = JFactory::getEditor();

JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');
JHTMLBehavior::modal();

$stockroom_id = JRequest::getVar('stockroom_id', '', 'request', 'string');

$date = JFactory::getDate();

//echo $date->toFormat('%a %d %b %Y - %H:%M');


?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.container_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_CONTAINER_ITEM_MUST_HAVE_A_NAME', true ); ?>");
		} else if (form.min_del_time.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_HAVE_A_DELIVERY_TIME', true ); ?>");
		} else if (form.max_del_time.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_HAVE_A_DELIVERY_TIME', true ); ?>");
		} else {
			submitform(pressbutton);
		}

	}
</script>
<?php
$showbuttons = JRequest::getCmd('showbuttons');
if ($showbuttons == 1)
{
	?>
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('save');">
				<?php echo JText::_('COM_REDSHOP_SAVE'); ?>
			</button>
			<button type="button" onclick="window.parent.SqueezeBox.close();">
				<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
			</button>
		</div>
		<div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_CONTAINER'); ?></div>
	</fieldset>
<?php } ?>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<?php
if ($this->detail->container_id)
{
	?>
	<!--<div style="width:100%; text-align:right; font-weight:bold; font-size:14px;">
	<a class="modal" href="index.php?tmpl=component&option=com_redshop&amp;view=product_detail&amp;task=edit&showbuttons=1&container=1&container_id=<?php echo $this->detail->container_id;?>" rel="{handler: 'iframe', size: {x: 900, y: 500}}">
		<?php echo JText::_('COM_REDSHOP_ADD_PRODUCT' ); ?>
	</a>
</div>
--><?php
}

//Get JPaneTabs instance
$myTabs = JPane::getInstance('tabs', array('startOffset' => 0));
$output = '';

//Create Pane
$output .= $myTabs->startPane('pane');
//Create 1st Tab
echo $output .= $myTabs->startPanel(JText::_('COM_REDSHOP_CONTAINER_INFO'), 'tab1');
?>
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key">
					<label for="name">
						<?php echo JText::_('COM_REDSHOP_NAME'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="container_name" id="container_name" size="32"
					       maxlength="250" value="<?php echo $this->detail->container_name; ?>"/>

				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_('COM_REDSHOP_CONTAINER_START_DATE'); ?>:
					</label>
				</td>
				<td>
					<?php
					if ($this->detail->creation_date)
						$datee = date("d-m-Y", $this->detail->creation_date);
					else
						$datee = date("d-m-Y", $date->_date);
					echo JHTML::_('calendar', $datee, 'creation_date', 'creation_date', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '32', 'maxlength' => '19'));
					?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_('COM_REDSHOP_MINIMUM_DELIVERY_TIME'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="min_del_time" id="min_del_time"
					       value="<?php echo $this->detail->min_del_time; ?>" size="32" maxlength="250"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MINIMUM_DELIVERY_TIME'), JText::_('COM_REDSHOP_MINIMUM_DELIVERY_TIME'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_('COM_REDSHOP_MAXIMUM_DELIVERY_TIME'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="max_del_time" id="max_del_time"
					       value="<?php echo $this->detail->max_del_time; ?>" size="32" maxlength="250"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAXIMUM_DELIVERY_TIME'), JText::_('COM_REDSHOP_MAXIMUM_DELIVERY_TIME'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="volume">
						<?php echo JText::_('COM_REDSHOP_VOLUME_SIZE'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="container_volume" id="container_volume"
					       value="<?php echo $this->detail->container_volume; ?>" size="32" maxlength="250"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_VOLUME_SIZE'), JText::_('COM_REDSHOP_VOLUME_SIZE'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="volume">
						<?php echo JText::_('COM_REDSHOP_CONTAINER_MOVE_TO'); ?>:
					</label>
				</td>
				<td>
					<?php if ($showbuttons)
					{
						$model = $this->getModel('container_detail');
						$stockdata = $model->stockroom_Data($stockroom_id);
						echo $stockdata[0]->text;
						?>
						<input type="hidden" name="stockroom_id" value="<?php echo $stockdata[0]->value; ?>">
					<?php
					}
					else
					{
						echo $this->lists['stock'];
					}
					?>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CONTAINER_MOVE_TO'), JText::_('COM_REDSHOP_CONTAINER_MOVE_TO'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<!--<tr>
		<td width="100" align="right" class="key">
		<label for="name"><?php echo JText::_('COM_REDSHOP_MANUFACTURER' ); ?></td>
		<td><?php echo $this->lists['manufacturers']; ?></td>
		</tr>
		-->
			<tr>
				<td width="100" align="right" class="key">
					<label for="name"><?php echo JText::_('COM_REDSHOP_SUPPLIER'); ?></td>
				<td><?php echo $this->lists['supplier']; ?></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
				</td>
				<td>
					<?php echo $this->lists['published']; ?>
				</td>
			</tr>

		</table>
	</fieldset>
</div>
<div class="col50">

</div>

<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?></legend>

		<table class="admintable">
			<tr>
				<td>
					<?php echo $editor->display("container_desc", $this->detail->container_desc, '$widthPx', '$heightPx', '100', '20', '1');    ?>

				</td>
			</tr>
		</table>
	</fieldset>
</div>

<?php
echo $myTabs->endPanel();
//Create 2nd Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_CONTAINER_PRODUCT'), 'tab2');
?>
<div class="col50">
	<table width="100%">
		<tr>
			<td valign="top">
				<table class="admintable" border="0">
					<tr>
						<td VALIGN="TOP" class="key" align="center" style="width: 250px">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_SOURCE'); ?> <br/><br/>
							<!--<input style="width: 200px" type="text"  id="input" name="input" value=""  />-->
							<!--<input style="width:200px;position:absolute;margin-left:-202px;margin-top:1px;padding:0px;" type="text" id="cinput" value="" onkeypress="change_input(event,this.value)" onkeyup="change_input(event,this.value)"  />-->
							<table border="0" width="100%">
								<tr align="left">
									<td>
										<!--<input  type="checkbox" onclick="chk_existingproduct(this);"     value="1"  />-->
									</td>
									<td>

										<a class="modal"
										   href="index.php?tmpl=component&option=com_redshop&amp;view=product_detail&amp;task=edit&showbuttons=1&container=1&container_id=<?php echo $this->detail->container_id; ?>"
										   rel="{handler: 'iframe', size: {x: 900, y: 500}}">
											<?php echo JText::_('COM_REDSHOP_CONTAINER_NEW_PRODUCT'); ?>
										</a>


									</td>
								</tr>
								<tr align="left">
									<td><input type="checkbox" onclick="chk_newproduct(this);"
									           value="1" <?php if ($this->chk_new != 1)
										{ ?>  <?php } ?> /></td>
									<td><?php echo JText::_('COM_REDSHOP_EXISTING_PRODUCTS'); ?> </td>
								</tr>
								<tr align="left">
									<td>
										<input type="checkbox" name="porder" id="porder1"
										       onclick="chk_preorder();" <?php if ($this->chk_new == 1)
										{ ?>   <?php } ?> value="0"/>
									</td>
									<td><?php echo JText::_('COM_REDSHOP_CONTAINER_PRE_ORDER_PRODUCT'); ?></td>
								</tr>

							</table>
						</td>
						<td>
							<?php //echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_VOLUME_SIZE' ), JText::_('COM_REDSHOP_PLEASE_SELECT_MANUFACTURER_FIRST' ), 'tooltip.png', '', '', false); ?>
						</td>
						<td colspan="2"></td>
					</tr>
					<tr>

						<td colspan="2" valign="top">
							<table id="container_table" class="adminlist" border="0">
								<thead>
								<tr>
									<!--<td>&nbsp;</td>-->
									<th class="title"
									    width="200"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
									<th width="20"><?php echo JText::_('COM_REDSHOP_PRODUCT_QUANTITY'); ?></th>
									<th width="20"><?php echo JText::_('COM_REDSHOP_PRODUCT_VOLUME_UNIT'); ?></th>
									<!--<th  width="50"><?php echo JText::_('COM_REDSHOP_DELETE' ); ?></th>
-->
									<th width="50"><?php echo JText::_('COM_REDSHOP_PRODUCT_QUANTITY'); ?></th>
								</tr>
								</thead>
								<tbody>
								<?php
								$container_product = $this->lists['container_product'];
								$h = 1;
								if ($this->chk_new == 1)
									$chk_new = 1;
								else
									$chk_new = 0;

								$pr_array = array();
								$pr_quantity["quantity"] = array();

								for ($f = 0; $f < count($container_product); $f++)
								{
									$tmp_array[] = $container_product[$f]->product_id;
									$pr_quantity["quantity"][$container_product[$f]->product_id][$f] = $container_product[$f]->quantity;
								}

								$volume = 0;
								for ($f = 0; $f < count($container_product); $f++)
								{
									break;

									if ($chk_new == 1)
									{

										$quantity_data = array_sum($pr_quantity["quantity"][$container_product[$f]->product_id]);

										if (!in_array($container_product[$f]->product_id, $pr_array))
										{
											$pr_array[] = $container_product[$f]->product_id;
											//$quantity_data=1;
											echo'<tr>';
											echo ' <input onclick = "calculateVolume();" id="container_product" name="container_product[]" type="hidden" value="' . $container_product[$f]->product_id . '"  >';
											echo '<td>' . $container_product[$f]->product_name . '</td>';
											echo '<td><input size="5" class="text_area" type="text" value="' . $quantity_data . '" onchange="changeM3(' . $container_product[$f]->product_id . ',this.value,' . $container_product[$f]->product_volume . ');calculateVolume();" name="quantity_' . $container_product[$f]->product_id . '" >
			<input type="hidden" value="' . $container_product[$f]->product_id . '"  name="container_product_' . $container_product[$f]->product_id . '" >
			<input type="hidden" value="' . $chk_new . '" name="container_porder[]" >';
											echo '</td>';
											echo '<td align="center"><input size="5" type="text" name="volume[]" id="volume' . $container_product[$f]->product_id . '" value="' . $quantity_data * $container_product[$f]->product_volume . '" readonly="readonly" /></td>';
											//echo '<td>';
											//echo '<input value="X" onclick="deleteRow_container(this);" class="button" type="button">';

											//echo '</td>';
											echo '<td><input size="5" class="text_area" type="text" value="' . $quantity_data . '"  id="quantity2"  name="quantity2[]" >';

											echo'</tr>';
											$volume += $quantity_data * $container_product[$f]->product_volume;
										}
										echo '<input type="hidden" value="' . $container_product[$f]->order_item_id . '" name="cid[]" >';
									}
									else
									{
										$quantity_data = array_sum($pr_quantity["quantity"][$container_product[$f]->product_id]);
										echo'<tr>';
										echo ' <input  onclick = "calculateVolume();" id="container_product" name="container_product[]" type="hidden" value="' . $container_product[$f]->product_id . '"  > ';
										echo '<td>' . $container_product[$f]->product_name . '</td>';
										echo '<td><input size="5" class="text_area" type="text" value="' . $container_product[$f]->quantity . '" onchange="changeM3(' . $container_product[$f]->product_id . ',this.value,' . $container_product[$f]->product_volume . ');calculateVolume();" name="quantity_' . $container_product[$f]->product_id . '" >
	<input type="hidden" value="' . $container_product[$f]->product_id . '" name="container_product_' . $container_product[$f]->product_id . '" >
	<input type="hidden" value="' . $chk_new . '" name="container_porder[]" >';

										echo '</td>';

										echo '<td align="center"><input size="5" type="text" name="volume[]" id="volume' . $container_product[$f]->product_id . '" value="' . $quantity_data * $container_product[$f]->product_volume . '" readonly="readonly" /></td>';
										echo '<td><input size="5" class="text_area" type="text" value="' . $quantity_data . '" id="quantity2"  name="quantity2[]" >';

										//echo '<td>';
										//echo '<input value="X" onclick="deleteRow_container(this);" class="button" type="button">';
										//echo '</td>';

										echo'</tr>';

										$volume += $quantity_data * $container_product[$f]->product_volume;
									}


									$h++;
								}

								?>

								</tbody>


							</table>
							<div id="existingproduct_div"></div>
							<div id="newproduct_div"></div>
							<div id="preorder_div"></div>
							<div align="right">
								<input type="button" onclick="savecontainer();"
								       value="<?php echo JText::_('COM_REDSHOP_ADD_TO_CONTAINER'); ?>">
							</div>
						</td>


					</tr>
				</table>
			</td>

			<td valign="top" id="selected_products">

			</td>
		</tr>
	</table>
</div>

<?php
echo  $myTabs->endPanel();
//End Pane
echo $myTabs->endPane();
?>

<div class="clr"></div>
<?php
if ($showbuttons)
{
	?>
	<input type="hidden" name="stockroom_id" value="<?php echo $stockroom_id; ?>"/>
	<input type="hidden" name="showbuttons" value="<?php echo $showbuttons; ?>"/>
<?php } ?>
<input type="hidden" name="container_id" value="<?php echo $this->detail->container_id; ?>"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="view" value="container_detail"/>
<input type="hidden" name="total_extra" id="total_extra" value="<?php echo $h - 1; ?>"/>
<input type="hidden" name="boxchecked" value="0"/>
</form>

<script type="text/javascript">
function chk_existingproduct(obj) {
	if (obj.checked) {

		xmlHttp = xml_object();
		xmlHttp.onreadystatechange = function () {
			if (xmlHttp.readyState == 4) {
				//alert(xmlHttp.responseText);
				document.getElementById("existingproduct_div").innerHTML = xmlHttp.responseText;

			}

		}
		var rand_no = Math.random();
		xmlHttp.open("GET", "index.php?tmpl=component&option=com_redshop&view=product_container&showbuttons=1&print_display=1&preorder=1&existingproducts=1&rand_no=" + rand_no + "&container_id=<?php echo $this->detail->container_id; ?>", true);
		xmlHttp.send(null);

	}
	else {
		document.getElementById("existingproduct_div").innerHTML = "";
	}
}
function chk_newproduct(obj) {
	if (obj.checked) {

		xmlHttp = xml_object();
		xmlHttp.onreadystatechange = function () {
			if (xmlHttp.readyState == 4) {
				//alert(xmlHttp.responseText);
				document.getElementById("newproduct_div").innerHTML = xmlHttp.responseText;

			}

		}
		var rand_no = Math.random();
		xmlHttp.open("GET", "index.php?tmpl=component&option=com_redshop&view=product_container&showbuttons=1&print_display=1&preorder=1&rand_no=" + rand_no + "&newproducts=1", true);
		xmlHttp.send(null);

	}
	else {
		document.getElementById("newproduct_div").innerHTML = "";
	}
}
var tmpstr = '';
tmpint = 0;
function savecontainer() {

	var volume = 0;

	var url = '';
	var container_volume = parseInt(document.adminForm.container_volume.value);
	var n = document.adminForm.container_product.length;
	var container_name = document.adminForm.container_name.value;
	var k = 0;
	var totvolume = 0;
	var totqty = 0;

	var str = "";
	var str2 = tmpstr;

	str += '<div class="key"><br><br>';

	str += '<h2>' + container_name + '</h2>';
	str += '<h2><?php echo JText::_('COM_REDSHOP_SELECTED_PRODUCTS'); ?></h2><br> ';

	str += '</div>';
	str += '<table id="tbl_selected" class="adminlist" border="0" ><thead><tr><th class="title" width="200"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME' ); ?></th><th  width="20"><?php echo JText::_('COM_REDSHOP_PRODUCT_QUANTITY' ); ?></th><th  width="20"><?php echo JText::_('COM_REDSHOP_PRODUCT_VOLUME_UNIT' ); ?></th><th  width="50"><?php echo JText::_('COM_REDSHOP_DELETE' ); ?></th></tr></thead>';


	for (i = 0; i < n; i++) {
		quantity = document.adminForm.quantity2[i].value;

		if (quantity < 1) {
			continue;
		}

		totqty += parseInt(quantity);

		product_id = document.adminForm.container_product[i].value;
		product_name = document.adminForm.product_name[i].value;

		v1 = document.adminForm.product_volume[i].value;

		volume = parseInt(quantity) * parseInt(v1);

		totvolume += volume;

		str2 += '<tr id="tr_' + tmpint + '">';
		str2 += '<input  name="container_product_2[]" id="container_product_2" type="hidden" value="' + product_id + '">';

		str2 += '<td width="200">' + product_name + '</td>';
		str2 += '	<td width="20"><input size="5" class="text_area" value="' + quantity + '" onchange="calculateVolume();"   name="quantity3[]"  id="quantity3" type="text">';
		str2 += '	<input value="' + product_id + '" name="container_product_' + product_id + '" type="hidden">';
		str2 += '	<input value="0" name="container_porder[]" type="hidden"></td>';
		str2 += '	<td align="center" width="20"><input size="5" name="volume[]" id="volume" value="' + volume + '" readonly="readonly" type="text"><input   name="org_volume[]" id="org_volume" value="' + v1 + '"  type="hidden"></td>';
		str2 += '	<td width="50">';
		str2 += '	<input value="X" onclick=\'deleteProduct("tr_' + tmpint + '");calculateVolume();\' class="button" type="button">';
		str2 += '   </td>';
		str2 += '  </tr>';
		tmpint++;
	}

	tmpstr = str2;

	str += str2;
	str += '<tr>';
	str += '<td><b><?php echo JText::_('COM_REDSHOP_TOTAL_VOLUME_IN_CONTAINER'); ?></b></td>';
	str += '<td><input size="5" name = "totqty" class="text_area" value="' + totqty + '"  type="text"></td>';
	str += '<td><input size="5" name = "totvolume" class="text_area" value="' + totvolume + '"  type="text"></td>';
	str += '<td>&nbsp;</td>';
	str += '</tr>';


	str += '<tr>';
	str += '<td><b><?php echo JText::_('COM_REDSHOP_VOLUME_LEFT_IN_CONTAINER'); ?></b></td>';
	str += '<td>&nbsp;</td>';
	str += '<td><input size="5" name="leftvolume" class="text_area" value="' + (container_volume - totvolume) + '"  type="text"></td>';
	str += '<td>&nbsp;</td>';
	str += '</tr>';
	str += '</table> ';

	document.getElementById('selected_products').innerHTML = str;

	calculateVolume();
}
function displaycontainerproducts() {

	request = getHTTPObject();
	request.onreadystatechange = viewProducts;
	var rand_no = Math.random();
	request.open("GET", "index.php?tmpl=component&option=com_redshop&tmpl=component&layout=products&view=container_detail&rand_no=" + rand_no + "&task=edit&cid[]=<?php echo $this->detail->container_id; ?>", true);
	request.send(null);
}
function deleteProduct(obj) {


	document.getElementById(obj).innerHTML = '';
	return;

	request = getHTTPObject();
	request.onreadystatechange = viewProducts;
	var rand_no = Math.random();
	request.open("GET", "index.php?tmpl=component&option=com_redshop&tmpl=component&no_html=1&view=container_detail&task=deleteproduct&rand_no=" + rand_no + "&container_id=" + container_id + "&product_id=" + product_id, true);
	request.send(null);
}
function viewProducts() {
	if (request.readyState == 4) {
		var output = request.responseText;
		document.getElementById('selected_products').innerHTML = output;
	}
}
function calculateVolume() {

	var totvolume = 0;

	var n = document.adminForm.quantity3.length;

	for (var i = 0; i < n; i++) {


		volume = document.adminForm.org_volume[i].value;
		qty = document.adminForm.quantity3[i].value;

		totvolume += parseInt(volume) * parseInt(qty);

		document.adminForm.volume[i].value = parseInt(volume) * parseInt(qty);

	}

	document.adminForm.totvolume.value = totvolume;
	var container_volume = parseInt(document.adminForm.container_volume.value);
	document.adminForm.leftvolume.value = container_volume - parseInt(totvolume);
}

//chk_manufacturer();

var mid = document.getElementById("supplier_id").value;
var rand_no = Math.random();
var options = {
	script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&rand_no=" + rand_no + "&container_id=<?php echo $this->detail->container_id;?>&alert=container&",
	varname: "input",
	json: true,
	shownoresults: false,
	callback: function (obj) {
		val = (obj.id);
		da_id = val.split("`");
		da_mid = da_id[1];
		var maid = document.getElementById("supplier_id").value;
		if (maid != 0) {
			if (da_mid == maid) {
				document.getElementById('input').value = "";
				create_table_data(obj.value, obj.volume, da_id[0]);
			}
			else {
				alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_SAME_SUPPLIER_PRODUCT_ONLY' );?>");
			}
		}
		else {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_SUPPLIER' );?>");
		}

	}
};

var as_json = new bsn.AutoSuggest('input', options);
<?php
if($this->detail->container_id){
	?>
window.onload = function () {
	//	  displaycontainerproducts();
	//var obj = document.getElementById('chk_existing');
	//chk_existingproduct(obj);
}
<?php
}
?>
</script>
