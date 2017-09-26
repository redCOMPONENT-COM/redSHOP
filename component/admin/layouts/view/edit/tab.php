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

$primaryKey = $data->getPrimaryKey();
$itemId     = $data->item->{$primaryKey};
$action     = 'index.php?option=com_redshop&task=.' . $data->getInstanceName() . '.edit&' . $primaryKey . '=' . $itemId;
?>

<form action="<?php echo $action ?>" method="post" id="adminForm" name="adminForm" class="form-validate form-horizontal adminform"
        enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-2">
            <div class="box">
                <div class="box-body no-padding">
                    <ul class="tabconfig nav nav-pills nav-stacked" role="tablist">
						<?php $i = 0; ?>
						<?php foreach ($data->fields as $fieldSet): ?>
                            <li role="presentation" <?php echo $i == 0 ? ' class="active"' : '' ?>>
                                <a href="#<?php echo $fieldSet->name ?>" role="tab" data-toggle="tab">
									<?php echo JText::_('COM_REDSHOP_' . $fieldSet->name, true) ?>
                                </a>
                            </li>
							<?php $i = 1; ?>
						<?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="tab-content">
				<?php $i = 0; ?>
				<?php foreach ($data->fields as $fieldSet): ?>
                    <div role="tabpanel" class="tab-pane <?php echo $i == 0 ? 'active' : '' ?>" id="<?php echo $fieldSet->name ?>">
                        <div class="box box-primary">
                            <div class="box-body">
								<?php echo $fieldSet->html ?>
                            </div>
                        </div>
                    </div>
					<?php $i = 1; ?>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="hidden">
		<?php echo implode('', $data->hiddenFields) ?>
		<?php echo JHtml::_('form.token'); ?>
        <input type="hidden" name="task" value=""/>
    </div>
</form>
