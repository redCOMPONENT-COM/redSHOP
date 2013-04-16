<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');
$uri = JURI::getInstance();
$url = $uri->root();
$addtocart_path = "/components/com_redshop/assets/images/";
?>
<table cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td width="50%">
			<fieldset class="adminform">
				<table class="admintable">
					<tr>
						<td class="config_param"><?php echo JText::_('COM_REDSHOP_CART_TEMPLATE_SETTINGS'); ?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_ONESTEP_CHECKOUT_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ONESTEP_CHECKOUT_ENABLE_LBL'); ?>">
				<label for="onestep_checkout"><?php echo JText::_('COM_REDSHOP_ONESTEP_CHECKOUT_ENABLE_LBL');?></label></span>
						</td>
						<td><?php echo $this->lists ['onestep_checkout_enable'];?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_ORDER_ID_RESET_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ORDER_ID_RESET_LBL'); ?>">
				<label for="onestep_checkout"><?php echo JText::_('COM_REDSHOP_ORDER_ID_RESET_LBL');?></label></span>
						</td>
						<td><a onclick="javascript:resetOrderId();"
						       title="<?php echo JText::_('COM_REDSHOP_ORDER_ID_RESET_LBL'); ?>"><?php echo JText::_('COM_REDSHOP_ORDER_ID_RESET');?></a>
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_INVOICE_NUMBER_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_INVOICE_NUMBER_TEMPLATE'); ?>">
			<label
				for="invoice_number_template"><?php echo JText::_('COM_REDSHOP_INVOICE_NUMBER_TEMPLATE_LBL');?></label></span>
						</td>
						<td>
							<input type="text" name="invoice_number_template" id="invoice_number_template"
							       value="<?php echo INVOICE_NUMBER_TEMPLATE; ?>">
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_AJAX_CART_BOX_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_AJAX_CART_BOX_TEMPLATE'); ?>">
			<label
				for="ajax_cart_box_template"><?php echo JText::_('COM_REDSHOP_AJAX_CART_BOX_TEMPLATE');?></label></span>
						</td>
						<td><?php echo $this->lists ['ajax_detail_template'];?></td>
					</tr>
				</table>
			</fieldset>

			<fieldset class="adminform">
				<table class="admintable" width="100%">
					<tr>
						<td class="config_param"><?php echo JText::_('COM_REDSHOP_CART_IMAGE_SETTINGS'); ?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CART_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_CART_THUMB_WIDTH_LBL'); ?>">
		<label for="name">
			<?php echo JText::_('COM_REDSHOP_CART_THUMB_WIDTH_HEIGHT');?>
		</label></span></td>
						<td>
							<input type="text" name="cart_thumb_width" id="cart_thumb_width"
							       value="<?php echo CART_THUMB_WIDTH; ?>">
							<input type="text" name="cart_thumb_height" id="cart_thumb_height"
							       value="<?php echo CART_THUMB_HEIGHT; ?>">
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_WATERMARK_CART_THUMB_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CART_THUMB_IMAGE'); ?>">
	<label for="name"> <?php echo JText::_('COM_REDSHOP_WATERMARK_CART_THUMB_IMAGE_LBL');?></label></span></td>
						<td><?php echo $this->lists ['watermark_cart_thumb_image'];?></td>
					</tr>
				</table>
			</fieldset>

			<fieldset class="adminform">
				<table class="admintable" width="100%">
					<tr>
						<td class="config_param"><?php echo JText::_('COM_REDSHOP_CART_DEFAULT_IMAGE_SETTINGS'); ?></td>
					</tr>
					<tr>
						<td align="right" class="key">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_IMAGE'); ?>">
		<?php echo JText::_('COM_REDSHOP_ADDTOCART_IMAGE_LBL');?>:</span></td>
						<td>
							<div style="width: 500px; height: 50px;">
								<div style="float: left;">
									<input class="text_area" type="file" name="cartimg" id="cartimg" size="50"/>
									<input type="hidden" name="addtocart_image" id="addtocart_image"
									       value="<?php echo ADDTOCART_IMAGE; ?>"/>
									<a href="#123"
									   onclick="delimg('<?php echo ADDTOCART_IMAGE ?>','cartdiv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
								</div>
							</div>
							<div>&nbsp;</div>
							<div id="cartdiv"><?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . ADDTOCART_IMAGE))
								{ ?>
									<div style="width: 500px; height: 50px;">
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . ADDTOCART_IMAGE; ?>"
									   title="<?php echo ADDTOCART_IMAGE; ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo ADDTOCART_IMAGE; ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . ADDTOCART_IMAGE; ?>"/></a>
									</div><?php } ?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td align="right" class="key"><span class="editlinktip hasTip"
						                                    title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_BACKGROUND_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_BACKGROUND'); ?>"><?php echo JText::_('COM_REDSHOP_ADDTOCART_BACKGROUND_LBL');?>
								:</span></td>
						<td>
							<div style="width: 500px; height: 50px;">
								<div style="float: left;">
									<input class="text_area" type="file" name="cartback" id="cartback" size="50"/>
									<input type="hidden" name="addtocart_background" id="addtocart_background"
									       value="<?php echo ADDTOCART_BACKGROUND; ?>"/>
									<a href="#123"
									   onclick="delimg('<?php echo ADDTOCART_BACKGROUND ?>','cartbgdiv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
								</div>
							</div>
							<div>&nbsp;</div>
							<div style="width: 500px; height: 50px;">
								<div
									id="cartbgdiv"><?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . ADDTOCART_BACKGROUND))
									{ ?>
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . ADDTOCART_BACKGROUND; ?>"
									   title="<?php echo ADDTOCART_BACKGROUND; ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo ADDTOCART_BACKGROUND; ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . ADDTOCART_BACKGROUND; ?>"/>
										</a><?php } ?>
								</div>
							</div>
							</div>
						</td>
					</tr>
					<tr>
						<td align="right" class="key">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_IMAGE_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_REQUESTQUOTE_IMAGE_LBL');?>:</span></td>
						<td>
							<div style="width: 400px; height: 40px;">
								<div style="float: left;">
									<input class="text_area" type="file" name="quoteimg" id="quoteimg" size="50"/>
									<input type="hidden" name="requestquote_image" id="requestquote_image"
									       value="<?php echo REQUESTQUOTE_IMAGE; ?>"/>
								</div>
							</div>
							<div>&nbsp;</div>
							<div id="quotediv"><?php if (is_file(JPATH_ROOT . $addtocart_path . REQUESTQUOTE_IMAGE))
								{ ?>
									<div style="width: 300px; height: 30px;">
									<a class="modal" href="<?php echo $url . $addtocart_path . REQUESTQUOTE_IMAGE; ?>"
									   title="<?php echo REQUESTQUOTE_IMAGE; ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo REQUESTQUOTE_IMAGE; ?>"
										     src="<?php echo $url . $addtocart_path . REQUESTQUOTE_IMAGE; ?>"/></a>
									<a class="remove_link" href="#123"
									   onclick="delimg('<?php echo REQUESTQUOTE_IMAGE ?>','quotediv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
									</div><?php } ?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td align="right" class="key"><span class="editlinktip hasTip"
						                                    title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_BACKGROUND_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_BACKGROUND_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_REQUESTQUOTE_BACKGROUND_LBL');?>:</span></td>
						<td>
							<div style="width: 500px; height: 50px;">
								<div style="float: left;">
									<input class="text_area" type="file" name="quoteback" id="quoteback" size="50"/>
									<input type="hidden" name="requestquote_background" id="requestquote_background"
									       value="<?php echo REQUESTQUOTE_BACKGROUND; ?>"/>
									<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . REQUESTQUOTE_BACKGROUND))
									{ ?>
										<a href="#123"
										   onclick="delimg('<?php echo REQUESTQUOTE_BACKGROUND ?>','quotebgdiv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE') ?></a><?php }?>
								</div>
							</div>
							<div>&nbsp;</div>
							<div style="width: 500px; height: 50px;">
								<div
									id="quotebgdiv"><?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . REQUESTQUOTE_BACKGROUND))
									{ ?>
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . REQUESTQUOTE_BACKGROUND; ?>"
									   title="<?php echo REQUESTQUOTE_BACKGROUND; ?>"
									   rel="{handler: 'image', size: {}}">
										<img alt="<?php echo REQUESTQUOTE_BACKGROUND; ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . REQUESTQUOTE_BACKGROUND; ?>"/>
										</a><?php } ?></div>
							</div>
							</div></td>
					</tr>
					<tr>
						<td align="right" class="key"><span class="editlinktip hasTip"
						                                    title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_UPDATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_UPDATE_LBL'); ?>"><?php echo  JText::_('COM_REDSHOP_ADDTOCART_UPDATE_LBL');?>
								:</span></td>
						<td>
							<div style="width: 500px; height: 50px;">
								<div style="float: left;">
									<input class="text_area" type="file" name="cartupdate" id="cartupdate" size="50"/>
									<input type="hidden" name="addtocart_update" id="addtocart_update"
									       value="<?php echo ADDTOCART_UPDATE; ?>"/>
									<a href="#123"
									   onclick="delimg('<?php echo ADDTOCART_UPDATE ?>','cartupdatediv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
								</div>
							</div>
							<div>&nbsp;</div>
							<div style="width: 500px; height: 50px;">
								<div
									id="cartupdatediv"><?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . ADDTOCART_UPDATE))
									{ ?>
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . ADDTOCART_UPDATE; ?>"
									   title="<?php echo ADDTOCART_UPDATE; ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo ADDTOCART_UPDATE; ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . ADDTOCART_UPDATE; ?>"/>
										</a><?php } ?>
								</div>
							</div>
							</div>
						</td>
					</tr>
					<tr>
						<td align="right" class="key"><span class="editlinktip hasTip"
						                                    title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_DELETE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_DELETE_LBL'); ?>"><?php echo  JText::_('COM_REDSHOP_ADDTOCART_DELETE_LBL');?>
								:</span></td>
						<td>
							<div style="width: 500px; height: 50px;">
								<div style="float: left;">
									<input class="text_area" type="file" name="cartdelete" id="cartdelete" size="50"/>
									<input type="hidden" name="addtocart_delete" id="addtocart_delete"
									       value="<?php echo ADDTOCART_DELETE; ?>"/>
									<a href="#123"
									   onclick="delimg('<?php echo ADDTOCART_DELETE ?>','cartdeldiv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
								</div>
							</div>
							<div>&nbsp;</div>
							<div style="width: 500px; height: 50px;">
								<div id="cartdeldiv"><?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . ADDTOCART_DELETE))
									{ ?>
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . ADDTOCART_DELETE; ?>"
									   title="<?php echo ADDTOCART_DELETE; ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo ADDTOCART_DELETE; ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . ADDTOCART_DELETE; ?>"/>
										</a><?php } ?>
								</div>
							</div>
							</div>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>
<script language="javascript" type="text/javascript">
	var xmlhttp
	function GetXmlHttpObject() {
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			return new XMLHttpRequest();
		}
		if (window.ActiveXObject) {
			// code for IE6, IE5
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		return null;
	}

	function resetOrderId() {
		if (!confirm("<?php echo Jtext::_('COM_REDSHOP_CONFIRM_ORDER_ID_RESET');?> ")) {
			return false;
		}
		else {
			xmlhttp = GetXmlHttpObject();
			if (xmlhttp == null) {
				alert("Your browser does not support XMLHTTP!");
				return;
			}
			var url = 'index.php?option=com_redshop&view=configuration&task=resetOrderId&sid=' + Math.random();
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4) {
					alert("<?php echo JText::_('COM_REDSHOP_SUCCESSFULLY_RESET_ORDER_ID');?>");
				}
			}
			xmlhttp.open("GET", url, true);
			xmlhttp.send(null);
		}
	}
	function quote_price(val) {

		if (val == "0") {
			document.getElementById('quotationprice').style.display = "none";
		} else {
			document.getElementById('quotationprice').style.display = "";
		}
	}
	quote_price(<?php echo DEFAULT_QUOTATION_MODE_PRE?>);
</script>
