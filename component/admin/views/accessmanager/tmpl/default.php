<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
$producthelper = new producthelper();

$config = new Redconfiguration();

$option = JRequest::getVar('option');



?>


<table class="adminlist" cellspacing="0" cellpadding="0" border="0">
	<thead>
	<tr>
		<th><?php echo JText::_('COM_REDSHOP_SECTION')?></th>
	</tr>
	</thead>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=statistic"><?php echo JText::_('COM_REDSHOP_STATISTIC')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=product"><?php echo JText::_('COM_REDSHOP_PRODUCTS')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=category"><?php echo JText::_('COM_REDSHOP_CATEGORIES')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=media"><?php echo JText::_('COM_REDSHOP_MEDIA')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=wrapper"><?php echo JText::_('COM_REDSHOP_WRAPPER')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=order"><?php echo JText::_('COM_REDSHOP_ORDER')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=quotation"><?php echo JText::_('COM_REDSHOP_QUOTATION')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=user"><?php echo JText::_('COM_REDSHOP_USER')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=container"><?php echo JText::_('COM_REDSHOP_CONTAINER')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=stockroom"><?php echo JText::_('COM_REDSHOP_STOCKROOM')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=manufacturer"><?php echo JText::_('COM_REDSHOP_MANUFACTURERS')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=newsletter"><?php echo JText::_('COM_REDSHOP_NEWSLETTER')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=mail"><?php echo JText::_('COM_REDSHOP_MAIL_CENTER')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=coupon"><?php echo JText::_('COM_REDSHOP_COUPON_MANAGEMENT')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=discount"><?php echo JText::_('COM_REDSHOP_DISCOUNT_MANAGEMENT')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=voucher"><?php echo JText::_('COM_REDSHOP_VOUCHER')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=fields"><?php echo JText::_('COM_REDSHOP_FIELDS')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=textlibrary"><?php echo JText::_('COM_REDSHOP_TEXT_LIBRARY')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=template"><?php echo JText::_('COM_REDSHOP_TEMPLATES')?></a>
		</td>
	</tr>


	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=shipping"><?php echo JText::_('COM_REDSHOP_SHIPPING')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=tax_group"><?php echo JText::_('COM_REDSHOP_TAX_GROUP')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=catalog"><?php echo JText::_('COM_REDSHOP_CATALOG_MANAGEMENT')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=sample_request"><?php echo JText::_('COM_REDSHOP_COLOUR_SAMPLE_MANAGEMENT')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=import"><?php echo JText::_('COM_REDSHOP_IMPORT_EXPORT')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=xmlimport"><?php echo JText::_('COM_REDSHOP_XML_IMPORT_EXPORT')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=accountgroup"><?php echo JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=configuration"><?php echo JText::_('COM_REDSHOP_CONFIG')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=giftcard"><?php echo JText::_('COM_REDSHOP_GIFTCARD')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=state"><?php echo JText::_('COM_REDSHOP_STATE')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=country"><?php echo JText::_('COM_REDSHOP_COUNTRY')?></a>
		</td>
	</tr>

	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=currency"><?php echo JText::_('COM_REDSHOP_CURRENCY')?></a>
		</td>
	</tr>
	<tr class="row0">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=question"><?php echo JText::_('COM_REDSHOP_QUESTION')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=accessmanager"><?php echo JText::_('COM_REDSHOP_ACCESS_MANAGER')?></a>
		</td>
	</tr>
	<tr class="row1">
		<td>
			<a href="<?php echo JURI::base() ?>index.php?option=<?php echo $option ?>&amp;view=accessmanager_detail&section=wizard"><?php echo JText::_('COM_REDSHOP_WIZARD')?></a>
		</td>
	</tr>

</table>
