<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$uri = JURI::getInstance();
$url = $uri->root();

$showbuttons = $this->input->getBool('showbuttons', false);
$section_id = $this->input->getInt('section_id', null);
$fsec = $this->input->getString('fsec', '');
$product_id = $this->input->getInt('cid', null);

if ($fsec == 'subproperty')
{
	$images = $this->model->getSubpropertyImages($section_id);
	$mainImage = $this->producthelper->getAttibuteSubProperty($section_id);
}
else
{
	$mainImage = $this->producthelper->getAttibuteProperty($section_id);
	$images = $this->model->getpropertyImages($section_id);
}

$mainImage = $mainImage[0];
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
			submitbutton(pressbutton);
	};

	submitbutton = function (pressbutton) {
		if (pressbutton == 'save') {
			submitform('property_more_img');
		}
	}
</script>

<fieldset class="adminform">

	<div>
		<button type="button" onclick="submitbutton('save');">
			<?php echo JText::_('COM_REDSHOP_SAVE'); ?>
		</button>
		<button type="button" onclick="window.parent.SqueezeBox.close();">
			<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
		</button>
	</div>

	<div class="configuration">
		<?php echo JText::_('COM_REDSHOP_PROPERTY_MORE_IMAGES_INFORMATION'); ?>
	</div>

</fieldset>

<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

	<fieldset class="adminform">

		<table class="admintable" border="0" id="admintable">

			<tr>
				<td class="key">
					<label for="property_main_img">
						<?php echo JText::_('COM_REDSHOP_PROPERTY_MAIN_IMAGE'); ?>
					</label>
				</td>
				<td>
					<input type="file" name="property_main_img" id="property_main_img" value="" size="75"/>
					<?php
						echo JHtml::tooltip(
									JText::_('COM_REDSHOP_TOOLTIP_PROPERTY_MAIN_IMAGE'),
									JText::_('COM_REDSHOP_PROPERTY_MAIN_IMAGE'),
									'tooltip.png',
									'',
									'',
									false
						);
					?>
				</td>
			</tr>

			<tr>
				<td class="key">
					<label for="property_sub_img">
						<?php echo JText::_('COM_REDSHOP_PROPERTY_SUB_IMAGE'); ?>
					</label>
				</td>
				<td>
					<input type="file" name="property_sub_img[]" id="property_sub_img" value="" size="75"/>
					<input type="button"
						   name="addvalue"
						   id="addvalue"
						   class="button"
						   value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>"
						   onclick="addNewRowOfProp('admintable');"
						/>
					<?php
						echo JHtml::tooltip(
									JText::_('COM_REDSHOP_TOOLTIP_PROPERTY_SUB_IMAGE'),
									JText::_('COM_REDSHOP_PROPERTY_SUB_IMAGE'),
									'tooltip.png',
									'',
									'',
									false
						);
					?>
				</td>
			</tr>

		</table>
	</fieldset>

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
	$mainimg .= '<div>';
	$mainimg .= "<img  height='50' width='50' src='" . REDSHOP_FRONT_IMAGES_ABSPATH . $rs_folder . "/" . $thumb . "'> ";
	$mainimg .= "</div>";

	echo '<div style="clear:both"><b>' . JText::_('COM_REDSHOP_MAIN_IMAGE') . '</b></div>';
	echo $mainimg;
}

if (count($images))
{
	echo '<div style="clear:both"><br><br><b>' . JText::_('COM_REDSHOP_ADDITIONAL_IMAGES') . '</b></div>';
	$more_images = '';

	for ($i = 0, $in = count($images); $i < $in; $i++)
	{
		$image = $images[$i];
		$thumb = $image->media_name;

		if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "property/" . $thumb) && $thumb != '')
		{
			$more_images .= '<div>';
			$more_images .= "<img  height='50' width='50' src='" . REDSHOP_FRONT_IMAGES_ABSPATH . "property/" . $thumb . "'/><br/>" .
							"<a href='index.php?tmpl=component&option=com_redshop&view=product_detail&section_id=" . $section_id .
							"&cid=" . $product_id . "&mediaid=" . $image->media_id . "&layout=property_images&showbuttons=1&task=deleteimage'>" .
							JText::_('COM_REDSHOP_DELETE') .
							"</a>";
			$more_images .= "</div>";
		}
	}

	echo $more_images;
}
