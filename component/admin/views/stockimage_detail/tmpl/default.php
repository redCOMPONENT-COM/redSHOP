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
$uri = JURI::getInstance();
$url = $uri->root();
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

		if (form.stock_amount_image_tooltip.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_STOCKIMAGE_TOOLTIP_MUST_HAVE_A_NAME', true ); ?>");
		} else if (form.stock_option.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_STOCKIMAGE_OPTION_MUST_HAVE_VALUE', true ); ?>");
		} else if (form.stock_quantity.value == "" || isNaN(form.stock_quantity.value)) {
			alert("<?php echo JText::_('COM_REDSHOP_STOCKIMAGE_QUANTITY_MUST_HAVE_VALUE', true ); ?>");
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

			<table class="admintable">
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_STOCK_AMOUNT_IMAGE_TOOLTIP_LBL'); ?>:
					</td>
					<td><input type="text" name="stock_amount_image_tooltip" id="stock_amount_image_tooltip"
					           value="<?php echo $this->detail->stock_amount_image_tooltip; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_STOCKROOM_NAME'); ?>:
					</td>
					<td><?php echo $this->lists['stockroom_id'];?></td>
				</tr>
				<tr>
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_STOCK_AMOUNT_OPTION_LBL'); ?>:
					</td>
					<td><?php echo $this->lists['stock_option'];?></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_STOCK_AMOUNT_QUANTITY_LBL'); ?>:
					</td>
					<td><input type="text" name="stock_quantity" id="stock_quantity"
					           value="<?php echo $this->detail->stock_quantity; ?>"></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_STOCK_AMOUNT_IMAGE_LBL'); ?>:
					</td>
					<td><input type="file" name="stock_amount_image"/>

						<div><img
								src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'stockroom/' . $this->detail->stock_amount_image; ?>"
								width="150px" height="90px"/></div>
						<input type="hidden" name="stock_image"
						       value="<?php echo $this->detail->stock_amount_image; ?>"/></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="stock_amount_id" value="<?php echo $this->detail->stock_amount_id; ?>"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->stock_amount_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="stockimage_detail"/>
</form>
