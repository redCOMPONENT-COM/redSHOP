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
$shopperlogo_path = "components/com_redshop/assets/images/shopperlogo";
?>
<table class="admintable">
	<tr>
		<td class="config_param" colspan="2"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_TAB'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PORTAL_SHOP'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PORTAL_SHOP_LBL'); ?>">
		<?php
			echo JText::_('COM_REDSHOP_PORTAL_SHOP_LBL');
			?></span>
		</td>
		<td><?php
			echo $this->lists ['portalshop'];
			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_URL_AFTER_PORTAL_LOGIN_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_URL_AFTER_PORTAL_LOGIN'); ?>">
		<?php
		echo JText::_('COM_REDSHOP_URL_AFTER_PORTAL_LOGIN');
		?></span></td>
		<td><?php
			echo $this->lists ['url_after_portal_login'];
			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_URL_AFTER_PORTAL_LOGOUT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_URL_AFTER_PORTAL_LOGOUT'); ?>">
		<?php
		echo JText::_('COM_REDSHOP_URL_AFTER_PORTAL_LOGOUT');
		?></span></td>
		<td><?php
			echo $this->lists ['url_after_portal_logout'];
			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PORTAL_NAME_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PORTAL_NAME'); ?>">
		<?php
			echo JText::_('COM_REDSHOP_DEFAULT_PORTAL_NAME_LBL');
			?></span></td>
		<td><input type="text" name="default_portal_name"
		           id="default_portal_name"
		           value="<?php
		           echo DEFAULT_PORTAL_NAME;
		           ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PORTAL_LOGO'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PORTAL_LOGO_LBL'); ?>">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_PORTAL_LOGO_LBL');
			?></td>
		<td>
			<div><input type="file" name="default_portal_logo"
			            id="default_portal_logo" size="57"/> <input type="hidden"
			                                                        name="default_portal_logo_tmp"
			                                                        value="<?php
			                                                        echo DEFAULT_PORTAL_LOGO;
			                                                        ?>"/><a href="#123"
			                                                                onclick="delimg('<?php echo DEFAULT_PORTAL_LOGO ?>','usrdiv','<?php echo $shopperlogo_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
			</div>
			<div id="usrdiv">
				<?php
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . DEFAULT_PORTAL_LOGO))
				{
					?>
					<div><a class="modal"
					        href="<?php
					        echo REDSHOP_FRONT_IMAGES_ABSPATH . 'shopperlogo/' . DEFAULT_PORTAL_LOGO;
					        ?>"
					        title="<?php
					        echo DEFAULT_PORTAL_LOGO;
					        ?>"
					        rel="{handler: 'image', size: {}}"> <img width="100" height="100"
					                                                 alt="<?php
					                                                 echo DEFAULT_PORTAL_LOGO;
					                                                 ?>"
					                                                 src="<?php
					                                                 echo REDSHOP_FRONT_IMAGES_ABSPATH . 'shopperlogo/' . DEFAULT_PORTAL_LOGO;
					                                                 ?>"/></a></div>
				<?php
				}
				?></div>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_PRIVATE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_PRIVATE_LBL'); ?>">
		<?php
			echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_PRIVATE_LBL');
			?></span></td>
		<td><?php
			echo $this->lists ['shopper_group_default_private'];

			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_COMPANY'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_COMPANY_LBL'); ?>">
		<?php
			echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_COMPANY_LBL');
			?></span></td>
		<td><?php
			echo $this->lists ['shopper_group_default_company'];

			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL'); ?>">
		<?php
			echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL');
			?></span></td>
		<td><?php
			echo $this->lists ['shopper_group_default_unregistered'];

			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">

		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL'); ?>">
		<?php
			echo JText::_('COM_REDSHOP_NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL');
			?></span></td>
		<td><?php
			echo $this->lists ['new_shopper_group_get_value_from'];

			?></td>
	</tr>
	<!--<tr>
		<td width="100" align="right" class="key">
		<?php
		echo JText::_ ( 'SHOPPER_GROUP_DEFAULT_TAX_EXEMPT_LBL' );
		?></td>
		<td><?php
		echo $this->lists ['shopper_group_default_tax_exempt'];

		?></td>
	</tr>
	-->

</table>
