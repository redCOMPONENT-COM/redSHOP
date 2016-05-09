<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
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
						<td colspan="2">
							<p class="text-warning">
							<strong>
							<?php
								echo JText::sprintf(
									'COM_REDSHOP_CONFIG_ORDER_FIELD_MOVED_HINT',
									'<a class="showOrderTab" href="javascript:void(0);">Orders</a>'
								);
							?>
							</strong>
						</p>
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
							       value="<?php echo $this->config->get('CART_THUMB_WIDTH'); ?>">
							<input type="text" name="cart_thumb_height" id="cart_thumb_height"
							       value="<?php echo $this->config->get('CART_THUMB_HEIGHT'); ?>">
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
									       value="<?php echo $this->config->get('ADDTOCART_IMAGE'); ?>"/>
									<a href="#123"
									   onclick="delimg('<?php echo $this->config->get('ADDTOCART_IMAGE') ?>','cartdiv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
								</div>
							</div>
							<div>&nbsp;</div>
							<div id="cartdiv"><?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $this->config->get('ADDTOCART_IMAGE')))
								{ ?>
									<div style="width: 500px; height: 50px;">
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $this->config->get('ADDTOCART_IMAGE'); ?>"
									   title="<?php echo $this->config->get('ADDTOCART_IMAGE'); ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo $this->config->get('ADDTOCART_IMAGE'); ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $this->config->get('ADDTOCART_IMAGE'); ?>"/></a>
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
									       value="<?php echo $this->config->get('ADDTOCART_BACKGROUND'); ?>"/>
									<a href="#123"
									   onclick="delimg('<?php echo $this->config->get('ADDTOCART_BACKGROUND') ?>','cartbgdiv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
								</div>
							</div>
							<div>&nbsp;</div>
							<div style="width: 500px; height: 50px;">
								<div
									id="cartbgdiv"><?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $this->config->get('ADDTOCART_BACKGROUND')))
									{ ?>
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $this->config->get('ADDTOCART_BACKGROUND'); ?>"
									   title="<?php echo $this->config->get('ADDTOCART_BACKGROUND'); ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo $this->config->get('ADDTOCART_BACKGROUND'); ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $this->config->get('ADDTOCART_BACKGROUND'); ?>"/>
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
							<?php $requestquoteImage = $this->config->get('REQUESTQUOTE_IMAGE'); ?>
							<div style="width: 400px; height: 40px;">
								<div style="float: left;">
									<input class="text_area" type="file" name="quoteimg" id="quoteimg" size="50"/>
									<input type="hidden" name="requestquote_image" id="requestquote_image"
									       value="<?php echo $requestquoteImage; ?>"/>
								</div>
							</div>
							<div>&nbsp;</div>
							<div id="quotediv"><?php if (is_file(JPATH_ROOT . $addtocart_path . $requestquoteImage))
								{ ?>
									<div style="width: 300px; height: 30px;">
									<a class="modal" href="<?php echo $url . $addtocart_path . $requestquoteImage; ?>"
									   title="<?php echo $requestquoteImage; ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo $requestquoteImage; ?>"
										     src="<?php echo $url . $addtocart_path . $requestquoteImage; ?>"/></a>
									<a class="remove_link" href="#123"
									   onclick="delimg('<?php echo $requestquoteImage ?>','quotediv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
									</div><?php } ?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td align="right" class="key"><span class="editlinktip hasTip"
						                                    title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_BACKGROUND_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_BACKGROUND_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_REQUESTQUOTE_BACKGROUND_LBL');?>:</span></td>
						<td>
							<?php $requestquoteBackground =  $this->config->get('REQUESTQUOTE_BACKGROUND'); ?>
							<div style="width: 500px; height: 50px;">
								<div style="float: left;">
									<input class="text_area" type="file" name="quoteback" id="quoteback" size="50"/>
									<input type="hidden" name="requestquote_background" id="requestquote_background"
									       value="<?php echo $requestquoteBackground; ?>"/>
									<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $requestquoteBackground))
									{ ?>
										<a href="#123"
										   onclick="delimg('<?php echo $requestquoteBackground ?>','quotebgdiv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE') ?></a><?php }?>
								</div>
							</div>
							<div>&nbsp;</div>
							<div style="width: 500px; height: 50px;">
								<div
									id="quotebgdiv"><?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $requestquoteBackground))
									{ ?>
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $requestquoteBackground; ?>"
									   title="<?php echo $requestquoteBackground; ?>"
									   rel="{handler: 'image', size: {}}">
										<img alt="<?php echo $requestquoteBackground; ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $requestquoteBackground; ?>"/>
										</a><?php } ?></div>
							</div>
							</div></td>
					</tr>
					<tr>
						<td align="right" class="key"><span class="editlinktip hasTip"
						                                    title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_UPDATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_UPDATE_LBL'); ?>"><?php echo  JText::_('COM_REDSHOP_ADDTOCART_UPDATE_LBL');?>
								:</span></td>
						<td>
							<?php $addtocartUpdate =  $this->config->get('ADDTOCART_UPDATE'); ?>
							<div style="width: 500px; height: 50px;">
								<div style="float: left;">
									<input class="text_area" type="file" name="cartupdate" id="cartupdate" size="50"/>
									<input type="hidden" name="addtocart_update" id="addtocart_update"
									       value="<?php echo $addtocartUpdate; ?>"/>
									<a href="#123"
									   onclick="delimg('<?php echo $addtocartUpdate ?>','cartupdatediv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
								</div>
							</div>
							<div>&nbsp;</div>
							<div style="width: 500px; height: 50px;">
								<div
									id="cartupdatediv"><?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $addtocartUpdate))
									{ ?>
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartUpdate; ?>"
									   title="<?php echo $addtocartUpdate; ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo $addtocartUpdate; ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartUpdate; ?>"/>
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
							<?php $addtocartDelete =  $this->config->get('ADDTOCART_UPDATE'); ?>
							<div style="width: 500px; height: 50px;">
								<div style="float: left;">
									<input class="text_area" type="file" name="cartdelete" id="cartdelete" size="50"/>
									<input type="hidden" name="addtocart_delete" id="addtocart_delete"
									       value="<?php echo $addtocartDelete; ?>"/>
									<a href="#123"
									   onclick="delimg('<?php echo $addtocartDelete ?>','cartdeldiv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
								</div>
							</div>
							<div>&nbsp;</div>
							<div style="width: 500px; height: 50px;">
								<div id="cartdeldiv"><?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $addtocartDelete))
									{ ?>
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartDelete; ?>"
									   title="<?php echo $addtocartDelete; ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo $addtocartDelete; ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartDelete; ?>"/>
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
			xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
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
	quote_price(<?php echo $this->config->get('DEFAULT_QUOTATION_MODE_PRE');?>);
</script>
