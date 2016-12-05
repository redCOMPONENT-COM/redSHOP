<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtmlBehavior::modal('a.joom-box');

$uri = JURI::getInstance();
$url = $uri->root();
$addtocart_path = "/components/com_redshop/assets/images/";
?>

<legend><?php echo JText::_('COM_REDSHOP_CART_TEMPLATE_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
					  title="<?php echo JText::_('COM_REDSHOP_ONESTEP_CHECKOUT_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ONESTEP_CHECKOUT_ENABLE_LBL'); ?>">
		<label for="onestep_checkout"><?php echo JText::_('COM_REDSHOP_ONESTEP_CHECKOUT_ENABLE_LBL');?></label></span>
	<?php echo $this->lists ['onestep_checkout_enable'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
				  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_AJAX_CART_BOX_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_AJAX_CART_BOX_TEMPLATE'); ?>">
		<label
				for="ajax_cart_box_template"><?php echo JText::_('COM_REDSHOP_AJAX_CART_BOX_TEMPLATE');?></label></span>
	<?php echo $this->lists ['ajax_detail_template'];?>
</div>

<legend><?php echo JText::_('COM_REDSHOP_CART_IMAGE_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CART_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_CART_THUMB_WIDTH_LBL'); ?>">
		<label for="name">
			<?php echo JText::_('COM_REDSHOP_CART_THUMB_WIDTH_HEIGHT');?>
		</label></span>
	<input type="text" name="cart_thumb_width" id="cart_thumb_width"
								   value="<?php echo $this->config->get('CART_THUMB_WIDTH'); ?>">
	<input type="text" name="cart_thumb_height" id="cart_thumb_height"
								   value="<?php echo $this->config->get('CART_THUMB_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		  title="<?php echo JText::_('COM_REDSHOP_WATERMARK_CART_THUMB_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CART_THUMB_IMAGE'); ?>">
	<label for="name"> <?php echo JText::_('COM_REDSHOP_WATERMARK_CART_THUMB_IMAGE_LBL');?></label></span>
	<?php echo $this->lists ['watermark_cart_thumb_image'];?>
</div>

<legend><?php echo JText::_('COM_REDSHOP_CART_DEFAULT_IMAGE_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_IMAGE'); ?>">
		<?php echo JText::_('COM_REDSHOP_ADDTOCART_IMAGE_LBL');?>:</span>
	<input class="text_area" type="file" name="cartimg" id="cartimg" size="50"/>
	<input type="hidden" name="addtocart_image" id="addtocart_image"
		   value="<?php echo $this->config->get('ADDTOCART_IMAGE'); ?>"/>


	<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $this->config->get('ADDTOCART_IMAGE'))) { ?>
	<div class="divimages" id="cartdiv">
		<a class="joom-box"
		   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $this->config->get('ADDTOCART_IMAGE'); ?>"
		   title="<?php echo $this->config->get('ADDTOCART_IMAGE'); ?>" rel="{handler: 'image', size: {}}">
			<img alt="<?php echo $this->config->get('ADDTOCART_IMAGE'); ?>"
				 src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $this->config->get('ADDTOCART_IMAGE'); ?>"/></a>
		<a class="remove_link" href="#" onclick="delimg('<?php echo $this->config->get('ADDTOCART_IMAGE') ?>','cartdiv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
	</div>
	<?php } ?>

</div>

<div class="form-group">
	<span class="editlinktip hasTip"
							title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_BACKGROUND_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_BACKGROUND'); ?>"><?php echo JText::_('COM_REDSHOP_ADDTOCART_BACKGROUND_LBL');?>:
	</span>
	<input type="text" name="addtocart_background" id="addtocart_background"
		   value="<?php echo $this->config->get('ADDTOCART_BACKGROUND'); ?>"/>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_IMAGE_LBL'); ?>">
	<?php echo JText::_('COM_REDSHOP_REQUESTQUOTE_IMAGE_LBL');?>:</span>
	<?php $requestquoteImage = $this->config->get('REQUESTQUOTE_IMAGE'); ?>
	<input class="text_area" type="file" name="quoteimg" id="quoteimg" size="50"/>
	<input type="hidden" name="requestquote_image" id="requestquote_image" value="<?php echo $requestquoteImage; ?>"/>

	<?php if (is_file(JPATH_ROOT . $addtocart_path . $requestquoteImage)) { ?>
	<div class="divimages" id="quotediv">
		<a class="joom-box" href="<?php echo $url . $addtocart_path . $requestquoteImage; ?>"
		   title="<?php echo $requestquoteImage; ?>" rel="{handler: 'image', size: {}}">
			<img alt="<?php echo $requestquoteImage; ?>"
				 src="<?php echo $url . $addtocart_path . $requestquoteImage; ?>"/></a>
		<a class="remove_link" href="#"
		   onclick="delimg('<?php echo $requestquoteImage ?>','quotediv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
	</div>
	<?php } ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		 title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_BACKGROUND_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_BACKGROUND_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_REQUESTQUOTE_BACKGROUND_LBL');?>:
	</span>
	<?php $requestquoteBackground =  $this->config->get('REQUESTQUOTE_BACKGROUND'); ?>
	<input type="text" name="requestquote_background" id="requestquote_background"
		   value="<?php echo $requestquoteBackground; ?>"/>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_UPDATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_UPDATE_LBL'); ?>"><?php echo  JText::_('COM_REDSHOP_ADDTOCART_UPDATE_LBL');?>:</span>
	<?php $addtocartUpdate =  $this->config->get('ADDTOCART_UPDATE'); ?>
	<input class="text_area" type="file" name="cartupdate" id="cartupdate" size="50"/>
	<input type="hidden" name="addtocart_update" id="addtocart_update"
		   value="<?php echo $addtocartUpdate; ?>"/>
	<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $addtocartUpdate)) { ?>
	<div class="divimages" id="cartupdatediv">
		<a class="joom-box"
			   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartUpdate; ?>"
			   title="<?php echo $addtocartUpdate; ?>" rel="{handler: 'image', size: {}}">
				<img alt="<?php echo $addtocartUpdate; ?>"
					 src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartUpdate; ?>"/>
		</a>
		<a class="remove_link" href="#" onclick="delimg('<?php echo $addtocartUpdate ?>','cartupdatediv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
	</div>
	<?php } ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		 title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_DELETE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_DELETE_LBL'); ?>"><?php echo  JText::_('COM_REDSHOP_ADDTOCART_DELETE_LBL');?>:</span>
	<?php $addtocartDelete =  $this->config->get('ADDTOCART_DELETE'); ?>
	<input class="text_area" type="file" name="cartdelete" id="cartdelete" size="50"/>
	<input type="hidden" name="addtocart_delete" id="addtocart_delete"
		   value="<?php echo $addtocartDelete; ?>"/>
	<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $addtocartDelete)){ ?>
	<div class="divimages" id="cartdeldiv">
		<a class="joom-box"
			   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartDelete; ?>"
			   title="<?php echo $addtocartDelete; ?>" rel="{handler: 'image', size: {}}">
				<img alt="<?php echo $addtocartDelete; ?>"
					 src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartDelete; ?>"/>
		</a>
		<a class="remove_link" href="#" onclick="delimg('<?php echo $addtocartDelete ?>','cartdeldiv','<?php echo $addtocart_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE')?></a>
	</div>
	<?php } ?>
</div>

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

</script>
