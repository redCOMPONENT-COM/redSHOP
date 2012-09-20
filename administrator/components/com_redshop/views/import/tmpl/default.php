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
<script type="text/javascript">
Joomla.submitbutton = function(pressbutton) {
	submitbutton(pressbutton);
	}

submitbutton = function(pressbutton) {
	var form = document.adminForm;

	if (form.separator.value=="")
	{
		alert( "<?php echo JText::_('COM_REDSHOP_CSV_COLUMN_SEPARATOR_NOT_NULL', true ); ?>" );
		return false;
	}
	else
	{
		submitform(pressbutton);
	}
}
</script>
<h1><?php echo JText::_('COM_REDSHOP_DATA_IMPORT'); ?></h1>
<br />
<?php echo $this->result; ?>
<br />
<br />
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<table class="adminList">
<tr>
	<td><?php echo JText::_('COM_REDSHOP_SEPRATOR');?></td>
	<td><input type="text" name="separator" size="1" value="," /></td>
</tr>
<tr>
	<td><input type="radio" name="import" value="categories"><?php echo JText::_('COM_REDSHOP_IMPORT_CATEGORIES'); ?></input></td>
	<td><input type="file" name="importfilecategories" size="75" /></td>
</tr>
<tr>
	<td><input type="radio" name="import" value="products"><?php echo JText::_('COM_REDSHOP_IMPORT_PRODUCTS'); ?></input></td>
	<td><input type="file" name="importfileproducts" size="75" /></td>
</tr>
<tr>
	<td><input type="radio" name="import" value="attributes"><?php echo JText::_('COM_REDSHOP_IMPORT_ATTRIBUTES'); ?></input></td>
	<td><input type="file" name="importfileattributes" size="75" /></td>
</tr>
<tr>
	<td><input type="radio" name="import" value="manufacturer"><?php echo JText::_('COM_REDSHOP_IMPORT_MANUFACTURER'); ?></input></td>
	<td><input type="file" name="importfilemanufacturer" size="75" /></td>
</tr>
<tr>
	<td><input type="radio" name="import" value="related_product"><?php echo JText::_('COM_REDSHOP_IMPORT_RELATED_PRODUCTS'); ?></input></td>
	<td><input type="file" name="importfilerelated_product" size="75" /></td>
</tr>
<tr>
	<td><input type="radio" name="import" value="fields"><?php echo JText::_('COM_REDSHOP_IMPORT_FIELDS'); ?></input></td>
	<td><input type="file" name="importfilefields" size="75" /></td>
</tr>
<!--<tr>
	<td><input type="radio" name="import" value="fields_data"><?php echo JText::_('COM_REDSHOP_IMPORT_FIELDS_DATA'); ?></input></td>
	<td><input type="file" name="importfilefields_data" size="75" /></td>
</tr>
-->
<tr>
	<td><input type="radio" name="import" value="users"><?php echo JText::_('COM_REDSHOP_IMPORT_USERS'); ?></input></td>
	<td><input type="file" name="importfileusers" size="75" /></td>
</tr>
<tr>
	<td><input type="radio" name="import" value="shipping_address"><?php echo JText::_('COM_REDSHOP_IMPORT_SHIPPING_ADDRESS'); ?></input></td>
	<td><input type="file" name="importfileshipping_address" size="75" /></td>
</tr>
<tr>
	<td><input type="radio" name="import" value="shopper_group_price"><?php echo JText::_('COM_REDSHOP_IMPORT_SHOPPER_GROUP_SPECIFIC_PRICE'); ?></input></td>
	<td><input type="file" name="importfileshopper_group_price" size="75" /></td>
</tr>
<tr>
	<td><input type="radio" name="import" value="product_stockroom_data"><?php echo JText::_('COM_REDSHOP_PRODUCT_STOCKROOM_DATA'); ?></input></td>
	<td><input type="file" name="importfileproduct_stockroom_data" size="75" /></td>
</tr>

</table>
<input type="hidden" name="view" value="import" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
</form>
