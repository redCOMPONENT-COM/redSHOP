<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

$uri = JURI::getInstance();
$url = $uri->root();
?>
<table class="admintable table">
	<tr>
		<td width="100" align="right" class="key">
			<?php
			echo JText::_('COM_REDSHOP_PORTAL_SHOP_LBL');
			?></td>
		<td><?php
			echo $this->lists ['portalshop'];
			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<?php
			echo JText::_('COM_REDSHOP_URL_AFTER_PORTAL_LOGIN');
			?></td>
		<td><?php
			echo $this->lists ['url_after_portal_login'];
			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<?php
			echo JText::_('COM_REDSHOP_URL_AFTER_PORTAL_LOGOUT');
			?></td>
		<td><?php
			echo $this->lists ['url_after_portal_logout'];
			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_PORTAL_NAME_LBL');
			?></td>
		<td><input type="text" name="default_portal_name"
		           id="default_portal_name"
		           value="<?php echo Redshop::getConfig()->get('DEFAULT_PORTAL_NAME'); ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<?php
			echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_PRIVATE_LBL');
			?></td>
		<td><?php
			echo $this->lists ['shopper_group_default_private'];

			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<?php
			echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_COMPANY_LBL');
			?></td>
		<td><?php
			echo $this->lists ['shopper_group_default_company'];

			?></td>
	</tr>
	<tr>
		<?php $DEFAULT_PORTAL_LOGO =  Redshop::getConfig()->get('DEFAULT_PORTAL_LOGO'); ?>
		<td width="100" align="right" class="key">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_PORTAL_LOGO_LBL');
			?></td>
		<td>
			<div><input type="file" name="default_portal_logo"
			            id="default_portal_logo" size="57"/> <input type="hidden"
			                                                        name="default_portal_logo_tmp"
			                                                        value="<?php
			                                                        echo $DEFAULT_PORTAL_LOGO;
			                                                        ?>"/></div>
			<?php
			if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . $DEFAULT_PORTAL_LOGO))
			{
				?>
				<div><a class="joom-box"
				        href="<?php
				        echo REDSHOP_FRONT_IMAGES_ABSPATH . 'shopperlogo/' . $DEFAULT_PORTAL_LOGO;
				        ?>"
				        title="<?php
				        echo $DEFAULT_PORTAL_LOGO;
				        ?>"
				        rel="{handler: 'image', size: {}}"> <img width="100" height="100"
				                                                 alt="<?php
				                                                 echo $DEFAULT_PORTAL_LOGO;
				                                                 ?>"
				                                                 src="<?php
				                                                 echo REDSHOP_FRONT_IMAGES_ABSPATH . 'shopperlogo/' . $DEFAULT_PORTAL_LOGO;
				                                                 ?>"/></a></div>
			<?php
			}
			?></td>
	</tr>
</table>
