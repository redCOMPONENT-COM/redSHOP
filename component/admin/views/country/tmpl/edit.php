<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidator');
$uri = JURI::getInstance();
$url = $uri->root();

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "country.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
		{
			Joomla.submitform(task);
		}
	};
');
?>

<form action="index.php?option=com_redshop&task=country.edit&id=<?php echo $this->item->id ?>"
	method="post"
	id="adminForm"
	name="adminForm"
	class="form-validate form-horizontal"
	enctype="multipart/form-data">
	<fieldset class="adminform">
		<div class="row-fluid">
			<div class="col-sm-12">
				<legend><?php echo JText::_('COM_REDSHOP_DETAILS') ?></legend>
				<?php echo $this->form->renderField('country_name') ?>
				<?php echo $this->form->renderField('country_3_code') ?>
				<?php echo $this->form->renderField('country_2_code') ?>
				<?php echo $this->form->renderField('country_jtext') ?>
			</div>
		</div>
		<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
</form>


