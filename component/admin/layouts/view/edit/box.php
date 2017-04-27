<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Layout variables
 * ======================================
 *
 * @var  array            $displayData Layout data.
 * @var  RedshopViewAdmin $data        Data.
 */
extract($displayData);

JHtml::_('behavior.formvalidator');

$action = 'index.php?option=com_redshop&task=' . $data->getInstanceName() . '.edit&id=' . $data->item->id;
?>

<form action="<?php echo $action ?>" method="post" id="adminForm" name="adminForm" class="form-validate form-horizontal adminform"
      enctype="multipart/form-data">
    <div class="row">
		<?php foreach ($data->fields as $fieldSet): ?>
            <div class="col-md-6">
				<?php
				echo RedshopLayoutHelper::render(
					'config.group', array('title' => JText::_('COM_REDSHOP_' . strtoupper($fieldSet->name)), 'content' => $fieldSet->html))
				?>
            </div>
		<?php endforeach; ?>
    </div>
    <div class="hidden">
		<?php echo implode('', $data->hiddenFields) ?>
		<?php echo JHtml::_('form.token'); ?>
        <input type="hidden" name="task" value=""/>
    </div>
</form>
