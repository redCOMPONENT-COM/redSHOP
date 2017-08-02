<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$user = JFactory::getUser();
$email = JRequest::getString('email');
$name = JRequest::getString('name');
$Itemid = JRequest::getInt('Itemid');

if ($user->id != "")
{
	$email = $user->email;
	$name  = $user->name;
}

if ($this->params->get('show_page_heading', 1))
{
	?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
<?php
}?>
<form method="post" action="" name="subscribeForm2" onsubmit="return validation();">
	<div class="redshop_newsletter">
		<div class="redshop_newsletter_label">
			<?php echo JText::_('COM_REDSHOP_NEWSLETTER_SUBSCRIPTION'); ?>
		</div>
		<div class="redshop_newsletter_input">
			<label><?php echo JText::_('COM_REDSHOP_NAME');?> : </label>
			<input type="text" name="name" id="name" value="<?php echo $name; ?>" class="redshop_newsletter_name"/>
		</div>
		<div class="redshop_newsletter_input">
			<label><?php echo JText::_('COM_REDSHOP_EMAIL');?> : </label>
			<input type="text" name="email1" id="email1" value="<?php echo $email; ?>"
			       class="redshop_newsletter_email"/>
		</div>
		<div class="redshop_newsletter_buttons">
			<input type="submit" name="subscribe" id="subscribe"
			       onClick="document.subscribeForm2.task.value='subscribe';"
			       value=<?php echo JText::_('COM_REDSHOP_SUBSCRIBE'); ?> class="redshop_newsletter_tilmeld"/>
			<input type="submit" name="unsubscribe" id="unsubscribe"
			       onClick="document.subscribeForm2.task.value='unsubscribe';"
			       value="<?php echo JText::_('COM_REDSHOP_UNSUBSCRIBE'); ?>" class="redshop_newsletter_afmeld"/>
		</div>
	</div>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="newsletter"/>
	<input type="hidden" name="layout" value="default"/>
</form>
<script type="text/javascript">
	function validation() {
		var name = document.subscribeForm2.name.value;
		var email = document.subscribeForm2.email.value;
		var patt1 = new RegExp("([a-z0-9_]+)@([a-z0-9_-]+)[.][a-z]");

		if (name == '') {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_A_NAME');?>");
			return false;
		} else if (email == '') {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_AN_EMAIL_ADDRESS');?>");
			return false;
		} else if (patt1.test(email) == false) {
			alert("<?php echo JText::_('COM_REDSHOP_EMAIL_ADDRESS_NOT_VALID');?>");
			return false;
		}
		else {
			return true;
		}
	}
</script>
