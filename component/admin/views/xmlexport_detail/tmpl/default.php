<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');
JHTML::_('behavior.tooltip');

$model = $this->getModel('xmlexport_detail');

$orderstyle = "none";
$productstyle = "none";
switch ($this->detail->section_type)
{
	case "product":
		$orderstyle = "none";
		$productstyle = "";
		break;
	case "order":
		$orderstyle = "";
		$productstyle = "none";
		break;
}
?>

<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		if (form.display_filename.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_XMLEXPORT_FILE_NAME', true ); ?>");
		} else if (form.parent_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_XMLEXPORT_PARENT_NAME', true ); ?>");
		} else if (form.section_type.value == "" || form.section_type.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_XMLEXPORT_SECTION_TYPE', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<div class="col50">
		<fieldset>
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable table">
				<tr>
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_XMLEXPORT_DISPLAY_FILENAME'); ?>:
					</td>
					<td><input type="text" name="display_filename" id="display_filename"
					           value="<?php echo $this->detail->display_filename; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_XMLEXPORT_PARENT_NAME'); ?>:
					</td>
					<td><input type="text" name="parent_name" id="parent_name"
					           value="<?php echo $this->detail->parent_name; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_SECTION_TYPE'); ?>:</td>
					<td><?php echo $this->lists['section_type'];?></td>
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
					<td><?php echo $this->lists['published']; ?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_XMLFILE'); ?>:</td>
					<td><?php if ($this->detail->filename != "")
						{
							echo '<a href="' . JURI::root() . 'index.php?option=com_redshop&view=category&tmpl=component&task=download&file=' . $this->detail->filename . '" target="_black">' . JURI::root() . 'index.php?option=com_redshop&view=category&tmpl=component&task=download&file=' . $this->detail->filename . '</a>';
						}    ?>
						<input type="hidden" name="filename" value="<?php echo $this->detail->filename; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_AUTOGENERATE_XMLFILE'); ?>:
					</td>
					<td><?php echo '<a href="' . JURI::root() . 'index.php?option=com_redshop&view=category&tmpl=component&task=generateXMLExportFile&xmlexport_id=' . $this->detail->xmlexport_id . '" target="_black">' . JURI::root() . 'index.php?option=com_redshop&view=category&tmpl=component&task=generateXMLExportFile&xmlexport_id=' . $this->detail->xmlexport_id . '</a>';    ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="col50">
		<fieldset>
			<legend><?php echo JText::_('COM_REDSHOP_XMLEXPORT_ACCESS'); ?></legend>
			<table class="admintable" width="100%" id="tblaccessIp">
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USE_TO_ALL_USERS'); ?>:
					</td>
					<td><?php echo $this->lists['use_to_all_users']; ?></td>
				</tr>
				<tr>
					<td colspan="2" align="right"><a
							href="javascript:addNewRow('tblaccessIp');"><?php echo JText::_('COM_REDSHOP_ADD');?></a>
					</td>
				</tr>
				<?php
				if (count($this->iparray) > 0)
				{
					for ($i = 0; $i < count($this->iparray); $i++)
					{
						?>
						<tr>
							<td width="100"><?php echo JText::_('COM_REDSHOP_IP_OR_URL_TO_ACCESS'); ?>:</td>
							<td><input type="text" name="access_ipaddress[]" id="access_ipaddress<?php echo $i; ?>"
							           value="<?php echo $this->iparray[$i]->access_ipaddress; ?>"/>
								<input type="hidden" name="xmlexport_ip_id[]"
								       value="<?php echo $this->iparray[$i]->xmlexport_ip_id; ?>"/>
								<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
								       onclick="deleteRow(this,<?php echo $this->iparray[$i]->xmlexport_ip_id; ?>);"
								       class="button" type="button"/></td>
						</tr>
					<?php
					}
				}
				else
				{
					?>
					<tr>
						<td width="100"><?php echo JText::_('COM_REDSHOP_IP_OR_URL_TO_ACCESS'); ?>:</td>
						<td><input type="text" name="access_ipaddress[]" id="access_ipaddress" value="">
							<input type="hidden" name="xmlexport_ip_id[]" value="0"/></td>
					</tr>
				<?php
				}    ?>
			</table>
		</fieldset>
	</div>
	<div class="col50" id="adminresult">
		<fieldset>
			<legend><?php echo JText::_('COM_REDSHOP_XMLEXPORT_FILE_DETAIL'); ?></legend>

			<table class="admintable" id="tblOrderType" style="display: <?php echo $orderstyle; ?>;">
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ADD_ORDER_DETAIL'); ?></td>
					<td><a class="joom-box"
					       href="index.php?tmpl=component&option=com_redshop&view=xmlexport_detail&cid[]=<?php echo $this->detail->xmlexport_id; ?>&layout=elementdetail&section_type=order&parentsection=orderdetail"
					       rel="{handler: 'iframe', size: {x: 750, y: 500}}">
							<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH . 'add.jpg'; ?>"
							     title="<?php echo JText::_('COM_REDSHOP_ADD_ORDER_DETAIL'); ?>"
							     alt="<?php echo JText::_('COM_REDSHOP_ADD_ORDER_DETAIL'); ?>">
						</a></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ADD_ORDER_USER_BILLING_INFORMATION'); ?></td>
					<td><a class="joom-box"
					       href="index.php?tmpl=component&option=com_redshop&view=xmlexport_detail&cid[]=<?php echo $this->detail->xmlexport_id; ?>&layout=elementdetail&section_type=order&parentsection=billingdetail"
					       rel="{handler: 'iframe', size: {x: 750, y: 500}}">
							<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH . 'add.jpg'; ?>"
							     title="<?php echo JText::_('COM_REDSHOP_ADD_ORDER_USER_BILLING_INFORMATION'); ?>"
							     alt="<?php echo JText::_('COM_REDSHOP_ADD_ORDER_USER_BILLING_INFORMATION'); ?>">
						</a></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ADD_ORDER_USER_SHIPPING_INFORMATION'); ?></td>
					<td><a class="joom-box"
					       href="index.php?tmpl=component&option=com_redshop&view=xmlexport_detail&cid[]=<?php echo $this->detail->xmlexport_id; ?>&layout=elementdetail&section_type=order&parentsection=shippingdetail"
					       rel="{handler: 'iframe', size: {x: 750, y: 500}}">
							<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH . 'add.jpg'; ?>"
							     title="<?php echo JText::_('COM_REDSHOP_ADD_ORDER_USER_SHIPPING_INFORMATION'); ?>"
							     alt="<?php echo JText::_('COM_REDSHOP_ADD_ORDER_USER_SHIPPING_INFORMATION'); ?>">
						</a></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ADD_ORDERITEMDETAIL'); ?></td>
					<td><a class="joom-box"
					       href="index.php?tmpl=component&option=com_redshop&view=xmlexport_detail&cid[]=<?php echo $this->detail->xmlexport_id; ?>&layout=elementdetail&section_type=order&parentsection=orderitem"
					       rel="{handler: 'iframe', size: {x: 750, y: 500}}">
							<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH . 'add.jpg'; ?>"
							     title="<?php echo JText::_('COM_REDSHOP_ADD_ORDERITEMDETAIL'); ?>"
							     alt="<?php echo JText::_('COM_REDSHOP_ADD_ORDERITEMDETAIL'); ?>">
						</a></td>
				</tr>
			</table>

			<table class="admintable" id="tblProductType" style="display: <?php echo $productstyle; ?>;">
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_CATEGORY_FILTER'); ?></td>
					<td><?php echo $this->lists['xmlexport_on_category'];?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT_DETAIL'); ?></td>
					<td><a class="joom-box"
					       href="index.php?tmpl=component&option=com_redshop&view=xmlexport_detail&cid[]=<?php echo $this->detail->xmlexport_id; ?>&layout=elementdetail&section_type=product&parentsection=productdetail"
					       rel="{handler: 'iframe', size: {x: 750, y: 500}}">
							<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH . 'add.jpg'; ?>"
							     title="<?php echo JText::_('COM_REDSHOP_ADD_PRODUCT_DETAIL'); ?>"
							     alt="<?php echo JText::_('COM_REDSHOP_ADD_PRODUCT_DETAIL'); ?>">
						</a></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT_STOCK_DETAIL'); ?></td>
					<td><a class="joom-box"
					       href="index.php?tmpl=component&option=com_redshop&view=xmlexport_detail&cid[]=<?php echo $this->detail->xmlexport_id; ?>&layout=elementdetail&section_type=product&parentsection=stockdetail"
					       rel="{handler: 'iframe', size: {x: 750, y: 500}}">
							<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH . 'add.jpg'; ?>"
							     title="<?php echo JText::_('COM_REDSHOP_ADD_PRODUCT_STOCK_DETAIL'); ?>"
							     alt="<?php echo JText::_('COM_REDSHOP_ADD_PRODUCT_STOCK_DETAIL'); ?>">
						</a></td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT_FIELD_DETAIL'); ?></td>
					<td><a class="joom-box"
					       href="index.php?tmpl=component&option=com_redshop&view=xmlexport_detail&cid[]=<?php echo $this->detail->xmlexport_id; ?>&layout=elementdetail&section_type=product&parentsection=prdextrafield"
					       rel="{handler: 'iframe', size: {x: 750, y: 500}}">
							<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH . 'add.jpg'; ?>"
							     title="<?php echo JText::_('COM_REDSHOP_ADD_PRODUCT_FIELD_DETAIL'); ?>"
							     alt="<?php echo JText::_('COM_REDSHOP_ADD_PRODUCT_FIELD_DETAIL'); ?>">
						</a></td>
				</tr>
				<?php /*?>
	<tr><th><?php echo JText::_('COM_REDSHOP_FIELD_NAME' ); ?></th><th><?php echo JText::_('COM_REDSHOP_XMLEXPORT_FILE_TAG_NAME' ); ?></th></tr>
<?php	for($i=0;$i<count($this->columns);$i++)
		{	?>
	<tr><td width="100" align="right" class="key"><?php echo $this->columns[$i]->Field ?>:</td>
		<td><input type="text" name="<?php echo $this->columns[$i]->Field ?>" id="<?php echo $this->columns[$i]->Field ?>" value="<?php echo $this->colvalue[$i];?>" /></td></tr>
<?php 	}*/    ?>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="tdIPText" id="tdIPText"
	       value="<?php echo JText::_('COM_REDSHOP_IP_OR_URL_TO_ACCESS'); ?>">
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->xmlexport_id; ?>"/>
	<input type="hidden" name="iparray" id="iparray" value="<?php echo count($this->iparray); ?>"/>
	<input type="hidden" name="xmlexport_id" id="xmlexport_id" value="<?php echo $this->detail->xmlexport_id; ?>"/>
	<input type="hidden" name="task" id="task" value=""/>
	<input type="hidden" name="view" value="xmlexport_detail"/>
</form>
