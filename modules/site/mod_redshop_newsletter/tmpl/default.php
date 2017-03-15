<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_newsletter
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$user = JFactory::getUser();
$email = JRequest::getString('email');
$name = JRequest::getString('name');
$Itemid = JRequest::getInt('Itemid');
$newsletteritemid = $params->get('redirectpage');

if ($user->id != "")
{
	$email = $user->email;
	$name  = $user->name;
}

$document = JFactory::getDocument();
$document->addScriptDeclaration('
function validation() {
		var name = document.subscribeForm.name.value;
		var email = document.subscribeForm.email.value;
		var patt1 = new RegExp("([a-z0-9_]+)@([^\\s+@\\s+$]+)[.][a-z]");

		if (name == \'\') {
			alert("' . JText::_('COM_REDSHOP_ENTER_A_NAME') . '");
			return false;
		} else if (email == \'\') {
			alert("' . JText::_('COM_REDSHOP_ENTER_AN_EMAIL_ADDRESS') . '");
			return false;
		} else if (patt1.test(email) == false) {
			alert("' . JText::_('COM_REDSHOP_EMAIL_ADDRESS_NOT_VALID') . '");
			return false;
		}
		else {
			return true;
		}
	}
');
?>
<form method="post" action="" name="subscribeForm" onsubmit="return validation();">
	<div class="redshop_newsletter">
		<div class="redshop_newsletter_label">
			<?php echo JText::_('COM_REDSHOP_NEWSLETTER_SUBSCRIPTION'); ?>
		</div>
		<div class="redshop_newsletter_input">
			<label for="name"><?php echo JText::_('COM_REDSHOP_FULLNAME');?> : </label>
			<input type="text" name="name" id="name" value="<?php echo $name; ?>" class="redshop_newsletter_name span12"/>
		</div>
		<div class="redshop_newsletter_input">
			<label for="email12"><?php echo JText::_('COM_REDSHOP_EMAIL');?> : </label>
			<input type="text" name="email" id="email12" value="<?php echo $email; ?>"
			       class="redshop_newsletter_email span12"/>
		</div>
		<div class="redshop_newsletter_buttons">
			<input type="submit" name="subscribe" id="subscribe"
			       onClick="document.subscribeForm.elements['task'].value='subscribe';"
			       value=<?php echo JText::_('COM_REDSHOP_SUBSCRIBE'); ?> class="redshop_newsletter_tilmeld btn span6 btn-small btn-success"/>
			<input type="submit" name="unsubscribe" id="unsubscribe"
			       onClick="document.subscribeForm.elements['task'].value='unsubscribe';"
			       value="<?php echo JText::_('COM_REDSHOP_UNSUBSCRIBE'); ?>" class="redshop_newsletter_afmeld btn span6 btn-small btn-inverse"/>
		</div>
	</div>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="newsletter"/>
	<input type="hidden" name="newsletteritemid" id="newsletteritemid" value="<?php echo $newsletteritemid; ?>">
	<input type="hidden" name="layout" value="default"/>
</form>
