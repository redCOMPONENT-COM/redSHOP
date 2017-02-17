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
$shopperlogo_path = "components/com_redshop/assets/images/shopperlogo";
?>

<legend><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_TAB'); ?></legend>
<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PORTAL_SHOP'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PORTAL_SHOP_LBL'); ?>">
		<label><?php
			echo JText::_('COM_REDSHOP_PORTAL_SHOP_LBL');
			?></label></span>
	<?php echo $this->lists ['portalshop']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_URL_AFTER_PORTAL_LOGIN_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_URL_AFTER_PORTAL_LOGIN'); ?>">
		<label><?php
		echo JText::_('COM_REDSHOP_URL_AFTER_PORTAL_LOGIN');
		?></label></span>
	<?php echo $this->lists ['url_after_portal_login']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_URL_AFTER_PORTAL_LOGOUT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_URL_AFTER_PORTAL_LOGOUT'); ?>">
		<label><?php
		echo JText::_('COM_REDSHOP_URL_AFTER_PORTAL_LOGOUT');
		?></label></span>
	<?php echo $this->lists ['url_after_portal_logout']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PORTAL_NAME_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PORTAL_NAME'); ?>">
		<label><?php
			echo JText::_('COM_REDSHOP_DEFAULT_PORTAL_NAME_LBL');
			?></label></span>
	<input type="text" name="default_portal_name"
	   id="default_portal_name"
	   value="<?php
	   echo $this->config->get('DEFAULT_PORTAL_NAME');
	   ?>"/>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PORTAL_LOGO'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PORTAL_LOGO_LBL'); ?>">
			<label><?php
			echo JText::_('COM_REDSHOP_DEFAULT_PORTAL_LOGO_LBL');
			?></label></span>
	<?php $defaultPortalLogo = $this->config->get('DEFAULT_PORTAL_LOGO'); ?>
	<input type="file" name="default_portal_logo"
				id="default_portal_logo" size="57"/>
	<input type="hidden"
		name="default_portal_logo_tmp"
		value="<?php
		echo $defaultPortalLogo;
		?>"/>

	<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . $defaultPortalLogo)) { ?>
	<div class="divimages"  id="usrdiv">
		<a class="joom-box"
					href="<?php
					echo REDSHOP_FRONT_IMAGES_ABSPATH . 'shopperlogo/' . $defaultPortalLogo;
					?>"
					title="<?php
					echo $defaultPortalLogo;
					?>"
					rel="{handler: 'image', size: {}}"> <img width="100" height="100"
															 alt="<?php
															 echo $defaultPortalLogo;
															 ?>"
															 src="<?php
															 echo REDSHOP_FRONT_IMAGES_ABSPATH . 'shopperlogo/' . $defaultPortalLogo;
															 ?>"/></a>
		<a class="remove_link" href="#"
				onclick="delimg('<?php echo $defaultPortalLogo ?>','usrdiv','<?php echo $shopperlogo_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
	</div>
	<?php } ?>

</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_PRIVATE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_PRIVATE_LBL'); ?>">
		<label><?php
			echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_PRIVATE_LBL');
			?></label></span>
	<?php echo $this->lists ['shopper_group_default_private']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_COMPANY'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_COMPANY_LBL'); ?>">
		<label><?php
			echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_COMPANY_LBL');
			?></label></span>
	<?php echo $this->lists ['shopper_group_default_company']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL'); ?>">
		<label><?php
			echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL');
			?></label></span>
	<?php echo $this->lists ['shopper_group_default_unregistered']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL'); ?>">
		<label><?php
			echo JText::_('COM_REDSHOP_NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL');
			?></label></span>
	<?php echo $this->lists ['new_shopper_group_get_value_from']; ?>
</div>
