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
defined ( '_JEXEC' ) or die ( 'restricted access' );

JHTML::_ ( 'behavior.tooltip' );
//require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'configuration.php' );

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'order.php' );
$order_functions = new order_functions();

$url = JURI::base();

$redconfig = new Redconfiguration();

$Itemid = JRequest::getVar ( 'Itemid' );
$option = JRequest::getVar ( 'option' );
$return = JRequest::getVar ( 'return' );
$post = JRequest::get('post'); 

	$detail = $this->detail;

	
	$firstname = $detail->firstname;
	$lastname = $detail->lastname;
	$address = $detail->address;
	$zipcode = $detail->zipcode;
	$city = $detail->city;
	$country = JText::_( $order_functions->getCountryName( $detail->country_code ));;
	$state = $order_functions->getStateName( $detail->state_code, $detail->country_code );
	$phone = $detail->phone;
	$user_email = $detail->user_email;
	

$field = new extra_field();	

if(DEFAULT_CUSTOMER_REGISTER_TYPE==1 || !DEFAULT_CUSTOMER_REGISTER_TYPE)
{
	$regtype=0;
}else{
	$regtype=1;
}

$link = 'index.php?option='.$option.'&view=cart&Itemid='.$Itemid;
?>
<script>
 
function validateInfo(){

	var frm = document.adminForm;
	 
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	if(frm.email.value==''){
 		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_ADDRESS')?>");
 		return false;
 	}
    
   var email = frm.email.value;

   if(reg.test(email) == false) {
      alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_EMAIL_ADDRESS')?>");
      return false;
   }
 	if(frm.username.value==''){
 		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_USERNAME')?>");
 		return false;
 	}
	 
	
   <?php if($regtype==1)
	{
		?>
		if(frm.company_name.value==''){
	 		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME')?>");
	 		return false;
	 	}
	 	
<?php } else {?>

		if(frm.firstname.value==''){
	 		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME')?>");
	 		return false;
	 	}
	 	if(frm.lastname.value==''){
	 		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME')?>");
	 		return false;
	 	}
	
<?php }?>
	
	
return true;
}
 
 </script>


<form action="<?php echo JRoute::_($this->request_url);?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<fieldset class="adminform"><legend><?php echo JText::_('COM_REDSHOP_ACCOUNT_CREATION' );?></legend>
<table class="admintable">
<?php	if ($regtype == 1) {	?>
<tr><td width="100" align="left"><?php echo JText::_('COM_REDSHOP_COMPANY_NAME' );?>:</td>
	<td> <input type='text' name="company_name" value='<?php echo @$post['company_name'];?>'/></td></tr>
	<?php //echo $detail->company_name;?>
<?php	} else {	?>
<tr><td width="100" align="left"><?php echo JText::_('COM_REDSHOP_FIRSTNAME' );?>:</td>
	<td> <input type='text' name="firstname" value='<?php echo @$post['firstname'];?>'/></td></tr>
<tr><td width="100" align="left"><?php echo JText::_('COM_REDSHOP_LASTNAME' );?>:</td>
	<td> <input type='text' name="lastname" value='<?php echo @$post['lastname'];?>'/></td></tr>
	<td><?php echo $lastname;?></td></tr>
<?php	}	?>

<tr><td width="100" align="left"><?php echo JText::_('COM_REDSHOP_USERNAME' );?>:</td>
	<td> <input type='text' name="username" value='<?php echo @$post['username'];?>'/></td></tr>

<tr><td width="100" align="left"><?php echo JText::_('COM_REDSHOP_EMAIL' );?>:</td>
	<td><input type='text' name="email" value="<?php echo @$post['email'];?>"/></td></tr>

<tr><td align="center" colspan="2"><input type="submit" class="greenbutton" name="btnsubmit" value="<?php echo JText::_('COM_REDSHOP_SUBMIT' );?>" onclick="return validateInfo();">
<input type="submit" class="greenbutton" name="cancel" value="<?php echo JText::_("COM_REDSHOP_CANCEL");?>" onclick="javascript:document.adminForm.task.value='cancel';"/></td></tr>
</table>
</fieldset>
<input type="hidden" name="is_company" value="<?php	echo $regtype;?>" />
<input type="hidden" name="option" value="<?php	echo $option;?>" /> 
<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
<input type="hidden" name="task" value="usercreate" />
<input type="hidden" name="view" value="quotation" />
<input type="hidden" name="return" value="<?php echo $return;?>" />

</form>
