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

$section_id = $this->input->getInt('section_id', null);
$product_id = $this->input->getInt('cid', null);
$images = $this->producthelper->getAttibuteSubProperty(0, $section_id)
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	};

	submitbutton = function (pressbutton) {

		if (pressbutton == 'save') {
			submitform('subattribute_color');
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
			<table class="admintable" border="0" id="admintable">

				<tr>
					<td colspan="2" class="key">
						<?php echo JText::_('COM_REDSHOP_SUBATTRIBUTE_NAME'); ?>
					</td>
					<td colspan="1" class="key">
						<?php echo JText::_('COM_REDSHOP_PROPERTY_SUB_IMAGE'); ?>
					</td>
					<td colspan="1" class="key">
						<input type="button"
							   name="addvalue"
							   id="addvalue"
							   class="button"
							   value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>"
							   onclick="addNewRowOfsub('admintable');"
							/>
					</td>
				</tr>

				<?php if (count($images) > 0) : ?>
					<?php
						for ($i = 0, $in = count($images); $i < $in; $i++)
						{
							$image = $images[$i];
							$thumb = $image->subattribute_color_image;

							$thumbUrl = RedShopHelperImages::getImagePath(
											$thumb,
											'',
											'thumb',
											'subcolor',
											Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE'),
											Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE'),
											Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
										);
					?>
						<tr>
							<td>
								<input type="text" name="subattribute_name[]" id="subattribute_name[]" value="<?php echo $image->subattribute_color_name; ?>" size="30"/>
							</td>
							<td>
								<?php if (file_exists($thumbUrl)) : ?>
									<img src="<?php echo $thumbUrl; ?>"/>
								<?php endif; ?>
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
					?>
				<?php else : ?>
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
				<?php endif; ?>
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
