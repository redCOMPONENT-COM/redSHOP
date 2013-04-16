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
$link_path = "/components/com_redshop/assets/images/";
?>
<table class="admintable">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_CATEGORY_SUFFIXES'); ?></td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_RETURN_TO_CATEGORY_PREFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_RETURN_TO_CATEGORY_PREFIX'); ?>">
     <?php echo JText::_('COM_REDSHOP_RETURN_TO_CATEGORY_PREFIX');?>:</span></td>
		<td>
			<input type="text" name="return_to_category_prefix" id="return_to_category_prefix"
			       value="<?php echo DAFULT_RETURN_TO_CATEGORY_PREFIX; ?>"/>
		</td>
	</tr>
	<!-- next-previous link-->
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DAFULT_PREVIOUS_PREFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DAFULT_PREVIOUS'); ?>">
     <?php echo JText::_('COM_REDSHOP_DAFULT_PREVIOUS_PREFIX_LBL');?>:</span></td>
		<td>
			<input type="text" name="default_previous_prefix" id="default_previous_prefix"
			       value="<?php echo DAFULT_PREVIOUS_LINK_PREFIX; ?>"/>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DAFULT_NEXT_SUFFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DAFULT_NEXT_SUFFIX'); ?>">
     <?php echo JText::_('COM_REDSHOP_DAFULT_NEXT_SUFFIX_LBL'); ?>:</span></td>
		<td>
			<input type="text" name="default_next_suffix" id="default_next_suffix"
			       value="<?php echo DAFULT_NEXT_LINK_SUFFIX; ?>"/>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CUSTOM_PREVIOUS_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CUSTOM_PREVIOUS_LINK'); ?>">
        <?php echo JText::_('COM_REDSHOP_CUSTOM_PREVIOUS_LINK');?>:</span></td>
		<td>
			<input type="text" name="custom_previous_link" id="custom_previous_link"
			       value="<?php echo CUSTOM_PREVIOUS_LINK_FIND; ?>"/>
		</td>
	</tr>

	<tr>
		<td align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CUSTOM_NEXT_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CUSTOM_NEXT_LINK'); ?>">
        <?php echo JText::_('COM_REDSHOP_CUSTOM_NEXT_LINK');?>:</span></td>
		<td>
			<input type="text" name="custom_next_link" id="custom_next_link"
			       value="<?php echo CUSTOM_NEXT_LINK_FIND; ?>"/>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_PREVIOUS_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_PREVIOUS_LINK'); ?>">
        <?php echo JText::_('COM_REDSHOP_IMAGE_PREVIOUS_LINK');?>:</span></td>
		<td>
			<div>
				<div>
					<input class="text_area" type="file" name="imgpre" id="imgpre" size="40"/>
					<input type="hidden" name="image_previous_link" id="image_previous_link"
					       value="<?php echo IMAGE_PREVIOUS_LINK_FIND; ?>"/>
					<a href="#123"
					   onclick="delimg('<?php echo IMAGE_PREVIOUS_LINK_FIND ?>','prvlinkdiv','<?php echo $link_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
				</div>
				<?php  if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . IMAGE_PREVIOUS_LINK_FIND))
				{ ?>
					<div id="prvlinkdiv">
						<a class="modal" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . IMAGE_PREVIOUS_LINK_FIND; ?>"
						   title="<?php echo IMAGE_PREVIOUS_LINK_FIND; ?>" rel="{handler: 'image', size: {}}"><img
								alt="<?php echo IMAGE_PREVIOUS_LINK_FIND; ?>"
								src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . IMAGE_PREVIOUS_LINK_FIND; ?>"/></a>
					</div>
				<?php } ?>
			</div>
		</td>
	</tr>


	<tr>
		<td align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_NEXT_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_NEXT_LINK'); ?>">
<?php
				echo JText::_('COM_REDSHOP_IMAGE_NEXT_LINK');
				?>:
</span></td>
		<td>
			<div>
				<div>
					<input class="text_area" type="file" name="imgnext" id="imgnext" size="40"/>
					<input type="hidden" name="image_next_link" id="image_next_link"
					       value="<?php echo IMAGE_NEXT_LINK_FIND; ?>"/>
					<a href="#123"
					   onclick="delimg('<?php echo IMAGE_NEXT_LINK_FIND ?>','nxtlinkdiv','<?php echo $link_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
				</div>
				<?php  if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . IMAGE_NEXT_LINK_FIND))
				{ ?>
					<div id="nxtlinkdiv">
					<a class="modal" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . IMAGE_NEXT_LINK_FIND; ?>"
					   title="<?php echo IMAGE_NEXT_LINK_FIND; ?>" rel="{handler: 'image', size: {}}"><img
							alt="<?php echo IMAGE_NEXT_LINK_FIND; ?>"
							src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . IMAGE_NEXT_LINK_FIND; ?>"/></a>
					</div><?php } ?>
			</div>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_NP_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_NP_LINK'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_NP_LINK_LBL'); ?></label></span>
		</td>
		<td><?php echo $this->lists ['next_previous_link'];?></td>
	</tr>
	<!-- next-previous link End-->

</table>
