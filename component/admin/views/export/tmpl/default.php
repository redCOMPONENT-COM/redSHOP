<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$option = JRequest::getVar('option', '', 'request', 'string');
?>
<h1><?php echo JText::_('COM_REDSHOP_DATA_EXPORT'); ?></h1>

<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
	<input type="radio" name="export" value="categories"
	       onclick="return product_export(this.value)"><?php echo JText::_('COM_REDSHOP_EXPORT_CATEGORIES'); ?></input>
	<br/>
	<input type="radio" name="export" value="products"
	       onclick="return product_export(this.value)"><?php echo JText::_('COM_REDSHOP_EXPORT_PRODUCTS'); ?></input>
	<br/>
<span id="product_export" style="display: none;">
<br/>
<strong><?php echo JText::_('COM_REDSHOP_EXPORT_PRODUCT_EXTRAFIELD');?></strong><input type="radio"
                                                                                       name="export_product_extra_field"
                                                                                       value="1"><?php echo JText::_('COM_REDSHOP_YES');?>
	<input type="radio" name="export_product_extra_field" value="0"
	       checked="checked"><?php echo JText::_('COM_REDSHOP_NO');?>
	<br/>
<b><?php echo JText::_('COM_REDSHOP_PRODUCT_CATEGORY'); ?></b>
<br/>
	<?php echo $this->lists['categories']; ?>
	<br/>
<br/>
<b><?php echo JText::_('COM_REDSHOP_PRODUCT_MANUFACTURER'); ?></b>
<br/>
	<?php echo $this->lists['manufacturers']; ?>
	<br/>
<br/>
</span>
	<input type="radio" name="export" value="attributes"
	       onclick="return product_export(this.value)"><?php echo JText::_('COM_REDSHOP_EXPORT_ATTRIBUTES'); ?></input>
	<br/>
	<input type="radio" name="export" value="related_product"
	       onclick="return product_export(this.value)"><?php echo JText::_('COM_REDSHOP_EXPORT_RELATED_PRODUCTS'); ?></input>
	<br/>
	<input type="radio" name="export" value="fields"
	       onclick="return product_export(this.value)"><?php echo JText::_('COM_REDSHOP_EXPORT_FIELDS'); ?></input>
	<br/>
	<input type="radio" name="export" value="users"
	       onclick="return product_export(this.value)"><?php echo JText::_('COM_REDSHOP_EXPORT_USERS'); ?></input>
	<br/>
	<input type="radio" name="export" value="shipping_address"
	       onclick="return product_export(this.value)"><?php echo JText::_('COM_REDSHOP_EXPORT_SHIPPING_ADDRESS'); ?></input>
	<br/>
	<input type="radio" name="export" value="shopper_group_price"
	       onclick="return product_export(this.value)"><?php echo JText::_('COM_REDSHOP_EXPORT_SHOPPER_GROUP_SPECIFIC_PRICE'); ?></input>
	<br/>
	<input type="radio" name="export" value="manufacturer"
	       onclick="return product_export(this.value)"><?php echo JText::_('COM_REDSHOP_EXPORT_MANUFACTURER'); ?></input>
	<br/>
	<input type="hidden" name="view" value="export"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>

<script type=" text/javascript">
	function product_export(val) {
		if (val == "products") {
			document.getElementById('product_export').style.display = "";
		} else {
			document.getElementById('product_export').style.display = "none";
		}
	}
</script>
