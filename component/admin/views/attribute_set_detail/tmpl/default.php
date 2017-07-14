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
$url = JURI::getInstance()->root();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.attribute_set_name.value == "") {

			alert("<?php
		echo JText::_('COM_REDSHOP_PRODUCT_ITEM_MUST_HAVE_A_NAME', true );
		?>");
		}
		else {

			submitform(pressbutton);
		}
	}
	function oprand_check(s) {
		var oprand = s.value;
		if (oprand != '+' && oprand != '-' && oprand != '=' && oprand != '*' && oprand != "/") {
			alert("<?php
		echo JText::_('COM_REDSHOP_WRONG_OPRAND', true );
		?>");

			s.value = "+";
		}
	}
	function jimage_insert(main_path, fid, fsec) {

		var path_url = "<?php echo $url;?>";
		var propimg;

		if (!fid && !fsec) {

			if (main_path) {
				document.getElementById("image_display").style.display = "block";
				document.getElementById("product_image").value = main_path;
				document.getElementById("image_display").src = path_url + main_path;
			}
			else {
				document.getElementById("product_image").value = "";
				document.getElementById("image_display").src = "";
			}
		} else {

			if (fsec == 'property') {
				if (main_path) {
					propimg = 'propertyImage' + fid;
					document.getElementById(propimg).style.display = "block";
					document.getElementById(propimg).width = "60";
					document.getElementById(propimg).heidth = "60";
					document.getElementById("propmainImage" + fid).value = main_path;
					document.getElementById(propimg).src = path_url + main_path;


				}
				else {
					document.getElementById("propmainImage" + fid).value = "";
					document.getElementById("propimg" + fid).src = "";
				}
			} else {
				if (main_path) {

					propimg = 'subpropertyImage' + fid;
					document.getElementById(propimg).style.display = "block";
					document.getElementById(propimg).width = "60";
					document.getElementById(propimg).heidth = "60";
					document.getElementById("subpropmainImage" + fid).value = main_path;
					document.getElementById(propimg).src = path_url + main_path;


				}
				else {
					document.getElementById("subpropmainImage" + fid).value = "";
					document.getElementById("propimg" + fid).src = "";
				}
			}
		}
	}
</script>
<form action="<?php
echo JRoute::_($this->request_url)?>" method="post"
      name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php
			echo JText::_('COM_REDSHOP_ATTRIBUTE_SET_INFORMATION');
			?></legend>
		<table class="admintable" border="0" width="100%">
			<tr valign="top">
				<td width="50%">
					<table>
						<tr>
							<td width="100" align="right" class="key">
								<label for="name"> <?php echo JText::_('COM_REDSHOP_ATTRIBUTE_SET_NAME'); ?><span class="star text-danger"> *</span>: </label>
							</td>
							<td>
								<input class="text_area" type="text" name="attribute_set_name" id="attribute_set_name"
								       size="32" maxlength="250"
								       value="<?php echo $this->detail->attribute_set_name; ?>"/>
								<?php
								echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_ATTRIBUTE_SET_NAME'), JText::_('COM_REDSHOP_ATTRIBUTE_SET_NAME'), 'tooltip.png', '', '', false);
								?>                </td>
						</tr>
						<tr>
							<td valign="top" align="right" class="key">
								<?php echo JText::_('COM_REDSHOP_PUBLISHED');?>:
							</td>
							<td>
								<?php echo $this->lists ['published'];?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTES'); ?></legend>
	<table class="admintable" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="2">
				<div><?php echo JText::_('COM_REDSHOP_HINT_ATTRIBUTE');?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2"><?php echo JText::_('COM_REDSHOP_COPY_ATTRIBUTES_FROM_ATTRIBUTE_SET');?></td>
		</tr>
		<tr>
			<td colspan="2">
				<a class="btn btn-success add_attribute btn-small"
				   href="#"> <?php echo '+ ' . JText::_('COM_REDSHOP_NEW_ATTRIBUTE'); ?></a>
			</td>
		</tr>
	</table>
</fieldset>
<?php echo RedshopLayoutHelper::render('product_detail.product_attribute', array('this' => $this)); ?>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->attribute_set_id; ?>"/>
<input type="hidden" name="attribute_set_id" value="<?php echo $this->detail->attribute_set_id; ?>"/>
<input type="hidden" name="product_id" value="0" />
<input type="hidden" name="task" value=""/>
<input type="hidden" name="view" value="attribute_set_detail"/>
</form>
<script type="text/javascript">
	function showBox(div) {
		var opendiv = document.getElementById(div);

		if (opendiv.style.display == 'block') opendiv.style.display = 'none';
		else opendiv.style.display = 'block';
		return false;
	}

</script>
