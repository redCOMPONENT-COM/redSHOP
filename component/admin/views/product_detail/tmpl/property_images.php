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
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
$producthelper = new producthelper();

JHTMLBehavior::modal();
$uri = JURI::getInstance();
$url = $uri->root();
$showbuttons = JRequest::getCmd('showbuttons');

$section_id = JRequest::getCmd('section_id');
$fsec = JRequest::getCmd('fsec');

$product_id = JRequest::getCmd('cid');
if ($fsec == 'subproperty')
{
	$images = $this->model->getSubpropertyImages($section_id);
	$mainImage = $producthelper->getAttibuteSubProperty($section_id);

}
else
{
	$mainImage = $producthelper->getAttibuteProperty($section_id);
	$images = $this->model->getpropertyImages($section_id);
}
$product_id = JRequest::getCmd('cid');
//$images = $this->model->getPropertyImages($section_id);

$mainImage = $mainImage[0];
?>
	<script language="javascript" type="text/javascript">
		Joomla.submitbutton = function (pressbutton) {
			submitbutton(pressbutton);
		}

		submitbutton = function (pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'save') {
				submitform('property_more_img');
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
			<fieldset class="adminform">

				<table class="admintable" border="0" width="100%" id="admintable">
					<tr>
						<td width="100" align="right" class="key">
							<label for="name"><?php echo JText::_('COM_REDSHOP_PROPERTY_MAIN_IMAGE'); ?>:</label>
						</td>
						<td>
							<input type="file" name="property_main_img" id="property_main_img" value="" size="75"/>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PROPERTY_MAIN_IMAGE'), JText::_('COM_REDSHOP_PROPERTY_MAIN_IMAGE'), 'tooltip.png', '', '', false);    ?>
						</td>
					</tr>
					<tr>
						<td valign="top" align="right" class="key">
							<label for="name"><?php echo JText::_('COM_REDSHOP_PROPERTY_SUB_IMAGE'); ?>:</label>
						</td>
						<td>
							<input type="file" name="property_sub_img[]" id="property_sub_img[]" value="" size="75"/>
							<input type="button" name="addvalue" id="addvalue" class="button"
							       Value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>"
							       onclick="addNewRowOfProp('admintable');"/>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PROPERTY_SUB_IMAGE'), JText::_('COM_REDSHOP_PROPERTY_SUB_IMAGE'), 'tooltip.png', '', '', false);    ?>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>

		<div class="clr"></div>
		<input type="hidden" name="cid" value="<?php echo $product_id; ?>"/>
		<input type="hidden" name="option" value="com_redshop"/>
		<input type="hidden" name="fsec" value="<?php echo $fsec ?>"/>
		<input type="hidden" name="section_id" value="<?php echo $section_id; ?>"/>
		<input type="hidden" name="attribute_set" value="<?php echo $attribute_set; ?>"/>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="view" value="product_detail"/>
	</form>
<?php

if ($fsec == 'subproperty')
{
	$thumb = $mainImage->subattribute_color_image;
	$rs_folder = 'subcolor';
}
else
{
	$thumb = $mainImage->property_main_image;
	$rs_folder = 'property';
}

$mainimg = '';
if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $rs_folder . "/" . $thumb) && $thumb != '')
{
	$mainimg .= '<div align="center" style="100px;float:left; border:1px solid #ccc">';

	//$mainimg .="<img src='".$url."/components/".$option."/helpers/thumb.php?filename=property/".$thumb."&newxsize=".PRODUCT_ADDITIONAL_IMAGE."&newysize=".PRODUCT_ADDITIONAL_IMAGE."'> ";
	$mainimg .= "<img  height='50' width='50' src='" . REDSHOP_FRONT_IMAGES_ABSPATH . $rs_folder . "/" . $thumb . "'> ";
	$mainimg .= "</div>";

	echo '<div style="clear:both"><b>' . JText::_('COM_REDSHOP_MAIN_IMAGE') . '</b></div>';
	echo $mainimg;

}
if (count($images))
{
	echo '<div style="clear:both"><br><br><b>' . JText::_('COM_REDSHOP_ADDITIONAL_IMAGES') . '</b></div>';
	$more_images = '';
	for ($i = 0; $i < count($images); $i++)
	{
		$image = $images[$i]; //print_r($image);
		$thumb = $image->media_name;
		if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "property/" . $thumb) && $thumb != '')
		{
			$more_images .= '<div align="center" style="100px;float:left; border:1px solid #ccc">';

			$more_images .= "<img  height='50' width='50' src='" . REDSHOP_FRONT_IMAGES_ABSPATH . "property/" . $thumb . "'/>
			 <br> <a href='index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=" . $section_id . "&cid=" . $product_id . "&mediaid=" . $image->media_id . "&layout=property_images&showbuttons=1&task=deleteimage'>" . JText::_('COM_REDSHOP_Delete') . "</a>
			 ";

			$more_images .= "</div>";
		}

	}
	echo $more_images;
}
?>
