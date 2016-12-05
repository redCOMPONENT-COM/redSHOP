<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtmlBehavior::modal('.joom-box');

$uri = JURI::getInstance();
$url = $uri->root();
$link_path = "/components/com_redshop/assets/images/";
?>
<legend><?php echo JText::_('COM_REDSHOP_NEXT_PREVIOUS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_RETURN_TO_CATEGORY_PREFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_RETURN_TO_CATEGORY_PREFIX'); ?>">
    	<label><?php echo JText::_('COM_REDSHOP_RETURN_TO_CATEGORY_PREFIX');?>:</label></span>
	<input type="text" name="return_to_category_prefix" id="return_to_category_prefix"
			       value="<?php echo $this->config->get('DAFULT_RETURN_TO_CATEGORY_PREFIX'); ?>"/>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DAFULT_PREVIOUS_PREFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DAFULT_PREVIOUS'); ?>">
    	<label><?php echo JText::_('COM_REDSHOP_DAFULT_PREVIOUS_PREFIX_LBL');?>:</label></span>
	<input type="text" name="default_previous_prefix" id="default_previous_prefix"
			       value="<?php echo $this->config->get('DAFULT_PREVIOUS_LINK_PREFIX'); ?>"/>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DAFULT_NEXT_SUFFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DAFULT_NEXT_SUFFIX'); ?>">
    	<label><?php echo JText::_('COM_REDSHOP_DAFULT_NEXT_SUFFIX_LBL'); ?>:</label></span>
	<input type="text" name="default_next_suffix" id="default_next_suffix"
			       value="<?php echo $this->config->get('DAFULT_NEXT_LINK_SUFFIX'); ?>"/>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CUSTOM_PREVIOUS_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CUSTOM_PREVIOUS_LINK'); ?>">
        <label><?php echo JText::_('COM_REDSHOP_CUSTOM_PREVIOUS_LINK');?>:</label></span>
	<input type="text" name="custom_previous_link" id="custom_previous_link"
			       value="<?php echo $this->config->get('CUSTOM_PREVIOUS_LINK_FIND'); ?>"/>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CUSTOM_NEXT_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CUSTOM_NEXT_LINK'); ?>">
        <label><?php echo JText::_('COM_REDSHOP_CUSTOM_NEXT_LINK');?>:</label></span>
	<input type="text" name="custom_next_link" id="custom_next_link"
			       value="<?php echo $this->config->get('CUSTOM_NEXT_LINK_FIND'); ?>"/>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_PREVIOUS_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_PREVIOUS_LINK'); ?>">
        <label><?php echo JText::_('COM_REDSHOP_IMAGE_PREVIOUS_LINK');?>:</label></span>
	<?php $imagePreviousLinkFind =  $this->config->get('IMAGE_PREVIOUS_LINK_FIND'); ?>
	<div>
		<div>
			<input class="text_area" type="file" name="imgpre" id="imgpre" size="40"/>
			<input type="hidden" name="image_previous_link" id="image_previous_link"
			       value="<?php echo $imagePreviousLinkFind; ?>"/>
			<a href="#123"
			   onclick="delimg('<?php echo $imagePreviousLinkFind ?>','prvlinkdiv','<?php echo $link_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
		</div>
		<?php  if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $imagePreviousLinkFind))
		{ ?>
			<div id="prvlinkdiv">
				<a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $imagePreviousLinkFind; ?>"
				   title="<?php echo $imagePreviousLinkFind; ?>" rel="{handler: 'image', size: {}}"><img
						alt="<?php echo $imagePreviousLinkFind; ?>"
						src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $imagePreviousLinkFind; ?>"/></a>
			</div>
		<?php } ?>
	</div>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_NEXT_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_NEXT_LINK'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_IMAGE_NEXT_LINK'); ?>:</label>
	</span>

	<?php $imageNextLinkFind =  $this->config->get('IMAGE_NEXT_LINK_FIND'); ?>
	<div>
		<div>
			<input class="text_area" type="file" name="imgnext" id="imgnext" size="40"/>
			<input type="hidden" name="image_next_link" id="image_next_link"
			       value="<?php echo $imageNextLinkFind; ?>"/>
			<a href="#123"
			   onclick="delimg('<?php echo $imageNextLinkFind ?>','nxtlinkdiv','<?php echo $link_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
		</div>
		<?php  if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $imageNextLinkFind))
		{ ?>
			<div id="nxtlinkdiv">
			<a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $imageNextLinkFind; ?>"
			   title="<?php echo $imageNextLinkFind; ?>" rel="{handler: 'image', size: {}}"><img
					alt="<?php echo $imageNextLinkFind; ?>"
					src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $imageNextLinkFind; ?>"/></a>
			</div><?php } ?>
	</div>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_NP_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_NP_LINK'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_NP_LINK_LBL'); ?></label></span>
	<?php echo $this->lists ['next_previous_link'];?>
</div>

