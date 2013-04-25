<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

JHTML::_('behavior.tooltip');
require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';
$producthelper = new producthelper();

JHTMLBehavior::modal();
$uri = JURI::getInstance();
$url = $uri->root();
$showbuttons = JRequest::getCmd('showbuttons');

$section_id = JRequest::getCmd('section_id');

$product_id = JRequest::getCmd('cid');
$images = $producthelper->getAttibuteSubProperty(0, $section_id)
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'save') {
			submitform('subattribute_color');
			return;
		}

	}
</script>

<fieldset class="adminform">
	<div style="float: right">
		<button type="button" onclick="submitbutton('save');">
			<?php
			echo JText::_('COM_REDSHOP_SAVE');
			?>
		</button>
		<button type="button"
		        onclick="window.parent.SqueezeBox.close();">
			<?php
			echo JText::_('COM_REDSHOP_CANCEL');
			?>
		</button>
	</div>
	<div class="configuration"><?php
		echo JText::_('COM_REDSHOP_PROPERTY_MORE_IMAGES_INFORMATION');
		?></div>
</fieldset>

<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">

	<div class="col50">
		<fieldset class="adminform" style="width: 100px;">
			<table class="admintable" border="0" width="100%" id="admintable">
				<tr>
					<td align="left" colspan="2" class="key">
						<?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_NAME'); ?>
					</td>
					<td align="left" colspan="1" class="key">
						<?php echo JText::_('COM_REDSHOP_PROPERTY_SUB_IMAGE'); ?>
					</td>
					<td align="left" colspan="1" class="key">
						<input type="button" name="addvalue" id="addvalue" class="button"
						       Value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>"
						       onclick="addNewRowOfsub('admintable');"/>
					</td>
				</tr>
				<?php
				if (count($images) > 0)
				{
					for ($i = 0; $i < count($images); $i++)
					{
						$image = $images[$i];
						$thumb = $image->subattribute_color_image;
						$imgpath = "/components/" . $option . "/helpers/thumb.php?filename=subcolor/" . $thumb . "&newxsize=" . PRODUCT_ADDITIONAL_IMAGE . "&newysize=" . PRODUCT_ADDITIONAL_IMAGE;
						?>
						<tr>
							<td>
								<input type="text" name="subattribute_name[]" id="subattribute_name[]"
								       value="<?php echo $image->subattribute_color_name; ?>" size="30"/>
							</td>
							<td>
								<?php if(is_file(JPATH_COMPONENT_SITE . $imgpath)) ?>
								<img src="<?php echo $url . $imgpath; ?>"/>
							</td>
							<td>
								<input type="file" name="property_sub_img[]" id="property_sub_img[]" value=""
								       size="51"/>
								<input type="hidden" name="property_sub_img_tmp[]"
								       value="<?php echo $image->subattribute_color_image; ?>"/>
								<input type="hidden" name="subattribute_color_id[]"
								       value="<?php echo $image->subattribute_color_id; ?>"/>
							</td>
							<td>
								<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
								       onclick="deleteRowOfsub(this)" class="button" type="button"/>
							</td>
						</tr>
					<?php
					}
				}
				else
				{

					?>
					<tr>
						<td>
							<input type="text" name="subattribute_name[]" id="subattribute_name[]" value="" size="30"/>
						</td>
						<td>
						</td>
						<td>
							<input type="file" name="property_sub_img[]" id="property_sub_img[]" value="" size="51"/>
							<input type="hidden" name="property_sub_img_tmp[]" value=""/>
							<input type="hidden" name="subattribute_color_id[]" value=""/>
						</td>
						<td>
						</td>
					</tr>
				<?php
				}
				?>
			</table>
		</fieldset>
	</div>

	<div class="clr"></div>
	<input type="hidden" name="cid" value="<?php echo $product_id; ?>"/>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="product_detail"/>
</form>
