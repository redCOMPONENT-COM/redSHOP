<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

?>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td width="50%">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_REDSHOP_SEO_GENERAL_TAB'); ?></legend>
				<?php echo $this->loadTemplate('seo_general');?>
			</fieldset>
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_REDSHOP_SEO_CATEGORY_TAB'); ?></legend>
				<?php echo $this->loadTemplate('seo_category');?>
			</fieldset>
		</td>
		<td width="50%">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_REDSHOP_SEO_PRODUCT_TAB'); ?></legend>
				<?php echo $this->loadTemplate('seo_product');?>
			</fieldset>
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_REDSHOP_SEO_MANUFACTURER_TAB'); ?></legend>
				<?php echo $this->loadTemplate('seo_manufacturer');?>
			</fieldset>
		</td>
	</tr>
</table>

<div class="col50">
	<fieldset class="adminform">
		<legend><?php
			echo JText::_('COM_REDSHOP_AVAILABLE_SEO_TAGS');
			?></legend>

		<?php
		$title = JText::_('COM_REDSHOP_TITLE_AVAILABLE_SEO_TAGS');
		echo $this->pane->startPane('stat-pane');
		echo $this->pane->startPanel($title, 'events');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					echo '<span style="margin-left:10px;">{productname} -- ' . JText::_('COM_REDSHOP_PRODUCT_SEO_DESC') . '</span>
<span style="margin-left:10px;">{manufacturer} -- ' . JText::_('COM_REDSHOP_MANUFACTURER_SEO_DESC') . '</span>
<span style="margin-left:10px;">{parentcategoryloop} -- ' . JText::_('COM_REDSHOP_PARENT_CATEGORY_LOOP_SEO_DEC') . '</span>
<span style="margin-left:10px;">{categoryname} -- ' . JText::_('COM_REDSHOP_CATEGORY_SEO_DESC') . '</span>
<span style="margin-left:10px;">{saleprice} -- ' . JText::_('COM_REDSHOP_SALEPRICE_SEO_DESC') . '</span>
<span style="margin-left:10px;">{saving} -- ' . JText::_('COM_REDSHOP_SAVING_SEO_DESC') . '</span>
<span style="margin-left:10px;">{shopname} -- ' . JText::_('COM_REDSHOP_SHOPNAME_SEO_DESC') . '</span>
<span style="margin-left:10px;">{productsku} -- ' . JText::_('COM_REDSHOP_PRODUCTSKU_SEO_DESC') . '</span>
<span style="margin-left:10px;">{categoryshortdesc} -- ' . JText::_('COM_REDSHOP_CATEGORY_SHORT_DESCRIPTION') . '</span>
<span style="margin-left:10px;">{productshortdesc} -- ' . JText::_('COM_REDSHOP_PRODUCT_SHORT_DESCRIPTION') . '</span>';


					?></td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();

		$title = JText::_('COM_REDSHOP_HEADING_AVAILABLE_SEO_TAGS');
		echo $this->pane->startPanel($title, 'events');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					echo '<span style="margin-left:10px;">{productname} -- ' . JText::_('COM_REDSHOP_PRODUCT_SEO_DESC') . '</span>
<span style="margin-left:10px;">{manufacturer} -- ' . JText::_('COM_REDSHOP_MANUFACTURER_SEO_DESC') . '</span>
<span style="margin-left:10px;">{categoryname} -- ' . JText::_('COM_REDSHOP_CATEGORY_SEO_DESC') . '</span>
<span style="margin-left:10px;">{productsku} -- ' . JText::_('COM_REDSHOP_PRODUCTSKU_SEO_DESC') . '</span>
<span style="margin-left:10px;">{categoryshortdesc} -- ' . JText::_('COM_REDSHOP_CATEGORY_SHORT_DESCRIPTION') . '</span>
<span style="margin-left:10px;">{productshortdesc} -- ' . JText::_('COM_REDSHOP_PRODUCT_SHORT_DESCRIPTION') . '</span>';
					?></td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();

		$title = JText::_('COM_REDSHOP_DESC_AVAILABLE_SEO_TAGS');
		echo $this->pane->startPanel($title, 'events');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					echo '<span style="margin-left:10px;">{productname} -- ' . JText::_('COM_REDSHOP_PRODUCT_SEO_DESC') . '</span>
<span style="margin-left:10px;">{manufacturer} -- ' . JText::_('COM_REDSHOP_MANUFACTURER_SEO_DESC') . '</span>
<span style="margin-left:10px;">{categoryname} -- ' . JText::_('COM_REDSHOP_CATEGORY_SEO_DESC') . '</span>
<span style="margin-left:10px;">{saleprice} -- ' . JText::_('COM_REDSHOP_SALEPRICE_SEO_DESC') . '</span>
<span style="margin-left:10px;">{saving} -- ' . JText::_('COM_REDSHOP_SAVING_SEO_DESC') . '</span>
<span style="margin-left:10px;">{shopname} -- ' . JText::_('COM_REDSHOP_SHOPNAME_SEO_DESC') . '</span>
<span style="margin-left:10px;">{productsku} -- ' . JText::_('COM_REDSHOP_PRODUCTSKU_SEO_DESC') . '</span>
<span style="margin-left:10px;">{categoryshortdesc} -- ' . JText::_('COM_REDSHOP_CATEGORY_SHORT_DESCRIPTION') . '</span>
<span style="margin-left:10px;">{productshortdesc} -- ' . JText::_('COM_REDSHOP_PRODUCT_SHORT_DESCRIPTION') . '</span>
<span style="margin-left:10px;">{categorydesc} -- ' . JText::_('COM_REDSHOP_CATEGORY_DESCRIPTION') . '</span>
<span style="margin-left:10px;">{productdesc} -- ' . JText::_('COM_REDSHOP_PRODUCT_DESCRIPTION') . '</span>';
					?></td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();

		$title = JText::_('COM_REDSHOP_KEYWORD_AVAILABLE_SEO_TAGS');
		echo $this->pane->startPanel($title, 'events');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					echo '<span style="margin-left:10px;">{productname} -- ' . JText::_('COM_REDSHOP_PRODUCT_SEO_DESC') . '</span>
<span style="margin-left:10px;">{manufacturer} -- ' . JText::_('COM_REDSHOP_MANUFACTURER_SEO_DESC') . '</span>
<span style="margin-left:10px;">{categoryname} -- ' . JText::_('COM_REDSHOP_CATEGORY_SEO_DESC') . '</span>
<span style="margin-left:10px;">{saleprice} -- ' . JText::_('COM_REDSHOP_SALEPRICE_SEO_DESC') . '</span>
<span style="margin-left:10px;">{saving} -- ' . JText::_('COM_REDSHOP_SAVING_SEO_DESC') . '</span>
<span style="margin-left:10px;">{shopname} -- ' . JText::_('COM_REDSHOP_SHOPNAME_SEO_DESC') . '</span>
<span style="margin-left:10px;">{productsku} -- ' . JText::_('COM_REDSHOP_PRODUCTSKU_SEO_DESC') . '</span>
<span style="margin-left:10px;">{categoryshortdesc} -- ' . JText::_('COM_REDSHOP_CATEGORY_SHORT_DESCRIPTION') . '</span>
<span style="margin-left:10px;">{productshortdesc} -- ' . JText::_('COM_REDSHOP_PRODUCT_SHORT_DESCRIPTION') . '</span>';

					?></td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
		echo $this->pane->endPane();
		?>
	</fieldset>
</div>
