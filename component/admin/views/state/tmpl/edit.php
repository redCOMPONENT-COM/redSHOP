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
?>

<form
	action="index.php?option=com_redshop&task=state.edit&id=<?php echo $this->item->id ?>"
	method="post"
	id="adminForm"
	name="adminForm"
	class="form-validate form-horizontal"
	enctype="multipart/form-data"
>
	<fieldset class="adminform">
        <div class="row-fluid">
            <div class="col-sm-12">
				<?php echo $this->form->renderField('state_name') ?>
				<?php echo $this->form->renderField('country_id') ?>
				<?php echo $this->form->renderField('state_3_code') ?>
				<?php echo $this->form->renderField('state_2_code') ?>
				<?php echo $this->form->renderField('show_state') ?>
            </div>
        </div>
	</fieldset>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
</form>
