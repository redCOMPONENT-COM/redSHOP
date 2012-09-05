<?php
defined('_JEXEC') or die('Restricted access');
$uri =& JURI::getInstance();
$url= $uri->root();
$currencyobject = JRequest::getVar( 'object' );
?>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) 
{
	var form = document.adminForm;
	
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	if (form.currency_name.value == "")
	{
		alert( "<?php echo JText::_( 'CURREMCY_MUST_HAVE_A_NAME', true ); ?>" );
	}
	else if(form.currency_code.value == "")
	{
		alert( "<?php echo JText::_( 'CURRENCY_CODE_MUST_HAVE_A_VALUE', true ); ?>" );
	}
	
	else 
	{
		submitform( pressbutton );
	}
}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<fieldset class="adminform">
<legend><?php echo JText::_( 'DETAILS' ); ?></legend>
<table class="admintable">
	<tr>
		<td class="key"><?php echo JText::_('CURRENCY_NAME'); ?></td>
		<td><input class="text_area" type="text" name="currency_name" id="currency_name" size="30" maxlength="100" value="<?php echo $this->detail->currency_name;?>" /></td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_( 'CURRENCY_CODE_LBL' ); ?>:</td>
		<td><input class="text_area" type="text" name="currency_code" id="currency_code" size="80" maxlength="255" value="<?php echo $this->detail->currency_code;?>" /></td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_( 'COUNTRY_NAME' ); ?>:</td>
		<td> <?php echo $this->lists['dynamic_country_id']; ?></td>
	</tr>
</table>
</fieldset>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->currency_id; ?>" /> 
<input type="hidden" name="task" value="" /> 
<input type="hidden" name="view" value="currency_detail" />
<?php if($currencyobject){?>
<input type="hidden" name="object" value="<?php echo $currencyobject;?>"/>
<?php }?>
</form>