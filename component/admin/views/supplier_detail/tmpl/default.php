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

JHTML::_('behavior.tooltip');



$editor =& JFactory::getEditor();
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		if (form.supplier_name.value == ""){
			alert( "<?php echo JText::_( 'SUPPLIER_ITEM_MUST_HAVE_A_NAME', true ); ?>" );
		}else {
			submitform( pressbutton );
		}
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<?php

?>
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'NAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="supplier_name" id="supplier_name" size="32" maxlength="250" value="<?php echo $this->detail->supplier_name;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'SUPPLIER_EMAIL' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="supplier_email" id="supplier_email" size="32" maxlength="250" value="<?php echo $this->detail->supplier_email;?>" />
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'PUBLISHED' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>

	</table>
	</fieldset>
</div>
<div class="col50">

</div>

<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DESCRIPTION' ); ?></legend>

		<table class="admintable">
		<tr>
			<td>
				<?php echo $editor->display("supplier_desc",$this->detail->supplier_desc,'$widthPx','$heightPx','100','20');	?>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>


<input type="hidden" name="cid[]" value="<?php echo $this->detail->supplier_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="supplier_detail" />
</form>


