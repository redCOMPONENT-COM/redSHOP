<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access');

$option = JRequest::getVar('option','','request','string');
?>
<h1><?php echo JText::_('DATA_EXPORT'); ?></h1>

<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" >
<?php echo JText::_('TXT_QUL');?>
<input type="text" name="text_qul" value='"' maxlength="1" size="1"/></br>
<input type="radio" name="export" value="categories" onclick="return product_export(this.value)"><?php echo JText::_('EXPORT_CATEGORIES'); ?></input>
<br />
<input type="radio" name="export" value="products" onclick="return product_export(this.value)" ><?php echo JText::_('EXPORT_PRODUCTS'); ?></input>
<br />
<span id="product_export" style="display: none;">
<br />
<strong><?php echo JText::_('EXPORT_PRODUCT_EXTRAFIELD');?></strong><input type="radio" name="export_product_extra_field" value="1"><?php echo JText::_('YES');?><input type="radio" name="export_product_extra_field" value="0" checked="checked"><?php echo JText::_('NO');?>
<br />
<b><?php echo JText::_( 'PRODUCT_CATEGORY' ); ?></b>
<br />
<?php echo $this->lists['categories']; ?>
<br />
<br />
<b><?php echo JText::_( 'PRODUCT_MANUFACTURER' ); ?></b>
<br />
<?php echo $this->lists['manufacturers']; ?>
<br />
<br />
</span>
<input type="radio" name="export" value="attributes" onclick="return product_export(this.value)"><?php echo JText::_('EXPORT_ATTRIBUTES'); ?></input>
<br />
<input type="radio" name="export" value="related_product" onclick="return product_export(this.value)"><?php echo JText::_('EXPORT_RELATED_PRODUCTS'); ?></input>
<br />
<input type="radio" name="export" value="fields" onclick="return product_export(this.value)"><?php echo JText::_('EXPORT_FIELDS'); ?></input>
<br/>
<input type="radio" name="export" value="users" onclick="return product_export(this.value)"><?php echo JText::_('EXPORT_USERS'); ?></input>
<br/>
<input type="radio" name="export" value="shipping_address" onclick="return product_export(this.value)"><?php echo JText::_('EXPORT_SHIPPING_ADDRESS'); ?></input>
<br/>
<input type="radio" name="export" value="shopper_group_price" onclick="return product_export(this.value)"><?php echo JText::_('EXPORT_SHOPPER_GROUP_SPECIFIC_PRICE'); ?></input>
<br/>
<input type="radio" name="export" value="manufacturer" onclick="return product_export(this.value)"><?php echo JText::_('EXPORT_MANUFACTURER'); ?></input>
<br/>
<input type="hidden" name="view" value="export" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<script type=" text/javascript">
	function product_export(val)
	{
		if(val=="products")
		{
			document.getElementById('product_export').style.display="";
		} else {
			document.getElementById('product_export').style.display="none";
		}
	}
</script>
