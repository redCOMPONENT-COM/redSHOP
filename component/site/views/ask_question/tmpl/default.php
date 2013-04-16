<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();
$redTemplate = new Redtemplate;
$uname = '';
$uemail = '';
$address = '';
$telephone = '';
$user = JFactory::getUser();

if ($user->id)
{
	$uname  = $user->name;
	$uemail = $user->email;
}

$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$pid = JRequest::getInt('pid');
$ask = JRequest::getInt('ask');
$category_id = JRequest::getInt('category_id');
$document = JFactory::getDocument();
$userhelper = new rsUserhelper;
JHTML::Script('jquery.tools.min.js', 'components/com_redshop/assets/js/', false);

$template = $redTemplate->getTemplate('ask_question_template');

if (count($template) > 0 && $template[0]->template_desc != "")
{
	$template_desc = $template[0]->template_desc;
}
else
{
	$template_desc = '<table border="0"><tbody><tr><td>{user_name_lbl}</td><td>{user_name}</td></tr><tr><td>{user_email_lbl}</td><td>{user_email}</td></tr><tr><td>{user_question_lbl}</td><td>{user_question}</td></tr><tr><td></td><td>{send_button}</td></tr></tbody></table>';
}

?>
<script type="text/javascript" language="javascript">var J = jQuery.noConflict();</script>
<script type="text/javascript" language="javascript">
	function validateQuestion() {
		var frm = document.frmaskquestion;

		if (frm.your_name.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_YOUR_NAME');?>");
			return false;
		} else if (frm.your_email.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_YOUR_EMAIL_ADDRESS');?>");
			return false;
		} else if (frm.your_question.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_YOUR_QUESTION_ABOUT_THIS_PRODUCT');?>");
			return false;
		} else if (frm.security_code.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_SECURITY_CODE');?>");
			return false;
		} else {
			return true;
		}
	}
</script>
<div align="center">
	<form name="frmaskquestion" action="<?php echo JURI::root(); ?>" method="post">
		<?php
		$template_desc = str_replace("{user_name_lbl}", JText::_('COM_REDSHOP_YOUR_NAME'), $template_desc);
		$template_desc = str_replace("{user_email_lbl}", JText::_('COM_REDSHOP_YOUR_EMAIL'), $template_desc);
		$template_desc = str_replace("{user_question_lbl}", JText::_('COM_REDSHOP_YOUR_QUESTION'), $template_desc);
		$template_desc = str_replace("{user_telephone_lbl}", JText::_('COM_REDSHOP_TELEPHONE'), $template_desc);
		$template_desc = str_replace("{user_address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $template_desc);

		$username = '<input type="text" name="your_name" id="your_name" value="' . $uname . '" />';
		$useremail = '<input type="text" name="your_email" id="your_email" value="' . $uemail . '" />';
		$telephone = '<input type="text" name="telephone" id="telephone" value="' . $telephone . '" />';
		$address = '<input type="text" name="address" id="address" value="' . $address . '" />';
		$userquestion = '<textarea name="your_question" id="your_question" cols="40" rows="10"></textarea>';
		$sendbutton = '<input type="submit" value="' . JText::_('COM_REDSHOP_SEND') . '" onclick="return validateQuestion();" />';

		$template_desc = str_replace("{user_name}", $username, $template_desc);
		$template_desc = str_replace("{user_email}", $useremail, $template_desc);
		$template_desc = str_replace("{user_question}", $userquestion, $template_desc);
		$template_desc = str_replace("{user_address}", $address, $template_desc);
		$template_desc = str_replace("{user_telephone}", $telephone, $template_desc);
		$template_desc = str_replace("{send_button}", $sendbutton, $template_desc);
		$template_desc = str_replace("{captcha_lbl}", JText::_('COM_REDSHOP_CAPTCHA'), $template_desc);
		$captcha = $userhelper->getAskQuestionCaptcha();
		$template_desc = str_replace("{captcha}", $captcha, $template_desc);

		echo eval("?>" . $template_desc . "<?php ");
		?>
		<input type="hidden" name="pid" id="pid" value="<?php echo $pid; ?>"/>
		<input type="hidden" name="view" id="view" value="ask_question"/>
		<input type="hidden" name="task" id="task" value="sendaskquestionmail"/>
		<input type="hidden" name="ask" id="ask" value="<?php echo $ask; ?>"/>
		<input type="hidden" name="question_date" id="question_date" value="<?php echo time(); ?>"/>
		<input type="hidden" name="option" id="option" value="<?php echo $option; ?>"/>
		<input type="hidden" name="category_id" id="category_id" value="<?php echo $category_id; ?>"/>
		<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $Itemid; ?>"/>
	</form>
</div>
