<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('webcomponent.joomla-alert')
    ->useScript('messages')
    ->useScript('form.validate');
//JHtml::_('behavior.formvalidator');

/**
 * Layout variables
 * ======================================
 *
 * @var  array            $displayData Layout data.
 * @var  RedshopViewAdmin $data        Data.
 */

extract($displayData);

$primaryKey    = $data->getPrimaryKey();
$itemId        = $data->item->{$primaryKey};
$action        = 'index.php?option=com_redshop&task=' . $data->getInstanceName(
) . '.edit&' . $primaryKey . '=' . $itemId;
$fieldSetClass = 'col-md-' . (12 / $data->formFieldsetsColumn);

?>
<form action="<?php echo $action ?>" method="post" id="adminForm" name="adminForm"
    class="form-validate form-horizontal adminform" enctype="multipart/form-data">
    <div class="row title-alias">
        <?php foreach ($data->fields as $fieldSet): ?>
            <div class="<?php echo $fieldSetClass ?>">
                <?php
                echo RedshopLayoutHelper::render(
                    'config.group',
                    array('title' => Text::_('COM_REDSHOP_' . strtoupper($fieldSet->name)),
                        'content' => $fieldSet->html
                    )
                )
                    ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="hidden">
        <?php echo implode('', $data->hiddenFields) ?>
        <?php echo JHtml::_('form.token'); ?>
        <input type="hidden" name="view" value="<?php echo $data->getInstancesName() ?>" />
        <input type="hidden" name="task" value="" />
    </div>
</form>