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

$billing = $this->billing;

if ($this->detail->user_id < 0)
{
	$style = "none";
}
else
{
	$style = "block";
}

$DEFAULT_QUANTITY = Redshop::getConfig()->get('DEFAULT_QUANTITY');
?>

<script type="text/javascript">
var xmlhttp;
var rowCount = 1;

function addNewproductRow(tblid)
{
	var table = document.getElementById(tblid);

	rowCount++;

	var newTR  = document.createElement('tr');
	var newTD  = document.createElement('td');
	var newTD1 = document.createElement('td');
	var newTD2 = document.createElement('td');
	var newTD3 = document.createElement('td');
	var newTD4 = document.createElement('td');
	var newTD5 = document.createElement('td');
	var newTD6 = document.createElement('td');
	var newTD7 = document.createElement('td');
	var item   = new Array();

	newTD.innerHTML  = '<img onclick="deleteOfflineProductRow(' + rowCount + ');" src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>cross.png" title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>" alt="<?php echo JText::_('COM_REDSHOP_DELETE');?>">';
	newTD1.innerHTML = '<input type="text" name="product' + rowCount + '" id="product' + rowCount + '" value="0" /><div id="divAttproduct' + rowCount + '"></div><div id="divAccproduct' + rowCount + '"></div><div id="divUserFieldproduct' + rowCount + '"></div>';
	newTD2.innerHTML = '';
	newTD2.id        = 'tdnoteproduct' + rowCount;
	newTD3.innerHTML = '<input type="number" min="0" name="prdexclpriceproduct' + rowCount + '" id="prdexclpriceproduct' + rowCount + '" onchange="changeOfflinePriceBox(\'product' + rowCount + '\');" value="0" size="10" >';
	newTD4.innerHTML = '<div id="prdtaxproduct' + rowCount + '"></div><input type="hidden" name="taxpriceproduct' + rowCount + '" id="taxpriceproduct' + rowCount + '" value="0">';
	newTD4.align     = 'right';
	newTD5.innerHTML = '<div id="prdpriceproduct' + rowCount + '"></div><input type="hidden" name="productpriceproduct' + rowCount + '" id="productpriceproduct' + rowCount + '" value="0">';
	newTD5.align     = 'right';
	newTD6.innerHTML = '<input type="number" min="1" name="quantityproduct' + rowCount + '" id="quantityproduct' + rowCount + '" onchange="changeOfflineQuantityBox(\'product' + rowCount + '\');" value="1" size="<?php echo $DEFAULT_QUANTITY;?>" maxlength="<?php echo $DEFAULT_QUANTITY;?>">';
	newTD7.innerHTML = '<div id="tdtotalprdproduct' + rowCount + '"></div><input name="subpriceproduct' + rowCount + '" id="subpriceproduct' + rowCount + '" type="hidden" value="0" /><input type="hidden" name="main_priceproduct' + rowCount + '" id="main_priceproduct' + rowCount + '" value="0" /><input type="hidden" name="tmp_product_priceproduct' + rowCount + '" id="tmp_product_priceproduct' + rowCount + '" value="0"><input type="hidden" name="product_vatpriceproduct' + rowCount + '" id="product_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="tmp_product_vatpriceproduct' + rowCount + '" id="tmp_product_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="wrapper_dataproduct' + rowCount + '" id="wrapper_dataproduct' + rowCount + '" value="0"><input type="hidden" name="wrapper_vatpriceproduct' + rowCount + '" id="wrapper_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="accessory_dataproduct' + rowCount + '" id="accessory_dataproduct' + rowCount + '" value="0"><input type="hidden" name="acc_attribute_dataproduct' + rowCount + '" id="acc_attribute_dataproduct' + rowCount + '" value="0"><input type="hidden" name="acc_property_dataproduct' + rowCount + '" id="acc_property_dataproduct' + rowCount + '" value="0"><input type="hidden" name="acc_subproperty_dataproduct' + rowCount + '" id="acc_subproperty_dataproduct' + rowCount + '" value="0"><input type="hidden" name="accessory_priceproduct' + rowCount + '" id="accessory_priceproduct' + rowCount + '" value="0"><input type="hidden" name="accessory_vatpriceproduct' + rowCount + '" id="accessory_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="attribute_dataproduct' + rowCount + '" id="attribute_dataproduct' + rowCount + '" value="0"><input type="hidden" name="property_dataproduct' + rowCount + '" id="property_dataproduct' + rowCount + '" value="0"><input type="hidden" name="subproperty_dataproduct' + rowCount + '" id="subproperty_dataproduct' + rowCount + '" value="0"><input type="hidden" name="requiedAttributeproduct' + rowCount + '" id="requiedAttributeproduct' + rowCount + '" value="0">';
	newTD7.align     = 'right';

	var item = document.getElementsByName('order_item');

	newTR.appendChild(newTD);
	newTR.appendChild(newTD1);
	newTR.appendChild(newTD2);
	newTR.appendChild(newTD3);
	newTR.appendChild(newTD4);
	newTR.appendChild(newTD5);
	newTR.appendChild(newTD6);
	newTR.appendChild(newTD7);

	newTR.id = 'trPrd' + rowCount;

	table.appendChild(newTR);

	createJsonObject(rowCount);
}

