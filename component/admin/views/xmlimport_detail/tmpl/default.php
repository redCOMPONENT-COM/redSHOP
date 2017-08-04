<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');


$model = $this->getModel('xmlimport_detail');
$uri = JURI::getInstance();
$url = $uri->root();

$style = "none";
$orderstyle = "none";
$productstyle = "none";
switch ($this->detail->section_type)
{
	case "product":
		$orderstyle = "none";
		$productstyle = "";
		$style = "";
		break;
	case "order":
		$orderstyle = "";
		$productstyle = "none";
		$style = "";
		break;
}    ?>

<script language="javascript" type="text/javascript">
function checkSourcePath() {
	var form = document.adminForm;
	if (form.xmlimport_url.value == "" && form.filename_url.value == "") {
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_XMLIMPORT_URL_OR_UPLOAD_XMLFILE', true ); ?>");
		return false;
	}
	if (form.section_type.value == "" || form.section_type.value == 0) {
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_XMLEXPORT_SECTION_TYPE', true ); ?>");
		return false;
	}
	if (form.element_name.value == "") {
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ELEMENT_NAME', true ); ?>");
		form.element_name.focus();
		return false;
	}

	/*if (form.section_type.value=="product")
	 {
	 if (form.stock_element_name.value==""){
	 alert( "
	<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ELEMENT_NAME', true ); ?>" );
	 form.stock_element_name.focus();
	 return false;
	 }
	 if (form.prdextrafield_element_name.value==""){
	 alert( "
	<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ELEMENT_NAME', true ); ?>" );
	 form.prdextrafield_element_name.focus();
	 return false;
	 }
	 }
	 else if (form.section_type.value=="order")
	 {
	 if (form.billing_element_name.value==""){
	 alert( "
	<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ELEMENT_NAME', true ); ?>" );
	 form.billing_element_name.focus();
	 return false;
	 }
	 if (form.shipping_element_name.value==""){
	 alert( "
	<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ELEMENT_NAME', true ); ?>" );
	 form.shipping_element_name.focus();
	 return false;
	 }
	 if (form.orderitem_element_name.value==""){
	 alert( "
	<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ELEMENT_NAME', true ); ?>" );
	 form.orderitem_element_name.focus();
	 return false;
	 }
	 }*/
	return true;
}
function getUniqueArray(a) {
	var l = a.length;
	for (var i = 0; i < l; i++) {
		for (var j = i + 1; j < l; j++) {
			// If this[i] is found later in the array
			if (a[i] == a[j]) {
				j = ++i;
			}
		}
		a.push(a[i]);
	}
	return a;
}
;

Array.prototype.unique = function () {
	var r = new Array();
	o:for (var i = 0, n = this.length; i < n; i++) {
		for (var x = 0, y = r.length; x < y; x++) {
			if (r[x] == this[i]) {
				continue o;
			}
		}
		r[r.length] = this[i];
	}
	return r;
}

Array.prototype.getUnique = function () {
	var o = new Object();
	var i, e;
	for (i = 0; e = this[i]; i++) {
		o[e] = 1
	}
	;
	var a = new Array();
	for (e in o) {
		a.push(e)
	}
	;
	return a;
}
Joomla.submitbutton = function (pressbutton) {
	submitbutton(pressbutton);
}

