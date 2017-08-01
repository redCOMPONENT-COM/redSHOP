<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

$user = JFactory::getUser();
$form = $displayData['form'];

if ($user->id)
{
	$form->setValue('your_name', null, $form->getValue('your_name', null, $user->name));
	$form->setValue('your_email', null, $form->getValue('your_email', null, $user->email));
}

$menuItemId = $app->input->getInt('Itemid', 0);
$pid        = $app->input->getInt('pid', 0);
$categoryId = $app->input->getInt('category_id', 0);
$template   = RedshopHelperTemplate::getTemplate('ask_question_template');

if (count($template) > 0 && $template[0]->template_desc != "")
{
	$templateContent = $template[0]->template_desc;
}
else
{
	$templateContent = '<table border="0"><tbody><tr><td>{user_name_lbl}</td><td>{user_name}</td></tr><tr><td>{user_email_lbl}</td><td>{user_email}</td></tr><tr><td>{user_question_lbl}</td><td>{user_question}</td></tr><tr><td></td><td>{send_button}</td></tr></tbody></table>';
}

?>
<script type="text/javascript" language="javascript">
	questionSubmitButton = function (task) {
		var askQuestionForm = document.getElementById('askQuestionForm');

		if (document.formvalidator.isValid(askQuestionForm)) {
			Joomla.submitform(task, askQuestionForm);
		}
	}
</script>
<form name="askQuestionForm" action="<?php echo JRoute::_('index.php?option=com_redshop'); ?>" method="post"
	  id="askQuestionForm" class="form-validate form-vertical">
	<?php
	$templateContent = str_replace('{user_name_lbl}', $form->getLabel('your_name'), $templateContent);
	$templateContent = str_replace('{user_email_lbl}', $form->getLabel('your_email'), $templateContent);
	$templateContent = str_replace('{user_question_lbl}', $form->getLabel('your_question'), $templateContent);
	$templateContent = str_replace('{user_telephone_lbl}', $form->getLabel('telephone'), $templateContent);
	$templateContent = str_replace('{user_address_lbl}', $form->getLabel('address'), $templateContent);
	$templateContent = str_replace('{user_name}', $form->getInput('your_name'), $templateContent);
	$templateContent = str_replace('{user_email}', $form->getInput('your_email'), $templateContent);
	$templateContent = str_replace('{user_question}', $form->getInput('your_question'), $templateContent);
	$templateContent = str_replace('{user_address}', $form->getInput('address'), $templateContent);
	$templateContent = str_replace('{user_telephone}', $form->getInput('telephone'), $templateContent);
	$templateContent = str_replace('{send_button}', '<input type="submit" class="btn" value="' . JText::_('COM_REDSHOP_SEND') . '" onclick="questionSubmitButton(\'ask_question.submit\')" />', $templateContent);

	$captcha = '';

	if ($user->guest)
	{
		$captcha = RedshopLayoutHelper::render('registration.captcha');
	}

	$templateContent = str_replace('{captcha}', $captcha, $templateContent);

	echo eval('?>' . $templateContent . '<?php ');
	?>
	<input type="hidden" name="pid" id="pid" value="<?php echo $pid; ?>"/>
	<input type="hidden" name="task" id="task" value=""/>
	<input type="hidden" name="ask" value="<?php echo $displayData['ask']; ?>"/>
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $categoryId; ?>"/>
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $menuItemId; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
