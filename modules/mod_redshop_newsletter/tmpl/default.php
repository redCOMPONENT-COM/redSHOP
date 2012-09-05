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
global $mainframe;
$user =& JFactory::getUser();
$email = JRequest::getVar('email');
$name = JRequest::getVar('name');
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
if($user->id!="")
{
	$email=$user->email;
	$name =$user->name;
}
$params = &$mainframe->getParams($option);
$newsletteritemid = $params->get('redirectpage');
?>
<form method="post" action="" name="subscribeForm" onsubmit="return validation();">
<div class="redshop_newsletter">
	<div class="redshop_newsletter_label">
		<?php echo JTEXT::_('NEWSLETTER_SUBSCRIPTION'); ?>
	</div>
	<div class="redshop_newsletter_input">
		<label><?php echo JText::_('FULLNAME');?> : </label>
		<input type="text" name="name" id="name"  value="<?php echo $name; ?>" class="redshop_newsletter_name"/>
	</div>
	<div class="redshop_newsletter_input">
		<label><?php echo JText::_('EMAIL');?> : </label>
		<input type="text" name="email1" id="email12" value="<?php echo $email; ?>" class="redshop_newsletter_email"/>
	</div>
	<div class="redshop_newsletter_buttons">
		<input type="submit" name="subscribe" id="subscribe" onClick="document.subscribeForm.elements['task'].value='subscribe';" value=<?php echo JTEXT::_('SUBSCRIBE');?> class="redshop_newsletter_tilmeld"/>
		<input type="submit" name="unsubscribe" id="unsubscribe" onClick="document.subscribeForm.elements['task'].value='unsubscribe';" value="<?php echo JTEXT::_('UNSUBSCRIBE');?>" class="redshop_newsletter_afmeld"/>
	</div>
</div>
<input type="hidden" name="option" value="com_redshop" />
<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="newsletter" />
<input type="hidden" name="newsletteritemid" id="newsletteritemid" value="<?php echo $newsletteritemid;?>">
<input type="hidden" name="layout" value="default" />
</form>

<script type="text/javascript">

/*function regularExp(str,strname) {

	var patt1=new RegExp("([a-z0-9_]+)@([a-z0-9_]+)[.][a-z]");

	if(str == "" && strname == 'name'){
		alert("<?php echo JTEXT::_('ENTER_A_NAME');?>");
		return false;
	}else {
		if(str == ""){
			alert("<?php echo JTEXT::_('ENTER_AN_EMAIL_ADDRESS');?>");
			return false;
		}
		if(patt1.test(str) == false){
			alert("<?php echo JTEXT::_('EMAIL_ADDRESS_NOT_VALID');?>");
			return false;
		}
	}

}
*/
function validation(){
	var name = document.subscribeForm.name.value;
	var email = document.subscribeForm.email1.value;
	//var patt1=new RegExp("([a-z0-9_]+)@([a-z0-9_-]+)[.][a-z]");
	var patt1=new RegExp("([a-z0-9_]+)@([^\\s+@\\s+$]+)[.][a-z]");

	if(name == ''){
		alert("<?php echo JTEXT::_('ENTER_A_NAME');?>");
		return false;
	}else if (email== ''){
		alert("<?php echo JTEXT::_('ENTER_AN_EMAIL_ADDRESS');?>");
		return false;
	}else if(patt1.test(email) == false){
		alert("<?php echo JTEXT::_('EMAIL_ADDRESS_NOT_VALID');?>");
		return false;
	}
	else {
		return true;
	}

}
</script>