Joomla.submitbutton = function (pressbutton)
{
	var form = document.adminForm;

	if (pressbutton == 'cancel')
	{
		submitform(pressbutton);
		return;
	}

	if ((pressbutton == 'save') || (pressbutton == 'send') || (pressbutton == 'apply'))
	{
		if (form.user_id.value == 0)
		{
			if (validateUserDetail() == false)
			{
				return;
			}
		}

		if (form.product1.value == 0)
		{
			alert("<?php echo JText::_('COM_REDSHOP_SELECT_PRODUCT');?>");
			return;
		}

		if (validateProductQuantity() == false)
 		{
 			return false;
 		}

		if (validateExtrafield(form) == false)
		{
			return false;
		}
	}
	submitform(pressbutton);
}

function validateUserDetail()
{
	var form = document.adminForm;

	if (form.firstname.value == '')
	{
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME')?>");
		form.firstname.focus();
		return false;
	}

	if (form.lastname.value == '')
	{
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME')?>");
		form.lastname.focus();
		return false;
	}

	if (form.address.value == '')
	{
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ADDRESS')?>");
		form.address.focus();
		return false;
	}

	if (form.zipcode.value == '')
	{
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ZIPCODE')?>");
		form.zipcode.focus();
		return false;
	}

	if (form.city.value == '')
	{
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_CITY')?>");
		form.city.focus();
		return false;
	}

	if (form.phone.value == '')
	{
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_PHONE')?>");
		form.phone.focus();
		return false;
	}

	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

	if (form.user_email.value == '') {
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_ADDRESS')?>");
		form.email.focus();
		return false;
	}

	if (document.getElementById('username') && form.username.value == "")
	{
		alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME', true ); ?>");
		form.username.focus();
		return false;
	}

	if (document.getElementById('password'))
	{
		if (form.password.value == '')
		{
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_PASSWORD')?>");
			form.password.focus();
			return false;
		}

		if (((trim(form.password.value) != "") || (trim(form.password2.value) != "")) && (form.password.value != form.password2.value))
		{
			alert("<?php echo JText::_('COM_REDSHOP_PASSWORD_NOT_MATCH', true ); ?>");
			form.password2.focus();
			return false;
		}
	}

	return;
}

function validateProductQuantity()
{
	var valid = true;
	var quantity = document.querySelectorAll("input[name*='quantityproduct']");

	for (i = 0; i < quantity.length; i++)
	{
		if (parseInt(quantity[i].value) <= 0)
		{
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_QUANTITY'); ?>");
			quantity[i].focus();
			valid = false;
			break;
		}
	}

	return valid;
}
</script>
<?php
$jinput = JFactory::getApplication()->input;

