<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidator');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("select").select2({width: "100%"});
    });

    Joomla.submitbutton = function(task)
    {
        if (task == "category.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task);
        }
    };
</script>
<form
        action="index.php?option=com_redshop&task=category.edit&id=<?php echo $this->item->id; ?>"
        method="post"
        name="adminForm"
        id="adminForm"
        class="form-validate form-horizontal"
        enctype="multipart/form-data"
>
	<?php
	echo RedshopLayoutHelper::render(
		'component.full.tab.main',
		array(
			'view'    => $this,
			'tabMenu' => $this->tabmenu->getData('tab')->items,
		)
	);
	?>
	<?php echo $this->form->getInput('id'); ?>
    <input type="hidden" name="old_image" id="old_image" value="<?php echo $this->item->category_full_image ?>">
    <input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