submitbutton = function (pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform(pressbutton);
		return;
	}
	if (document.getElementsByName("xmlfiletag[]")) {
		var xmltag = document.getElementsByName("xmlfiletag[]");
		var totallen = document.getElementsByName("xmlfiletag[]").length;
		var idval = new Array();
		for (i = 0, j = 0; i < totallen; i++) {
			if (document.getElementById(xmltag[i].value).value != "") {
				idval[j] = document.getElementById(xmltag[i].value).value;
				j++;
			}
		}
		var firstlen = idval.length;
		idval = idval.unique();
		if (firstlen == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_SELECT_FIELDNAME', true ); ?>");
			return false;
		}
		if (idval.length != firstlen) {
			alert("<?php echo JText::_('COM_REDSHOP_DUPLICATE_FIELDNAME', true ); ?>");
			return false;
		}
	}
	if (document.getElementsByName("xmlbillingtag[]")) {
		var xmltag = document.getElementsByName("xmlbillingtag[]");
		var totallen = xmltag.length;
		if (totallen > 0) {
			var idval = new Array();
			for (i = 0, j = 0; i < totallen; i++) {
				if (document.getElementById("bill_" + xmltag[i].value).value != "") {
					idval[j] = document.getElementById("bill_" + xmltag[i].value).value;
					j++;
				}
			}
			var firstlen = idval.length;
			idval = idval.unique();
			if (firstlen == 0) {
				alert("<?php echo JText::_('COM_REDSHOP_SELECT_FIELDNAME', true ); ?>");
				return false;
			}
			if (idval.length != firstlen) {
				alert("<?php echo JText::_('COM_REDSHOP_DUPLICATE_FIELDNAME', true ); ?>");
				return false;
			}
		}
	}
	if (document.getElementsByName("xmlshippingtag[]")) {
		var xmltag = document.getElementsByName("xmlshippingtag[]");
		var totallen = xmltag.length;
		if (totallen > 0) {
			var idval = new Array();
			for (i = 0, j = 0; i < totallen; i++) {
				if (document.getElementById("shipp_" + xmltag[i].value).value != "") {
					idval[j] = document.getElementById("shipp_" + xmltag[i].value).value;
					j++;
				}
			}
			var firstlen = idval.length;
			idval = idval.unique();
			if (firstlen == 0) {
				alert("<?php echo JText::_('COM_REDSHOP_SELECT_FIELDNAME', true ); ?>");
				return false;
			}
			if (idval.length != firstlen) {
				alert("<?php echo JText::_('COM_REDSHOP_DUPLICATE_FIELDNAME', true ); ?>");
				return false;
			}
		}
	}
	if (document.getElementsByName("xmlitemtag[]")) {
		var xmltag = document.getElementsByName("xmlitemtag[]");
		var totallen = xmltag.length;
		if (totallen > 0) {
			var idval = new Array();
			for (i = 0, j = 0; i < totallen; i++) {
				if (document.getElementById("item_" + xmltag[i].value).value != "") {
					idval[j] = document.getElementById("item_" + xmltag[i].value).value;
					j++;
				}
			}
			var firstlen = idval.length;
			idval = idval.unique();
			if (firstlen == 0) {
				alert("<?php echo JText::_('COM_REDSHOP_SELECT_FIELDNAME', true ); ?>");
				return false;
			}
			if (idval.length != firstlen) {
				alert("<?php echo JText::_('COM_REDSHOP_DUPLICATE_FIELDNAME', true ); ?>");
				return false;
			}
		}
	}
	if (document.getElementsByName("xmlstocktag[]")) {
		var xmltag = document.getElementsByName("xmlstocktag[]");
		var totallen = xmltag.length;
		if (totallen > 0) {
			var idval = new Array();
			for (i = 0, j = 0; i < totallen; i++) {
				if (document.getElementById("stock_" + xmltag[i].value).value != "") {
					idval[j] = document.getElementById("stock_" + xmltag[i].value).value;
					j++;
				}
			}
			var firstlen = idval.length;
			idval = idval.unique();
			if (firstlen == 0) {
				alert("<?php echo JText::_('COM_REDSHOP_SELECT_FIELDNAME', true ); ?>");
				return false;
			}
			if (idval.length != firstlen) {
				alert("<?php echo JText::_('COM_REDSHOP_DUPLICATE_FIELDNAME', true ); ?>");
				return false;
			}
		}
	}
	if (document.getElementsByName("xmlprdextrafieldtag[]")) {
		var xmltag = document.getElementsByName("xmlprdextrafieldtag[]");
		var totallen = xmltag.length;
		if (totallen > 0) {
			var idval = new Array();
			for (i = 0, j = 0; i < totallen; i++) {
				if (document.getElementById("prdext_" + xmltag[i].value).value != "") {
					idval[j] = document.getElementById("prdext_" + xmltag[i].value).value;
					j++;
				}
			}
			var firstlen = idval.length;
			idval = idval.unique();
			if (firstlen == 0) {
				alert("<?php echo JText::_('COM_REDSHOP_SELECT_FIELDNAME', true ); ?>");
				return false;
			}
			if (idval.length != firstlen) {
				alert("<?php echo JText::_('COM_REDSHOP_DUPLICATE_FIELDNAME', true ); ?>");
				return false;
			}
		}
	}
	if (form.display_filename.value == "") {
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_XMLIMPORT_FILE_NAME', true ); ?>");
	} else if (form.cntxmlfiletag.value == 0) {
		alert("<?php echo JText::_('COM_REDSHOP_NO_DATA_TO_IMPORT', true ); ?>");
	} else {
		submitform(pressbutton);
	}
}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<table class="admintable table">
				<tr>
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_XMLIMPORT_DISPLAY_FILENAME'); ?>:
					</td>
					<td><input type="text" name="display_filename" id="display_filename"
					           value="<?php echo $this->detail->display_filename; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_XMLIMPORT_FILE_URL'); ?>
						:
					</td>
					<td><input type="text" name="xmlimport_url" id="xmlimport_url"
					           value="<?php echo $this->detail->xmlimport_url; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_XMLFILE'); ?>:</td>
					<td><input type="file" name="filename_url" id="filename_url" value=""/></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_SECTION_TYPE'); ?>:</td>
					<td><?php echo $this->lists['section_type'];?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><span id="prdelement_name"
					                                                style="display: <?php echo $productstyle; ?>;"><?php echo JText::_('COM_REDSHOP_PRODUCT_ELEMENT_NAME'); ?>
							:</span><span id="ordelement_name"
					                      style="display: <?php echo $orderstyle; ?>;"><?php echo JText::_('COM_REDSHOP_ORDERDETAIL_ELEMENT_NAME'); ?>
							:</span></td>
					<td><span id="tdelement_name" style="display: <?php echo $style; ?>"><input type="text"
					                                                                            name="element_name"
					                                                                            id="element_name"
					                                                                            value="<?php echo $this->detail->element_name; ?>"/><?php echo JText::_('COM_REDSHOP_XML_ELEMENT_NOTE');?></span>
					</td>
				</tr>
				<tr id="trStockdetail" style="display: <?php echo $productstyle; ?>;">
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_STOCKDETAIL_ELEMENT_NAME'); ?>:
					</td>
					<td><input type="text" name="stock_element_name" id="stock_element_name"
					           value="<?php echo $this->detail->stock_element_name; ?>"/><?php echo JText::_('COM_REDSHOP_XML_ELEMENT_NOTE');?>
					</td>
				</tr>
				<tr id="trExtrafield" style="display: <?php echo $productstyle; ?>;">
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_EXTRAFIELD_ELEMENT_NAME'); ?>:
					</td>
					<td><input type="text" name="prdextrafield_element_name" id="prdextrafield_element_name"
					           value="<?php echo $this->detail->prdextrafield_element_name; ?>"/><?php echo JText::_('COM_REDSHOP_XML_ELEMENT_NOTE');?>
					</td>
				</tr>
				<tr id="trBillingdetail" style="display: <?php echo $orderstyle; ?>;">
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_BILLING_ELEMENT_NAME'); ?>:
					</td>
					<td><input type="text" name="billing_element_name" id="billing_element_name"
					           value="<?php echo $this->detail->billing_element_name; ?>"/><?php echo JText::_('COM_REDSHOP_XML_ELEMENT_NOTE');?>
					</td>
				</tr>
				<tr id="trShippingdetail" style="display: <?php echo $orderstyle; ?>;">
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_SHIPPING_ELEMENT_NAME'); ?>:
					</td>
					<td><input type="text" name="shipping_element_name" id="shipping_element_name"
					           value="<?php echo $this->detail->shipping_element_name; ?>"/><?php echo JText::_('COM_REDSHOP_XML_ELEMENT_NOTE');?>
					</td>
				</tr>
				<tr id="trOrderitem" style="display: <?php echo $orderstyle; ?>;">
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_ORDERITEM_ELEMENT_NAME'); ?>:
					</td>
					<td><input type="text" name="orderitem_element_name" id="orderitem_element_name"
					           value="<?php echo $this->detail->orderitem_element_name; ?>"/><?php echo JText::_('COM_REDSHOP_XML_ELEMENT_NOTE');?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_AUTO_SYNCHRONIZE'); ?>:
					</td>
					<td><?php echo $this->lists['auto_sync'];?></td>
				</tr>
				<tr>
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_SYNCHRONIZE_ON_REQUEST'); ?>:
					</td>
					<td><?php echo $this->lists['sync_on_request'];?></td>
				</tr>
				<tr>
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_SYNCHRONIZE_INTERVAL'); ?>:
					</td>
					<td><?php echo $this->lists['auto_sync_interval'];?></td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:</td>
					<td><?php echo $this->lists['xmlpublished']; ?></td>
				</tr>
				<tr>
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_OVERRIDE_EXISTING_IF_EXISTS'); ?>:
					</td>
					<td><?php echo $this->lists['override_existing'];?></td>
				</tr>
				<tr>
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_ADD_PREFIX_FOR_EXISTING'); ?>:
					</td>
					<td><input type="text" name="add_prefix_for_existing" id="add_prefix_for_existing"
					           value="<?php echo $this->detail->add_prefix_for_existing; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"></td>
					<td><input type="submit" name="readxmlfile" id="readxmlfile"
					           value="<?php echo JText::_('COM_REDSHOP_READ_XMLFILE'); ?>"
					           onclick="return checkSourcePath();"/></td>
				</tr>
			</table>
		</fieldset>
	</div><?php
	if ($this->detail->section_type != "")
	{
		?>
		<div class="col50" id="adminresult">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_XMLEXPORT_FILE_DETAIL'); ?></legend>
			<table class="admintable table">
				<tr>
					<th><?php echo JText::_('COM_REDSHOP_FIELD_NAME'); ?></th>
					<th><?php echo JText::_('COM_REDSHOP_XMLEXPORT_FILE_TAG_NAME'); ?></th>
					<th><?php echo JText::_('COM_REDSHOP_UPDATE_IMPORT_FIELD'); ?></th>
				</tr>
				<?php    if (count($this->xmlfiletag) > 0)
				{
					if ($this->detail->section_type == "order")
					{
						?>
						<tr>
							<th><?php echo JText::_('COM_REDSHOP_ORDER_INFORMATION');?></th>
							<th><?php echo JText::_('COM_REDSHOP_ORDER_IMPORT_NOTE');?></th>
						</tr>
					<?php
					}
					elseif ($this->detail->section_type == "product")
					{
						?>
						<tr>
							<th><?php echo JText::_('COM_REDSHOP_PRODUCT');?></th>
							<th><?php echo JText::_('COM_REDSHOP_PRODUCT_IMPORT_NOTE');?></th>
						</tr>
					<?php
					}
					for ($i = 0; $i < count($this->xmlfiletag); $i++)
					{
						$chk = ($this->updatefiletag[$i] == 1) ? "checked" : "";
						if ($this->xmlfiletag[$i] == "category_id")
						{
							?>
							<input type="hidden" name="xmlfiletag[<?php echo $i; ?>]"
							       value="<?php echo $this->xmlfiletag[$i]; ?>"/>
							<input type="hidden" name="<?php echo $this->xmlfiletag[$i]; ?>"
							       id="<?php echo $this->xmlfiletag[$i]; ?>"
							       value="<?php echo $this->xmlfiletag[$i]; ?>"/>
						<?php }
						else
						{ ?>
							<tr>
								<td width="100" align="right" class="key"><?php echo $this->xmlfiletag[$i]; ?>:</td>
								<td><?php echo $this->lists[$this->xmlfiletag[$i]];?>
									<input type="hidden" name="xmlfiletag[]"
									       value="<?php echo $this->xmlfiletag[$i]; ?>"/></td>
								<td><input type="checkbox" name="updatefiletag[<?php echo $i; ?>]"
								           value="1" <?php echo $chk;?> /></td>
							</tr>
						<?php
						}
					}
				}
				if (count($this->xmlbillingtag) > 0)
				{
					?>
					<tr>
						<th><?php echo JText::_('COM_REDSHOP_BILLING_INFORMATION');?></th>
						<th>&nbsp;</th>
					</tr>
					<?php    for ($i = 0; $i < count($this->xmlbillingtag); $i++)
				{
					$chk = ($this->updatebillingtag[$i] == 1) ? "checked" : "";    ?>
					<tr>
						<td width="100" align="right" class="key"><?php echo $this->xmlbillingtag[$i]; ?>:</td>
						<td><?php echo $this->lists["bill_" . $this->xmlbillingtag[$i]];?>
							<input type="hidden" name="xmlbillingtag[]"
							       value="<?php echo $this->xmlbillingtag[$i]; ?>"/></td>
						<td><input type="checkbox" name="updatebillingtag[<?php echo $i; ?>]"
						           value="1" <?php echo $chk;?> /></td>
					</tr>
				<?php
				}
				}
				if (count($this->xmlshippingtag) > 0)
				{
					?>
					<tr>
						<th><?php echo JText::_('COM_REDSHOP_SHIPPING_INFORMATION');?></th>
						<th>&nbsp;</th>
					</tr>
					<?php    for ($i = 0; $i < count($this->xmlshippingtag); $i++)
				{
					$chk = ($this->updateshippingtag[$i] == 1) ? "checked" : "";    ?>
					<tr>
						<td width="100" align="right" class="key"><?php echo $this->xmlshippingtag[$i]; ?>:</td>
						<td><?php echo $this->lists["shipp_" . $this->xmlshippingtag[$i]];?>
							<input type="hidden" name="xmlshippingtag[]"
							       value="<?php echo $this->xmlshippingtag[$i]; ?>"/></td>
						<td><input type="checkbox" name="updateshippingtag[<?php echo $i; ?>]"
						           value="1" <?php echo $chk;?> /></td>
					</tr>
				<?php
				}
				}
				if (count($this->xmlitemtag) > 0)
				{
					?>
					<tr>
						<th><?php echo JText::_('COM_REDSHOP_ORDER_ITEM_ADDED');?></th>
						<th><?php echo JText::_('COM_REDSHOP_ORDER_ITEM_IMPORT_NOTE');?></th>
					</tr>
					<?php    for ($i = 0; $i < count($this->xmlitemtag); $i++)
				{
					$chk = ($this->updateitemtag[$i] == 1) ? "checked" : "";    ?>
					<tr>
						<td width="100" align="right" class="key"><?php echo $this->xmlitemtag[$i]; ?>:</td>
						<td><?php echo $this->lists["item_" . $this->xmlitemtag[$i]];?>
							<input type="hidden" name="xmlitemtag[]" value="<?php echo $this->xmlitemtag[$i]; ?>"/></td>
						<td><input type="checkbox" name="updateitemtag[<?php echo $i; ?>]"
						           value="1" <?php echo $chk;?> /></td>
					</tr>
				<?php
				}
				}
				if (count($this->xmlstocktag) > 0)
				{
					?>
					<tr>
						<th><?php echo JText::_('COM_REDSHOP_PRODUCT_STOCK_DETAIL_ADDED');?></th>
						<th><?php echo JText::_('COM_REDSHOP_STOCK_IMPORT_NOTE');?></th>
					</tr>
					<?php    for ($i = 0; $i < count($this->xmlstocktag); $i++)
				{
					$chk = ($this->updatestocktag[$i] == 1) ? "checked" : "";    ?>
					<tr>
						<td width="100" align="right" class="key"><?php echo $this->xmlstocktag[$i]; ?>:</td>
						<td><?php echo $this->lists["stock_" . $this->xmlstocktag[$i]];?>
							<input type="hidden" name="xmlstocktag[]" value="<?php echo $this->xmlstocktag[$i]; ?>"/>
						</td>
						<td><input type="checkbox" name="updatestocktag[<?php echo $i; ?>]"
						           value="1" <?php echo $chk;?> /></td>
					</tr>
				<?php
				}
				}
				if (count($this->xmlprdextrafieldtag) > 0)
				{
					?>
					<tr>
						<th><?php echo JText::_('COM_REDSHOP_PRODUCT_EXTRA_FIELD_ADDED');?></th>
						<th><?php echo JText::_('COM_REDSHOP_PRDEXTRA_IMPORT_NOTE');?></th>
					</tr>
					<?php    for ($i = 0; $i < count($this->xmlprdextrafieldtag); $i++)
				{
					$chk = ($this->updateprdexttag[$i] == 1) ? "checked" : "";    ?>
					<tr>
						<td width="100" align="right" class="key"><?php echo $this->xmlprdextrafieldtag[$i]; ?>:</td>
						<td><?php echo $this->lists["prdext_" . $this->xmlprdextrafieldtag[$i]];?>
							<input type="hidden" name="xmlprdextrafieldtag[]"
							       value="<?php echo $this->xmlprdextrafieldtag[$i]; ?>"/></td>
						<td><input type="checkbox" name="updateprdexttag[<?php echo $i; ?>]"
						           value="1" <?php echo $chk;?> /></td>
					</tr>
				<?php
				}
				}    ?>
			</table>
		</fieldset>
		</div><?php
	}
	else
	{
		?>
		<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_XMLEXPORT_FILE_DETAIL'); ?></legend>
			<table class="admintable table">
				<tr>
					<td id="tdNodata"><?php echo JText::_('COM_REDSHOP_NO_DATA_TO_IMPORT');?></td>
				</tr>
			</table>
		</fieldset>
		</div><?php
	}    ?>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->xmlimport_id; ?>"/>
	<input type="hidden" name="xmlimport_id" id="xmlimport_id" value="<?php echo $this->detail->xmlimport_id; ?>"/>
	<input type="hidden" name="filename" value="<?php echo $this->detail->filename; ?>"/>
	<input type="hidden" name="tmpxmlimport_url" value="<?php echo $this->tmpxmlimport_url; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="cntxmlfiletag" value="<?php echo count($this->xmlfiletag); ?>"/>
	<input type="hidden" name="view" value="xmlimport_detail"/>
</form>
