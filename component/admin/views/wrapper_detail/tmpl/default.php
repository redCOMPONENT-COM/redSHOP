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
JHTML::_('behavior.tooltip');
$showall = JRequest::getVar('showall','0');
$uri =& JURI::getInstance();
$url= $uri->root();
$producthelper = new producthelper();
?>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	if (form.wrapper_name.value == "" ){
		alert( "<?php echo JText::_('ENTER_WRAPPER_NAME');?>" );
	} else if (isNaN(form.wrapper_price.value)){
		alert( "<?php echo JText::_('WRAPPER_PRICE_NOT_VALID');?>" );
		form.wrapper_price.focus();
	} else {
		submitform( pressbutton );
	}
}
</script>
<?php if($showall)
{	?>
<fieldset>
	<div style="float: right">
	<button type="button" onclick="submitbutton('save');">
			<?php echo JText::_( 'SAVE' ); ?>
		</button>
		<button type="button" onclick="submitbutton('cancel');">
			<?php echo JText::_( 'CANCEL' ); ?>
		</button>
	</div>
	<div class="configuration"><?php echo JText::_( 'ADD_WRAPPER' ); ?></div>
</fieldset>
<?php }	?>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col50">
<fieldset class="adminform"><legend><?php echo JText::_( 'DETAILS' ); ?></legend>
<table class="admintable" width="90%">
	<tr><td width="100" align="right" class="key"><?php echo JText::_( 'WRAPPER_NAME' );?></td>
		<td><input class="text_area" type="text" name="wrapper_name" id="wrapper_name" size="32" maxlength="250" value="<?php echo $this->detail->wrapper_name;?>" />
			<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_WRAPPER_NAME' ), JText::_( 'WRAPPER_NAME' ), 'tooltip.png', '', '', false); ?></td></tr>
	<tr><td width="100" align="right" class="key"><?php echo JText::_( 'WRAPPER_PRICE' );?></td>
		<td><input class="text_area" type="text" name="wrapper_price" id="wrapper_price" size="10" maxlength="10" value="<?php echo $producthelper->redpriceDecimal($this->detail->wrapper_price);?>" />
			<?php echo JHTML::tooltip(JText::_( 'TOOLTIP_WRAPPER_PRICE' ), JText::_( 'WRAPPER_PRICE' ), 'tooltip.png', '', '', false); ?> </td></tr>
	<?php if(!$showall) {	?>		
	<tr><td width="100" align="right" class="key"><?php echo JText::_( 'CATEGORY_NAME' );?></td>
		<td><?php echo $this->lists['category_name'];	?></td></tr>
	<?php }	?>	
<table class="admintable">
<tr width="100px"><td VALIGN="TOP" class="key"  align="center">
<?php echo JText::_( 'PRODUCT' ); ?> <br /><br />
<input style="width: 130px" type="text" id="input" value="" />
<div style="display:none"><?php 
echo $this->lists['product_all']; 
?></div>
</td>
<TD align="center">
<input type="button" value="-&gt;" onClick="moveRight(10);" title="MoveRight">
<BR><BR>
<input type="button" value="&lt;-" onClick="moveLeft();" title="MoveLeft">
</TD>
<TD VALIGN="TOP" align="right" class="key" style="width: 200px">
<?php echo JText::_( 'PRODUCT_NAME' ); ?><br /><br />
<?php 
echo $this->lists['product_name'];?>
</td>
</tr>
</table><table class="admintable">
	<tr><td width="100" align="right" class="key" valign="top"><?php echo JText::_( 'WRAPPER_IMAGE' );?></td>
		<td><input class="text_area" type="file" name="wrapper_image" id="wrapper_image" />
				<?php echo JHTML::tooltip(JText::_( 'TOOLTIP_WRAPPER_IMAGE' ), JText::_( 'WRAPPER_IMAGE' ), 'tooltip.png', '', '', false); ?>
				<?php $wimage_path = 'components/com_redshop/assets/images/wrapper/'.$this->detail->wrapper_image;
				if(is_file(JPATH_SITE.DS.$wimage_path)) {	?>
<!--				<div align="right" class="image"><img src="<?php echo $url.$wimage_path;?>" alt="wrapper" title="Wrapper" border="0" width="200" /></div>-->
			<?php }	?>	</td></tr>
	<tr><td valign="top" align="right" class="key"><?php echo JText::_( 'USE_TO_ALL_PRODUCT' ); ?>:</td>
			<td><?php echo $this->lists['use_to_all']; ?></td></tr>
	<tr><td valign="top" align="right" class="key"><?php echo JText::_( 'PUBLISHED' ); ?>:</td>
			<td><?php echo $this->lists['published']; ?></td></tr>		
	</table></fieldset></div>
<div class="clr"></div>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->wrapper_id; ?>" />
<input type="hidden" name="product_id" value="<?php echo $this->product_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="wrapper_detail" />
<input type="hidden" name="showall" value="<?php echo $showall;?>" />
</form>
<script type="text/javascript">

	var options = {
		script:"index3.php?option=com_redshop&view=search&json=true&alert=wrapper&",
		varname:"input",
		json:true,
		shownoresults:true,	
			
		callback: function (obj) { 
		var selTo = document.adminForm.container_product;
		var chk_add=1;
		for (var i = 0; i < selTo.options.length; i++) {  	
	        if(selTo.options[i].value==obj.id)
	        { chk_add=0;
	        }
		} 
		if(chk_add==1)
		{
		var newOption = new Option(obj.value, obj.id);
		selTo.options[selTo.options.length] = newOption;
		}
		document.adminForm.input.value = "";
		 }
	};
	
	var as_json = new bsn.AutoSuggest('input', options);
	 
</script>