if (!$jinput->get('ajaxtask'))
{
?>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<table border="0" cellspacing="0" cellpadding="0" class="adminlist table">
		<tbody>
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="0" class="adminlist">
					<tbody>
					<tr>
						<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_SELECT_USER'); ?>:</td>
						<td>
							<?php
							echo JHTML::_('redshopselect.search', '', 'user_id',
								array(
									'select2.ajaxOptions' => array('typeField' => ', addreduser:1'),
									'select2.options' => array(
										'events' => array('select2-selecting' => 'function(e) {document.getElementById(\'user_id\').value = e.object.id;showquotationUserDetail()}')
									)
								)
							);
							?>
						</td>
					</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td id="userinforesult">
				<?php
}
				?>
				<table width="100%" class="adminlist">
					<tbody>
					<tr style="background-color: #cccccc">
						<th colspan="2"><?php echo JText::_('COM_REDSHOP_ACCOUNT_INFORMATION'); ?></th>
					</tr>
					<tr>
						<td width="25%"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:</td>
						<td><input type="text" name="firstname" id="firstname"
						           value="<?php echo $billing->firstname; ?>"/></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</td>
						<td><input type="text" name="lastname" id="lastname" value="<?php echo $billing->lastname; ?>"/>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</td>
						<td><input type="text" name="address" id="address" value="<?php echo $billing->address; ?>"/>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</td>
						<td><input type="text" name="zipcode" id="zipcode" value="<?php echo $billing->zipcode; ?>"/>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</td>
						<td><input type="text" name="city" id="city" value="<?php echo $billing->city; ?>"/></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</td>
						<td><?php echo $this->lists['country_code']; ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</td>
						<td><?php echo $this->lists['state_code']; ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</td>
						<td><input type="text" name="phone" id="phone" value="<?php echo $billing->phone; ?>"/></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</td>
						<td><input type="text" name="user_email" id="user_email"
						           value="<?php echo $billing->user_email; ?>"/></td>
					</tr>
					<tr>
						<td colspan="2"><?php echo $this->lists['quotation_extrafield'];?>    </td>
					</tr>

					<input type="hidden" name="users_info_id" value="<?php echo $billing->users_info_id; ?>"/>
					</tbody>
				</table>
				<?php
				if ($this->detail->user_id <= 0)
				{
					?>
					<table id="tblcreat" style="display:<?php echo $style; ?>;">
						<tr>
							<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USERNAME'); ?>:
							</td>
							<td><input class="inputbox" type="text" name="username" id="username" size="32"
							           maxlength="250" value="" onblur="validate(1);"/><span id="user_valid"></span>
							</td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_NEW_PASSWORD_LBL'); ?>:</td>
							<td><input class="inputbox" type="password" name="password" id="password" size="32"
							           maxlength="250" value=""/></td>
						</tr>
						<tr>
							<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_VERIFIED_PASSWORD_LBL'); ?>
								:
							</td>
							<td><input class="inputbox" type="password" name="password2" id="password2" size="32"
							           maxlength="250" value=""/></td>
						</tr>
					</table>
				<?php
				}

				if ($jinput->get('ajaxtask') == "getuser")
				{
					die();
				}

				if (!$jinput->get('ajaxtask'))
				{
				?>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="adminlist">
					<tbody>
					<tr style="background-color: #cccccc">
						<th align="left"><?php echo JText::_('COM_REDSHOP_QUOTATION_DETAILS'); ?></th>
					</tr>
					<tr>
						<td align="right"><a
								href="javascript:addNewproductRow('tblproductRow');"><?php echo JText::_('COM_REDSHOP_NEW'); ?></a>
						</td>
					</tr>
					<tr>
						<td>
							<table class="adminlist" id="tblproductRow" width="100%">
								<tr>
									<th width="5%"><?php echo JText::_('COM_REDSHOP_ACTION'); ?></th>
									<th width="30%"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
									<th width="20%"><?php echo JText::_('COM_REDSHOP_ORDER_PRODUCT_NOTE'); ?></th>
									<th width="10%"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_WITHOUT_VAT'); ?></td>
									<th width="10%" align="right"><?php echo JText::_('COM_REDSHOP_TAX'); ?></td>
									<th width="10%"
									    align="right"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></th>
									<th width="5%"><?php echo JText::_('COM_REDSHOP_PRODUCT_QTY'); ?></th>
									<th width="10%"
									    align="right"><?php echo JText::_('COM_REDSHOP_TOTAL_PRICE'); ?></th>
								</tr>
								<tr id="trPrd1">
									<td align="center"></td>
									<td>
										<?php
										echo JHTML::_('redshopselect.search', '', 'product1',
											array(
												'select2.ajaxOptions' => array('typeField' => ', isproduct:1'),
												'select2.options' => array(
													'events' => array('select2-selecting' => 'function(e) {document.getElementById(\'product1\').value = e.object.id;displayProductDetailInfo(\'product1\', 0);}')
												)
											)
										);
										?>
										<div id="divAttproduct1"></div>
										<div id="divAccproduct1"></div>
										<div id="divUserFieldproduct1"></div>
									</td>
									<td id="tdnoteproduct1"></td>
									<td><input type="number" min="0" name="prdexclpriceproduct1" id="prdexclpriceproduct1"
									           onchange="changeOfflinePriceBox('product1');" value="0" size="10"></td>
									<td align="right">
										<div id="prdtaxproduct1"></div>
										<input name="taxpriceproduct1" id="taxpriceproduct1" type="hidden" value="0"/>
									</td>
									<td align="right">
										<div id="prdpriceproduct1"></div>
										<input name="productpriceproduct1" id="productpriceproduct1" type="hidden"
										       value="0"/></td>

									<td><input type="number" min="1" name="quantityproduct1" id="quantityproduct1"
									           onchange="changeOfflineQuantityBox('product1');" value="1"
									           size="<?php echo $DEFAULT_QUANTITY; ?>"
									           maxlength="<?php echo $DEFAULT_QUANTITY; ?>"></td>
									<td align="right">
										<div id="tdtotalprdproduct1"></div>
										<input name="subpriceproduct1" id="subpriceproduct1" type="hidden" value="0"/>

										<input type="hidden" name="main_priceproduct1" id="main_priceproduct1"
										       value="0"/>
										<input type="hidden" name="tmp_product_priceproduct1"
										       id="tmp_product_priceproduct1" value="0">
										<input type="hidden" name="product_vatpriceproduct1"
										       id="product_vatpriceproduct1" value="0">
										<input type="hidden" name="tmp_product_vatpriceproduct1"
										       id="tmp_product_vatpriceproduct1" value="0">
										<input type="hidden" name="wrapper_dataproduct1" id="wrapper_dataproduct1"
										       value="0">
										<input type="hidden" name="wrapper_vatpriceproduct1"
										       id="wrapper_vatpriceproduct1" value="0">

										<input type="hidden" name="accessory_dataproduct1" id="accessory_dataproduct1"
										       value="0">
										<input type="hidden" name="acc_attribute_dataproduct1"
										       id="acc_attribute_dataproduct1" value="0">
										<input type="hidden" name="acc_property_dataproduct1"
										       id="acc_property_dataproduct1" value="0">
										<input type="hidden" name="acc_subproperty_dataproduct1"
										       id="acc_subproperty_dataproduct1" value="0">
										<input type="hidden" name="accessory_priceproduct1" id="accessory_priceproduct1"
										       value="0">
										<input type="hidden" name="accessory_vatpriceproduct1"
										       id="accessory_vatpriceproduct1" value="0">

										<input type="hidden" name="attribute_dataproduct1" id="attribute_dataproduct1"
										       value="0">
										<input type="hidden" name="property_dataproduct1" id="property_dataproduct1"
										       value="0">
										<input type="hidden" name="subproperty_dataproduct1"
										       id="subproperty_dataproduct1" value="0">
										<input type="hidden" name="requiedAttributeproduct1"
										       id="requiedAttributeproduct1" value="0">

									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="adminlist">
								<tbody>
								<tr align="left">
									<td align="right" width="70%">
										<strong><?php echo JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL'); ?>:</strong></td>
									<td align="right" width="30%">
										<div id="divSubTotal"></div>
										<input name="order_subtotal" id="order_subtotal" type="hidden" value="0"/></td>
								</tr>
								<tr align="left">
									<td align="right" width="70%">
										<strong><?php echo JText::_('COM_REDSHOP_QUOTATION_TAX'); ?>:</strong></td>
									<td align="right" width="30%">
										<div id="divTax"></div>
										<input name="order_tax" id="order_tax" type="hidden" value="0"/></td>
								</tr>

								<tr align="left">
									<td colspan="2" align="left">
										<hr/>
									</td>
								</tr>
								<tr align="left">
									<td align="right" width="70%">
										<strong><?php echo JText::_('COM_REDSHOP_QUOTATION_TOTAL'); ?>:</strong></td>
									<td align="right" width="30%">
										<div id="divFinalTotal"></div>
										<input name="order_total" id="order_total" type="hidden" value="0"/></td>
								</tr>
								<tr align="left">
									<td colspan="2" align="left">
										<hr/>
									</td>
								</tr>
								</tbody>
							</table>
						</td>
					</tr>
					</tbody>
				</table>
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="view" value="addquotation_detail"/>
</form>
<div id="divCalc"></div>
			<?php
				}
			?>
<script type="text/javascript">

function createJsonObject(uniqueId)
{
	<?php
	echo JHTML::_('redshopselect.search', '', "product' + uniqueId + '",
		array(
			'select2.ajaxOptions' => array('typeField' => ', isproduct:1'),
			'select2.options' => array(
				'events' => array(
					'select2-selecting' => 'function(e) {document.getElementById(\'product\' + uniqueId).value = e.object.id;displayProductDetailInfo(\'product\' + uniqueId, 0);}'
				)
			)
		), true
	);
	?>
}

function validateInputFloat(e)
{
    if ((e.keyCode == 189) || (e.keyCode == 109))
    {
        e.preventDefault();
    }
}

jQuery(document).ready(function() {

    jQuery("input[type=number]").keydown(function(e){
        validateInputFloat(e);
    });
});
</script>
