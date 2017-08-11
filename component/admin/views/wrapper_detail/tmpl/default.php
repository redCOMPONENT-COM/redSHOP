<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JHTML::_('behavior.tooltip');
$showall = JFactory::getApplication()->input->get('showall', '0');
$producthelper = productHelper::getInstance();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		if (form.wrapper_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_WRAPPER_NAME');?>");
		} else if (isNaN(form.wrapper_price.value)) {
			alert("<?php echo JText::_('COM_REDSHOP_WRAPPER_PRICE_NOT_VALID');?>");
			form.wrapper_price.focus();
		} else {
			submitform(pressbutton);
		}
	}
</script>
<?php if ($showall)
{
	?>
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('save');">
				<?php echo JText::_('COM_REDSHOP_SAVE'); ?>
			</button>
			<button type="button" onclick="submitbutton('cancel');">
				<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
			</button>
		</div>
		<div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_WRAPPER'); ?></div>
	</fieldset>
<?php } ?>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<table class="admintable" width="90%">
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_WRAPPER_NAME');?>
					<span class="star text-danger"> *</span></td>
					<td><input class="text_area" type="text" name="wrapper_name" id="wrapper_name" size="32"
					           maxlength="250" value="<?php echo $this->detail->wrapper_name; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_WRAPPER_NAME'), JText::_('COM_REDSHOP_WRAPPER_NAME'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_WRAPPER_PRICE');?></td>
					<td><input class="text_area" type="text" name="wrapper_price" id="wrapper_price" size="10"
					           maxlength="10"
					           value="<?php echo $producthelper->redpriceDecimal($this->detail->wrapper_price); ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_WRAPPER_PRICE'), JText::_('COM_REDSHOP_WRAPPER_PRICE'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<?php if (!$showall)
				{ ?>
					<tr>
						<td width="100" align="right"
						    class="key"><?php echo JText::_('COM_REDSHOP_CATEGORY_NAME');?></td>
						<td><?php echo $this->lists['category_name'];    ?></td>
					</tr>
				<?php }    ?>
					<tr>
						<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME');?></td>
						<td><?php echo $this->lists['product_name'];?>
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key"
						    valign="top"><?php echo JText::_('COM_REDSHOP_WRAPPER_IMAGE');?></td>
						<td><input class="text_area" type="file" name="wrapper_image" id="wrapper_image"/>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_WRAPPER_IMAGE'), JText::_('COM_REDSHOP_WRAPPER_IMAGE'), 'tooltip.png', '', '', false); ?>
							<?php $wimage_path = 'wrapper/' . $this->detail->wrapper_image;?>
						</td>
					</tr>
					<tr>
						<td valign="top" align="right"
						    class="key"><?php echo JText::_('COM_REDSHOP_USE_TO_ALL_PRODUCT'); ?>:
						</td>
						<td><?php echo $this->lists['use_to_all']; ?></td>
					</tr>
					<tr>
						<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
						</td>
						<td><?php echo $this->lists['published']; ?></td>
					</tr>
				</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->wrapper_id; ?>"/>
	<input type="hidden" name="product_id" value="<?php echo $this->product_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="wrapper_detail"/>
	<input type="hidden" name="showall" value="<?php echo $showall; ?>"/>
</form>
