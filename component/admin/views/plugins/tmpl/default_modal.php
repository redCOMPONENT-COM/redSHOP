<?php

defined('_JEXEC') or die;


?>

<button type="button"
        data-target="#moduleEditModal"
        class="btn btn-link module-edit-link"
        title="<?php echo JText::_('COM_MENUS_EDIT_MODULE_SETTINGS'); ?>"
        id="title-<?php echo $module->id; ?>"
        data-module-id="<?php echo $module->id; ?>">
    <?php echo $this->escape($module->title); ?></button>

