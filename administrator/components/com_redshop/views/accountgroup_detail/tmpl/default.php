<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=accountgroup_detail'); ?>"
      method="post" name="adminForm" id="adminForm">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
        <ul class="adminformlist">
            <?php foreach($this->form->getFieldset() as $field): ?>
            <li><?php echo $field->label;
                echo $field->input;?></li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
    <div>
        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
